<?php
header("Content-type:text/html;charset=utf-8");

//inkluderar hemliga saker
include '../secretstuff.php';

//För att förtydliga vad som skickas in i connect-funktionen
$dbname = $user;

// koppla upp mot databasen
$connection = mysqli_connect($link, $user, $pass, $dbname);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

session_start(); // This starts the session which is like a cookie, but it isn't saved on your hdd and is much more secure.

if(isset($_SESSION['inloggad']))
{
    die(print '<html><head><meta http-equiv="Refresh" content="2; URL=inloggad.php"></head>Redan inloggad, skickar dig till tipset...</html>');
} // That bit of code checks if you are logged in or not, and if you are, you can't log in again!
else if(isset($_POST['anvandarnamn']))
{
    $anvandarnamn = mysqli_real_escape_string($connection,$_POST['anvandarnamn']); // The function mysql_real_escape_string() stops hackers!
    $losenord = mysqli_real_escape_string($connection,$_POST['losenord']); // We won't use MD5 encryption here because it is the simple tutorial, if you don't know what MD5 is, dont worry!

    $statement = mysqli_prepare($connection, "SELECT * FROM ANVANDARE WHERE ANVANDARNAMN = '$anvandarnamn' AND LOSENORD = '$losenord'"); // This code uses MySQL to get all of the users in the database with that username and password.
    /* execute query */
    mysqli_stmt_execute($statement);

    /* store result */
    mysqli_stmt_store_result($statement);

    if(mysqli_stmt_num_rows($statement) < 1)
    {
        die(print '<html><head><meta http-equiv="Refresh" content="3; URL=loginmain.php"></head>Felaktiga inloggningsuppgifter...</html>');
    } // That snippet checked to see if the number of rows the MySQL query was less than 1, so if it couldn't find a row, the password is incorrect or the user doesn't exist!

    /* bind result variables */
    mysqli_stmt_bind_result($statement, $tipparid, $anvandarnamn, $losenord);
    mysqli_stmt_fetch($statement);

    $userquery = mysqli_query($connection, "SELECT * FROM TIPPARE WHERE `TIPPAR-ID` = '$tipparid'");

    $meranvandardata = mysqli_fetch_assoc($userquery);

    $_SESSION['inloggad'] = "ja"; // Set it so the user is logged in!
    $_SESSION['tipparid'] = $tipparid;
    $_SESSION['anvandarnamn'] = $anvandarnamn; // Make it so the username can be called by $_SESSION['name']

    $_SESSION['namn'] = utf8_encode($meranvandardata["NAMN"]);
    $_SESSION['ort'] = $meranvandardata["ORT"];
    $_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha). Börjar med startsidan.

    /* free result set */
    mysqli_free_result($userquery);

    mysqli_close($connection);

    die(
      print '<html><head><meta http-equiv="Refresh" content="0; URL=index.php"></head><body>DU LOGGADE IN!</body></html>'  //
    ); // Kill the script here so it doesn't show the login form after you are logged in!
    
} // That bit of code logs you in! The "$_POST['submit']" bit is the submission of the form down below VV

print '<html><head><meta http-equiv="Refresh" content="3; URL=loginmain.php"></head>Hur hamnade du här?</html>';

?>