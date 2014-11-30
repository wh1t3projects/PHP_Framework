<?php
// Setup file. 
/* Used for first-time use. Allow users to easly configure the framework.

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
error_reporting(E_ALL);
session_start();
if (isset ($_GET['close'])) {@unlink ("setup.active") or die ("No open session <a href='setup.php'>Start new session</a>"); session_destroy(); echo "Session closed. <a href='setup.php'>Start new session</a>"; exit(); }
if (file_exists('config.php')){ echo "A configuration file already exist. If you want to re-run this configuration wizard, please delete it or rename it";exit(1);}
if (file_exists('setup.active')) { $sesid = file_get_contents('setup.active');}
if (isset ($sesid) and $sesid !== session_id() or isset ($_SESSION['ip']) and $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    echo "Setup is already running. If you want to restart the setup session, please delete the file 'setup.active'";
}
else{

    echo <<<HEAD
        <html lang="en">
    <head>
        
        <title>Framework configuration wizard</title>
        <meta charset="utf-8" http-equiv="content-type">
        
        <style>
            body{
                margin:0px;
                padding:0px;
                font-family: sans-serif;
                font-size: 14px;
                font-style: normal;
                
            }
            *{ box-sizing: border-box;
            box-sizing: padding-box;}
            [class*="col-"]{
            float: left;
            position: relative;
            min-height: 1px;
            padding:10px;
            content: "";
            display: table;
            /*background-color: #f1f1f1;
            border: 1px solid;*/
            }

            .container{
            margin:20px;
            }

            .col-12{ width: 100%;       }
            .col-11{ width: 91.666666%; }
            .col-10{ width: 83.333333%; }
            .col-9 { width: 75%;        }
            .col-8 { width: 66.666666%; }
            .col-7 { width: 58.333333%; }
            .col-6 { width: 50%;        }
            .col-5 { width: 41.666666%; }
            .col-4 { width: 33.333333%; }
            .col-3 { width: 25%;        }
            .col-2 { width: 16.666666%; }
            .col-1 { width: 8.333333%;  }
            
            .banner{
                width:100%;
                background-color:#f1f1f1;
                color:#393939;
                border-bottom: 2px solid #393939;
            }
            .banner h1{
                font-size: 15px;
                padding-top: 10px;
                text-transform: uppercase;
                text-align: center;
            }
            .text{
                font-size:15px;
                border-right: 2px solid #f1f1f1;
                padding: 8px 8px 8px 8px;
                text-align: right;
            }
            .input{
                font-weight: 100;
                padding: 5px 5px 5px 5px;
                border: 2px solid #f1f1f1;
                width: 60%;
                min-width: 325px;
            }                
            
            fieldset{
                border: 2px solid #333333;
                padding:5px;
                margin-bottom:25px;
                border-radius:5px;
            }
            fieldset legend{
                padding:10px;
                text-transform: uppercase;
                font-size: 12px;
                font-weight: 800;
                background-color:#333333;
                color:#ffffff;
                border-radius:5px;
            }
            table{
                border: none;
                width: 100%;
                font-size: 12px;
            }
            table td{
                padding:5px;
                
                    
            }
            .right{
                text-align: right;
                font-weight: 800;
                width: 35%;
            }
            placeholder{
                font-weight: 100;
            }
            .yn{
                padding:0px 10px 0px 0px;
            }
            .error{
                background-color:#FFE5E5;
                border:2px solid #AA0114;
                padding:8px;
                width:30%;
                color:#AA0114;
                text-align: center;
                margin:5px;
                border-radius:5px;
            }
            .success{
                background-color:#dbffd4;
                border:2px solid #188f00;
                padding:8px;
                width:30%;
                color:#188f00;
                text-align: center;
                margin:5px;
                border-radius:5px;
            }
            .info{
                background-color:#e3e3e3;
                border:2px solid #4d4d4d;
                padding:8px;
                width:30%;
                color:#4d4d4d;
                text-align: center;
                margin:5px;
                border-radius:5px;
            }
            .warning{
                background-color:#ffffc7;
                border:2px solid #858503;
                padding:8px;
                width:30%;
                color:#858503;
                text-align: center;
                margin:5px;
                border-radius:5px;
            }
            .btn{
                background-color:#333333;
                width:15%;
                color:#ffffff;
                border:none;
                padding: 7px;
                
            }
            #reset{
                text-align:center;
                float: right;
            }
            .btn:hover{
                background-color:#222222;
            }
            .btn:active{
                background-color:#000000;
            }
        </style>
    
    </head>
    <body>
        <div class="banner">
        <h1>Framework configuration wizard</h1>
        </div>
        <div class="container">
            <div class="col-12">
HEAD;

    if (isset ($sesid)) {
        
        // Default settings value
        if (!isset ($_SESSION['isactive'])) {
            $_SESSION['webroot'] = 'webroot';
            $_SESSION['themes'] = 'themes';
            $_SESSION['themes_css'] = 'css';
            $_SESSION['themes_js'] = 'js';
            $_SESSION['themes_img'] = 'img';
            $_SESSION['themes_mod'] = 'modules';
            $_SESSION['modules'] = 'modules';
            $_SESSION['sql_user'] = '';
            $_SESSION['sql_pass'] = '';
            $_SESSION['sql_host'] = '';
            $_SESSION['sql_db'] = '';
            $_SESSION['sql_prefix'] = 'prefix_';
            $_SESSION['sql_drv'] = '';
            $_SESSION['app_location'] = substr($_SERVER['REQUEST_URI'], 0, -9);
            $_SESSION['app_real_location'] = getcwd();
            $_SESSION['default_document'] = 'index';
            $_SESSION['theme'] = 'default';
            $_SESSION['lang'] = 'en';
            $_SESSION['timezone'] = date_default_timezone_get();
            $_SESSION['allowed_file_ext'] = 'jpg;gif;png;txt';
            $_SESSION['debug'] = "false";
            $_SESSION['debug_file'] = '/debug.log';
            $_SESSION['debug_level'] = "0";
            $_SESSION['debug_panic'] = "true";
            $_SESSION['isactive'] = true;
            
        } elseif( isset ($_POST['debug_file'])) {
            
            $_SESSION['webroot'] = $_POST['webroot'];
            $_SESSION['themes'] = $_POST['themes'];
            $_SESSION['themes_css'] = $_POST['themes_css'];
            $_SESSION['themes_js'] = $_POST['themes_js'];
            $_SESSION['themes_img'] = $_POST['themes_img'];
            $_SESSION['themes_mod'] = $_POST['themes_mod'];
            $_SESSION['modules'] = $_POST['modules'];
            $_SESSION['sql_user'] = $_POST['sql_user'];
            $_SESSION['sql_pass'] = $_POST['sql_pass'];
            $_SESSION['sql_host'] = $_POST['sql_host'];
            $_SESSION['sql_db'] = $_POST['sql_db'];
            $_SESSION['sql_prefix'] = $_POST['sql_prefix'];
            $_SESSION['sql_drv'] = $_POST['sql_drv'];
            $_SESSION['app_location'] = $_POST['app_location'];
            $_SESSION['app_real_location'] = $_POST['app_real_location'];
            $_SESSION['default_document'] = $_POST['default_document'];
            $_SESSION['theme'] = $_POST['theme'];
            $_SESSION['lang'] = $_POST['lang'];
            $_SESSION['timezone'] = $_POST['timezone'];
            $_SESSION['allowed_file_ext'] = $_POST['allowed_file_ext'];
            $_SESSION['debug'] = $_POST['debug'];
            $_SESSION['debug_file'] = $_POST['debug_file'];
            $_SESSION['debug_level'] = $_POST['debug_level'];
            $_SESSION['debug_panic'] = $_POST['debug_panic'];
        }
        echo '
        <form action="setup.php" method="POST">
        <input value="Save and apply" type="submit" class="btn"/><input readonly id="reset" value="Close session / Reset" class="btn" onclick="document.location = \'setup.php?close\'"/><p/>';
        if (isset ($_POST['debug_file'])) {
            // Check required settings
            $error = false;
            if ($_SESSION['webroot'] == null or $_SESSION['themes'] == null or $_SESSION['themes_css'] == null or $_SESSION['themes_js'] == null or $_SESSION['themes_img'] == null or $_SESSION['themes_mod'] == null or $_SESSION['modules'] == null or $_SESSION['app_location'] == null or $_SESSION['app_real_location'] == null or $_SESSION['theme'] == null or $_SESSION['lang'] == null or $_SESSION['timezone'] == null or $_SESSION['allowed_file_ext'] == null or $_SESSION['debug'] == null or $_SESSION['debug_file'] == null) { $error = true;}
            if (!is_dir ($_SESSION['app_real_location']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['webroot']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['themes']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['themes'].'/'.$_SESSION['theme'].'/'.$_SESSION['themes_css']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['themes'].'/'.$_SESSION['theme'].'/'.$_SESSION['themes_js']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['themes'].'/'.$_SESSION['theme'].'/'.$_SESSION['themes_js']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['themes'].'/'.$_SESSION['theme'].'/'.$_SESSION['themes_img']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['themes'].'/'.$_SESSION['theme'].'/'.$_SESSION['themes_mod']) or !is_dir ($_SESSION['app_real_location'].'/'.$_SESSION['modules'])) { $error = true;}
            if ($_SESSION['debug_level'] < 0 or $_SESSION['debug_level'] > 4) { $error = true;}
            if ($error == true) {
                echo '<center><div class="error">One or more settings are using a bad or missing value</div></center>';
            } else {
                $config = "<?php
if (! DEFINED('INSCRIPT')) {echo 'Direct access denied'; exit(1);}\r\n";
                foreach($_SESSION as $key=>$item) {
                    if ($key == 'ip' or $key == 'isactive') {} else {
                        if ($key === 'debug' or $key === 'debug_panic' or $key === 'debug_level') {
                            $config .= "\$CONFIG['$key'] = $item;\r\n";
                        } elseif ($key === 'allowed_file_ext') {
                            $item = explode(';',$item);
                            $config .= "\$CONFIG['$key'] = array(";
                            foreach ($item as $item2) {
                                $config .= "'$item2',";
                            }
                            $config = substr($config, 0, -1).");\r\n";
                        } else {
                        $config .= "\$CONFIG['$key'] = '$item';\r\n";            
                        }
                    }
                }
                $config .= '?>';
                                // Create htaccess
                $htaccess = 'Options +SymLinksifOwnerMatch -Indexes

ErrorDocument 403 /'.$_SESSION['app_location'].'index.php
ErrorDocument 404 /'.$_SESSION['app_location'].'index.php
RewriteEngine On
RewriteCond %{REQUEST_URI} !.themes/.*$ [NC]
RewriteRule ^(.*) index.php';
                
                if (file_put_contents('config.php',$config) === false){
                    echo '<center><div class="error">An error as occured while saving the configuration. Please check the permissions and try again</div></center>';
                } elseif (file_put_contents('.htaccess',$htaccess) === false) {
                    echo '<center><div class="error">An error as occured while saving the .htaccess file. Please check the permissions and try again</div></center>';
                } else {
                    echo '<center><div class="success">The configuration has been saved successfuly. For security reason, this wizard is now disabled<br/><a href=".">Click here to test the installation</a></div></center>';
                    session_destroy();
                    unlink('setup.active');
                }
            }
        }
        echo '                <fieldset>
                    <legend>System</legend>
                    <table>
                        <tr>
                            <td class="right">Which folder is the root of the website?</td>
                            <td><input name="webroot" type="text" class="input" placeholder="" value="'.$_SESSION['webroot'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">Which folder hold the themes?</td>
                            <td><input name="themes" type="text" class="input" placeholder="" value="'.$_SESSION['themes'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">Which folder hold the modules?</td>
                            <td><input name="modules" type="text" class="input" placeholder="" value="'.$_SESSION['modules'].'"></td>
                        </tr>
                    
                    </table>
                    
                </fieldset>
                <fieldset>
                    <legend>Database</legend>
                    <table>
                    <center><div class="info">If you don\'t plan to use SQL, you can leave the defaults</div></center>
                        
                        <tr>
                            <td class="right">What is the SQL host name?</td>
                            <td><input name="sql_host" type="text" class="input" placeholder="Optional" value="'.$_SESSION['sql_host'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the username?</td>
                            <td><input name="sql_user" type="text" class="input" placeholder="Optional" value="'.$_SESSION['sql_user'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the password?</td>
                            <td><input name="sql_pass" type="text" class="input" placeholder="Optional" value="'.$_SESSION['sql_pass'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the name of the database?</td>
                            <td><input name="sql_db" type="text" class="input" placeholder="Optional" value="'.$_SESSION['sql_db'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">Which driver to use?</td>
                            <td><select name="sql_drv"><option value="">None</option>';
        $dirhandle = opendir('Kernel/sql/');
while (false !== ($file = readdir($dirhandle))) {
    if (stripos($file,'.') !== 0 and substr($file,-4) == ".php" and $file != 'init.php') {
        $file = substr($file,0,-4);
        echo'<option ';
        if ($file === $_SESSION['sql_drv']) {echo 'selected ';}
        echo "value=$file>$file</option>";
    }
}
closedir($dirhandle);
unset ($dirhandle);
echo '        </select><em>Those are the currently available drivers. If you want to add more, please see "SQL Drivers" in the documentation</em></td>
                        </tr>
                        <tr>
                            <td class="right">What is the tables prefix?</td>
                            <td><input name="sql_prefix" type="text" class="input" placeholder="" value="'.$_SESSION['sql_prefix'].'"></td>
                        </tr>
                    
                    </table>
                    
                </fieldset>
                <fieldset>
                    <legend>APPLICATION</legend>
                    <table>
                        <tr>
                            <td class="right">What is the base URL?</td>
                            <td><input name="app_location" type="text" class="input" value="'.$_SESSION['app_location'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">Where the framework is installed?</td>
                            <td><input name="app_real_location" type="text" class="input" value="'.$_SESSION['app_real_location'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the name of the theme?</td>
                            <td><input name="theme" type="text" class="input" value="'.$_SESSION['theme'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the name of the default document to show?</td>
                            <td><input name="default_document" type="text" class="input" value="'.$_SESSION['default_document'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the ISO 639-1 language code?</td>
                            <td><input name="lang" type="text" class="input" value="'.$_SESSION['lang'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is your time zone (in PHP format)?</td>
                            <td><input name="timezone" type="text" class="input" value="'.$_SESSION['timezone'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What are the file"s extension that can be downloaded directly?</td>
                            <td><input name="allowed_file_ext" type="text" class="input" value="'.$_SESSION['allowed_file_ext'].'"></td>
                        </tr>
                    
                    </table>
                    
                </fieldset>
                  <fieldset>
                    <legend>Theme</legend>
                    <center><div class="warning">Those folders are relative to the \'themes\' folder setting</div></center>
                    <table>
                        <tr>
                            <td class="right">What is the folder that hold the css files?</td>
                            <td><input name="themes_css" type="text" class="input" value="'.$_SESSION['themes_css'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the folder that hold the js files?</td>
                            <td><input name="themes_js" type="text" class="input" value="'.$_SESSION['themes_js'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the folder that hold the image files?</td>
                            <td><input name="themes_img" type="text" class="input" value="'.$_SESSION['themes_img'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">What is the folder that hold the modules theme files?</td>
                            <td><input name="themes_mod" type="text" class="input" value="'.$_SESSION['themes_mod'].'"></td>
                        </tr>
                    </table>
                </fieldset>
                <fieldset>
                    <legend>Debug</legend>
                    <table>
                        <tr>
                            <td class="right">Enable debugging?</td>'; 
        if ($_SESSION['debug'] === "true") {
            echo '                            <td><input name="debug" type="radio" value=true checked><span class="yn" enabled>Yes</span><input type="radio" name="debug" value=false>No</td>';
        } else {
            echo '                            <td><input name="debug" type="radio" value=true><span class="yn" enabled>Yes</span><input type="radio" name="debug" value=false checked>No</td>';
        }
        echo '                        </tr>
                        <tr>
                            <td class="right">If debug is enabled, which file is used for storing the log?</td>
                            <td><input name="debug_file" type="text" class="input" value="'.$_SESSION['debug_file'].'"></td>
                        </tr>
                        <tr>
                            <td class="right">Allow application to run even if a kernel panic occured before?</td>'; 
        if ($_SESSION['debug_panic'] === "true") {
            echo '                            <td><input name="debug_panic" type="radio" value=true checked><span class="yn" enabled>Yes</span><input type="radio" name="debug_panic" value=false>No</td>';
        } else {
            echo '                            <td><input name="debug_panic" type="radio" value=true><span class="yn" enabled>Yes</span><input type="radio" name="debug_panic" value=false checked>No</td>';
        }
        echo '                        </tr>
                        
                        <tr>
                            <td class="right">If debug is enabled, which level of debugging do you want to show?</td>
                            <td><input name="debug_level" type="number" class="" style="width: 40px" min="0" max="4" step="1" value="'.$_SESSION['debug_level'].'"></td>
                        </tr>
                    </table>
                    
                </fieldset>
                    <input type="submit" value="Save and apply" class="btn"/><input readonly id="reset" value="Close session / Reset" class="btn" onclick="document.location = \'setup.php?close\'"/>
                </form>';
        
        //session_destroy();
    } else {
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        file_put_contents('setup.active',session_id()) or die ("Permission error in current directoy");
        echo 'Initializing, please wait...<script type="text/javascript"> window.setTimeout(function(){window.location.href = "setup.php";},500);</script>';
        ;
        
    }
    echo <<<FOOT
    </body>
    </html>
FOOT;
}
?>