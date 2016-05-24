<?php

include("dbiface.php");

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    // TODO log logout
    //if (!dbUpdateLogin_by_userid($_SESSION['user_id'])) {
	    //$_SESSION['errormsg'] .= "Logout insert failed.";
	//}
	//else {
	    //$_SESSION['errormsg'] .= "Logout insert failed - unknown userid.";
	//}	
}

// end session
session_unset();
session_destroy();

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
	<h3 align="center">
		*** EINDE ***
	</h3>
	<p align="center">		
		<br/>
		<br/>
		Dank je wel voor het meedoen!
	</p>
	<p align="center">
	    <br/>
		<br/>
	    <a href="login.php">Inlogscherm</a>
	</p>
</body>
</html>
