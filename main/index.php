<?php
session_start(); // NEVER forget this!
header("Content-type:text/html;charset=utf-8");
if(!isset($_SESSION['inloggad']))
{
    die("Du får inte titta om du inte <a href='loginmain.php'>Loggar in</a>"); // Make sure they are logged in!
} // What the !isset() code does, is check to see if the variable $_SESSION['loggedin'] is there, and if it isn't it kills the script telling the user to log in!

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
       	<title>Test av Pinnacle API</title>
        <meta name="description" content="Detta är en testsida, fan va fett!"> <!-- Typ bara beskrivning. -->
        <meta name="viewport" content="width=device-width"> <!-- iPhone använder sig av viewport. -->

        <link rel="stylesheet" href="../css/bootstrap.css"> <!-- Bootstrap är en css från twitter, lite för styling. Kanske inte nödvändig. -->

        <link rel="stylesheet" href="../css/bootstrap-responsive.css"> <!-- Samma som ovan. -->
        
        <script src="../js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script> <!-- Lite importerat javascript. -->

		<link href="../css/trontastic/jquery-ui-1.10.2.custom.css" rel="stylesheet"><!-- Ett jQuery-tema som jag laddade ner. Coolt. -->

		<script src="../js/jquery-1.9.1.js"></script> <!-- Själva jQuery. -->
		<script src="../js/jquery-ui-1.10.2.custom.js"></script> <!-- jQuery UI. -->

		<link rel="stylesheet" href="../custom/main.css"> <!-- Den personliga CSSen. -->

		<script src="../custom/main.js"></script> <!-- Personliga javascript. -->
	</head>
	
	<body>
		<div id="all">
			<div id="content">
				<div id="header"><h1>HEADER</h1></div>
				<div id="menu"><h2>MENY (Nyheter/Hem - Mitt tips - Allas Tips/Tabell)</h2></div>
				<div id="maincontent">
					<div id="newsfeed">
						<h2>NYHETER<br />
						(XML-feed)</h2>
					</div>
					<div id="discussion">
						<h2>DISKUSSION<br />
						Användare kan posta små inlägg.<br />
						Man kan bara posta när man är inloggad.<br />
						Inloggade användare kan tabort sina egna inlägg.<br />
						Kan behövas en databas för inläggen.<br />
						Bild, tidsstämpel och text publiceras.</h2>
					</div>
					<div id="standings">
						<h2>TABELL<br />
						(Simplare tabell)</h2>
						<!--
						<div class="ui-widget">
							<h2 class="demoHeaders">Testarsidan, va.</h2>
							<button class="btn btn-primary" onClick="xml_parse('name')">Lista på lag</button>
							<button id="button" onClick="xml_parse('homePrice')">Lista på hemmaodds</button>
							<button class="btn btn-large" onClick="xml_parse('startDateTime')">Lista på tider</button>
							<h3>
								<div id="xmlrespons">Här finns inget att se, hihi.</div>
							</h3>
						</div>
						-->
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<h3 style="display:inline;">Inloggad som <?php echo $_SESSION['anvandarnamn']; ?></h3>
			<a class="btn btn-large" href="logout.php" style="float:right; margin:5px;">Logga ut mig!</a>
		</div>
	</body>
</html>