<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "table.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).

?>
<script type="text/javascript">
	function insertTableLine(plats,namn,antalratt,poang,bild)
	{
		var bild2 = "<img src='../images/"+bild+"' height='40px' width='40px' />";
		var h_odds = "3.41";
		var traffpro = "22%";
		var snittodds = "1.72";

		//Script som placerar in rätt värde i rätt td för startade matcher
		$("#standingstable").append("<tr class='standingstablerow'><td class='plats'>"+plats+"</td><td>"+bild2+"</td><td>"+namn+"</td><td><b>"+h_odds+"</b></td><td><b>"+traffpro+"</b></td><td><b>"+snittodds+"</b></td><td><b>"+antalratt+"</b></td><td><b>"+poang+"</b></td></tr>");

	}
</script>
<div id="tablepage">
	<h2 class="standings">Tips-tabellen</h2>
	<div id="bigbettable">
		<table id="standingstable">
			<tr id="standingstablehead">
				<th>
				</th>
				<th>					
				</th>
				<th>
					<h4>Namn</h4>
				</th>
				<th>
					<h4>Högsta Odds</h4>
				</th>
				<th>
					<h4>Träffprocent<br /><small>rätta tecken/match</small></h4>
				</th>
				<th>
					<h4>Snitt-odds<br /><small>odds/vunnen match</small></h4>
				</th>
				<th>
					<h4>Antal Rätt</h4>
				</th>
				<th>
					<h4>Poäng</h4>
				</th>
			</tr>
		</table>
	</div>
	<div id="rightcolumn">
		<div id="thisround">
			Omgångens...<br />
			- Bästa odds<br />
			- Bästa totalpoäng<br />
			- Flest antal rätt<br />
		</div>
		<div id="formdiv">
			<h4 class="standings">Formtabellen<small><br/>Senaste 10 matcherna</small></h4>
			<table id="formtable">
				<tr id="formtablehead">
					<th>
						Placering
					</th>
					<th>
						Namn
					</th>	
					<th>
						Antal Rätt
					</th>
					<th>
						Poäng
					</th>
				</tr>
			</table>
		</div>
	</div>
</div>

<?php

	//inkluderar hemliga saker
	include '../secretstuff.php';

	//För att förtydliga vad som skickas in i connect-funktionen
	$dbname = $user;

	// koppla upp mot databasen
	$connection = mysqli_connect($link, $user, $pass, $dbname);
	if (mysqli_connect_errno()) {
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$kollapoang = mysqli_query($connection, "SELECT NAMN, BILD, TOTALPOANG, ANTALRATT FROM TIPPARE ORDER BY TOTALPOANG DESC");
	$plats = 0;

	while ($kollapoang_row = mysqli_fetch_assoc($kollapoang))
	{
		$plats = $plats + 1;
		
		echo "<script>insertTableLine('" . $plats . "','" . utf8_encode($kollapoang_row['NAMN']) . "','" . $kollapoang_row['ANTALRATT'] . "','" . $kollapoang_row['TOTALPOANG'] . "','" . utf8_encode($kollapoang_row['BILD']) . "')</script>";
	}

?>

<!--
<h3><b>Inget att se här för tillfället!</b></h3>
Jag tänker lite vad som ska finnas här:
<ul>
	<li>Namn</li>
	<li>Poäng</li>
	<li>Antal Rätt (tecken)</li>
	<li>(Antal Rätta resultat)</li>
	<li>(Högsta odds)</li>
	<li>Träffprocent? (antal rätta tecken per match)</li>
	<li>Odds-snitt? (Lite som den ovan)</li>
	<li>Bild på tipparen?</li>
</ul>
-->