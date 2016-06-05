<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');

if (!isset($_SESSION["aut1"])) {
	$stimuli = dbSelectAllStimuli_aut();

	$i=0;
	foreach($stimuli as $skey => $svalue) {
		$i++;
		$_SESSION["autid$i"] = $skey;
		$_SESSION["aut$i"] = $svalue;
	}
}

// get response if there is one
if ($_SERVER["REQUEST_METHOD"] == ("GET" || "POST")) {	
	get_task_data();
}

if (!isset($_SESSION['autitemnr'])) {
	$_SESSION['autitemnr'] = 1;
} else {
	$_SESSION['autitemnr']++;
}

if ($_SESSION['autitemnr'] > AUT_NUMSTIMULI) {
	$_SESSION['autitemnr'] = RESET;
	header("location: expBP2016.php");
}
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="crea.css" />
	<script type="text/javascript" src="aut.js"></script>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body onload="load()">
	<h1 id="title">
		Alternatieve Toepassingen
	</h1>
	<p id="instruction1">
		Bedenk zo veel mogelijk alternatieve toepassingen voor een:
	</p>
	<p id="stimulus">
	</p>
	<p id="instruction2">
		Je krijgt 2 minuten de tijd. Typ elke toepassing in het vakje hieronder. 
		Druk op Enter om deze aan de lijst toe te voegen:
	</p>
	<p>
	<form id="answerform">
	<input type="text" name="answer" size="100" maxlength="100" onKeyPress="return submitEnter(this,event)" id="answer"/>&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="itemnr" value="<?php echo $_SESSION['autitemnr']?>" />
		<input type="hidden" id="itemstimulus" value="<?php $itemnr = $_SESSION['autitemnr']; echo $_SESSION["aut$itemnr"]?>" />
	</form>
	</p>
	<p id="response">
	</p>
</body>
</html>

<?php
$_SESSION['errormsg'] = "";
?>