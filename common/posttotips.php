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

// Nu ska vi bara hämta oddset för det rätta tecknet!

$filename = "datan.xml";
$oddsfeed = simplexml_load_file($filename);

foreach ($oddsfeed->fd->sports->sport->leagues->league->events->event as $match) //Stor slinga som stoppar in alla matcherna i databasen!
{
	if ($match->id == $matchid) 
	{
		foreach ($match->periods->period as $oddsfeed2) 
		{
			if ($hemmamal_t>$bortamal_t) 
			{
				$odds = $oddsfeed2->moneyLine->homePrice;
			}
			else if ($hemmamal_t==$bortamal_t) 
			{
				$odds = $oddsfeed2->moneyLine->drawPrice;
			}
			else if ($hemmamal_t<$bortamal_t) 
			{
				$odds = $oddsfeed2->moneyLine->awayPrice;
			}
			else
			{
				print_r("Något knas med resultatjämförelse.");
			}
		}
	}
}



print $tipparid . " " . $matchid . " " . $hemmamal_t . " " . $bortamal_t . " " . $odds;

// två sql-satser
$queryinsert = "INSERT INTO TIPS VALUES ('$tipparid','$matchid','$hemmamal_t','$bortamal_t','$odds')";
$queryupdate = "UPDATE TIPS SET HEMMAMAL_T='$hemmamal_t', BORTAMAL_T='$bortamal_t', ODDS='$odds' WHERE `TIPPAR-ID`='$tipparid' AND `MATCH-ID`='$matchid'";



// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysqli_query($connection, "SELECT `TIPPAR-ID`, `MATCH-ID` FROM TIPS HAVING `TIPPAR-ID`='$tipparid' AND `MATCH-ID`='$matchid'");
$results = mysqli_fetch_assoc($simplequery);

if($results['MATCH-ID']!='' && $results['TIPPAR-ID']!='')
{
	// utför själva frågan
	mysqli_query($connection, $queryupdate);
	print_r(" uppdaterar");
	    //or die(header("location:pinnacleapitest.php"));
		//or die();
}
else
{
	// utför själva frågan
	mysqli_query($connection, $queryinsert);
	print_r(" insertar");
	    //or die(header("location:pinnacleapitest.php"));
		//or die();
}
?>
