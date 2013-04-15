<?php
header("Content-type:text/html;charset=utf-8");
//header("Refresh:3;form.php");

//inkluderar hemliga saker
include '../secretstuff.php';

// koppla upp mot databasen
$link = mysql_connect($link, $user, $pass)
    or die("Could not connect");
// välj databasen 
mysql_select_db("msundh_se")
    or die("Could not select database");

$filename = "datan.xml";

$oddsfeed = simplexml_load_file($filename);

$hemmamal = "2";
$bortamal = "2";
$matchid = "2";


// två sql-satser
$queryinsert = "INSERT INTO RESULTAT VALUES ('$matchid','$hemmamal','$bortamal')";
$queryupdate = "UPDATE RESULTAT SET HEMMAMAL='$hemmamal', BORTAMAL='$bortamal' WHERE `MATCH-ID`='$matchid'";

echo "<br />";


// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysql_query("SELECT `MATCH-ID` FROM RESULTAT HAVING `MATCH-ID`='$matchid'");
$results = mysql_fetch_assoc($simplequery);
echo '<pre>'.print_r($results,true).'</pre>';


if($results['MATCH-ID']!='')
{
	// utför själva frågan
	mysql_query($queryupdate)
	    //or die(header("location:pinnacleapitest.php"));
		or die();
}
else
{
	// utför själva frågan
	mysql_query($queryinsert)
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