<?php
// Kernel "override" module
/* Small library for overriding how the framework work.
 
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
if (! DEFINED("INSCRIPT")) {echo "Direct access denied";exit(1);}



function kernel_override_url ($URL = null, $pathToScript = null, $mode = 2){
    $callinfo = debug_backtrace();
	$file = $callinfo[0]['file'];
	$file = str_replace ("\\","/",$file); 
    static $DATA = array();
    if ($file === $GLOBALS['CONFIG']['app_real_location']."/index.php") { 
        return $DATA;
    }
    if (gettype($URL) != 'string' or $URL == '' or gettype($pathToScript) != 'string' or $pathToScript == '') {
        kernel_log('Missing argument for "kernel_override_url"', 3);
        return false;
    }
	if (($script = realpath(dirname($file) . '/' . $pathToScript)) === FALSE) {
        kernel_log("File '$pathToScript' does not exist or is inaccessible",3);
        return false;
    }
    if (strpos(str_replace('\\', '/', $script), $GLOBALS['CONFIG']['app_real_location'] . '/' . $GLOBALS['CONFIG']['modules'] . '/' . kernel_getCallerModule() . '/') !== 0) {
        kernel_log('Attempt to register script outside of module\'s directory', 3);
        return false;
    }
	if ($mode != 1 and $mode != 2) {
        kernel_log("Invalid type '$mode'",3);
        return false;
    }
	if (stripos($URL,'/') !== 0) { 
        $URL = "/".$URL;
    }
	if ($URL[strlen($URL) - 1] == "/") {
        $URL = substr($URL,0,-1);
    }
	foreach ($DATA as $i) {
        if ($URL == $i['URL']) {
            kernel_log("Attempt to register an already registered URL '{$i['URL']}'. Request ignored.", 3);
            return false;
        } 
    }
    $newOverride = array();
	$newOverride['URL'] = $URL;
	$newOverride['SCRIPT'] = $script;
	$newOverride['TYPE'] = $mode;
	$newOverride['SCRIPTNAME'] = $pathToScript;
    $DATA[] = $newOverride;
	$log = "Script '$pathToScript' registered with URL '$URL' using ";
	switch ($mode) {
		case 1:
			$log .= "normal mode";
			break;
		case 2:
			$log .= "explicit mode";
			break;
	}
	kernel_log($log);
	return true;
}
kernel_log("Module ready");
?>