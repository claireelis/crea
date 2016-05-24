<?php
include_once('common.php');

function set_txt_personalia() {
	echo "
		Leuk dat je mee wil doen aan ons onderzoek naar creativiteit.
		Het onderzoek bestaat uit drie onderdelen en zal maximaal 
		&eacute;&eacute;n uur duren. Tussendoor zijn er een aantal korte pauzes.
		<br/><br/>
		Mocht er iets niet duidelijk zijn tijdens het onderzoek,
		schroom niet om te vragen.
		<br/><br/>
		Veel succes en plezier!
		";
}

function set_txt_welcome($trainingphase) {
	switch($trainingphase) {
		case train_instr: 
			echo '*** WELKOM ***';
			break;
		case train_end: 
			echo '*** EINDE ***';
			break;
	}
}

function set_txt_instruction1($trainingtype, $trainingphase) {
	if ($trainingphase == train_instr) {
		switch($trainingtype) {
			case AU: 
				set_txt_instruction1_AU_traininstr();
				break;
			case OC: 
				set_txt_instruction1_OC_traininstr();
				break;
			case RS: 
				set_txt_instruction1_RS_traininstr();
				break;
		}
	}
}

function set_txt_instruction1_AUT() {
	echo
	"Je gaat zo beginnen met het eerste onderdeel van dit onderzoek. 
	Deze duurt 16 minuten met een korte pauze tussendoor. </br></br>
	Het is de bedoeling dat je zoveel mogelijk alternatieve toepassingen 
	(gebruiksmogelijkheden) voor gepresenteerde voorwerpen bedenkt.
	Je krijgt in totaal 8 voorwerpen te zien. 
	Voor ieder voorwerp krijg je 2 minuten de tijd om zo veel mogelijk 
	toepassingen te bedenken. 
	Iedere bedachte toepassing typ je in de balk onder de naam van het voorwerp.
	Na het intypen van een toepassing druk je op Enter zodat deze aan 
	de lijst links onder in het scherm wordt toegevoegd.  
	Na de 2 minuten komt het volgende voorwerp in beeld.</br></br>
	Let op:
	<ol>
		<li>De toepassingen dienen <b>anders</b> te zijn dan waarvoor 
			het voorwerp bedoeld is, maar niet <b>onmogelijk</b>.</li>
		<li>Bedenk zoveel mogelijk verschillende toepassingen.</li>
		<li>Je mag van alles opschrijven. Schaam je niet!</li>
	</ol>";
}

function set_txt_instruction1_OC_traininstr() {
	echo
	"Je gaat zo beginnen met de braintraining sessie van vandaag. 
	Deze duurt 2x 10 minuten met een korte pauze tussendoor. </br></br>
	Het is de bedoeling dat je zoveel mogelijk eigenschappen voor gepresenteerde voorwerpen bedenkt. 
	Je krijgt in totaal 10 voorwerpen te zien. 
	Voor ieder voorwerp krijg je 2 minuten de tijd om zo veel mogelijk eigenschappen te bedenken. 
	Iedere bedachte eigenschap typ je in de balk onder de naam van het voorwerp.
	Na het intypen van een eigenschap druk op Enter zodat deze aan de lijst links onder in het scherm wordt toegevoegd.  
	Na de 2 minuten komt het volgende voorwerp in beeld.</br></br>";
}

function set_txt_instruction1_RS_traininstr() {
	echo
	"Je gaat zo beginnen met de braintraining sessie van vandaag. 
	Deze duurt 2x 10 minuten met een korte pauze tussendoor. </br></br>
	Je ziet straks rechthoeken en vierkanten gemaakt uit kleine rechthoekjes of vierkantjes. 
	Het is de bedoeling dat je het plaatje categoriseert als een rechthoek of vierkant volgens de regel getoond aan de zijkanten.
	Voor ieder plaatje moet je zo snel mogelijk beslissen in welke categorie het past door op de <b>linkertoets</b> (<b>q</b>) of <b>rechtertoets</b> (<b>p</b>) te drukken.
	<br/><br/>
	<table align='center'>
	  <tr>
	    <td>
 	      Voorbeeld 1.
	    </td>
	    <td>
	      Voorbeeld 2.
	    </td>
	  </tr>
	  <tr>
	    <td>
		<img width='400' src='gfx/rs3/gsr.png' />
	    </td>
	    <td>
		<img width='400' src='gfx/rs3/lrs.png' />
	    </td>
	  </tr>
	</table>
	<br/>
	Regel 1: Let op de grote rechthoek/vierkant. In het eerste voorbeeld kies je het vierkant met de rechtertoets.
	<br/>
	Regel 2: Let op waar de rechthoek/vierkant uit bestaat - kleine rechthoekjes of vierkantjes. 
	In het tweede voorbeeld kies je het kleine vierkant met de linkertoets.
	<br/><br/>
	In de eerste deel van de training worden plaatjes volgens regel 1 getoond. Daarna worden plaatjes van regel 2 getoond.
	Na de pauze gaan deze 2 regels doorelkaar en zal je soms op de grootte rechthoek/vierkant moeten 
	letten en soms op de kleine rechthoekjes of vierkantjes.
	";
}

function set_txt_instruction2($trainingphase) {
	if ($trainingphase = train_instr) {
		echo "Als je er klaar voor ben druk op Volgende.";
	}
}

function set_txt_answer_traininstr($trainingphase) {
	if ($trainingphase == train_instr) {
		echo "<form id='answerform' action='braintraining.php' method='POST'>
			<input type='submit' id='submit' value='OK' />
			</form>";
	}
	elseif ($trainingphase == train_post_motiv) {
		echo 'Dank je wel voor het trainen vandaag!<br/><br/>';
		echo 'Hierna volgt een kort vragenlijst van wat je ervan vond. Wil je dit eventjes invullen?';
		echo "<form id='answerform' action='postmotivation.php' method='POST'>
			<input type='submit' id='submit' value='Volgende' />
			</form>";
	}
	else {
		echo 'Super bedankt voor je medewerking!<br/><br/>';
		echo "<form id='answerform' action='logout.php' method='POST'>
			<input type='submit' id='submit' value='UITLOGGEN' />
			</form>";
	}
}

?>
