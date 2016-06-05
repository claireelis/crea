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
		$starttime = explode(';', $_GET['starttime']);
	}
	if (isset($_GET['endtime'])) {
		$endtimes = explode(';', $_GET['endtime']);
	}
	
	// combine starttimes and endtimes to get complete starttimes array
	// endtime of previous answer is starttime of next answer
	// last element of enttimes is not a starttime so remove it
	if (isset($starttime) && (count($starttime) == 1)) {
		$starttimes = array_merge($starttime, array_slice($endtimes, 0, -1));
	}

	// insert each response tuple
	if ((!empty($responses))&&(!empty($starttimes))&&(!empty($endtimes))) {
		if ($_SESSION['expphase'] == EXP_AUT) {
			$itemnr = $_SESSION['autitemnr'];
			$stimulusid = $_SESSION["autid$itemnr"];
		} else if ($_SESSION['expphase'] == EXP_VF) {
			$itemnr = $_SESSION['vfitemnr'];
			$stimulusid = $_SESSION["vfid$itemnr"];
		} else {
			$_SESSION['errormsg'] = "incorrect experiment phase to insert GET or POST data.";
		}
		
		if((count($responses) != count($starttimes)) 
			|| (count($starttimes) != count($endtimes))) {
			$_SESSION['errormsg'] .= "GET/POST data vars not of equal size";
		} else {		
			for ($i=0; $i < sizeof($responses);$i++) {
				if (strlen($responses[$i]) < 2) {
					$_SESSION['errormsg'] = "no response";
				} else {
					if ($_SESSION['expphase'] == EXP_AUT) {
						$dbres = dbInsertAUTResponses_by_userid_stimulusid(
						$_SESSION['user_id'], $stimulusid, $responses[$i], 
						$starttimes[$i], $endtimes[$i]);
					} else if ($_SESSION['expphase'] == EXP_VF) {
						$dbres = dbInsertVFResponses_by_userid_stimulusid(
						$_SESSION['user_id'], $stimulusid, $responses[$i], 
						$starttimes[$i], $endtimes[$i]);
					}	else {
						$_SESSION['errormsg'] = "2: incorrect experiment phase to insert GET or POST data.";
					}
					if (!$dbres) {
						$_SESSION['errormsg'] = "response insert failed.";
					}
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

// count num elements in array, but set count to 0 if empty
function check_count($counts) {
	if (empty($counts)) {
		$count = 0;
	} else {
		$count = count($counts);
	}
	return $count;
}

/*
 * DO NOT CHANGE THESE CONSTANTS
 */
// user_id unknown
define("UNKNOWN", 9999);
define("RESET", 999);
 
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
