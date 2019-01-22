<?php
// Kernel "event_handler " module.
/* Handle all the events in the framework.
 
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
if (! DEFINED('INSCRIPT')) { echo 'Direct access denied'; exit(1); }
/* Depencies:
- Log
*/

$EVENTS['MODULESLOADED'] = array();
$EVENTS['STARTUP'] = array();
$EVENTS['SHOWHEADER'] = array();
$EVENTS['SHOWCONTENT'] = array();
$EVENTS['SHOWFOOTER'] = array();
$EVENTS['SHUTDOWN'] = array();

kernel_protected_var('kernel_event_blocked_functions',array(
'return',
'exit',
'shell',
'kernel_log'));

function kernel_event_trigger($event) {
    if (gettype($event) != 'string' or $event == null) {
        kernel_log("Call to 'kernel_event_trigger' without required argument!", 3);
        return false;
    }
    if (array_key_exists($event, $GLOBALS['EVENTS'])) {
        kernel_log("Event '$event' triggered", 5);
        foreach ($GLOBALS['EVENTS'][$event] as $cmd) {
            if (! kernel_event_validateString($cmd)) {
                kernel_log("Call to unauthorized command '$cmd'. Skipping it.", 4);
                continue;
            }
            kernel_log("Executing '$cmd'...",5);
            eval($cmd.";");
        }
        return true;
    } else { 
        kernel_log("Unknown event '$event' triggered", 4);
        return false;
    }
}
function kernel_event_create($eventName) {
    if (gettype($eventName) != 'string' or $eventName == null) {
        kernel_log('Call to \'kernel_event_create\' without a required argument!', 3);
        return false;
    }
    if (isset($EVENTS[$eventName])) {
        kernel_log("Event '$eventName' already exist. Cannot create a new event", 3);
        return false;
    }
    $GLOBALS['EVENTS'][$eventName] = array();
    kernel_log("Event '$eventName' created");
    return true;
}
function kernel_event_register($eventName, $command) {
    if (gettype($eventName) != 'string' or $eventName == null or gettype($command) != 'string' or $command == null) {
        kernel_log('Missing argument for \'kernel_event_register\'!', 3);
        return false;
    }
    if (! isset($GLOBALS['EVENTS'][$eventName])) {
        kernel_log('Event \'$eventName\' doesn\'t exist', 3);
        return false;
    }
    if (! kernel_event_validateString($command)) {
        kernel_log("Attempt to add unauthorized command '$command'. Refusing",4);
        return false;
    }    
    array_push($GLOBALS['EVENTS'][$eventName], $command);
    kernel_log("Command registered to event $eventName");
    return true;
}
function kernel_event_validateString($stringToValidate) {
    foreach (kernel_protected_var("kernel_event_blocked_functions") as $find) {
        if ($stringToValidate == $find or preg_match('/'.$find.'[ \(|\(]/', $stringToValidate) == 1) {
            return false;
        }
    }
    return true;
}
kernel_log("Module ready");
?>