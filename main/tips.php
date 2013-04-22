<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "tips.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).

	?>

<script src="../js/jquery-1.9.1.js"></script> <!-- Själva jQuery. -->
<script type="text/javascript">
	function insertLine(tid,hemma,borta,tips,matchid)
	{
		res="";
		tecken="";
		poang="(6,66)";
		aendra="<input id='"+matchid+"' type='text' size='1'></input>";
		//Script som placerar in rätt värde i rätt td
		$("#bettable").append("<tr><td>"+tid+"</td><td>"+hemma+"</td><td>-</td><td>"+borta+"</td><td>"+res+"</td><td>"+tecken+"</td><td>"+tips+"</td><td>"+poang+"</td><td>"+aendra+"</td></tr>");
	}

	$("#sparatips").click(function()
	{
		$("input").each(function()
		{
			if($(this).val()!="")
			{
				var korrektformat = false;
				var hemma;
				var borta;
				try 
				{ 
					resultatinput = $(this).val(); //Värdet i det aktuella inmatningsfältet
					var pattern = /[0-9]*(?=-)/; //Ett så kallat regular expression som letar efter siffror fram till dess att det kommer ett -
					hemma = resultatinput.match(pattern); //Sparar det talet som står innan -
					hemma = hemma.toString(); //Svaret kommer som en array så vi måste göra om det till en sträng
					borta = resultatinput.substring(hemma.length+1,resultatinput.length); //Sparar det som står efter - (tar inte med -)
					korrektformat = true;
				} 
				catch(e) 
				{
					alert("Resultatet skall vara på formatet X-Y! <br />ex) 2-0")
				}
				finally
				{
					if(korrektformat)
					{
						inputid = $(this).attr("id");
						//var urltopost = "matchid=" + inputid + "&hemmamal=" + hemmamal + "&bortamal=" + bortamal;
						
						/* 
						$.ajax({  
						  type: "POST",  
						  url: "http://www.masundh.se/maudambet/common/posttotips.php",  
						  data: urltopost,  
						  success: function() { 
						  	alert("postade nog nu!");
						  }  
						});
						*/

						$.post("../common/posttotips.php", { matchid: inputid, hemmamal: hemma, bortamal: borta } ).error(function() {alert("error");});    
					}
						
				}
				
			}
		});
		getPage("tips.php");
	});
</script>

<?php

	//inkluderar hemliga saker
	include '../secretstuff.php';

	//För att förtydliga vad som skickas in i connect-funktionen
	$dbname = $user;

	// koppla upp mot databasen
	$connection = mysqli_connect($link, $user, $pass, $dbname);
	if (mysqli_connect_errno()) {
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT * FROM MATCHER";

	if ($result = mysqli_query($connection, $query)) {

	    /* fetch associative array */
	    while ($row = mysqli_fetch_assoc($result)) {
	    	$matchid = $row['MATCH-ID'];
	    	$tipparid = $_SESSION['tipparid'];
	        $query = "SELECT * FROM TIPS WHERE `MATCH-ID`=$matchid AND `TIPPAR-ID`=$tipparid";

			if ($result2 = mysqli_query($connection, $query)) {

			    /* fetch associative array */
			    while ($row2 = mysqli_fetch_assoc($result2)) {
			        echo "<script>insertLine('" . $row['MATCHTID'] . "','" . $row['HEMMALAG'] . "','" . $row['BORTALAG'] . "','" . $row2['HEMMAMAL_T'] . "-" . $row2['BORTAMAL_T'] . "','" . $matchid . "')</script>";
			    }

			    /* free result set */
			    mysqli_free_result($result2);
			}

	    }

	    /* free result set */
	    mysqli_free_result($result);
	}

	/* close connection */
	mysqli_close($connection);


	//Skriver ut vad klockan är nu
	print "klockan är nu: " . date('H:i:s') . " och det är följande datum: " . date('Y-m-d');

?>

<button id="sparatips" class="btn btn-large">Spara Tips</button>
<table id="bettable">
	<tr id="bettablehead">
		<th>
			Deadline / Avspark
		</th>
		<th>
			Hemmalag
		</th>
		<th>
			
		</th>
		<th>
			Bortalag
		</th>
		<th>
			Resultat
		</th>
		<th>
			Tecken
		</th>
		<th>
			Mitt Tips
		</th>
		<th>
			Poäng
		</th>
		<th>
			Ändra Tips
		</th>
	</tr>
</table>