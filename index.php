<?php
// Index file. 
/* Load kernel and modules, show content and take care of URL override

Copyright 2014 Gaël Stébenne (alias Wh1t3c0d3r)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/


DEFINE ('INSCRIPT',"1");
DEFINE ('framework_version','1.2.1 BETA');
ob_start();
if (file_exists('config.php')) {require_once 'config.php';} else {echo 'Can\'t find config.php. Please run <a href="setup.php">setup.php</a>';exit(1);} // Config file
error_reporting(E_ALL);
if ($CONFIG['debug'] === true) {error_reporting(E_ALL);}
else {error_reporting(0);}
session_start();
$CONFIG['app_real_location'] = str_replace("\\","/",$CONFIG['app_real_location']); // Convert Windows path to unix for compatibility
// Define system's dynamic variables
$THEME['location'] 	= $CONFIG['themes']."/".$CONFIG['theme'];
$THEME['css']		= $THEME["location"]."/".$CONFIG['themes_css']."/";
$THEME['js']		= $THEME["location"]."/".$CONFIG['themes_js']."/";
$THEME['img']		= $THEME["location"]."/".$CONFIG['themes_img']."/";
$THEME['module']	= $THEME["location"]."/".$CONFIG['themes_mod']."/";

// Config file auto-test
$error = false;
$error_array = array();
if (!file_exists($CONFIG['app_real_location'])) {$error = true; array_push($error_array,'app_real_location');}
if (!file_exists($CONFIG['app_real_location'].'/'.$CONFIG['webroot'])) {$error = true; array_push($error_array,'webroot');}
if (!file_exists($CONFIG['app_real_location'].'/'.$CONFIG['themes'])) {$error = true; array_push($error_array,'themes');}
if (!file_exists($CONFIG['app_real_location'].'/'.$CONFIG['modules'])) {$error = true; array_push($error_array,'modules');}
if (php_sapi_name() !== 'cli' and $CONFIG['app_location'] != substr($_SERVER['REQUEST_URI'],0,strlen($CONFIG['app_location']))) {$error = true; array_push($error_array,'app_location');}
if (substr($CONFIG['app_location'],-1,1) != '/') {$error = true; array_push($error_array,'app_location');}
if ($error === true) {header('HTTP/1.1 500 Internal Server Error');
    echo "CONFIG FILE TEST FAILED! Failed check(s):<br/><br/>\r\n\r\n"; 
    echo "-- The following settings are using non-existing folders or bad values --<br/>\r\n";
    foreach ($error_array as $item) {echo $item."<br/>\r\n";} exit(1);
}
unset ($error);
unset ($error_array);
if (php_sapi_name() === 'cli') {
    ob_end_flush();
}
// Load and boot the kernel
chdir ('Kernel');
require_once 'boot.php';
chdir ($CONFIG['app_real_location']);
if (php_sapi_name() === 'cli') {
    kernel_log('Framework is running from command line or as a service.');
} else {
    kernel_log ('Remote IP: '.$_SERVER['REMOTE_ADDR']);
}
$INFO['modules'] = array();

// Load modules. Other than executing their code now, they should register the functions in the event system.
kernel_log ("Loading modules from the module directory");
$dirhandle = opendir($CONFIG['app_real_location']."/".$CONFIG['modules']) or die (kernel_log("Could not open module directory. Please check configuration",1));
$TEMP['system']['moduletoload'] = array();
while (false !== ($item = readdir($dirhandle))) {if (stripos($item,'.') !== 0) {array_push($TEMP['system']['moduletoload'],$item);}}
closedir($dirhandle);
unset ($dirhandle);
foreach ($TEMP['system']['moduletoload'] as $item) {
	if (preg_match("/\/(system|kernel|theme|webroot|_|-)/i","/$item")){ break;} // If a module start with one of the name, exclude it. That prevent confusion in the log.
	if (file_exists($CONFIG['app_real_location']."/".$CONFIG['modules']."/$item/init.php") and is_readable($CONFIG['app_real_location']."/".$CONFIG['modules']."/$item/init.php")) { 
		kernel_log("Loading module '$item'..."); 
		chdir ($CONFIG['app_real_location']."/".$CONFIG['modules']."/$item");
		$result = include_once("init.php");
		if ($result === 1) {array_push($INFO['modules'],$item);} else {kernel_log("Failed to load module '$item'");}
		unset ($result);
}}
kernel_log("Done loading modules");
chdir ($CONFIG['app_real_location']."/".$CONFIG['webroot']);
kernel_vartemp_clear();

if (php_sapi_name() === 'cli') {
    $INFO['web_location'] = "cli"; // Framework is running from command line. Set it to 'cli'
} else {
    $INFO['web_location'] = $_SERVER['REQUEST_URI']; // Set current web location
}

//$INFO['web_location'] = "/framework/"; // Uncomment to override web-location USEFULL WHEN DUBUGGING THROUGHT THE CONSOLE!!

// Filter WEB URL and find docpath
if ($INFO['web_location'] !== 'cli' and strpos($INFO['web_location'],"?") !== false) {$INFO['web_location'] = substr($INFO['web_location'], 0, strpos($INFO['web_location'],"?"));}
if ($CONFIG['app_location'] === '/') {
	$TEMP['docpath'] = $INFO['web_location'];
} else { 	
	$TEMP['regex_app_location'] = str_replace("/","\\/",$CONFIG['app_location']); 
	preg_match('/(?<='.$TEMP['regex_app_location'].').*/',$INFO['web_location'],$TEMP['docpath']); // Get the URL of the web location (with app_location as root)
	$TEMP['docpath'] = '/'.$TEMP['docpath'][0];
	
}

// If at root, make sure that there is a slash.
if ($TEMP['docpath'] !== "/") {
	if ($TEMP['docpath'] == "") {
		$TEMP['docpath'] = "/";
	} elseif ($TEMP['docpath'][strlen($TEMP['docpath']) - 1] === "/"){
		$TEMP['docpath'] = substr($TEMP['docpath'],0,-1);
	}
}
if ($INFO['web_location'] === 'cli') {
    kernel_log('WEB-URL: Running from command line');
} else {
    kernel_log("WEB-URL: ". $TEMP['docpath']);
}
$i = kernel_override_url();
while (true) {
	static $i2 = 0;
	if (! isset ($i['TYPE'][$i2])) {__render_page(); unset ($i); break;}
		if ($i['TYPE'][$i2] === 2) {
			if ($TEMP['docpath'] === $i['URL'][$i2]) {kernel_log("Executing script ".$i['SCRIPTNAME'][$i2]." with URL ".$i['URL'][$i2]." using explicit mode"); include_once $i['SCRIPT'][$i2]; unset ($i); break;}
		} elseif($i['TYPE'][$i2] === 1) {
			if (substr($TEMP['docpath'] . "/", 0, strlen($i['URL'][$i2]."/")) === $i['URL'][$i2]."/") {kernel_log("Executing script ".$i['SCRIPTNAME'][$i2]." with URL ".$i['URL'][$i2]." using normal mode"); $i['URL'][$i2]; include_once $i['SCRIPT'][$i2];unset ($i); break;}
		}
	$i2++;
}

function __render_page () {
    if (php_sapi_name() !== 'cli') {
    	include ("docname.inc.php");
    
    	if ($GLOBALS['TEMP']['docpath'] == "/" and file_exists('./'.$GLOBALS['CONFIG']['default_document'].'.html')) {
    		define ('DOCPATH',"/".$GLOBALS['CONFIG']['default_document']);
    		
    		$GLOBALS['THEME']['page_title'] = $DOCNAME[$GLOBALS['CONFIG']['default_document']];
    		kernel_log("Sent DEFAULT document");
    	} elseif (file_exists(".".$GLOBALS['TEMP']['docpath'].'.html')) {
    		define ('DOCPATH',$GLOBALS['TEMP']['docpath']);
    
    		if (! isset($DOCNAME[substr(DOCPATH,1)])) {
    		    $GLOBALS['THEME']['page_title'] = $GLOBALS['INFO']['web_location'];
    		} else {
    		    $GLOBALS['THEME']['page_title'] = $DOCNAME[substr(DOCPATH,1)];
    		}
    		kernel_log("Sent document '". DOCPATH ."'");
    
    	} elseif (file_exists(".".$GLOBALS['TEMP']['docpath']) and is_dir(".".$GLOBALS['TEMP']['docpath']) and file_exists(".".$GLOBALS['TEMP']['docpath'].'/'.$GLOBALS['CONFIG']['default_document'].'.html')) {
            define ('DOCPATH',$GLOBALS['TEMP']['docpath'].'/'.$GLOBALS['CONFIG']['default_document']);
            
    	        if (! isset($DOCNAME[substr(DOCPATH,1)])) {
    			    $GLOBALS['THEME']['page_title'] = $GLOBALS['INFO']['web_location'];
    			} else {
    			    $GLOBALS['THEME']['page_title'] = $DOCNAME[substr(DOCPATH,1)];
    		}
            kernel_log("Sent DEFAULT document for ".DOCPATH);
        } else {
            $file = $GLOBALS['TEMP']['docpath'];
            $fileext = strrchr($file,'.');
            
            if ($fileext !== false and substr($file,1,1) != "." and strlen($fileext) >= 3) {
                foreach ($GLOBALS['CONFIG']['allowed_file_ext'] as $ext) {
                    
                    if ($fileext == '.'.$ext and file_exists(substr($file,1))){
                        
                        if ($GLOBALS['CONFIG']['debug'] === true and $GLOBALS['CONFIG']['debug_level'] !== 0){kernel_log("DEBUG IS ENABLED and debug_level is not set to zero. File download is not possible in that case. Please disable debug or set debug_level to 0",2); unset ($fileext); break;
                        } else { 
    						kernel_log("Download of file '$file' requested");
                            unset($fileext);
    						$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type
    						$mimetype = finfo_file($finfo, substr($file,1));
    						finfo_close($finfo);
    						$filesize = filesize(substr($file,1));
                            ob_end_clean();ob_end_clean();
    						header('Content-Type: $mimetype');
    						header("Content-Length: $filesize");
    						kernel_log ('Download of file '.substr($file,1)." started. File size: $filesize Bytes");
    						readfile (substr($file,1));
    						kernel_log ('Download completed');
    						kernel_shutdown();
                        }
    				}
                }
                if (file_exists('./404.html')){DEFINE('DOCPATH','/404');} else {DEFINE('DOCPATH','404');}
            } else {
    			if (file_exists('./404.html')){DEFINE('DOCPATH','/404');} else {DEFINE('DOCPATH','404');}
    		}
    	}
    		if (DEFINED ('DOCPATH') and DOCPATH !== '404') {
    			if (DOCPATH === '/404') {kernel_log ("File ". $GLOBALS['TEMP']['docpath'] ." not found. Sent 404",4);}
    			kernel_vartemp_clear();
    			kernel_event_trigger("STARTUP");
    			include_once ($GLOBALS['THEME']['location']."/functions.php");
    			include_once ($GLOBALS['THEME']['location']."/header.php");
    			kernel_event_trigger('SHOWHEADER');
    			include_once(".". DOCPATH .".html");
    			kernel_event_trigger('SHOWCONTENT');
    			include_once ($GLOBALS['THEME']['location'].'/footer.php');
    			kernel_event_trigger('SHOWFOOTER');
            } elseif (DEFINED ('DOCPATH') and DOCPATH === '404') {
                    kernel_log ("File ". $GLOBALS['TEMP']['docpath'] ." not found. Sent 404",4);
                    header("HTTP/1.0 404 Not Found");
                echo <<<OUT
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
OUT;
                echo "<p>The requested URL ". $_SERVER['REQUEST_URI'] ." was not found on this server.</p>";
                echo <<<OUT
<p>Additionally, a 404 Not found error as occurred while trying to find the 404 document</p>
<hr/>
OUT;
echo '<p style="font-style:italic;">Generated by Wh1t3project\'s Pure PHP framework V'.framework_version.'</p>
</body></html>';

            }
    } else {
        echo("Starting up the framework CLI\r\nPlease note that this interface is built for easier development and not to be used in production environnement\r\n");
        sleep (1);
        $cmd = null;
        chdir($GLOBALS['CONFIG']['app_real_location']);
        echo ("Type exit to quit and shutdown the framework\r\n");
        while ($cmd !== 'exit'){
            $cmd = readline('CLI> ');
            readline_add_history($cmd);
            if ($cmd !== 'exit') {
                eval($cmd.';');
                if ($cmd != null) {echo "\r\n";}
            }
        }
    }
}


kernel_shutdown();
?>

