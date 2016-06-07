<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');

// *** PROCESS INPUT ***
// if POST then participant supplied their top two
// check if exactly two (or less if less results)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// check that all ratings are value (i.e. > 0)	
	$i = 0;
	$flagerror = 0;
	foreach ($_POST as $rating) {
		if($rating == 0 && $i < (3*AUTRATING_NUMSTIMULI)) {
			$_SESSION['errormsg'] = "Geef beoordelingen [1-10] voor originaliteit,
			bruikbaarheid en creativiteit. Ratings van 0 zijn niet toegestaan.";	
			$flagerror = 1;
			break;
		}
		$i++;
	}
	
	if ($flagerror == 0) { 
		insert_ratings();
	}
}

function set_session_responses() {
	$itemnr = $_SESSION['autitemnr'];
	$stimulusid = $_SESSION["autid$itemnr"];
	
	//get top two responses $wh ere = "WHERE `toptwo`=1;
	$participant_responses = dbSelectAUTResponsesTopTwo_by_userid_stimulusid(
			 $_SESSION['user_id'], $stimulusid); 
	
	// get expert participant responses
	$other_responses = dbSelectAUTResponses_by_userid_stimulusid(
			 EXPERT_PARTICIPANTID, $stimulusid); 
	// combine responses
	$responses = array_merge($participant_responses, $other_responses);
	$i = 0;
	foreach ($responses as $responseid => $response) {
		$i++;
		$_SESSION["response$i"] = $response;
		$_SESSION["responseid$i"] = $responseid;
	}
}

function insert_ratings() {
	$participantid = $_SESSION['user_id'];
	$expert_participantid = EXPERT_PARTICIPANTID;	
	$itemnr = $_SESSION["autitemnr"];
	
	// insert ratings of top two and then other answers
	// DEPENDENCY: ratings are the first $j form elements
	for($j=1; $j<=AUTRATING_NUMSTIMULI; $j++) {
		dbInsertAUTResponseRating_by_responseid_stimulusid_participantid(
			$_SESSION["responseid$j"],  $_SESSION["autid$itemnr"], $participantid, 
			$_POST["orig$j"], $_POST["util$j"], $_POST["crea$j"]);
	}
}

function reset_session_responses() {
	// reset all session response vars to 0
	for ($i=1; $i<=AUTRATING_NUMSTIMULI; $i++) {
		$_SESSION["response$i"] = 0;
		$_SESSION["responseid$i"] = 0;
	}
}

function reset_post_ratings() {
	// reset all post response vars to 0
	$_POST = array();
}

// GO TO NEXT AUT RATING ITEM?
// update autitemnr, our counter to loop through the items
if ($_SESSION['autitemnr'] == RESET) {
	$_SESSION['autitemnr'] = 1;
} else {
	// if no errors go to next item, else stay on page
	if (empty($_SESSION['errormsg'])) {
		reset_post_ratings();
		reset_session_responses();
		$_SESSION['autitemnr']++;
	}
}

// *** START NEW RATING ITEM
// set user's toptwo responses and the other responses in the session
set_session_responses();

// *** END OF TASK?
// after looping through all items reset counter and goto state machine
if ($_SESSION['autitemnr'] > AUT_NUMSTIMULI) {
	$_SESSION['autitemnr'] = RESET;
	header("location: expBP2016.php");
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="crea.css" />
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>
	<h1 id="title">
		Beoordelen van Alternatieve Toepassingen
	</h1>
	<p id="error">
		<?php echo $_SESSION['errormsg']; $_SESSION['errormsg'] = ''; ?>
	</p>
	<p id="stimulus">
		<?php $itemnr = $_SESSION['autitemnr']; echo $_SESSION["aut$itemnr"];?>
	</p>
	<p align="center">
	<form method="post" action="aut_rating.php">
	<table border="1" align='center'>			
	<th>
	  <td>&nbsp;originaltieit&nbsp;</td>
	  <td>&nbsp;bruikbaarheid&nbsp;</td>
	  <td>&nbsp;creativiteit&nbsp;</td>
	</th>
	<tr>
	  <td><?php echo $_SESSION["response1"] ?></td>
	  <td align='center'>
	    <select name="orig1">
		<option value="0"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['orig1']) && ($_POST['orig1']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['orig1']) && ($_POST['orig1']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="util1">
		<option value="0"  <?php if(isset($_POST['util1']) && ($_POST['util1']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['util1']) && ($_POST['util1']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['util1']) && ($_POST['util1']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['util1']) && ($_POST['util1']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['util1']) && ($_POST['util1']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['util1']) && ($_POST['util1']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['util1']) && ($_POST['util1']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['util1']) && ($_POST['util1']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['util1']) && ($_POST['util1']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['util1']) && ($_POST['util1']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['util1']) && ($_POST['util1']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="crea1">
		<option value="0"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['crea1']) && ($_POST['crea1']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['crea1']) && ($_POST['crea1']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	</tr>
	<tr>
	  <td><?php echo $_SESSION["response2"] ?></td>
	  <td align='center'>
	    <select name="orig2">
		<option value="0"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['orig2']) && ($_POST['orig2']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['orig2']) && ($_POST['orig2']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="util2">
		<option value="0"  <?php if(isset($_POST['util2']) && ($_POST['util2']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['util2']) && ($_POST['util2']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['util2']) && ($_POST['util2']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['util2']) && ($_POST['util2']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['util2']) && ($_POST['util2']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['util2']) && ($_POST['util2']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['util2']) && ($_POST['util2']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['util2']) && ($_POST['util2']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['util2']) && ($_POST['util2']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['util2']) && ($_POST['util2']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['util2']) && ($_POST['util2']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="crea2">
		<option value="0"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['crea2']) && ($_POST['crea2']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['crea2']) && ($_POST['crea2']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	</tr>
	<tr>
	  <td><?php echo $_SESSION["response3"] ?></td>
	  <td align='center'>
	    <select name="orig3">
		<option value="0"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['orig3']) && ($_POST['orig3']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['orig3']) && ($_POST['orig3']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="util3">
		<option value="0"  <?php if(isset($_POST['util3']) && ($_POST['util3']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['util3']) && ($_POST['util3']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['util3']) && ($_POST['util3']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['util3']) && ($_POST['util3']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['util3']) && ($_POST['util3']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['util3']) && ($_POST['util3']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['util3']) && ($_POST['util3']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['util3']) && ($_POST['util3']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['util3']) && ($_POST['util3']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['util3']) && ($_POST['util3']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['util3']) && ($_POST['util3']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="crea3">
		<option value="0"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['crea3']) && ($_POST['crea3']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['crea3']) && ($_POST['crea3']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	</tr>
	<tr>
	  <td><?php echo $_SESSION["response4"] ?></td>
	  <td align='center'>
	    <select name="orig4">
		<option value="0"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['orig4']) && ($_POST['orig4']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['orig4']) && ($_POST['orig4']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="util4">
		<option value="0"  <?php if(isset($_POST['util4']) && ($_POST['util4']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['util4']) && ($_POST['util4']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['util4']) && ($_POST['util4']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['util4']) && ($_POST['util4']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['util4']) && ($_POST['util4']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['util4']) && ($_POST['util4']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['util4']) && ($_POST['util4']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['util4']) && ($_POST['util4']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['util4']) && ($_POST['util4']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['util4']) && ($_POST['util4']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['util4']) && ($_POST['util4']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="crea4">
		<option value="0"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['crea4']) && ($_POST['crea4']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['crea4']) && ($_POST['crea4']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	</tr>
	<tr>
	  <td><?php echo $_SESSION["response5"] ?></td>
	  <td align='center'>
	    <select name="orig5">
		<option value="0"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['orig5']) && ($_POST['orig5']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['orig5']) && ($_POST['orig5']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="util5">
		<option value="0"  <?php if(isset($_POST['util5']) && ($_POST['util5']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['util5']) && ($_POST['util5']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['util5']) && ($_POST['util5']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['util5']) && ($_POST['util5']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['util5']) && ($_POST['util5']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['util5']) && ($_POST['util5']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['util5']) && ($_POST['util5']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['util5']) && ($_POST['util5']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['util5']) && ($_POST['util5']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['util5']) && ($_POST['util5']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['util5']) && ($_POST['util5']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="crea5">
		<option value="0"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['crea5']) && ($_POST['crea5']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['crea5']) && ($_POST['crea5']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>	  
	</tr>	
	<tr>
	  <td><?php echo $_SESSION["response6"] ?></td>
	  <td align='center'>
	    <select name="orig6">
		<option value="0"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['orig6']) && ($_POST['orig6']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['orig6']) && ($_POST['orig6']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="util6">
		<option value="0"  <?php if(isset($_POST['util6']) && ($_POST['util6']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['util6']) && ($_POST['util6']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['util6']) && ($_POST['util6']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['util6']) && ($_POST['util6']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['util6']) && ($_POST['util6']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['util6']) && ($_POST['util6']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['util6']) && ($_POST['util6']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['util6']) && ($_POST['util6']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['util6']) && ($_POST['util6']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['util6']) && ($_POST['util6']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['util6']) && ($_POST['util6']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	  <td align='center'>
		<select name="crea6">
		<option value="0"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==0)) {echo("selected");}?>>0</option>
		<option value="1"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==1)) {echo("selected");}?>>1</option>
		<option value="2"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==2)) {echo("selected");}?>>2</option>
		<option value="3"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==3)) {echo("selected");}?>>3</option>
		<option value="4"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==4)) {echo("selected");}?>>4</option>
		<option value="5"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==5)) {echo("selected");}?>>5</option>
		<option value="6"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==6)) {echo("selected");}?>>6</option>
		<option value="7"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==7)) {echo("selected");}?>>7</option>
		<option value="8"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==8)) {echo("selected");}?>>8</option>
		<option value="9"  <?php if(isset($_POST['crea6']) && ($_POST['crea6']==9)) {echo("selected");}?>>9</option>
		<option value="10" <?php if(isset($_POST['crea6']) && ($_POST['crea6']==10)){echo("selected");}?>>10</option>
	    </select>
	  </td>
	</tr>
	<tr>
	  <td colspan='4' align='center'>
	    <input type="submit" name="Submit" value="OK">
	  </td>	
	</tr>	
	</table>
	<form>
	</p>
	<p>
	Instructie: <br/>
	<br/><b>Originaliteit</b> nieuw en onverwacht? 1 = helemaal niet origineel ... 10 = heel origineel
	<br/><b>Bruikbaarheid</b> uitvoerbaar, bruikbaar of waardevol? 1 = absoluut niet bruikbaar ... 10 = heel bruikbaar
	<br/><b>Creativiteit</b> je eigen definitie. 1 = helemaal niet creatief ... 10 = heel creatief. 
	</p>
</body>
</html>
