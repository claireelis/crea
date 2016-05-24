<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');
include_once('expBP2016_texts.php');

// set errormessaging
if (!(isset($_SESSION['errormsg']))) {
	$_SESSION['errormsg'] = '';
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
		AUT rating
	</p>
	<p>
		<a href="expBP2016.php">NEXT TASK</a>
	</p>
</body>
</html>
