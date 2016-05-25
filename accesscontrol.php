<?php 

if (!isset($_SESSION)) {
	session_start();
}

//debugging
print_r($_SESSION);

include_once('common.php'); 
include_once('dbiface.php'); 

// check that the session is still running
if (!isset($_SESSION['user_id'])) {
	$_SESSION['errormsg'] += "gebruiker onbekend";
	header("Location: logout.php");
} 

?>
