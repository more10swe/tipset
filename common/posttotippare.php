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

$tipparid = "1";
$namn = "Jacke Rövgren";
$ort = "Jakan";
$mail = "noll_kontrol95@hotmail.com";


// två sql-satser
$queryinsert = "INSERT INTO TIPPARE VALUES ('$tipparid','$namn','$ort','$mail')";
$queryupdate = "UPDATE TIPPARE SET NAMN='$namn', ORT='$ort', MAIL='$mail' WHERE `TIPPAR-ID`='$tipparid'";

echo "<br />";


// En snäll notering: MAN MÅSTE ANVÄNDA SÅ KALLADE BACKTICKS NÄR SQL-TABELLEN ELLER KOLUMNEN INNEHÅLLER ETT BINDESTRECK ELLER LIKNANDE. ALLTSÅ SÅNAHÄRA: `
$simplequery = mysqli_query($connection, "SELECT `TIPPAR-ID` FROM TIPPARE HAVING `TIPPAR-ID`='$tipparid'");
$results = mysql_fetch_assoc($simplequery);
echo '<pre>'.print_r($results,true).'</pre>';


if($results['TIPPAR-ID']!='')
{
	// utför själva frågan
	mysqli_query($connection, $queryupdate);
	    //or die(header("location:pinnacleapitest.php"));
		or die();
}
else
{
	// utför själva frågan
	mysqli_query($connection, $queryupdate);
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