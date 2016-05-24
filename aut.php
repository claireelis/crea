<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');

if (!isset($_SESSION["aut1"])) {
	$_SESSION["aut1"] = "baksteen";
	$_SESSION["aut2"] = "snoer";
	$_SESSION["aut3"] = "riem";
	$_SESSION["aut4"] = "book";
	$_SESSION["aut5"] = "paperclip";
	$_SESSION["aut6"] = "vork";
	$_SESSION["aut7"] = "krant";
	$_SESSION["aut8"] = "blikje";
}

if (!isset($_SESSION['autitemnr'])) {
	$_SESSION['autitemnr'] = 1;
} else {
	$_SESSION['autitemnr']++;
}

if ($_SESSION['autitemnr'] > AUT_NUMSTIMULI) {
	header("location: expBP2016.php");
}
/*
// get response if there is one
if (($_SERVER["REQUEST_METHOD"] == ("GET" || "POST")) && (isset($_SESSION['trainingtype']))) {	
	get_training_data();
}

function get_training_data() {
	$responses = array();
	$timestamps = array();
	if ($_SESSION['trainingtype'] == RS) {
		$stimuli = array();
		$rts = array();
	}

	// get responses, timestamps, answers and reactiontimes
	if (isset($_GET['response'])) {
		$responses = explode(';', $_GET['response']);
	}
	if (isset($_GET['timestamp'])) {
		$timestamps = explode(';', $_GET['timestamp']);
	}
	if (isset($_GET['rt'])) {
		$rts = explode(';', $_GET['rt']);
	}
	if (isset($_GET['stimulus'])) {
		$stimuli = explode(';', $_GET['stimulus']);
	}

	// insert each response tuple
	if ((!empty($responses))||(!empty($timestamps))) {
		if (($_SESSION['trainingtype'] == AU) || ($_SESSION['trainingtype'] == OC)) {
			$itemnr = $_SESSION['itemnr'];
			$stimulusid = $_SESSION["stimulusid$itemnr"];
			for ($i=0; $i < sizeof($responses);$i++) {
				if (!dbInsertResponses_by_userid_sessionnr_stimulusid($_SESSION['user_id'], $_SESSION['session_nr'], $stimulusid, $responses[$i], $timestamps[$i])) {
					$_SESSION['errormsg'] .= "response insert failed for $stimulusid, $responses[$i], $timestamps[$i] failed.";
				}
			}
		}
		else if ($_SESSION['trainingtype'] == RS) {
			for ($i=0; $i < sizeof($responses);$i++) {
				$stimulusid = dbSelectStimulusid_by_stimulusname($stimuli[$i]);
				if (!dbInsertResponses_by_userid_sessionnr_stimulusid($_SESSION['user_id'], $_SESSION['session_nr'], $stimulusid, $responses[$i], $timestamps[$i], $rts[$i])) {
					$_SESSION['errormsg'] .= "response insert failed for $stimulusid, $responses[$i], $timestamps[$i], $rts[$i] failed.";
				}
			}
		}
		else {
			$_SESSION['errormsg'] .= "response insert failed for $stimulusid, $responses[$i], $timestamps[$i] failed.";
		}
	}
}
*/
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
		Bedenk zo veel mogelijke alternatieve toepassingen voor een:
	</p>
	<p id="stimulus">
	</p>
	<p id="instruction2">
		Je krijgt 2 minuten de tijd. Typ elk toepassing in het vakje hieronder. 
		Druk op Enter om deze aan de lijst toe te voegen:
	</p>
	<p>
	<form id="answerform">
	<input type="text" name="answer" size="100" maxlength="100" onKeyPress="return submitEnter(this,event)" id="answer"/>&nbsp;&nbsp;&nbsp;
		<input type="hidden" id="itemnr" value="<?php echo $_SESSION['autitemnr']?>" />
		<input type="hidden" id="itemstimulus" value="<?php $itemnr = $_SESSION['autitemnr']; echo $_SESSION["aut$itemnr"]?>" />
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