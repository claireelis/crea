<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');

// set errormessaging
if (!(isset($_SESSION['errormsg']))) {
	$_SESSION['errormsg'] = '';
}

// set experiment phase
// if not set then start at the beginning - providing personalia
// if set then we have just finished the current phase and can go to next one
if (!isset($_SESSION['expphase'])) {
	$_SESSION['expphase'] = EXP_PERSONALIA;
} else {
	$_SESSION['expphase']++;
}

// experiment phases state machine
switch ($_SESSION['expphase']) {
	case EXP_PERSONALIA:
		header("location: personalia.php");
		break;
	case EXP_AUTINSTR:
		header("location: autinstr.php");
		break;
	case EXP_AUT:
		header("location: aut.php");
		break;
	case EXP_AUTTOPTWO:
		header("location: aut_toptwo.php");
		break;
	case EXP_VFINSTR:
		header("location: vfinstr.php");
		break;
	case EXP_VF:
		header("location: vf.php");
		break;
	case EXP_AUTRATING:
		header("location: aut_rating.php");
		break;
	case EXP_END:
		header("location: logout.php");
		break;
	default:
		$_SESSION['errormsg'] .= "experiment phase onbekend";
		header("location: logout.php");
		break;
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="crea.css" />
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>
	<h1 id="title">
		Creatief Bezig!
	</h1>
	<p>
		This page should redirect to the next experiment task.
	</p>
</body>
</html>
