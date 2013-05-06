<?php
	header('Content-Type: text/xml, charset=utf-8');
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).		
	if(!isset($_SESSION['inloggad']))
	{
		die("Du får inte titta om du inte <a href='loginmain.php'>Loggar in</a>"); // Make sure they are logged in!
	}	
	include '../secretstuff.php';
	//För att förtydliga vad som skickas in i connect-funktionen
	$dbname = $user;	
	$connection = mysqli_connect($link, $user, $pass, $dbname);	
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	mysqli_set_charset($connection,"utf8"); // mysqli extension
	
	// denna kan antingen vara post, get eller remove
	$what = $_POST['what'];
	$tipparid = $_SESSION['tipparid'];	
	
	function create_xmlstring($query){
		global $tipparid;
		global $connection;		
		$result = mysqli_query($connection, $query);		
		$xmlstring = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		$xmlstring .= "<allt>";
		while ($row = mysqli_fetch_assoc($result)) {				
			$kommentator = $row['TIPPAR-ID'];
			$text = $row['TEXT'];
			$namn = $row['NAMN'];
			$tidsstampel = date('Y-m-d H:i:s', strtotime($row['TIDSSTAMPEL'])+2*60*60);	
			$kommentarid = $row['KOMMENTAR-ID'];
			
			$xmlstring .= "<kommentar>\n\t\t";
			$xmlstring .= "<kommentator>".$kommentator."</kommentator>\n\t\t";
			$xmlstring .= "<text>".$text."</text>\n\t\t";
			$xmlstring .= "<namn>".$namn."</namn>\n\t\t";
			$xmlstring .= "<tidsstampel>".$tidsstampel."</tidsstampel>\n\t\t";
			$xmlstring .= "<tipparid>".$tipparid."</tipparid>\n\t\t";
			$xmlstring .= "<kommentarid>".$kommentarid."</kommentarid>\n\t\t";
			$xmlstring .= "</kommentar>\n\t";
		}		
		$xmlstring .= "</allt>";		
		return $xmlstring;
	} 
	
	if($what == "post"){
		$text = htmlspecialchars($_POST['comment']);
		$query = "INSERT INTO KOMMENTARER VALUES (NULL, $tipparid, '$text', CURRENT_TIMESTAMP)";
		mysqli_query($connection, $query);
		$query = "SELECT * FROM KOMMENTARER NATURAL JOIN TIPPARE ORDER BY TIDSSTAMPEL DESC LIMIT 1";
		$xmlstring = create_xmlstring($query);		
		echo $xmlstring;
	}
	
	else if($what == "get"){
		$query = "SELECT * FROM KOMMENTARER NATURAL JOIN TIPPARE ORDER BY TIDSSTAMPEL DESC";
		
		$xmlstring = create_xmlstring($query);
		echo $xmlstring;
	}

	else if($what == "remove"){
		$kommentarid = $_POST['kommentarid'];
		$query = "SELECT * FROM `KOMMENTARER` WHERE `KOMMENTAR-ID` = $kommentarid";
		$result = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$tipparid_ref = $row['TIPPAR-ID'];
		}
		if($tipparid == $tipparid_ref){
			$query = "DELETE FROM `KOMMENTARER` WHERE `KOMMENTAR-ID` = $kommentarid";
			mysqli_query($connection, $query);
			echo "<?xml version='1.0' encoding='UTF-8' standalone='yes'?><authorized>true</authorized>";
		}
	}
	
	/* close connection */
	mysqli_close($connection);
?>