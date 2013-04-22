<?php
session_start(); // NEVER forget this!

//inkluderar hemliga saker
include '../secretstuff.php';

//För att förtydliga vad som skickas in i connect-funktionen
$dbname = $user;

// koppla upp mot databasen
$connection = mysqli_connect($link, $user, $pass, $dbname);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$tipparid = $_SESSION['tipparid'];
$matchid = $_POST['matchid'];
$hemmamal_t = $_POST['hemmamal'];
$bortamal_t = $_POST['bortamal'];

print $tipparid . " " . $matchid . " " . $hemmamal_t . " " . $bortamal_t;

// två sql-satser
$queryinsert = "INSERT INTO TIPS VALUES ('$tipparid','$matchid','$ort','$mail')";
$queryupdate = "UPDATE TIPS SET HEMMAMAL_T='$hemmamal_t', BORTAMAL_T='$bortamal_t' WHERE `TIPPAR-ID`='$tipparid' AND `MATCH-ID`='$matchid'";



// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysqli_query($connection, "SELECT `TIPPAR-ID`, `MATCH-ID` FROM TIPS HAVING `TIPPAR-ID`='$tipparid' AND `MATCH-ID`='$matchid'");
$results = mysqli_fetch_assoc($simplequery);

if($results['MATCH-ID']!='' && $results['TIPPAR-ID']!='')
{
	// utför själva frågan
	mysqli_query($connection, $queryupdate);
	    //or die(header("location:pinnacleapitest.php"));
		//or die();
}
else
{
	// utför själva frågan
	mysqli_query($connection, $queryinsert);
	    //or die(header("location:pinnacleapitest.php"));
		//or die();
}
?>
