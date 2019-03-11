<?php
// mysqli driver for SQL kernel module
/* Handle request for SQL using mysqli PHP extension

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
$TEMP['callinfo'] = debug_backtrace();
$TEMP['file'] = $TEMP['callinfo'][0]['file'];
if (! $TEMP['file'] === $GLOBALS['CONFIG']['app_real_location'] . "/Kernel/sql/init.php") { 
    kernel_log("Illegal attempt to load MySQLi driver",1); 
    exit(1);
}
if (extension_loaded('mysqli') === false) {
    kernel_log('The MySQLi PHP extension is not installed/loaded. This driver will not work',1);
}
kernel_protected_var('sql_res_id', false);

function SQL_connect($host = null, $user = null, $pass = null, $dbname = null, $port = null){
	if ($host != null) {
		kernel_log("Attempting to connect to MySQL host '$host' with user '$user'");
		$sql_res_id = mysqli_connect($host, $user, $pass);
		if ($sql_res_id == null) { 
            kernel_log('Error while connecting to MySQL:' . mysqli_connect_error(), 2);
            return false;
		} else { 
            kernel_log ("Connected to MySQL. Connecting to database '$dbname'...");
        }
		mysqli_select_db($sql_res_id, $dbname);
		mysqli_set_charset ($sql_res_id, 'utf8');
		if (mysqli_error($sql_res_id) != null) {
            mysqli_close ($sql_res_id);
            kernel_log('Error while connecting to database: ' . mysqli_error($sql_res_id), 2);
            return false;
		} else { 
            kernel_log ('Connection successful. Ready to process query');
            SQL_resid($sql_res_id);
            return $sql_res_id;
        }
	} else {
		kernel_log('Attempting to connect to MySQL host \'' . $GLOBALS['CONFIG']['sql_host'] . '\' with user \'' . $GLOBALS['CONFIG']['sql_user'] . '\'');
		$sql_res_id = mysqli_connect($GLOBALS['CONFIG']['sql_host'], $GLOBALS['CONFIG']['sql_user'], $GLOBALS['CONFIG']['sql_pass']);
		if ($sql_res_id == null) {
            kernel_log('Error while connecting to MySQL: ' . mysqli_connect_error(), 2);
            return false;
		} else {
            kernel_log ('Connected to MySQL. Connecting to database \'' . $GLOBALS['CONFIG']['sql_db'] . '\'...');
        }
		mysqli_select_db($sql_res_id, $GLOBALS['CONFIG']['sql_db']);
		mysqli_set_charset ($sql_res_id, 'utf8');
		$sql_error = mysqli_error($sql_res_id);
		if ($sql_error != null) {
            mysqli_close ($sql_res_id); 
            kernel_log("Error while connecting to database: $sql_error", 2);
            return false;
		} else {
            kernel_log ('Connection successful. Ready to process query'); 
            SQL_resid($sql_res_id);
            return $sql_res_id;
        }
    }
}
function SQL_close($sql_res_id = null) {
	if ($sql_res_id == null) { if (($sql_res_id = sql_resid()) === false) {
        kernel_log('No ressource identifier specified and none is stored in memory');
        return;
    }
                       }
    if (SQL_status($sql_res_id)) { 
	    mysqli_close($sql_res_id);
	    if ($sql_res_id === SQL_resid()) { 
            SQL_resid(false);
        }
        kernel_log('Connection to MySQL closed');
	    return true;
	} else { 
	    kernel_log('Invalid ressource identifier. The connection is either already closed or the host is down.'); 
	    return false;
	}
	return true;
}
function SQL_error($sql_res_id = null){
    if ($sql_res_id == null) { 
        return SQL_resid() != null ? mysqli_error(SQL_resid()) : false ;
    } else { 
        return mysqli_error($sql_res_id);
    }
}
function SQL_resid($sql_res_id = null){
	if ($sql_res_id == null) { 
        if (kernel_protected_var('sql_res_id') === false) {
            return false;
        } else {
            return kernel_protected_var('sql_res_id');
        }
	} else {
		if (SQL_status ($sql_res_id) === true) { 
            kernel_protected_var('sql_res_id', $sql_res_id);
            kernel_log ('New resource ID set');
            return true;
        } else {
            kernel_log ('Invalid ressource identifier');
            return false;
        }
	}
}
function SQL_status ($sql_res_id = null){
    if ($sql_res_id === null) { 
        $sql_res_id = SQL_resid();
    }
    if ($sql_res_id !== false) { 
        return mysqli_ping($sql_res_id);
    } else {
        return false;
    }
}
function SQL_query($query, $sql_res_id = null){
	if ($query == null) {
        kernel_log("Missing argument(s) for 'SQL_query'", 3);
        return;
    }
	if (gettype($query) != 'string') { 
        kernel_log("Invalid argument type for 'SQL_query'", 3);
        return;
    }
	if ($sql_res_id == null and ($sql_res_id = kernel_protected_var('sql_res_id')) == null) {
        kernel_log ("No SQL resource ID specified", 2);
        return false;
    }
	if (! SQL_status ($sql_res_id) === true) {
        kernel_log("Attempt to use 'SQL_query' with an invalid SQL resource ID or host is down", 3);
        return;
    }
	$result = mysqli_query($sql_res_id, $query);
	if (mysqli_error($sql_res_id) != "") {
        kernel_log("An error occurred while executing the query: $query. The error was: " . mysqli_error($sql_res_id), 3);
        return false;
	} else {
        if (gettype($result) === 'object') {
            $return = array();
            while ($result_array = mysqli_fetch_array($result)) {
                array_push($return, $result_array);
            }
            return $return;
        } else { 
            return $result;
        }
    }
}
function SQL_multi_query($queries, $res_id = null) {
    if ($queries == null) {
        kernel_log('Missing argument(s) for \'SQL_query\'', 3);
        return false;
    }
	if (gettype($queries) != 'string') {
        kernel_log('Invalid argument type for \'SQL_query\'', 3);
        return false;
    }
	if ($res_id == null and ($res_id = kernel_protected_var('sql_res_id')) == null) {
        kernel_log ('No SQL resource ID specified', 2);
        return false;
    }
	if (! SQL_status ($sql_res_id) === true) { 
        kernel_log('Attempt to use \'SQL_multi_query\' with an invalid SQL resource ID or host is down', 3);
        return false;
    }
	if (mysqli_multi_query($sql_res_id, $queries)) {
        $return = array();
        do {
            $resultArray = array();
            if ($result = mysqli_store_result($sql_res_id)) {
                while ($row = mysqli_fetch_array($result)) {
                    array_push($resultArray, $row);
                }   
                mysqli_free_result($result);
                array_push($return, $resultArray);
            }
        } while (mysqli_more_results($sql_res_id) and mysqli_next_result($sql_res_id));
    }
	if (mysqli_error($sql_res_id) != null) {
        kernel_log("An error occurred while executing the query: $queries. The error was: " . mysqli_error($sql_res_id), 3);
        kernel_log('The execution has been halted', 3);
	} 
	return($return);
}
function SQL_select($columnName, $tableName, $whereColumnName = null, $whereValueToSearch = null){
	if ($columnName == null or $tableName == null) {
        kernel_log('Missing argument for \'SQL_select\'', 3);
        return false;
    }
	if (gettype($columnName) != 'string' or gettype($tableName) != 'string' ) { 
        kernel_log('Invalid argument type for \'SQL_select\'', 3);
        return false;
    }
	$return = null;
    $columnName = mysqli_real_escape_string(sql_resid(), $columnName);
    $tableName = mysqli_real_escape_string(sql_resid(), $tableName);
    $whereColumnName = mysqli_real_escape_string(sql_resid(), $whereColumnName);
    $whereValueToSearch = mysqli_real_escape_string(sql_resid(), $whereValueToSearch);
    
	$query = "SELECT $columnName FROM {$GLOBALS['CONFIG']['sql_prefix']}$tableName";
	if ($whereColumnName != null) {
        $query .= " WHERE `$whereColumnName` = '$whereValueToSearch'";
    }
	if (($result = SQL_query ($query)) === null) { 
        kernel_log ('An error occurred while executing a SELECT query. Please check log for details', 3); 
        return false;
    } else { 
        return $result;
    }
}

function SQL_insert($tableName, $columns, $valuesToInsert){
	if ($tableName == null or $columns == null or $valuesToInsert == null) { 
        kernel_log("Missing argument for 'SQL_insert'", 3);
        return false;
    }
	if (gettype($tableName) != 'string' or gettype($columns) != 'array' and gettype($columns) != 'string' or gettype($valuesToInsert) != 'array' and gettype($valuesToInsert) != 'string') { 
        kernel_log("Invalid argument type provided for 'SQL_insert'");
        return false;
    }
    $tableName = mysqli_real_escape_string(sql_resid(), $tableName);
	$query = "INSERT INTO {$GLOBALS['CONFIG']['sql_prefix']}$tableName (";
	if (gettype ($columns) === 'string') { 
        $query .= '`' . mysqli_real_escape_string(sql_resid(), $columns) . '`) VALUES ('; 
    } else {
        foreach ($columns as $item) { 
            if ($item == null) { 
                kernel_log("Empty or null value provided for 'SQL_insert",3);
                return false;
            }
            $query .= '`' . mysqli_real_escape_string(sql_resid(), $item) . '`,';
        }
        $query = substr($query, 0, -1) . ') VALUES (';
    }
	if (gettype ($valuesToInsert) === 'string') {
        $query .= '"' . mysqli_real_escape_string(sql_resid(), $valuesToInsert) . '")'; 
    } else {
        foreach ($valuesToInsert as $item) { 
            if ($item === null or $item === '') { 
                kernel_log("Empty or null value provided for 'SQL_insert",3);
                return false;
            }
            $query .= '"' . mysqli_real_escape_string(sql_resid(), $item) . '",';
        }
        $query = substr($query, 0, -1) . ')';
    }
	if (sql_query($query)) {
        return true;
    } else {
        return false;
    }
}
//TODO HERE: add sql_create_table
function SQL_update ($tableName, $columnToUpdate, $valueToSet, $columnToMatch, $valueToMatch, $additionalArguments = null){
	if (gettype($tableName) != 'string' or gettype($columnToUpdate) != 'string' or gettype($valueToSet) != 'string' or gettype($columnToMatch) != 'string' or gettype($valueToMatch) != 'string' or $additionalArguments != null and gettype($additionalArguments) != 'string') { 
        kernel_log('Invalid argument type for \'SQL_update\'', 3);
        return false;
    }
	$tableName = mysqli_real_escape_string(sql_resid(), $tableName);
    $columnToUpdate = mysqli_real_escape_string(sql_resid(), $columnToUpdate);
    $valueToSet = mysqli_real_escape_string(sql_resid(), $valueToSet);
    $columnToMatch = mysqli_real_escape_string(sql_resid(), $columnToMatch);
    $valueToMatch =  mysqli_real_escape_string(sql_resid(), $valueToMatch);
    $additionalArguments =  mysqli_real_escape_string(sql_resid(), $additionalArguments);
    
	$query = "UPDATE `{$GLOBALS['CONFIG']['sql_prefix']}$tableName` SET `$columnToUpdate`='$valueToSet' WHERE `$columnToMatch`='$valueToMatch'";
	if ($additionalArguments != null) {
        $query .= " $additionalArguments";
    }
    if (sql_query($query) != false) {
        return true;
    } else {
        kernel_log('An error occurred while executing a UPDATE query. Please check log for details', 3);
        return false;
    }
}
function SQL_delete($tableName, $columnToMatch, $valueToMatch, $addtionalArguments = null){
	if (gettype($tableName) != 'string' or gettype($columnToMatch) != 'string' or gettype($valueToMatch) != 'string' and gettype($valueToMatch) != 'integer' or $addtionalArguments != null and gettype($addtionalArguments) != 'string') {
        kernel_log('Invalid argument type for \'SQL_delete\'', 3);
        return false;
    }
	$tableName = mysqli_real_escape_string(sql_resid(),$tableName);
    $columnToMatch = mysqli_real_escape_string(sql_resid(),$columnToMatch);
    $valueToMatch = mysqli_real_escape_string(sql_resid(),$valueToMatch);
    $addtionalArguments = mysqli_real_escape_string(sql_resid(),$addtionalArguments);
	$query = "DELETE FROM {$GLOBALS['CONFIG']['sql_prefix']}$tableName WHERE `$columnToMatch`='$valueToMatch'";
	if ($addtionalArguments != null) {
        $query .= " $addtionalArguments";
    }
	if(sql_query($query) != false) {
        return true;
    } else {
        kernel_log('An error occurred while executing a DELETE query. Please check log for details', 3);
        return false;
    }
}
?>