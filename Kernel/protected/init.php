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
        kernel_log("No argument or empty value when calling 'kernel_protected_var'",3);
        return;
    }
    if (gettype($varName) != 'string') {
        kernel_log('Invalid argument type for \'kernel_protected_var\'', 3);
    }
	static $DATA = array();
	$callinfo = debug_backtrace();
	$file = $callinfo[0]['file'];
	if ($varData === "") {
		if (isset ($DATA[$file][$varName])) {
            return $DATA[$file][$varName];
		} else { 
            kernel_log("Undefined variable: $varName in $file on line ".$callinfo[0]['line'],4);
            return null;
        }
	} else {
		if ($varData === null) {
            unset ($DATA["$file"]["$varName"]);
            return true;
		} else { 
            $DATA["$file"]["$varName"] = $varData;
            return true;
        }
	}
}
kernel_log("Module ready");
?>