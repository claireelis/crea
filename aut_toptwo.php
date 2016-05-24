<?php 
include_once('accesscontrol.php');
include_once('expBP2016_defs.php');
include_once('expBP2016_texts.php');

// set errormessaging
if (!(isset($_SESSION['errormsg']))) {
	$_SESSION['errormsg'] = '';
}

if (!isset($_SESSION["aut1answer"])) {
	$_SESSION["aut1answer"] = array ("barbie huis bouwen",
		"nagelvijl","boekenstop","deur open houden","raam open houden",
		"bureau verhogen","paper weight");
	$_SESSION["aut2answer"] = array ("waslijn","schilderij ophangen",
		"riem","trek speelgoed zoals loopauto","traphekje vastzetten");
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
		Hieronder zie je alle begrippen die je zojuist bedacht hebt
		nog een keer. Kies per voorwerp de twee antwoorden uit
		die jij het meest creatief vindt.
	</p>
	<p>
	</p>
		TODO loop through items (one page per item); loop through answers; 
		checkbox before each answer; check that only two answers were checked.
	<p>
		<a href="expBP2016.php">NEXT TASK</a>
	</p>
</body>
</html>