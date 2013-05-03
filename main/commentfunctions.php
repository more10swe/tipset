<?php
	header('Content-Type: text/xml, charset=utf-8');
	/*echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';*/
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).
	
	$text = $_GET['comment'];
	$tipparid = $_SESSION['tipparid'];
	
	$what = $_GET['what'];
	//if (isset($_GET['comment'])){
		
	//inkluderar hemliga saker
	include '../secretstuff.php';
	//För att förtydliga vad som skickas in i connect-funktionen
	$dbname = $user;	
	// koppla upp mot databasen
	$connection = mysqli_connect($link, $user, $pass, $dbname);	
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	mysqli_set_charset($connection,"utf8"); // mysqli extension
	
	
	
	if($what == "post"){
		$query = "INSERT INTO KOMMENTARER VALUES (NULL, $tipparid, '$text', CURRENT_TIMESTAMP)";
	}
	else if ($what == "get"){
		$query = "SELECT * FROM KOMMENTARER NATURAL JOIN TIPPARE ORDER BY TIDSSTAMPEL ASC";
	}
	
	
	$result = mysqli_query($connection, $query);
	
	if($what == "post"){
		$xmlstring = "";
		$xmlstring .= "<allt>";
		
		$namn = $_SESSION['namn'];
		$tidsstampel = date('Y-m-d H:i:s');				
		
		$xmlstring .= "<text>".$text."</text>\n\t\t";
		$xmlstring .= "<namn>".$namn."</namn>\n\t\t";
		$xmlstring .= "<tidsstampel>".$tidsstampel."</tidsstampel>\n\t\t";
		$xmlstring .= "<tipparid>".$tipparid."</tipparid>\n\t\t";

		$xmlstring .= "</allt>";
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		echo $xmlstring;
	}
	
	else if($what == "get"){
		$xmlstring = "";
		$xmlstring .= "<allt>";
		while ($row = mysqli_fetch_assoc($result)) {
				
				$kommentator = $row['TIPPAR-ID'];
				$text = $row['TEXT'];
				$namn = $row['NAMN'];
				$tidsstampel = $row['TIDSSTAMPEL'];				
				$tipparid = $_SESSION['tipparid'];
				
				$xmlstring .= "<kommentar>\n\t\t";
				$xmlstring .= "<kommentator>".$kommentator."</kommentator>\n\t\t";
				$xmlstring .= "<text>".$text."</text>\n\t\t";
				$xmlstring .= "<namn>".$namn."</namn>\n\t\t";
				$xmlstring .= "<tidsstampel>".$tidsstampel."</tidsstampel>\n\t\t";
				$xmlstring .= "<tipparid>".$tipparid."</tipparid>\n\t\t";
				$xmlstring .= "</kommentar>\n\t";
		}
		$xmlstring .= "</allt>";
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		echo $xmlstring;
	}
	
	/* close connection */
	mysqli_close($connection);
?>