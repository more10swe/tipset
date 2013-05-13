<?php
session_start(); // NEVER forget this!
header("Content-type:text/html;charset=utf-8");

if(!isset($_SESSION['inloggad']))
{
    die("Du får inte titta om du inte <a href='loginmain.php'>Loggar in</a>"); // Make sure they are logged in!
} // What the !isset() code does, is check to see if the variable $_SESSION['loggedin'] is there, and if it isn't it kills the script telling the user to log in!


//inkluderar hemliga saker
include '../secretstuff.php';

//För att förtydliga vad som skickas in i connect-funktionen
$dbname = $user;

// koppla upp mot databasen
$connection = mysqli_connect($link, $user, $pass, $dbname);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


/*
Här vill vi räkna ut poänger för alla matcher och alla tippare!
*/
$tipparquery = "SELECT * FROM TIPPARE ORDER BY `TIPPAR-ID` ASC";

if ($tipparquery2 = mysqli_query($connection, $tipparquery)) 
{

    /* fetch associative array */
    while ($tipparquery3 = mysqli_fetch_assoc($tipparquery2)) 
    {

		$matchquery = "SELECT * FROM MATCHER ORDER BY `MATCH-ID` ASC";

		if ($matchquery2 = mysqli_query($connection, $matchquery)) 
		{

		    /* fetch associative array */
		    while ($matchquery3 = mysqli_fetch_assoc($matchquery2)) 
		    {
		    	$matchid = $matchquery3['MATCH-ID'];
		    	$tipparid = $tipparquery3['TIPPAR-ID'];
		        $tipsquery = "SELECT * FROM TIPS WHERE `MATCH-ID`=$matchid AND `TIPPAR-ID`=$tipparid";

				if ($tipsquery2 = mysqli_query($connection, $tipsquery)) 
				{

				    /* fetch associative array */
				    $tipsquery3 = mysqli_fetch_assoc($tipsquery2);
				    
				    /* free result set */
				    mysqli_free_result($tipsquery2);
				}

				## Här bestäms om matchen har startat eller inte
				## Jag är dock inte helt med på varför jämförelsen blir som den blir, det borde bli tvärtom..
				$avspark = date_create($matchquery3['MATCHTID']);
				$nutid = date_create("now");
				if ($avspark<$nutid) 
				{
					$resultat_h = mysqli_query($connection, "SELECT HEMMAMAL FROM RESULTAT WHERE `MATCH-ID`=$matchid");
					$res_answer = mysqli_fetch_assoc($resultat_h);
					$res_h = $res_answer['HEMMAMAL'];

					mysqli_free_result($resultat_h);

					$resultat_b = mysqli_query($connection, "SELECT BORTAMAL FROM RESULTAT WHERE `MATCH-ID`=$matchid");
					$res_answer = mysqli_fetch_assoc($resultat_b);				
					$res_b = $res_answer['BORTAMAL'];

					mysqli_free_result($resultat_b);

					$finnspoang = "SELECT POANG FROM TIPS WHERE `MATCH-ID`=$matchid AND `TIPPAR-ID`=$tipparid";
					$finnspoang2 = mysqli_query($connection, $finnspoang);
					$finnspoang3 = mysqli_fetch_assoc($finnspoang2);

					$poang = $finnspoang3['POANG'];
					$ingenpoang = -1;


					if($poang==$ingenpoang)
					{
						$hamtaodds = "SELECT ODDS FROM TIPS WHERE `MATCH-ID`=$matchid AND `TIPPAR-ID`=$tipparid";
						$hamtaodds2 = mysqli_query($connection, $hamtaodds);
						$hamtaodds3 = mysqli_fetch_assoc($hamtaodds2);
						
						$oddspoang = $hamtaodds3['ODDS'];
						$nollpoang = 0;
						$orapporterad = -1;

						$sparapoang1 = "UPDATE TIPS SET POANG='$oddspoang' WHERE `MATCH-ID`='$matchid' AND `TIPPAR-ID`=$tipparid";
						$sparapoang2 = "UPDATE TIPS SET POANG='$nollpoang' WHERE `MATCH-ID`='$matchid' AND `TIPPAR-ID`=$tipparid";
						$sparapoang3 = "UPDATE TIPS SET POANG='$orapporterad' WHERE `MATCH-ID`='$matchid' AND `TIPPAR-ID`=$tipparid";
						
						if ($res_h==999 && $res_b==999) 
						{
							mysqli_real_query($connection, $sparapoang3);
						}
						else
						{
							if (($tipsquery3['HEMMAMAL_T']>$tipsquery3['BORTAMAL_T']) && ($res_h>$res_b)) 
							{
								//Spara 1a-odds
								mysqli_real_query($connection, $sparapoang1);
							}
							elseif (($tipsquery3['HEMMAMAL_T']==$tipsquery3['BORTAMAL_T']) && ($res_h==$res_b)) 
							{
								// Spara X-odds
								mysqli_real_query($connection, $sparapoang1);																
							}
							elseif (($tipsquery3['HEMMAMAL_T']<$tipsquery3['BORTAMAL_T']) && ($res_h<$res_b)) 
							{
								// Spara 2a-odds
								mysqli_real_query($connection, $sparapoang1);
							}
							else
							{
								//Spara 0 poäng
								mysqli_real_query($connection, $sparapoang2);
							}
						}
		
						/* free result set */
						mysqli_free_result($hamtaodds2);
					}
					/* free result set */
					mysqli_free_result($finnspoang2);
				}					
				
		    }

		    
		}
	}
}
/* free result set */
mysqli_free_result($tipparquery2);




/* 
Här börjar uträkningen av totalpoäng som sedan sparas till databasen. 
*/


$kollapoang = mysqli_query($connection, "SELECT `TIPPAR-ID`, POANG FROM TIPS ORDER BY `TIPPAR-ID`");

$nuvarande_anv = null;
while ($kollapoang_row = mysqli_fetch_assoc($kollapoang))
{
	if ($kollapoang_row['TIPPAR-ID'] != $nuvarande_anv)
	{
		if($nuvarande_anv != null)
		{
			mysqli_real_query($connection, "UPDATE TIPPARE SET TOTALPOANG='$totalpoang', ANTALRATT='$antalratt' WHERE `TIPPAR-ID`='$nuvarande_anv'");
			if($nuvarande_anv==$_SESSION['tipparid'])
			{
				$_SESSION['totalpoang'] = $totalpoang; // Sparar den aktuelle användarens totalpoäng!
				$_SESSION['antalratt'] = $antalratt; // Sparar den aktuelle användarens antal rätt!
			}
		}
		$nuvarande_anv = $kollapoang_row['TIPPAR-ID'];
		$totalpoang = 0;
		$antalratt = 0;
	}

	
	if($kollapoang_row['POANG']>0)
	{
		$totalpoang = $totalpoang + $kollapoang_row['POANG'];
		$antalratt = $antalratt + 1;
	}
}
//För den siste i raden av tippar-id:n!
mysqli_real_query($connection, "UPDATE TIPPARE SET TOTALPOANG='$totalpoang', ANTALRATT='$antalratt' WHERE `TIPPAR-ID`='$nuvarande_anv'");
if($nuvarande_anv==$_SESSION['tipparid'])
{
	$_SESSION['totalpoang'] = $totalpoang; // Sparar den aktuelle användarens totalpoäng!
	$_SESSION['antalratt'] = $antalratt; // Sparar den aktuelle användarens antal rätt!
}

/*
Slut på spara poängen!
*/

?>
<!DOCTYPE html>

<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>
    	<link rel="icon" href="../favicon.ico">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
       	<title>Kompistipset</title>
        <meta name="description" content="Detta är en testsida, fan va fett!"> <!-- Typ bara beskrivning. -->
        <meta name="viewport" content="width=device-width"> <!-- iPhone använder sig av viewport. -->

        <link rel="stylesheet" href="../css/bootstrap.css"> <!-- Bootstrap är en css från twitter, lite för styling. Kanske inte nödvändig. -->

        <link rel="stylesheet" href="../css/bootstrap-responsive.css"> <!-- Samma som ovan. -->
		
		<link href='http://fonts.googleapis.com/css?family=Oranienbaum&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
		
        <script src="../js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script> <!-- Lite importerat javascript. -->
        <script src="../js/vendor/bootstrap.js"></script> <!-- Bootstrap-javascript. -->

		<link href="../css/trontastic/jquery-ui-1.10.2.custom.css" rel="stylesheet"><!-- Ett jQuery-tema som jag laddade ner. Coolt. -->

		<script src="../js/jquery-1.9.1.js"></script> <!-- Själva jQuery. -->
		<script src="../js/jquery-ui-1.10.2.custom.js"></script> <!-- jQuery UI. -->

		<link rel="stylesheet" href="../custom/main.css"> <!-- Den personliga CSSen. -->

		<script src="../custom/main.js"></script> <!-- Personliga javascript. -->
		
	</head>
	
	<body>
		<div id="all">
			<div id="content">
				<div id="header"><h1>VM-tipset 2014</h1></div>
				<div id="menu"><button class="btn btn-info menubutton" onclick="getPage('start.php','nej')">Hem &nbsp; <i class="icon-home"></i></button><button class="btn btn-info menubutton" onclick="getPage('tips.php','nej')">Mitt tips &nbsp; <i class="icon-pencil"></i></button><button class="btn btn-info menubutton" onclick="getPage('table.php','nej')">Tabell &nbsp; <i class="icon-list-alt"></i></button></div>
				<div id="maincontent">
				</div>
			</div>
		</div>
		<div id="footer">
			<h3 style="display:inline;">Inloggad som <?php echo $_SESSION['anvandarnamn']; ?> / Tipspoäng: <?php echo $_SESSION['totalpoang']; ?> / Antal rätt: <?php echo $_SESSION['antalratt']; ?></h3>
			<a class="btn btn-large btn-danger" href="logout.php" style="float:right; margin:5px;">Logga ut mig! &nbsp; <i class="icon-off"></i></a>
		</div>

		<?php
		//DET HÄR ÄR KOD FÖR ATT LADDA OM DEN SIDA SOM MAN TITTADE PÅ. DET ÄR IRRITERANDE ATT HAMNA PÅ STARTSIDAN HELA TIDEN!
			if (isset($_SESSION['sida'])) 
			{
				print("<script>getPage('" . $_SESSION['sida'] . "','ja');</script>");
			}
		?>
	</body>
</html>