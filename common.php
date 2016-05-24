<?php

function error_post_requiredfields($required) {
	$error = 0;
	foreach($required as $field) {
		if (!isset($_POST[$field]) || ($_POST[$field] == '')) {
			$error = 1;
		}
	}
	return $error;
}

//return 1 if female (f) and 0 if male (m)
function convert_gender($gender) {
	return ($gender == 'f') ? 1 : 0;
}

// convert unix timestamp to mysqldate
function date_unix2mysql($unixtimestamp) {
	$mysqldate = date('Y-m-d', $unixtimestamp);
	return $mysqldate;
}

/*
 * DO NOT CHANGE THESE CONSTANTS
 */
// user_id unknown
define("UNKNOWN", 9999);
 
// user roles
define("PARTICIPANT", 1);
define("ADMIN", 2);
define("EXPERT", 3);

// text blocks
define("TXT_WELCOME", 0);
define("TXT_ERROR", 1);
define("TXT_INSTRUCTION1", 2);
define("TXT_STIMULUS", 3);
define("TXT_INSTRUCTION2", 4);
define("TXT_ANSWER", 5);
define("TXT_RESPONSE", 6);

// admin views
define("OVERVIEW", 1);
define("USERVIEW", 2);
define("LOGINVIEW", 3);
define("SESSIONVIEW", 4);
define("RESPONDENTSVIEW", 5);

?>
