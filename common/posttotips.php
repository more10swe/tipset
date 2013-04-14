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

$tipparid = "1";
$matchid = "2";
$hemmamal_t = "1";
$bortamal_t = "1";


// två sql-satser
$queryinsert = "INSERT INTO TIPS VALUES ('$tipparid','$matchid','$ort','$mail')";
$queryupdate = "UPDATE TIPS SET HEMMAMAL_T='$hemmamal_t', BORTAMAL_T='$bortamal_t' WHERE `TIPPAR-ID`='$tipparid' AND `MATCH-ID`='$matchid'";

$db = new dbAccess('msundh.se.mysql' , 'msundh_se' , 'JyVjkJCN' , 'msundh_se');

echo "<br />";


// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysql_query("SELECT `TIPPAR-ID`, `MATCH-ID` FROM TIPS HAVING `TIPPAR-ID`='$tipparid' AND `MATCH-ID`='$matchid'");
$results = mysql_fetch_assoc($simplequery);
echo '<pre>'.print_r($results,true).'</pre>';

if($results['MATCH-ID']!='' && $results['TIPPAR-ID']!='')
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