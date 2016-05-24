<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');
include_once('expBP2016_texts.php');

$participant_required_fields = array('name', 'dob_d', 'dob_m', 'dob_y', 'gender', 
	'informedconsent');
$participant_other_fields = array('email', 'lottery', 'studentnr', 'credit');
$participant_fields = array_merge($participant_required_fields, 
	$participant_other_fields);

// set errormessaging
if (!(isset($_SESSION['errormsg']))) {
	$_SESSION['errormsg'] = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (error_post_requiredfields($participant_required_fields)) {
		$_SESSION['errormsg']="Graag alle vragen beantwoorden.";
	} else {
		$dob = mktime(0,0,0,$_POST['dob_m'], $_POST['dob_d'], $_POST['dob_y']);
		for ($i=0, $participant=array(); $i<sizeof($participant_fields); $i++) {
			$participant[$participant_fields[$i]] = $_POST[$participant_fields[$i]]; 
			$_SESSION[$participant_fields[$i]] = $_POST[$participant_fields[$i]]; 
		}
		/*
		dbInsertParticipant($participant['name'], $participant['participantnr'], 
			date_unix2mysql($dob), convert_gender($participant['gender']), 
			$participant['email'], $participant['informedconsent'], 
			$participant['reward']);
			
		TODO sessionUpdateUser_id();
		*/
		header("Location: expBP2016.php");
	}
} 

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="crea.css" />	
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
</head>
<body>
	<h1 id="title">
		*** WELKOM ***
	</h1>
	<h3 align="center">
		Welkom bij ons onderzoek!
	</h3>
	<p id="error">
		<?php echo $_SESSION['errormsg']; $_SESSION['errormsg'] = ''; ?>
	</p>
	<form method="post" action="personalia.php">
	<p>
		<input type="checkbox" name="informedconsent" value="1" /> 
			Ik heb informatie over het onderzoek ontvangen, gelezen
			en het toestemmingsformulier ondertekend.
	</p>
	<table>			
	<tr>
		<td>voornaam:</td>
		<td><input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"/></td>
	</tr>
	<tr>
		<td>geboortedatum (dag-maand-jaar):</td>
		<td>
			<select name="dob_d">
			<option value="1" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==1)){echo("selected");}?>>1</option>
			<option value="2" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==2)){echo("selected");}?>>2</option>
			<option value="3" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==3)){echo("selected");}?>>3</option>
			<option value="4" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==4)){echo("selected");}?>>4</option>
			<option value="5" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==5)){echo("selected");}?>>5</option>
			<option value="6" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==6)){echo("selected");}?>>6</option>
			<option value="7" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==7)){echo("selected");}?>>7</option>
			<option value="8" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==8)){echo("selected");}?>>8</option>
			<option value="9" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==9)){echo("selected");}?>>9</option>
			<option value="10" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==10)){echo("selected");}?>>10</option>
			<option value="11" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==11)){echo("selected");}?>>11</option>
			<option value="12" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==12)){echo("selected");}?>>12</option>
			<option value="13" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==13)){echo("selected");}?>>13</option>
			<option value="14" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==14)){echo("selected");}?>>14</option>
			<option value="15" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==15)){echo("selected");}?>>15</option>
			<option value="16" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==16)){echo("selected");}?>>16</option>
			<option value="17" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==17)){echo("selected");}?>>17</option>
			<option value="18" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==18)){echo("selected");}?>>18</option>
			<option value="19" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==19)){echo("selected");}?>>19</option>
			<option value="20" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==20)){echo("selected");}?>>20</option>
			<option value="21" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==21)){echo("selected");}?>>21</option>
			<option value="22" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==22)){echo("selected");}?>>22</option>
			<option value="23" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==23)){echo("selected");}?>>23</option>
			<option value="24" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==24)){echo("selected");}?>>24</option>
			<option value="25" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==25)){echo("selected");}?>>25</option>
			<option value="26" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==26)){echo("selected");}?>>26</option>
			<option value="27" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==27)){echo("selected");}?>>27</option>
			<option value="28" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==28)){echo("selected");}?>>28</option>
			<option value="29" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==29)){echo("selected");}?>>29</option>
			<option value="30" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==30)){echo("selected");}?>>30</option>
			<option value="31" <?php if(isset($_POST['dob_d']) && ($_POST['dob_d']==31)){echo("selected");}?>>31</option>
			</select>-
			<select name="dob_m">
			<option value="1" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==1)){echo("selected");}?>>1</option>
			<option value="2" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==2)){echo("selected");}?>>2</option>
			<option value="3" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==3)){echo("selected");}?>>3</option>
			<option value="4" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==4)){echo("selected");}?>>4</option>
			<option value="5" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==5)){echo("selected");}?>>5</option>
			<option value="6" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==6)){echo("selected");}?>>6</option>
			<option value="7" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==7)){echo("selected");}?>>7</option>
			<option value="8" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==8)){echo("selected");}?>>8</option>
			<option value="9" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==9)){echo("selected");}?>>9</option>
			<option value="10" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==10)){echo("selected");}?>>10</option>
			<option value="11" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==11)){echo("selected");}?>>11</option>
			<option value="12" <?php if(isset($_POST['dob_m']) && ($_POST['dob_m']==12)){echo("selected");}?>>12</option>
			</select>-
			<select name="dob_y">
			<option value="1990" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1990)){echo("selected");}?>>1990</option>
			<option value="1991" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1991)){echo("selected");}?>>1991</option>
			<option value="1992" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1992)){echo("selected");}?>>1992</option>
			<option value="1993" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1993)){echo("selected");}?>>1993</option>
			<option value="1994" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1994)){echo("selected");}?>>1994</option>
			<option value="1995" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1995)){echo("selected");}?>>1995</option>
			<option value="1996" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1996)){echo("selected");}?>>1996</option>
			<option value="1997" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1997)){echo("selected");}?>>1997</option>
			<option value="1998" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1998)){echo("selected");}?>>1998</option>
			<option value="1999" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1999)){echo("selected");}?>>1999</option>
			<option value="2000" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2000)){echo("selected");}?>>2000</option>
			<option value="2001" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2001)){echo("selected");}?>>2001</option>
			<option value="2002" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2002)){echo("selected");}?>>2002</option>
			<option value="2003" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2003)){echo("selected");}?>>2003</option>
			<option value="2004" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2004)){echo("selected");}?>>2004</option>
			<option value="2005" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2005)){echo("selected");}?>>2005</option>
			<option value="2006" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2006)){echo("selected");}?>>2006</option>
			<option value="2007" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2007)){echo("selected");}?>>2007</option>
			<option value="2008" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2008)){echo("selected");}?>>2008</option>
			<option value="2009" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2009)){echo("selected");}?>>2009</option>
			<option value="2010" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==2010)){echo("selected");}?>>2010</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>geslacht:&nbsp;</td>
		<td>
			<input type="radio" name="gender" value="m" <?php if(isset($_POST['gender']) && ($_POST['gender']=='m')){echo("checked=\"checked\"");}?>/> man
			<input type="radio" name="gender" value="f" <?php if(isset($_POST['gender']) && ($_POST['gender']=='f')){echo("checked=\"checked\"");}?>/> vrouw
		</td>
	</tr>
	<tr>
		<td>studentnr:&nbsp;</td>
		<td><input type="text" name="studentnr"/></td>
	</tr>
	<tr>
		<td>email:&nbsp;</td>
		<td><input type="text" name="email"/></td>
	</tr>
	</table>
	<p>
		<input type="checkbox" name="credit" value="1" />&nbsp; 
		Ik wil graag proefpersooncredits ontvangen
		<b>(controleer je studentnr)</b>.
	</p>
	<p>
		<input type="checkbox" name="lottery" value="1" />&nbsp; 
		Ik wil graag meedoen met de boekenbon loting 
		<b>(controleer je emailadres)</b>.
	</p>
	<p>
		<input type="submit" name="Submit" value="OK">
	</p>
	</form>
	</body>
	</html>