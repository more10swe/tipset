<?php
session_start();
header("Content-type:text/html;charset=utf-8");
if(!isset($_SESSION['inloggad']))
{
    die("Du var aldrig inloggad, gå och <a href='loginmain.php'>Logga in</a>"); // Make sure they are logged in!
} // What the !isset() code does, is check to see if the variable $_SESSION['loggedin'] is there, and if it isn't it kills the script telling the user to log in!
else
{
	$_SESSION = array();
	session_destroy();
	die(print '<html><head><meta http-equiv="Refresh" content="2; URL=loginmain.php"></head>Loggar ut...</html>');
}
    
?>