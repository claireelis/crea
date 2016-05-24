<?php 

include_once('accesscontrol.php');
include('tablebuilder.php');

if (isset($_GET['viewtype'])) {
	$viewtype = $_GET['viewtype'];
}
else {
	$viewtype = OVERVIEW;
}

if (isset($_GET['user_id'])) {
	$userid = $_GET['user_id'];
}
else {
	$userid = 0;
}

$viewtable = '';
/*
switch($viewtype) {
	case USERVIEW:
		$viewtable .= generate_userview();
		break;
	case LOGINVIEW:
		$viewtable .= generate_loginview();
		break;
	case SESSIONVIEW:
		$viewtable .= generate_sessionview();
		break;
	case RESPONDENTSVIEW:
		$viewtable .= generate_respondentsview();
		break;
	case OVERVIEW:
		$viewtable .= generate_overview();
		break;
	default:
		$viewtable .= generate_userview();
		break;
}
*/

function generate_respondentsview() {
	$respondents = sqlSelectRespondents();
	$fields = array('respondentnr','naam','leeftijdsgroep','conditie','email','telefoon','gebruikersnaam','wachtwoord');
	
	$viewtable = starttable();
	$viewtable .= maketableheader($fields);
	$viewtable .= maketable($respondents, $fields);
	$viewtable .= endtable();
	
	return $viewtable;
}

function generate_userview($userid) {
	$responses = sqlSelectResponses($userid);
	$fields = array('respnr','session_nr','stimulus','response');
	
	$viewtable = starttable();
	$viewtable .= maketableheader($fields);
	$viewtable .= maketable($respondents, $fields);
	$viewtable .= endtable();
	
	return $viewtable;
}

function generate_loginview() {
	$sql =  sqlSelectLogin_by_userid($userid);
	$fields = array();
	
	$viewtable = starttable();
	$viewtable .= maketable($sql, $fields);
	$viewtable .= endtable();
	
	print_r($info);
}

function generate_sessionview() {
	$sql = sqlSelectSession_by_userid($userid);
	$fields = array();
	
	$viewtable = starttable();
	$viewtable .= maketable($sql, $fields);
	$viewtable .= endtable();
	
	print_r($info);
}

function generate_overview() {
	$respondents = dbSelectRespondentids();
	$fields = array('respondentnr','naam','leeftijdsgroep','conditie','#sessies','laaste login');

	$viewtable = starttable();
	$viewtable .= maketableheader($fields);
	foreach ($respondents as $i => $resp) {
		foreach ($resp as $respid) {
			$respondent = dbSelectRespondentOverview_by_userid($respid);
			$viewtable .= makerow($respondent, $fields);
		}
	}
	$viewtable .= endtable();
	
	return $viewtable;
}

echo 
"<html>
<head>
	<link rel='stylesheet' type='text/css' href='crea.css' />
</head>
<body>
	<h1 id='title'>
		Creatief Bezig!
	</h1>
	<h1 id='welcome'>
		*** PARTICIPANTEN OVERZICHT ***
	</h1>
	<h3>
		<a href='admin.php?viewtype=5'>Participanten Lijst</a> <br/>
		<a href='admin.php?viewtype=1'>Tracking Lijst</a> <br/>
		<a href='logout.php'>Uitloggen</a>
	</h3>
	<p id='admin'>
		$viewtable
	</p> </body>
</html>"
?>

