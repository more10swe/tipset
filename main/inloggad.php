<?php
session_start(); // NEVER forget this!
header("Content-type:text/html;charset=utf-8");
if(!isset($_SESSION['inloggad']))
{
    die("Du får inte titta om du inte <a href='loginmain.php'>Loggar in</a>"); // Make sure they are logged in!
} // What the !isset() code does, is check to see if the variable $_SESSION['loggedin'] is there, and if it isn't it kills the script telling the user to log in!

?>
<html>
	<head>
		<title>Huvudmeny</title>
		<link rel="icon" href="../favicon.ico">
        <meta charset="utf-8">

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
		<div class="all">
			<div class="header">
				<h1>Wiiiie! Inloggad som <?php echo $_SESSION['anvandarnamn']; ?></h1>
			</div>
			<div class="content">
				<h2>Så det är du som är <?php echo $_SESSION['namn'] . " från " . $_SESSION['ort'] . "?";?>
			</div>
			<br />
			<a href="logout.php">Logga ut</a>
		</div>
	</body>
</html>