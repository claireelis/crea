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

function dbSelectUserid_by_participantnr_name($participantnr, $name) {
	$sql = "SELECT user_id FROM user WHERE participantnr=$participantnr and name='$name'";
	$result = dbQuery($sql);
	$count = mysqli_num_rows($result);
	
	if ($count == 1) { 
		$output = mysqli_fetch_array($result);
	}
	else {
		$output = 0;
	}

	dbClose($result);
	return $output[0];
}

function dbInsertParticipant($name, $participantnr, $gender, $dob, $credit=FALSE,
	$lottery=TRUE, $informedconsent=TRUE, $email=NULL, $studentnr=NULL) {
	global $dbconnect;
	
	$sql="INSERT INTO `user`(`role`, `participantnr`, `name`, `email`, 
	`gender`, `dob`, `informed-consent`, `credit`, `lottery`, `studentnr`) 
	VALUES ('participant', $participantnr, '$name', '$email', '$gender', $dob,
	$informedconsent, $credit, $lottery, '$studentnr')";
	
	$result = dbQuery($sql);
	return $result;
}

function dbInsertAUTResponses_by_userid_stimulusid($userid, $stimulusid, 
	$response, $starttime, $endtime) {

	$sql="INSERT INTO `aut-response` 
		(`participant_fk`, `stimulus_fk`, `response`, `starttime`, `endtime`) 
		VALUES ($userid, $stimulusid, \"$response\", $starttime, $endtime)";
	
	$result = dbQuery($sql);
	return $result;
}

function dbUpdateSession_starttime_by_userid($userid) {
	$sql = "UPDATE `session` SET `starttime`=NOW() WHERE `user_fk`=$userid";
	return dbQuery($sql);
}

function dbInsertSession_by_userid($userid) {
	$sql = "INSERT INTO `session` (`user_fk`, `starttime`) VALUES ($userid, NOW())"; 
	return dbQuery($sql);
}

function dbSelectAllStimuli_aut() {
	$sql = "SELECT * FROM `aut-stimulus`";
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

function dbSelectAllStimuli_vf() {
	$sql = "SELECT * FROM `vf-stimulus`";
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

/*
function dbInsertLogin_by_userid($userid) {
	$sql = "INSERT INTO login (user_fk, starttime) VALUES ($userid, NOW())";
	return dbQuery($sql);
}

function dbUpdateLogin_by_userid($userid) {
	$sql = "UPDATE login SET finishtime=NOW() WHERE user_fk=$userid";
	return dbQuery($sql);
}
*/

function dbSelectRespondentids($orderby="user_id") {
	$sql = "SELECT user_id FROM user WHERE role='participant' ORDER BY ";
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
/*
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
	 *
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
	 *
}
*/

function dbSelectAUTStimulusid_by_stimulusname($name) {
	$sql = "SELECT aut-stimulus_id FROM `aut-stimulus` WHERE stimulus_name=\"$name\"";
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

function dbSelectVFStimulusid_by_stimulusname($name) {
	$sql = "SELECT vf-stimulus_id FROM `vf-stimulus` WHERE name=\"$name\"";
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

/*
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
*/
/*
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
*/
?> 
