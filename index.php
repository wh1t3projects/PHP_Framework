<?php
// Index file. 
/* Load kernel and modules, show content and take care of URL override

Copyright 2014 - 2019 Gaël Stébenne (alias Wh1t3c0d3r)

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
DEFINE ('framework_version','2.0');
ob_start();
if (file_exists('config.php')) {require_once 'config.php';} else {echo 'Can\'t find config.php. Please run <a href="setup.php">setup.php</a>';exit(1);} // Config file
error_reporting(E_ALL);
if ($CONFIG['debug'] === true) {error_reporting(E_ALL);}
else {error_reporting(0);}
session_start();
$CONFIG['app_real_location'] = str_replace("\\","/",$CONFIG['app_real_location']); // Convert Windows path to unix for compatibility

$THEME['location'] 	= $CONFIG['themes']."/".$CONFIG['theme'];
$THEME['css']		= $THEME["location"]."/".$CONFIG['themes_css']."/";
$THEME['js']		= $THEME["location"]."/".$CONFIG['themes_js']."/";
$THEME['img']		= $THEME["location"]."/".$CONFIG['themes_img']."/";

// Config file auto-test
$error = false;
$error_array = array();
if (!file_exists($CONFIG['app_real_location'])) {
    $error = true; 
    array_push($error_array,'app_real_location is invalid');
}
if (!file_exists($CONFIG['app_real_location'].'/'.$CONFIG['webroot'])) {
    $error = true;
    array_push($error_array,'webroot directory does not exist');
}
if (!file_exists($CONFIG['app_real_location'].'/'.$CONFIG['themes'])) {
    $error = true;
    array_push($error_array,'themes directory does not exist');
}
if (!file_exists($CONFIG['app_real_location'].'/'.$CONFIG['modules'])) {
    $error = true;
    array_push($error_array,'modules directory does not exist');
}
if ($GLOBALS['CONFIG']['debug'] === TRUE and $GLOBALS['CONFIG']['debug_output_level'] > 0) {
            if (file_exists($CONFIG['app_real_location'].$CONFIG['debug_file']) and ! is_writable($CONFIG['app_real_location'].$CONFIG['debug_file'])) {
                $error = true;
                array_push($error_array,'Debug log file is not writable');
            } elseif (! is_writable($CONFIG['app_real_location'])) {
                $error = true;
                array_push($error_array,'Debug log file cannot be created: the app_real_location directory is not writable');
            }
        }
if (php_sapi_name() !== 'cli' and $CONFIG['app_location'] != substr($_SERVER['REQUEST_URI'], 0, strlen($CONFIG['app_location']))) {
    $error = true; 
    array_push($error_array, 'app_location');
}
if (substr($CONFIG['app_location'],-1,1) != '/') {
    $error = true; 
    array_push($error_array,'app_location');
}
if ($error === true) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "CONFIG FILE TEST FAILED! Failed check(s):<br/><br/>\r\n\r\n"; 
    foreach ($error_array as $item) {
        echo $item."<br/>\r\n";
    } 
    exit(1);
}
if (!defined('COREDEBUG')) { define ('COREDEBUG', false); }
unset ($error);
unset ($error_array);
// END auto-test

if (php_sapi_name() === 'cli') {
    ob_end_flush();
}
chdir ('Kernel');
require_once 'boot.php';
chdir ($CONFIG['app_real_location']);
if (php_sapi_name() === 'cli') {
    kernel_log('Framework is running from command line or as a service.');
} else {
    kernel_log ('Remote IP: ' . $_SERVER['REMOTE_ADDR']);
}
$INFO['modules'] = array();
kernel_log ("Loading modules from the module directory");
$dirhandle = opendir($CONFIG['app_real_location'] . "/" . $CONFIG['modules']) or kernel_log('Could not open module directory. Please check configuration', 1);
$TEMP['system']['moduletoload'] = array();
while (false !== ($item = readdir($dirhandle))) {
    if (stripos($item, '.') !== 0) {
        array_push($TEMP['system']['moduletoload'], $item);
    }
}
closedir($dirhandle);
unset ($dirhandle);
foreach ($TEMP['system']['moduletoload'] as $item) {
	if (preg_match("/\/(system|kernel|theme|webroot|_|-)/i", "/$item")){ 
        break;
    }
	if (kernel_checkIfModuleIsValid($item)) { 
		kernel_log("Loading module '$item'..."); 
		chdir ($CONFIG['app_real_location'] . "/" . $CONFIG['modules'] . "/$item");
        if ((include_once('init.php')) == TRUE) {
            array_push($INFO['modules'], $item);
        } else {
            kernel_log("Failed to load module '$item'");
        }
    }
}
kernel_log("Done loading modules");
chdir ($CONFIG['app_real_location'] . "/" . $CONFIG['webroot']);
kernel_vartemp_clear();
kernel_event_trigger('MODULESLOADED');
if (php_sapi_name() === 'cli') {
    $INFO['web_location'] = "cli";
} else {
    $INFO['web_location'] = $_SERVER['REQUEST_URI'];
}
if ($INFO['web_location'] !== 'cli' and strpos($INFO['web_location'], "?") !== false) {
    $INFO['web_location'] = substr($INFO['web_location'], 0, strpos($INFO['web_location'],"?"));
}
if ($CONFIG['app_location'] === '/') {
	$TEMP['docpath'] = $INFO['web_location'];
} else { 	
	$TEMP['regex_app_location'] = str_replace("/","\\/", $CONFIG['app_location']); 
	preg_match('/(?<=' . $TEMP['regex_app_location'] . ').*/', $INFO['web_location'], $TEMP['docpath']);
	$TEMP['docpath'] = '/' . $TEMP['docpath'][0];
}
if ($TEMP['docpath'] !== "/") {
	if ($TEMP['docpath'] == "") {
		$TEMP['docpath'] = "/";
	} elseif ($TEMP['docpath'][strlen($TEMP['docpath']) - 1] === "/" && !is_dir(substr($TEMP['docpath'], 1, strlen($TEMP['docpath'])))) {
		$TEMP['docpath'] = substr($TEMP['docpath'], 0, -1);
	}
}
if ($INFO['web_location'] === 'cli') {
    kernel_log('WEB-URL: Running from command line');
} else {
    kernel_log("WEB-URL: ". $TEMP['docpath']);
}
$overrideInfo = kernel_override_url();
while (true) {
	static $i = 0;
	if (! isset ($overrideInfo['TYPE'][$i])) {
        __render_page();
        unset ($overrideInfo);
        break;
    }
		if ($overrideInfo['TYPE'][$i] === 2) {
			if ($TEMP['docpath'] === $overrideInfo['URL'][$i]) {
                kernel_log("Executing script " . $overrideInfo['SCRIPTNAME'][$i] . " with URL " . $overrideInfo['URL'][$i] . " using explicit mode");
                include_once $overrideInfo['SCRIPT'][$i];
                unset ($overrideInfo);
                break;
            }
		} elseif($overrideInfo['TYPE'][$i] === 1) {
			if (substr($TEMP['docpath'] . "/", 0, strlen($overrideInfo['URL'][$i] . "/")) === $overrideInfo['URL'][$i] . "/") {
                kernel_log("Executing script " . $overrideInfo['SCRIPTNAME'][$i] . " with URL " . $overrideInfo['URL'][$i] . " using normal mode");
                $overrideInfo['URL'][$i];
                include_once $overrideInfo['SCRIPT'][$i];
                unset ($overrideInfo);
                break;
            }
		}
	$i++;
}

function __render_page () {
    if (php_sapi_name() !== 'cli') {
    	include ("docname.inc.php");
    	if (strpos($GLOBALS['TEMP']['docpath'], $GLOBALS['CONFIG']['themes_fromWebroot'] . '/') === 1) {
    	    $destinationFile = $GLOBALS['CONFIG']['app_real_location'] . '/' . $GLOBALS['THEME']['location'] . '/' . substr($GLOBALS['TEMP']['docpath'], strlen($GLOBALS['CONFIG']['themes_fromWebroot'])+2);        	    
        	    if (strpos($destinationFile, '/.') === false and file_exists($destinationFile) and !is_dir($destinationFile)) {
        	        $fileInfoHandle = finfo_open(FILEINFO_MIME_TYPE);
        			$mimeType = finfo_file($fileInfoHandle, $destinationFile);
        			finfo_close($fileInfoHandle);
        			if (substr($mimeType,0,4) === 'text') {
        			    $ext = pathinfo($destinationFile, PATHINFO_EXTENSION);
        			    switch ($ext) {
        			        case 'css':
        			            $mimeType = 'text/css';
        			            break;
        			        case 'js' :
        			            $mimeType = 'text/js';
        			            break;
        			    }
        			}
        	        header("Content-Type: $mimeType");
        	        include ($destinationFile);
        	        define ('DOCPATH', $GLOBALS['TEMP']['docpath']);
        	        kernel_log('Sent the theme file '.DOCPATH);
        	    } else {
        	        kernel_log('File ' . substr($GLOBALS['TEMP']['docpath'], strlen($GLOBALS['CONFIG']['themes_fromWebroot'])+1) . ' not found in the current theme', 4);
        	        if (file_exists('./404.html')){
                        DEFINE('DOCPATH','/404');
                        $GLOBALS['THEME']['page_title'] = $DOCNAME[404];
                    } else {
                        DEFINE('DOCPATH','404');
                    }
        	    }
    	}
    	elseif ($GLOBALS['TEMP']['docpath'] == "/" and file_exists('./'.$GLOBALS['CONFIG']['default_document']. '.' . $GLOBALS['CONFIG']['documents_extension'])) {
    		define ('DOCPATH',"/".$GLOBALS['CONFIG']['default_document']);
    		$GLOBALS['THEME']['page_title'] = $DOCNAME[$GLOBALS['CONFIG']['default_document']];
    		kernel_log("Sent DEFAULT document");
    	} elseif (file_exists(".".$GLOBALS['TEMP']['docpath'].'.' . $GLOBALS['CONFIG']['documents_extension'])) {
    		define ('DOCPATH',$GLOBALS['TEMP']['docpath']);
    		if (! isset($DOCNAME[substr(DOCPATH,1)])) {
    		    $GLOBALS['THEME']['page_title'] = $GLOBALS['INFO']['web_location'];
    		} else {
    		    $GLOBALS['THEME']['page_title'] = $DOCNAME[substr(DOCPATH,1)];
    		}
    		kernel_log("Sent document '" . DOCPATH . "'");
    
    	} elseif (file_exists(".".$GLOBALS['TEMP']['docpath']) and is_dir(".".$GLOBALS['TEMP']['docpath']) and file_exists(".".$GLOBALS['TEMP']['docpath'] . '/' . $GLOBALS['CONFIG']['default_document'] . '.' . $GLOBALS['CONFIG']['documents_extension'] )) {
            define ('DOCPATH',$GLOBALS['TEMP']['docpath'] . '/' . $GLOBALS['CONFIG']['default_document']);    
    	        if (! isset($DOCNAME[substr(DOCPATH,1)])) {
    			    $GLOBALS['THEME']['page_title'] = $GLOBALS['INFO']['web_location'];
    			} else {
    			    $GLOBALS['THEME']['page_title'] = $DOCNAME[substr(DOCPATH, 1)];
    		}
            kernel_log("Sent DEFAULT document for " . DOCPATH);
        } else {
            $file = $GLOBALS['TEMP']['docpath'];
            $fileExt = strrchr($file, '.');
            
            if ($fileExt !== false and substr($file, 1, 1) != "." and strlen($fileExt) >= 3) {
                foreach ($GLOBALS['CONFIG']['allowed_file_ext'] as $ext) {
                    if ($fileExt == '.'.$ext and file_exists(substr($file,1))) {
                        if ($GLOBALS['CONFIG']['debug'] === true and $GLOBALS['CONFIG']['debug_output_level'] !== 0){
                            kernel_log("DEBUG IS ENABLED and debug_output_level is not set to zero. File download is not possible in that case. Please disable debug or set debug_output_level to 0", 2);
                            unset ($fileExt);
                            break;
                        } else { 
    						kernel_log("File '$file' requested");
                            unset($fileExt);
    						$fileInfoHandle = finfo_open(FILEINFO_MIME_TYPE);
    						$mimeType = finfo_file($fileInfoHandle, substr($file,1));
    						finfo_close($fileInfoHandle);
    						$fileSize = filesize(substr($file,1));
                            ob_end_clean();ob_end_clean();
    						header('Content-Type: $mimeType');
    						header("Content-Length: $fileSize");
    						kernel_log ('Download of file ' . substr($file, 1)." requested. File size: $fileSize Bytes");
    						readfile (substr($file, 1));
    						define('SHUTTINGDOWN', true);
                            kernel_shutdown();
                        }
    				}
                }
                if (file_exists('./404.html')){ //TODO: Optimize this
                    DEFINE('DOCPATH','/404');
                    $GLOBALS['THEME']['page_title'] = $DOCNAME[404];
                } else {
                    DEFINE('DOCPATH','404');
                }
            } else {
    			if (file_exists('./404.html')){
                    DEFINE('DOCPATH','/404');
                    $GLOBALS['THEME']['page_title'] = $DOCNAME[404];
                } else {
                    DEFINE('DOCPATH','404');
                }
    		}
    	}
    		if (DEFINED ('DOCPATH') and DOCPATH !== '404' and strpos($GLOBALS['TEMP']['docpath'], $GLOBALS['CONFIG']['themes_fromWebroot'] . '/') !== 1) {
    			if (DOCPATH === '/404') {
                    kernel_log ("File ". $GLOBALS['TEMP']['docpath'] ." not found. Sent 404",4);
                }
    			kernel_vartemp_clear();
    			kernel_event_trigger("STARTUP");
    			
    			include_once ($GLOBALS['THEME']['location'] . "/functions.php");
                if ($GLOBALS['CONFIG']['theme_checkVersion']) {
                    $isCompatible = false;
                    foreach (theme_getCompatibleVersions() as $version) {
                        if (version_compare(framework_version, $version) == 0) {
                            $isCompatible = true;
                            break;
                        }
                    }
                    if (!$isCompatible) {
                        kernel_log('Possible incompatible theme detected! Please check version', 4);
                    }
                }
    			include_once ($GLOBALS['THEME']['location'] . "/header.php");
    			kernel_event_trigger('SHOWHEADER');
    			include_once("." . DOCPATH . "." . $GLOBALS['CONFIG']['documents_extension']);
    			kernel_event_trigger('SHOWCONTENT');
    			include_once ($GLOBALS['THEME']['location'] . '/footer.php');
    			kernel_event_trigger('SHOWFOOTER');
            } elseif (DEFINED ('DOCPATH') and DOCPATH === '404') {
                    kernel_log ("File " . $GLOBALS['TEMP']['docpath'] . " and 404 document not found. Sent 404 with built-in error page", 4);
                    header("HTTP/1.0 404 Not Found");
                echo <<<OUT
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
OUT;
                echo "<p>The requested URL " . $_SERVER['REQUEST_URI'] ." was not found on this server.</p>";
                echo <<<OUT
<p>Additionally, an error occurred while trying to find the 404 document</p>
<hr/>
OUT;
echo '<p style="font-style:italic;">Generated by Wh1t3project\'s PHP framework V' . framework_version . '</p>
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
                if ($cmd != null) {
                    echo "\r\n";
                }
            }
        }
    }
}

define('SHUTTINGDOWN', true);
kernel_shutdown(0);
?>