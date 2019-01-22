<?php
// Kernel 'bootloader'. 
/* Load all kernel modules

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

// Note about how the kernel works:
// Since there are critical dependencies, we cannot load all the kernel modules using a generic listing.
// Instead, we load each module separately with a manual 'include'. This also prevent problems if 
// someone put a module in the directory by error because it won't be loaded at all.

// Core functions. DO NOT ALTER!
if (! DEFINED('INSCRIPT')) {
    echo 'Direct access denied'; 
    exit(1);
}

if ($CONFIG['debug_panic'] === FALSE and file_exists($GLOBALS['CONFIG']['app_real_location'] . '/panic.log')) {
    echo 'A Kernel panic has occured and "debug_panic" is disabled. Cannot continue.';
    exit(9999);
}
    
$TEMP = array();
date_default_timezone_set($CONFIG['timezone']) or die ('Error while setting timezone. Please check config.');

function kernel_date($format = null) {
    if ($format == null) {
        return (date('Y-m-d H:i:s'));
    } else { 
        return (date($format));
    }
}
function kernel_panic() {
    kernel_log('(PANIC) KERNEL PANIC TRIGGERED! DUMPING LOG AND EXITING!');
    ob_start();
    debug_print_backtrace();
    $stack = ob_get_clean();
    kernel_log ("STACK TRACE:\r\n" . $stack);
    $LOG = kernel_log();
    $file = $GLOBALS['CONFIG']['app_real_location'] . '/panic.log';
    if (is_writable($file) or ! file_exists($file)) {
        file_put_contents($file, $LOG);
    } else {
        echo 'PANIC: A FATAL EXCEPTION OCCURED AND CANNOT WRITE TO THE LOG! PLEASE CHECK PERMISSIONS!'; 
    }
    define ('SHUTTINGDOWN', true);
    kernel_shutdown(666);
}
function kernel_vartemp_clear() {
    unset ($GLOBALS['TEMP']);
    $GLOBALS['TEMP'] = array();
}

function kernel_log($message = null, $messageLevel = 5) {
    static $LOG = array();
    if (isset($message)) {
        if (!is_string($message) or !is_numeric($messageLevel)) {
            kernel_log ("Invalid argument type when calling 'kernel_log'. 1: $message, 2: $messageLevel.", 3);
            return false; 
        }
        if ($messageLevel > 5) {
            kernel_log("Log level is greater than 5. 1: $message, 2: $messageLevel", 4); 
            $messageLevel = 5;
        }
        if ($messageLevel < 1) {
            kernel_log('Log level is lower than 1. Ignoring the request', 3);
            return false;
        }
        $callInfo = debug_backtrace();
        $file = $callInfo[0]['file'];
        $file = str_replace("\\", "/", $file); 

        if (isset ($callInfo['function']) and $callInfo['function'] === 'kernel_log') {
            $prefix = 'kernel_log';
        } else {
            if ($module = kernel_getCallerModule()) {
                $prefix = strtoupper($module);
                $location = "{$callInfo[0]['file']}:{$callInfo[0]['line']}";
            } else if ($file === $GLOBALS['CONFIG']['app_real_location'] . '/index.php') {
                $prefix = 'SYSTEM';
                if (COREDEBUG) {
                    $location = "{$callInfo[0]['file']}:{$callInfo[0]['line']}";
                } else {
                    $location = null;
                }
            } else if ($file === $GLOBALS['CONFIG']['app_real_location'] . '/Kernel/boot.php') {
                $prefix = 'KERNEL';
                if (COREDEBUG or php_sapi_name() === 'cli' and !isset($callInfo[1]['file']) or $callInfo[1]['function'] === 'kernel_shutdown') {
                    $location = "{$callInfo[0]['file']}:{$callInfo[0]['line']}";
                } else if($callInfo[1]['function'] == 'kernel_phpErrorHandler') {
                    $location = null;
                } else {
                    $location = "{$callInfo[1]['file']}:{$callInfo[1]['line']}";
                }
            } else if (strpos($file, $GLOBALS['CONFIG']['app_real_location'] . '/Kernel') === 0) {
                preg_match('/(?<=Kernel\/).*?(?=\/)/', $file, $module);
                $prefix = 'KERNEL_' . strtoupper($module[0]);
                if (COREDEBUG or php_sapi_name() === 'cli' and !isset($callInfo[1]['file'])) {
                    $location = "{$callInfo[0]['file']}:{$callInfo[0]['line']}";
                } else {
                    $location = "{$callInfo[1]['file']}:{$callInfo[1]['line']}";
                }
            } else if (strpos ($file, $GLOBALS['CONFIG']['app_real_location'] . '/' . $GLOBALS['CONFIG']['themes']) === 0) {
                $prefix = $GLOBALS['CONFIG']['theme'] . ' THEME';
                $location = "{$callInfo[0]['file']}:{$callInfo[0]['line']}";
            } else if (php_sapi_name() === 'cli') {
                $prefix = 'CLI';
                $location = false;
            } else {
                $prefix = basename($file); 
            }
        }
        $prefix .= ': ';
        $suffix = null;
        switch ($messageLevel) {
            case 1:
                $prefix .= '(PANIC) ';
                $suffix = null;
                break;
            case 2:
                $prefix .= '(FATAL) ';
                $suffix = $location ?" at $location":null;
                break;
            case 3:
                $prefix .= '(CRITICAL) ';
                $suffix = $location ?" at $location":null;
                break;
            case 4:
                $prefix .= '(WARNING) ';
                $suffix = null;
                break;
        }
        $text = '[' . kernel_date() . "] $prefix$message$suffix\r\n";
        array_push($LOG, $text);
        if ($GLOBALS['CONFIG']['debug'] == TRUE){ 
            switch ($messageLevel) {
                case 5:
                    if ($GLOBALS['CONFIG']['debug_level'] >= 4) {
                        file_put_contents($GLOBALS['CONFIG']['app_real_location'] . $GLOBALS['CONFIG']['debug_file'], $text, FILE_APPEND);
                    }
                    if ($GLOBALS['CONFIG']['debug_output_level'] >= 4) {
                        echo $text;
                    }
                    break;
                case 4:
                    if ($GLOBALS['CONFIG']['debug_level'] >= 3) {
                        file_put_contents($GLOBALS['CONFIG']['app_real_location'] . $GLOBALS['CONFIG']['debug_file'], $text, FILE_APPEND);
                    }
                    if ($GLOBALS['CONFIG']['debug_output_level'] >= 3) {
                        echo $text;
                    }
                    break;
                case 3:
                    if ($GLOBALS['CONFIG']['debug_level'] >= 2) {
                        file_put_contents($GLOBALS['CONFIG']['app_real_location'] . $GLOBALS['CONFIG']['debug_file'], $text, FILE_APPEND);
                    }
                    if ($GLOBALS['CONFIG']['debug_output_level'] >= 2) {
                        echo $text;
                    }
                    break;
                case 2:
                    if ($GLOBALS['CONFIG']['debug_level'] >= 1) {
                        file_put_contents($GLOBALS['CONFIG']['app_real_location'] . $GLOBALS['CONFIG']['debug_file'], $text, FILE_APPEND);
                    }
                    if ($GLOBALS['CONFIG']['debug_output_level'] >= 1) {
                        echo $text;
                    }
                    break;
                case 1:
                    $GLOBALS['CONFIG']['debug_level'] = 4; 
                    echo $text; 
                    file_put_contents($GLOBALS['CONFIG']['app_real_location'] . $GLOBALS['CONFIG']['debug_file'], $text, FILE_APPEND);
                    kernel_panic();
                    break;
            }
        }
        return true;
    } else {        
        return $LOG;
    }
}
function kernel_shutdown($exitcode = null) {
    if (!defined('SHUTTINGDOWN')) {
        $debug_code = rand(1000000, 9999999);
        error_log("Unexpected shutdown. DEBUG CODE: $debug_code");
        kernel_log("Unexpected shutdown detected! Possible PHP FATAL error. DEBUG CODE: $debug_code", 1);
    }
    if ($exitcode !== 9999) {
        kernel_log('Shutting down...');
        kernel_event_trigger('SHUTDOWN');
        kernel_log("HALT\r\n");
        define ('EXITCODE', $exitcode);
    }
    exit(EXITCODE);
}
function kernel_getCaller($depth = 0) {
    $callInfo = debug_backtrace();
    if (gettype($depth) !==  'integer') { 
        kernel_log('Invalid argument type. Expecting integer, got ' . gettype($depth));
        return false;
    }
    if ($depth < 0) { 
        kernel_log('Argument cannot be negative', 3);
        return false;
    }
    if (!isset ($callInfo[2+$depth])) { 
        kernel_log('Depth is too far.', 3);
        return false;
    }
    return $callInfo[2+$depth]['function'];
}
function kernel_getCallerModule() {
    $callInfo = debug_backtrace();
    $file = $callInfo[1]['file'];
    $file = str_replace ("\\", "/", $file);
    preg_match('/(?<='.$GLOBALS['CONFIG']['modules'] . '\/).*?(?=\/)/', $file, $module);
    if (! isset($module[0]) or $module[0] == null) {
        return false;
    } else {
        return $module[0];
    }
}
function kernel_getModuleRealPath() {
    $callInfo = debug_backtrace();
    $file = $callInfo[0]['file'];
    $file = str_replace ("\\", "/", $file);
    preg_match('/.*(?<='.$GLOBALS['CONFIG']['modules'] . '\/).*?(?=\/)/', $file, $module);
    if (! isset($module[0]) or $module[0] == null) {
        return false;
    } else {
        return $module[0];
    }
}
function kernel_getModulePath() {
    if ($moduleName = kernel_getCallerModule()) {
        return '/' . $GLOBALS['CONFIG']['modules'] . "/$moduleName";
    } else {
        return false;
    }
}
function kernel_phpErrorHandler($errorNumber, $errorString, $errorFile, $errorLine){
    $callInfo = debug_backtrace();
    $callInfo[1]['file'] = str_replace("\\", "/", $callInfo[1]['file']); 
    $errorString = trim($errorString);
    if (strpos($callInfo[1]['file'], $GLOBALS['CONFIG']['app_real_location'] . '/Kernel') === 0 and !COREDEBUG) {
        kernel_log("PHP ERROR $errorNumber: '$errorString'", 4);
    } else {
        kernel_log("PHP ERROR $errorNumber: '$errorString' in $errorFile at line $errorLine", 4);
    }
    if ($GLOBALS['CONFIG']['debug'] === true) {
        if (php_sapi_name() === 'cli') {
            echo $errorString;
        } else {
            echo '<div style="display:inline-block;background-color:#FFE5E5;border: solid 2px #AA0114;padding:8px;color:#AA0114;text-align: center;margin:5px;">An error occured:</br>' . $errorString . '</div>';}
        }
    return true ;
}
function kernel_checkIfModuleIsValid ($moduleName) {
    if (file_exists($GLOBALS['CONFIG']['app_real_location'] . '/' . $GLOBALS['CONFIG']['modules'] . "/$moduleName/init.php") and is_readable($GLOBALS['CONFIG']['app_real_location'] . '/' . $GLOBALS['CONFIG']['modules'] . "/$moduleName/init.php")){
        return true;
    } else {
        return false;
    }
}
function kernel_checkIfModuleExist($moduleName) { // Alias of kernel_checkIfModuleIsValid
    return kernel_checkIfModuleIsValid($moduleName);
}

kernel_log(framework_version . ' booting');
set_error_handler('kernel_phpErrorHandler');
require_once 'protected/init.php';
require_once 'event_handler/init.php';
require_once 'override/init.php';
require_once 'sql/init.php';
register_shutdown_function('kernel_shutdown', 9999); 
kernel_vartemp_clear();


?>