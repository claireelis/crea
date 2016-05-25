<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');

if (!isset($_SESSION["vf1"])) {
	$stimuli = dbSelectAllStimuli_vf();

	$i=0;
	foreach($stimuli as $skey => $svalue) {
		$i++;
		$_SESSION["vfid$i"] = $skey;
		$_SESSION["vf$i"] = $svalue;
	}
}

if (!isset($_SESSION['vfitemnr'])) {
	$_SESSION['vfitemnr'] = 1;
} else {
	$_SESSION['vfitemnr']++;
}

if ($_SESSION['vfitemnr'] > VF_NUMSTIMULI) {
	header("location: expBP2016.php");
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="crea.css" />
	<script type="text/javascript" src="vf.js"></script>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body onload="load()">
	<h1 id="title">
		Begrippen Bedenken
	</h1>
	<p id="instruction1">
		Bedenk zo veel mogelijke begrippen die passen binnen de
		categorie:
	</p>
	<p id="stimulus">
	</p>
	<p id="instruction2">
		Je krijgt 2 minuten de tijd. Typ elk antwoord in de balk en
		druk op Enter om hem op te slaan.
	</p>
	<p>
	<form id="answerform">
	<input type="text" name="answer" size="100" maxlength="100" onKeyPress="return submitEnter(this,event)" id="answer"/>&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="itemnr" value="<?php echo $_SESSION['vfitemnr']?>" />
		<input type="hidden" id="itemstimulus" value="<?php $itemnr = $_SESSION['vfitemnr']; echo $_SESSION["vf$itemnr"]?>" />
		<input type="button" value="OK" onclick="prependResponse()" />
	</form>
	</p>
	<p id="response">
	</p>
	<p>
		ONLY FOR TESTING PHASE: <a href="expBP2016.php">GOTO NEXT TASK</a>
	</p>
</body>
</html>
