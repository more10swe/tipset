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

$db = new dbAccess('msundh.se.mysql' , 'msundh_se' , 'JyVjkJCN' , 'msundh_se');

echo "<br />";


// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysql_query("SELECT `MATCH-ID` FROM MATCHER HAVING `MATCH-ID`='$matchid'");
$results = mysql_fetch_assoc($simplequery);
echo '<pre>'.print_r($results,true).'</pre>';
echo "Kan det bli en tvåa? " . $results['MATCH-ID'] . "<br />";


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