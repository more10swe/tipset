<?php
header("Content-type:text/html;charset=utf-8");
//header("Refresh:3;form.php");

//inkluderar hemliga saker
include '../secretstuff.php';

//För att förtydliga vad som skickas in i connect-funktionen
$dbname = $user;

// koppla upp mot databasen
$connection = mysqli_connect($link, $user, $pass, $dbname);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$filename = "datan.xml";

$oddsfeed = simplexml_load_file($filename);

$hemmalag = $oddsfeed->fd->sports->sport->leagues->league->events->event->homeTeam->name;
$bortalag = $oddsfeed->fd->sports->sport->leagues->league->events->event->awayTeam->name;
$matchtid = $oddsfeed->fd->sports->sport->leagues->league->events->event->startDateTime;
$matchid = $oddsfeed->fd->sports->sport->leagues->league->events->event->id;

# För att se strukturen enkelt.
#<rsp status="ok"><fd><sports><sport><leagues><league><events><event><startDateTime>2013-04-12T16:59:00Z</startDateTime><id>296512768</id><homeTeam><name>Brommapojkarna</name><rotNum>5901</rotNum></homeTeam><awayTeam><name>IFK Norrkoping FK
#
echo $hemmalag . " - " . $bortalag;

// två sql-satser
$queryinsert = "INSERT INTO MATCHER VALUES ('$matchid','$hemmalag','$bortalag','$matchtid')";
$queryupdate = "UPDATE MATCHER SET HEMMALAG='$hemmalag', BORTALAG='$bortalag', MATCHTID='$matchtid' WHERE 'MATCH-ID'='$matchid'";

echo "<br />";


// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysqli_query($connection, "SELECT `MATCH-ID` FROM MATCHER HAVING `MATCH-ID`='$matchid'");
$results = mysqli_fetch_assoc($simplequery);
echo '<pre>'.print_r($results,true).'</pre>';
echo "Kan det bli en tvåa? " . $results['MATCH-ID'] . "<br />";


if($results['MATCH-ID']!='')
{
	// utför själva frågan
	mysqli_query($connection, $queryupdate);
	    //or die(header("location:pinnacleapitest.php"));
		or die();
}
else
{
	// utför själva frågan
	mysqli_query($connection, $queryinsert);
	    //or die(header("location:pinnacleapitest.php"));
		or die();
}
?>
<html>
	<head>
		<title>
			Postar!
		</title>
	</head>
	<body>
		Inget att se.
	</body>
</html>