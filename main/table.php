<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "table.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).

	//inkluderar hemliga saker
	include '../secretstuff.php';

	//För att förtydliga vad som skickas in i connect-funktionen
	$dbname = $user;

	// koppla upp mot databasen
	$connection = mysqli_connect($link, $user, $pass, $dbname);
	if (mysqli_connect_errno()) {
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT * FROM MATCHER";

?>
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