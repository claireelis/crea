<?php

if (!isset($_SESSION)) {
    session_start();
}

include("dbiface.php");
include('common.php');

// set errormessaging
if (!(isset($_SESSION['errormsg']))) {
    $_SESSION['errormsg'] = '';
}

/* 3 routes to logging in:
 * participant: participant, creaBP2016 -> redirects to experiment
 * expert: username, password -> redirects to expert site (provide ratings)
 * admin: username, password -> redirects to admin site (check input)
 */ 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
	
    if ($username == 'participant' && $password == 'creaBP2016') {
	    $_SESSION['errormsg'] = '';
		$_SESSION['user_id'] = UNKNOWN;
		$_SESSION['role'] = PARTICIPANT;
	    header("location: expBP2016.php");	
	}
    else {
	    $user = dbSelectUser_by_username_password($username, $password);
		
        if ($user == 0) {
            $_SESSION['errormsg']="gebruikersnaam en/of wachtwoord onbekend";
	    } else {
		    // TODO dbInsertSession_by_user_id($user['user_id']);
			// TODO sessAddUser_by_user_id();
			$_SESSION['user_id'] = $user['user_id'];	
			$_SESSION['name'] = $user['name'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['password'] = $user['password'];
			$_SESSION['email'] = $user['email'];
			
			switch ($user['role']) {
				case 'admin':
				    $_SESSION['role'] = ADMIN;
					$_SESSION['errormsg'] = '';
					header("location: admin.php");
					break;
				case 'expert': 
				    $_SESSION['role'] = EXPERT;
					$_SESSION['errormsg'] = '';
					header("location: expert.php");
					break;
				default:
					$_SESSION['errormsg']="Inloggen niet mogelijk: gebruikersrol onbekend.";
			} 
		}
	}
}
?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="crea.css" />
</head>
<body">
	<br/>
	<br/>
	<br/>
	<p>
		<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
			<tr>
				<form method="post" action="login.php">
					<td>
						<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
							<tr>
								<td colspan="2" align="center"><h1>CREATIEF BEZIG</h1></td>
							</tr>
							<tr>
								<td width="220" align="right">gebruikersnaam:</td>
								<td width="280"><input name="username" type="text"></td>
							</tr>
							<tr>
								<td align="right">wachtwoord:</td>
								<td><input name="password" type="password"></td>
							</tr>
							<tr>
								<td colspan="2"><p id="error"><br/><?php echo $_SESSION['errormsg']; ?></p></td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<br/>
									<input type="submit" name="Submit" value="Inloggen">
								</td>
							</tr>
						</table>
					</td>
				</form>
			</tr>
		</table>
	</p>
</body>
</html>
