<?php 
session_start(); // This starts the session which is like a cookie, but it isn't saved on your hdd and is much more secure.
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Logga in</title>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width"> <!-- iPhone använder sig av viewport. -->
		
		<script src="../js/jquery-1.9.1.js"></script> <!-- Själva jQuery. -->
		<script src="../custom/main.js"></script> <!-- Personliga javascript. -->
		<script src="../js/jquery.mobile-1.3.0.js"></script> <!-- Själva jQuery Mobile. -->
		
		<link rel="stylesheet" href="../css/jquery.mobile.structure-1.3.0.css" /> <!-- jQuery Mobile CSSen. -->
		<link rel="stylesheet" href="../css/jquery.mobile.theme-1.3.0.css" /> <!-- jQuery Mobile CSSen. -->
		<link rel="stylesheet" href="../css/jquery.mobile-1.3.0.css" /> <!-- jQuery Mobile CSSen. -->
	</head>
	<body>
		<div class="ui-widget">
			<h2 class="demoHeaders">Logga In</h2>
			<form action="" method="post">
			    <ul data-role="listview" data-inset="true">
			        <li data-role="fieldcontain">
			            <label for="text-basic">Användarnamn:</label>
			            <input type="text" name="anvandarnamn" value="" autofocus="true" id="text-basic"><br />
			        </li>
			        <li data-role="fieldcontain">
			            <label for="password">Lösenord:</label>
			            <input type="password" name="losenord" id="password" value="" autocomplete="off">
			        </li>
			        <li class="ui-body ui-body-b">
			            <fieldset class="ui-grid-a">
			                    <div class="ui-block-a"><button type="reset" data-theme="d">Cancel</button></div>
			                    <div class="ui-block-b"><button type="submit" name="submit" value="login" data-theme="a">Submit</button></div>
			            </fieldset>
			        </li>
			    </ul>
			</form>
		</div>
		<?php
			if($_POST['submit']=='login')
			{
				//inkluderar hemliga saker
				include '../secretstuff.php';

				//För att förtydliga vad som skickas in i connect-funktionen
				$dbname = $user;

				// koppla upp mot databasen
				$connection = mysqli_connect($link, $user, $pass, $dbname);
				if (mysqli_connect_errno()) {
				    echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}

				

				if(isset($_SESSION['inloggad']))
				{
				    echo "<script>alert('Redan inloggad som " . $_SESSION['anvandarnamn'] . "!');</script>";
				    //header('Location: inloggad.php');
				    die();
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
				        die("<script>alert('Felaktig inloggning!');</script>");
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

				    /* free result set */
				    mysqli_free_result($userquery);

				    mysqli_close($connection);

				    die(
				      header('Location: inloggad.php') //<meta http-equiv="Refresh" content="2; URL=../mobile/inloggad.php">
				    );
				    
				}

			}
		?>
	</body>
</html>
