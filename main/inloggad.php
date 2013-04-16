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
			<a href="../common/logout.php">Logga ut</a>
		</div>
	</body>
</html>