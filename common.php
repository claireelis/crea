<?php

function get_task_data() {
	$responses = array();
	$starttimes = array();
	$endtimes = array();

	// get responses, starttimes and endtimes
	if (isset($_GET['response'])) {
		$responses = explode(';', $_GET['response']);
	}
	if (isset($_GET['starttime'])) {
		$starttimes = explode(';', $_GET['starttime']);
	}
	if (isset($_GET['endtime'])) {
		$endtimes = explode(';', $_GET['endtime']);
	}

	// insert each response tuple
	if ((!empty($responses))||(!empty($starttimes))||(!empty($endtimes))) {
		if ($_SESSION['expphase'] == EXP_AUT) {
			$itemnr = $_SESSION['autitemnr'];
			$stimulusid = $_SESSION["autid$itemnr"];
		} else if ($_SESSION['expphase'] == EXP_VF) {
			$itemnr = $_SESSION['vfitemnr'];
			$stimulusid = $_SESSION["vfid$itemnr"];
		} else {
			$_SESSION['errormsg'] .= "incorrect experiment phase to insert GET or POST data.";
			print_r($responses);
			print_r($starttimes);
			print_r($endtimes);
		}
		
		print_r($responses);
		print_r($starttimes);
		print_r($endtimes);
		
		if((sizeof($responses) != sizeof($starttimes)) 
			|| (sizeof($starttimes) != sizeof($endtimes))) {
			$_SESSION['errormsg'] .= "GET/POST data vars not of equal size";
		} else {
			for ($i=0; $i < sizeof($responses);$i++) {
				if (!dbInsertAUTResponses_by_userid_stimulusid($_SESSION['user_id'],
					$stimulusid, $responses[$i], $starttimes[$i], $endtimes[$i])) {
					$_SESSION['errormsg'] .= "response insert failed for 
					$stimulusid, $responses[$i], $starttimes[$i], $endtimes[$i].";
				}
			}	
		}
	}
}

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
