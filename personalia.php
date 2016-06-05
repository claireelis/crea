<?php 

include_once('accesscontrol.php');
include_once('expBP2016_defs.php');
include_once('expBP2016_texts.php');

$participant_required_fields = array('name', 'dob_d', 'dob_m', 'dob_y', 'gender', 
	'informedconsent','credit','lottery');
$participant_other_fields = array('email','studentnr');
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
		$dob = date_unix2mysql(mktime(0,0,0,$_POST['dob_m'], $_POST['dob_d'], $_POST['dob_y']));
		$informedconsent = 1;
		$participantnr = date("YmdHi");
		$name = $_POST['name'];
		
		if (!dbInsertParticipant($name, $participantnr, 
			$_POST['gender'], $dob,  
			$_POST['lottery'], $_POST['credit'], 
			$informedconsent, $_POST['email'], $_POST['studentnr'])) {
			$_SESSION['errormsg'].= "dbInsertParticipant failed.";
		}
		
		// update user id
		$userid = dbSelectUserid_by_participantnr_name($participantnr, $name);
		if ($userid) {
			$_SESSION['user_id'] = $userid;
		}

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
			Ik heb informatie over het onderzoek ontvangen, gelezen,
			begrepen en het toestemmingsformulier ondertekend.
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
			<option value="1950" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1950)){echo("selected");}?>>1950</option>
			<option value="1951" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1951)){echo("selected");}?>>1951</option>
			<option value="1952" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1952)){echo("selected");}?>>1952</option>
			<option value="1953" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1953)){echo("selected");}?>>1953</option>
			<option value="1954" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1954)){echo("selected");}?>>1954</option>
			<option value="1955" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1955)){echo("selected");}?>>1955</option>
			<option value="1956" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1956)){echo("selected");}?>>1956</option>
			<option value="1957" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1957)){echo("selected");}?>>1957</option>
			<option value="1958" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1958)){echo("selected");}?>>1958</option>
			<option value="1959" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1959)){echo("selected");}?>>1959</option>
			<option value="1960" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1960)){echo("selected");}?>>1960</option>
			<option value="1961" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1961)){echo("selected");}?>>1961</option>
			<option value="1962" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1962)){echo("selected");}?>>1962</option>
			<option value="1963" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1963)){echo("selected");}?>>1963</option>
			<option value="1964" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1964)){echo("selected");}?>>1964</option>
			<option value="1965" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1965)){echo("selected");}?>>1965</option>
			<option value="1966" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1966)){echo("selected");}?>>1966</option>
			<option value="1967" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1967)){echo("selected");}?>>1967</option>
			<option value="1968" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1968)){echo("selected");}?>>1968</option>
			<option value="1969" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1969)){echo("selected");}?>>1969</option>
			<option value="1970" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1970)){echo("selected");}?>>1970</option>
			<option value="1971" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1971)){echo("selected");}?>>1971</option>
			<option value="1972" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1972)){echo("selected");}?>>1972</option>
			<option value="1973" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1973)){echo("selected");}?>>1973</option>
			<option value="1974" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1974)){echo("selected");}?>>1974</option>
			<option value="1975" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1975)){echo("selected");}?>>1975</option>
			<option value="1976" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1976)){echo("selected");}?>>1976</option>
			<option value="1977" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1977)){echo("selected");}?>>1977</option>
			<option value="1978" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1978)){echo("selected");}?>>1978</option>
			<option value="1979" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1979)){echo("selected");}?>>1979</option>
			<option value="1980" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1980)){echo("selected");}?>>1980</option>
			<option value="1981" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1981)){echo("selected");}?>>1981</option>
			<option value="1982" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1982)){echo("selected");}?>>1982</option>
			<option value="1983" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1983)){echo("selected");}?>>1983</option>
			<option value="1984" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1984)){echo("selected");}?>>1984</option>
			<option value="1985" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1985)){echo("selected");}?>>1985</option>
			<option value="1986" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1986)){echo("selected");}?>>1986</option>
			<option value="1987" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1987)){echo("selected");}?>>1987</option>
			<option value="1988" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1988)){echo("selected");}?>>1988</option>
			<option value="1989" <?php if(isset($_POST['dob_y']) && ($_POST['dob_y']==1989)){echo("selected");}?>>1989</option>		
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
			<input type="radio" name="gender" value="male" <?php if(isset($_POST['gender']) && ($_POST['gender']=='male')){echo("checked=\"checked\"");}?>/> man
			<input type="radio" name="gender" value="female" <?php if(isset($_POST['gender']) && ($_POST['gender']=='female')){echo("checked=\"checked\"");}?>/> vrouw
		</td>
	</tr>
	<tr>
		<td>email:&nbsp;</td>
		<td><input type="text" name="email" value="<?php if(isset($_POST['email'])){echo($_POST['email']);}?>"/></td>
	</tr>
	<tr>
		<td>studentnr:&nbsp;</td>
		<td><input type="text" name="studentnr" value="<?php if(isset($_POST['studentnr'])){echo($_POST['studentnr']);}?>"/></td>
	</tr>
	</table>
	<p>
		Wil je  meedoen met de boekenbon loting?
		<b>(controleer je emailadres)</b>
		<br/><input type="radio" name="lottery" value="1" <?php if(isset($_POST['lottery']) && ($_POST['lottery']==1)){echo("checked=\"checked\"");}?>/> Ja
		<br/><input type="radio" name="lottery" value="0" <?php if(isset($_POST['lottery']) && ($_POST['lottery']==0)){echo("checked=\"checked\"");}?>/> Nee
	</p>
	<p>
		Wil je proefpersooncredits ontvangen?
		<b>(controleer je studentnr)</b>
		<br/><input type="radio" name="credit" value="1" <?php if(isset($_POST['credit']) && ($_POST['credit']==1)){echo("checked=\"checked\"");}?>/> Ja
		<br/><input type="radio" name="credit" value="0" <?php if(isset($_POST['credit']) && ($_POST['credit']==0)){echo("checked=\"checked\"");}?>/> Nee
	</p>
	<p>
		<input type="submit" name="Submit" value="OK">
	</p>
	</form>
	</body>
	</html>