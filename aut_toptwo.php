<?php 
include_once('accesscontrol.php');
include_once('expBP2016_defs.php');

// set errormessaging
if (!(isset($_SESSION['errormsg']))) {
	$_SESSION['errormsg'] = '';
}

// *** PROCESS INPUT ***
// if POST then participant supplied their top two
// check if exactly two (or less if less results)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// get top two checklist{
	if (isset($_POST['auttoptwo'])) {
		$auttoptwo = $_POST['auttoptwo'];
	} else {
		$auttoptwo = 0;
	}
	
	// how many aut responses did s/he give?
	$itemnr = $_SESSION['autitemnr'];
	$numresponses = dbCountAUTResponses_by_userid_stimulusid($_SESSION['user_id'], 
		$_SESSION["autid$itemnr"]);
	
	// how many top's did s/he choose?
	$numtops = check_count($auttoptwo);
		
	// if >= 2 responses then should be exactly 2 tops
	// if > 2 tops then errormsg to supply only 2
	// else if <2 responses then # responses = # tops
	if ($numresponses >= 2) {
		if ($numtops == 2) {
			// mark responses in dbase with toptwo boolean
			mark_toptwo_responses();
		} else {
			$_SESSION['errormsg'] = "Kies 2 antwoorden.";			
		}
	} else {
		// mark response (if there was one) in dbase with toptwo boolean
		if (!empty($numresponses)) {			
			mark_toptwo_responses();
		}
	}
}

function mark_toptwo_responses() {
	foreach ($_POST['auttoptwo'] as $responseid) {
		if(!dbUpdateAUTResponses_by_responseid($responseid)) {
			print "DB error: update top two response";
		}
	}
}


// GO TO NEXT TOP TWO ITEM?
// update autitemnr, our counter to loop through the items
if ($_SESSION['autitemnr'] == RESET) {
	$_SESSION['autitemnr'] = 1;
} else {
	// if no errors go to next item, else stay on page
	if (empty($_SESSION['errormsg'])) {
		$_SESSION['autitemnr']++;
	}
}

// *** START NEW TOP TWO ITEM
// get all of this user's responses to current autitemnr
$itemnr = $_SESSION['autitemnr'];
$stimulusid = $_SESSION["autid$itemnr"];
$responses = dbSelectAUTResponses_by_userid_stimulusid(
			 $_SESSION['user_id'], $stimulusid); 
			 
// display this user's responses to this item as checkbox list
$responselist = "";
foreach ($responses as $responseid => $response) {
	$responselist .= "<input type='checkbox' name='auttoptwo[]' 
		value=$responseid>$response<br/>";
}

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
		Alternatieve Toepassingen - Top Twee!
	</h1>
	<p>
		Hier zie je de alternatieve toepassingen die je zojuist bedacht hebt
		voor:
	</p>
	<p id="stimulus">
		<?php echo $_SESSION["aut$itemnr"];?>
	</p>		
	<p>
		Kies de twee antwoorden die jij het meest creatief vindt.
	</p>
	<p id="error">
		<?php echo $_SESSION['errormsg']; $_SESSION['errormsg'] = ''; ?>
	</p>
	<p>
	<form method="post" action="aut_toptwo.php">
		<input type="hidden" id="test" value="<?php echo "1"; ?>" />			
		<?php echo $responselist; ?>
		<input type="submit" name="Submit" value="OK">
	</form>
	</p>
</body>
</html>