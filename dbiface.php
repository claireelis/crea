<?php

include('include/db.inc.php');

$dbconnect = 0;

function dbConnect() {
	global $dbconnect, $dbhost, $dbuser, $dbpswd, $dbase;
	
	/* establish connection */
	$dbconnect = mysqli_connect($dbhost, $dbuser, $dbpswd, $dbase);
	
	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Mysqli connect failed: %s\n", mysqli_connect_error());
	    exit();
	}
}

function dbDisconnect() {
	global $dbconnect;
	
	mysqli_close($dbconnect);
}

function dbQuery($query) {
	global $dbconnect; 
	
	dbConnect();
	$result = mysqli_query($dbconnect, $query);
	
	return $result;
}

function dbClose($result) {
	mysqli_free_result($result);
	dbDisconnect();
}

function dbSelectUser_by_username_password($username, $password) {
	$sql = "SELECT * FROM user WHERE username='$username' and password='$password'";
	$result = dbQuery($sql);
	$count = mysqli_num_rows($result);
	
	if ($count == 1) { 
		$output = mysqli_fetch_array($result);
	}
	else {
		$output = 0;
	}
	dbClose($result);
	return $output;
}

function dbSelectTrainingtype_by_userid($userid) {
	$sql = "SELECT trainingtype_fk FROM user_trainingtype WHERE user_fk='$userid'";
	$result = dbQuery($sql);
	$count = mysqli_num_rows($result);
	
	if ($count == 1) { 
		$output = mysqli_fetch_array($result);
	}
	else {
		$output = 0;
	}
	dbClose($result);
	return $output;
}

function dbInsertResponses_by_userid_sessionnr_stimulusid($userid, $sessionnr, $stimulusid, $response, $timestamp, $rt=0) {
	global $dbconnect;
	
	$sessionid = dbSelectSessionid_by_userid_sessionnr($userid, $sessionnr);
	$response = mysqli_real_escape_string($dbconnect, $response);
	if ($rt) {
		$sql="INSERT INTO response (stimulus_fk, session_fk, timestamp, response, reactiontime) VALUES ($stimulusid, $sessionid, FROM_UNIXTIME($timestamp), \"$response\", $rt)";
	} 
	else {
		$sql="INSERT INTO response (stimulus_fk, session_fk, timestamp, response) VALUES ($stimulusid, $sessionid, FROM_UNIXTIME($timestamp), \"$response\")";
	}
	$result = dbQuery($sql);
	return $result;
}

function dbSelectMaxSessionNr_by_userid($userid) {
	$sql = "SELECT MAX(`session_nr`) FROM `session` WHERE `user_fk` = $userid";
	$result = mysqli_fetch_array(dbQuery($sql));
	if (!isset($result)) {
		return 0;
	}
	else {
		return $result[0];
	}
}

function dbUpdateSession_starttime_by_userid_sessionnr($userid, $sessionnr) {
	$sql = "UPDATE `session` SET `starttime`=NOW() WHERE `user_fk`=$userid and `session_nr`=$sessionnr";
	return dbQuery($sql);
}

function dbUpdateSession_pausetime_by_userid_sessionnr($userid, $sessionnr) {
	if ($trainingtype == RS) {
		$item_duration = item_duration_rs;
	} else {
		$item_duration = item_duration;
	}
	$sql = "UPDATE `session` SET `pausetime`=NOW()+interval $item_duration minute WHERE `user_fk`=$userid and `session_nr`=$sessionnr";
	return dbQuery($sql);
}

function dbUpdateSession_resumetime_by_userid_sessionnr($userid, $sessionnr, $trainingtype) {
	$sql = "UPDATE `session` SET `resumetime`=NOW() WHERE `user_fk`=$userid and `session_nr`=$sessionnr";
	return dbQuery($sql);
}

function dbUpdateSession_finishtime_by_userid_sessionnr($userid, $sessionnr) {
	$sql = "UPDATE `session` SET `finishtime`=NOW() WHERE `user_fk`=$userid and `session_nr`=$sessionnr";
	return dbQuery($sql);
}

function dbInsertSession_by_userid_sessionnr($userid, $sessionnr) {
	$sql = "INSERT INTO `session` (`user_fk`, `session_nr`) VALUES ($userid, $sessionnr)"; 
	return dbQuery($sql);
}

function dbSelectStimuli_by_trainingtype_sessionnr($trainingtype, $sessionnr) {
	$sql = "SELECT stimulus_id, stimulus_name FROM stimulus WHERE trainingtype_fk = $trainingtype AND session_nr = $sessionnr ORDER BY RAND()";
	$result = dbQuery($sql);
	
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot find stimuli.';
		return 0;
	}
	else {
		$stimuli = array();
		while ($row = mysqli_fetch_row($result)) {
			$stimuli[$row[0]] = $row[1];
		}
		
		return $stimuli;
	}
}

function dbSelectStimuli_by_userid_trainingtype($userid, $trainingtype) {
	$numstimuli = num_stimuli;

	$sql = "SELECT DISTINCT stimulus_id, stimulus_name FROM stimulus WHERE stimulus_id NOT IN \n"
	    . "(SELECT t1.stimulus_fk FROM response AS t1, session AS t2 WHERE t1.session_fk = t2.session_id AND t2.user_fk=$userid) \n"
	    . "AND trainingtype_fk=$trainingtype ORDER BY Rand() LIMIT $numstimuli";
	$result = dbQuery($sql);
	
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot find stimuli.';
		return 0;
	}
	else {
		$stimuli = array();
		while ($row = mysqli_fetch_row($result)) {
			$stimuli[$row[0]] = $row[1];
		}
		
		return $stimuli;
	}
}

function dbSelectSessionid_by_userid_sessionnr($userid, $sessionnr) {
	$sql = "SELECT `session_id` FROM `session` WHERE user_fk = $userid AND session_nr = $sessionnr";
	$result = mysqli_fetch_array(dbQuery($sql));
	if (!isset($result[0])) {
		return 0;
	}
	else {
		return $result[0];
	}
}

function dbInsertLogin_by_userid($userid) {
	$sql = "INSERT INTO login (user_fk, starttime) VALUES ($userid, NOW())";
	return dbQuery($sql);
}

function dbUpdateLogin_by_userid($userid) {
	$sql = "UPDATE login SET finishtime=NOW() WHERE user_fk=$userid";
	return dbQuery($sql);
}

function dbSelectRespondentids($orderby="user_id") {
	$sql = "SELECT user_id FROM user WHERE role_fk=".respondent." AND (user_id < 100 OR user_id > 999) ORDER BY ";
	$sql .= $orderby;
	$result = dbQuery($sql);
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot find userids.';
		return 0;
	}
	else {
		$respondents = array();
		$i=0;
		while ($row = mysqli_fetch_row($result)) {
			$respondents[$i] = $row;
			$i++;
		}
		dbClose($result);
		return $respondents;
	}
}

function dbSelectRespondentOverview_by_userid($userid) {
	$sql = "SELECT t1.respnr, t1.name, t5.agegroup_name, t4.trainingtype_name, MAX(t6.session_nr), MAX(t7.starttime)  \n"
	    . "FROM user AS t1, user_trainingtype AS t2, role AS t3, trainingtype AS t4, agegroup AS t5, session AS t6, login AS t7  \n"
	    . "WHERE t1.user_id=t2.user_fk AND t1.role_fk=t3.role_id  \n"
	    . "AND t2.trainingtype_fk=t4.trainingtype_id AND t1.user_id=$userid \n"
	    . "AND t5.agegroup_id=t1.agegroup_fk AND t6.user_fk=t1.user_id\n"
	    . "AND t7.user_fk = t1.user_id ";
	$result = dbQuery($sql);
	
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot select overview.';
		return 0;
	}
	else {
		$row = mysqli_fetch_row($result);
		dbClose($result);
		return $row;
	}
}

function dbSelectRespondent_by_userid($userid) {
	$sql = "SELECT t1.user_id, t1.name, t5.agegroup_name, t4.trainingtype_name, MAX(t6.session_nr), MAX(t7.starttime)  \n"
	    . "FROM user AS t1, user_trainingtype AS t2, role AS t3, trainingtype AS t4, agegroup AS t5, session AS t6, login AS t7  \n"
	    . "WHERE t1.user_id=t2.user_fk AND t1.role_fk=t3.role_id  \n"
	    . "AND t2.trainingtype_fk=t4.trainingtype_id AND t1.user_id=$userid \n"
	    . "AND t5.agegroup_id=t1.agegroup_fk AND t6.user_fk=t1.user_id\n"
	    . "AND t7.user_fk = t1.user_id ";
	$result = dbQuery($sql);
	
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot select overview.';
		return 0;
	}
	else {
		$row = mysqli_fetch_row($result);
		dbClose($result);
		return $row;
	}
}

function sqlSelectRespondents($orderby="ORDER BY t1.user_id ASC") {
	$sql = "SELECT t1.respnr, t1.name, t4.agegroup_name, t3.trainingtype_name, t1.email, t1.telnr, t1.username, t1.password  \n"
		. "FROM user AS t1, user_trainingtype AS t2, trainingtype AS t3, agegroup as t4 \n"
		. "WHERE t1.role_fk=" . respondent . " \n"
		. "AND t1.user_id=t2.user_fk AND t2.trainingtype_fk=t3.trainingtype_id AND t4.agegroup_id=t1.agegroup_fk "
		. "AND (t1.user_id < 100 OR t1.user_id > 999)";
	$sql .= $orderby;
	$result = dbQuery($sql);
	
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot select overview.';
		return 0;
	}
	else {
		$respondents = array();
		$i=0;
		while ($row = mysqli_fetch_row($result)) {
			$respondents[$i] = $row;
			$i++;
		}
		dbClose($result);
		return $respondents;
	}
}

function sqlSelectSession_by_userid($userid, $orderby="t1.session_id, t3.response_id ASC ") {
	$sql = "SELECT t2.name, t1.*, t3.*, t4.stimulus_name FROM session AS t1, user AS t2, response AS t3, stimulus AS t4 \n"
	    . "WHERE t2.user_id=t1.user_fk\n"
	    . "AND t1.session_id=t3.session_fk\n"
	    . "AND t4.stimulus_id=t3.stimulus_fk\n"
	    . "AND t2.user_id = $userid ORDER BY ";
	$sql .= $orderby;
	return $sql;
	/*
	$result = mysqli_fetch_array(dbQuery($sql));

	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot find userids.';
		return 0;
	}
	else {
		return $result;
	}
	 */
}


function sqlSelectLogin_by_userid($userid, $orderby="starttime DESC") {
	$sql = "SELECT t1.*, t2.name FROM login AS t1, user AS t2 where user_fk = $userid ORDER BY ";
	$sql .= $orderby;
	
	return $sql;
	/*
	$result = mysqli_fetch_array(dbQuery($sql));
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot find logins.';
		return 0;
	}
	else {
		$logins = array();
		while ($row = mysqli_fetch_row($result)) {
			$logins[$row[0]] = $row[1];
		}
		dbClose($result);
		return $logins;
	}
	 */
}

function dbSelectStimulusid_by_stimulusname($name) {
	$sql = "SELECT stimulus_id FROM `stimulus` WHERE stimulus_name=\"$name\"";
	$result = dbQuery($sql);
	
	$count = mysqli_num_rows($result);
	if ($count > 0) { 
		$output = mysqli_fetch_array($result);
	}
	else {
		$output = 0;
	}
	dbClose($result);
	return $output[0];
}

function dbSelect_stimulusid_by_stimulusname_trainingtype($name, $trainingtype) {
	$sql = "SELECT stimulus_id FROM `stimulus` WHERE stimulus_name=\"$name\" and trainingtype_fk=$trainingtype";
	$result = dbQuery($sql);

	$count = mysqli_num_rows($result);
	if ($count > 0) { 
		$output = mysqli_fetch_array($result);
	}
	else {
		$output = 0;
	}
	dbClose($result);
	return $output[0];
}

function dbInsertResponses_by_trainingtype_stimulusnames($userid, $sessionnr, $trainingtype, $names, $responses) {
	$sqltuples = "";
	$i = 0;
	
	foreach ($names as $name) {
		$stimulusid = dbSelect_stimulusid_by_stimulusname_trainingtype($name, $trainingtype);
		$sessionid = dbSelectSessionid_by_userid_sessionnr($userid, $sessionnr);
		$sqltuples .= "($stimulusid,$sessionid,";
		$sqltuples .= "$responses[$i]";
		$sqltuples .= ")";
		
		$i++; if ($i < sizeof($names)) {
			$sqltuples .= ",";
		}
	}
	$sql="INSERT INTO response (stimulus_fk, session_fk, response) VALUES $sqltuples";
	return dbQuery($sql);
}


function sqlSelectResponsesOverview_by_userid($userid) {
	$sql = "SELECT user.respnr, session.session_nr, stimulus.stimulus_name, response.response ".
		"FROM `user`, `session`, `response`, `stimulus` ".
		"WHERE user.user_id = session.user_fk AND response.session_fk = session.session_id ". 
		"AND response.stimulus_fk = stimulus.stimulus_id AND user.user_id = $userid ". 
		"ORDER BY user.user_id, session.session_nr, response.response_id";
	$result = dbQuery($sql);
	
	if (!($result)) {
		$_SESSION['errormsg'] .= 'DBerror: cannot select overview.';
		return 0;
	}
	else {
		$responses = array();
		$i=0;
		while ($row = mysqli_fetch_row($result)) {
			$responses[$i] = $row;
			$i++;
		}
		dbClose($result);
		return $responses;
	}
}
?> 
