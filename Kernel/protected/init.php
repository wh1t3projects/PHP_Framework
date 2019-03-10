<?php
// Kernel "Protected" module
/* Small library for protecting data from other scripts.

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
if (! DEFINED('INSCRIPT')) {echo 'Direct access denied'; exit(1);}

function kernel_protected_var($varName, $varData = "") {
	if ($varName == null) {
        kernel_log("No argument or empty value when calling 'kernel_protected_var'", 3);
        return;
    }
    if (gettype($varName) != 'string') {
        kernel_log('Invalid argument type for \'kernel_protected_var\'', 3);
    }
	static $DATA = array();
	$callinfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
	$file = $callinfo[0]['file'];
    if (__FILE__ === $file) {
        $requestInfo = json_decode($varName, TRUE);
        $file = $callinfo[1]['file'];
        preg_match('/.*(?<='.$GLOBALS['CONFIG']['modules'].'\/).*?(?=\/)/', str_replace('\\', '/', $file), $pathToModule);
        if ($requestInfo['pathToScript'][0] == '/') {
            $pathToScript = realpath("{$pathToModule[0]}{$requestInfo['pathToScript']}");
        } else {
            $pathToScript = realpath(dirname($file) . "/{$requestInfo['pathToScript']}");
        }
        static $SHAREDVARS = array();
        switch ($requestInfo['ACTION']) {
            case 'addScript':
                if ($pathToScript === FALSE) {
                    kernel_log("'{$requestInfo['pathToScript']}' doesn't exist or is inaccessible", 3);
                    return false;
                }
                if (!isset ($DATA[$file][$requestInfo['varName']])) {
                    kernel_log("'{$requestInfo['varName']}' doesn't exist", 3);
                    return false;
                }
                if (isset ($DATA[$pathToScript][$requestInfo['varName']])) {
                    kernel_log("'{$requestInfo['varName']}' is already set in target script", 4);
                    return false;
                }
                $DATA[$pathToScript][$requestInfo['varName']] = &$DATA[$file][$requestInfo['varName']];
                $SHAREDVARS["$pathToScript:{$requestInfo['varName']}"] = $file;
                break;
            case 'delScript':
                if (!isset ($DATA[$pathToScript][$requestInfo['varName']])) {
                    kernel_log("'{$requestInfo['varName']}' is not set in target script", 4);
                    return false;
                } else if ($SHAREDVARS["$pathToScript:{$requestInfo['varName']}"] != $file) {
                    kernel_log("Invalid attempt to unshare variable '{$requestInfo['varName']}' in script '{$requestInfo['pathToScript']}'", 3);
                    return false;
                } else {
                    unset ($DATA[$pathToScript][$requestInfo['varName']]);
                }
                break;
        }
        return true;
    }
	if ($varData === "") {
		if (isset ($DATA[$file][$varName])) {
            return $DATA[$file][$varName];
		} else { 
            kernel_log("Undefined variable: $varName in $file on line " . $callinfo[0]['line'], 4);
            return null;
        }
	} else {
		if ($varData === null) {
            unset ($DATA[$file][$varName]);
            return true;
		} else { 
            $DATA[$file][$varName] = $varData;
            return true;
        }
	}
    
}
function kernel_protected_var_addScript($varName, $pathToScript) {
    if (kernel_protected_var(json_encode(array(
        'ACTION' => 'addScript',
        'varName' => $varName,
        'pathToScript' => $pathToScript
    )))) {
        kernel_log("Variable '$varName' shared with script '$pathToScript'");
        return true;
    } else {
        return false;
    }
}
function kernel_protected_var_deleteScript($varName, $pathToScript) {
    if (kernel_protected_var(json_encode(array(
        'ACTION' => 'delScript',
        'varName' => $varName,
        'pathToScript' => $pathToScript
    )))) {
        kernel_log("Variable '$varName' removed from '$pathToScript'");
        return true;
    } else {
        return false;
    }
}
kernel_log("Module ready");
?>