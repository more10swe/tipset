<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "tips.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).

?>

<script src="../js/jquery-1.9.1.js"></script> <!-- Själva jQuery. -->
<script src="../js/jquery-ui-1.10.2.custom.js"></script> <!-- jQuery UI. -->
<script type="text/javascript">
	function insertLine(tid,hemma,borta,tips,matchid,odds,startat,resultat)
	{
		res="x-x";
		tecken="";
		aendra="<input class='nyttips' id='"+matchid+"' type='text'></input>";
		//Här kommer en if-sats som kollar om matchen har startat eller ej!
		if(startat=="ja")
		{
			//Script som placerar in rätt värde i rätt td för startade matcher
			$("#bettable").append("<tr><td>"+tid+"</td><td>"+hemma+"</td><td>-</td><td>"+borta+"</td><td>"+resultat+"</td><td>"+tecken+"</td><td>"+tips+"</td><td>"+odds+"</td><td></td></tr>");
		}
		else if(startat=="nej")
		{
			//Script som placerar in rätt värde i rätt td för ickestartade matcher
			$("#bettable").append("<tr><td>"+tid+"</td><td>"+hemma+"</td><td>-</td><td>"+borta+"</td><td>"+res+"</td><td>"+tecken+"</td><td>"+tips+"</td><td>("+odds+")</td><td>"+aendra+"</td></tr>");
		}
		
	}

	function addToolTip(matchid, odds1, oddsx, odds2)
	{
		var tooltipoutput = "Odds:<br /><table><tr><td>1</td><td>X</td><td>2</td></tr><tr><td>"+odds1+"</td><td>"+oddsx+"</td><td>"+odds2+"</td></tr></table>";
		
		$("#"+matchid).tooltip({ 
		track: true,
		items: "#"+matchid,
		content: tooltipoutput });	
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
					alert("Resultatet skall vara på formatet X-Y! ex) 2-0")
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
			    $row2 = mysqli_fetch_assoc($result2);
			    
			    /* free result set */
			    mysqli_free_result($result2);
			}

			## Här bestäms om matchen har startat eller inte
			## Jag är dock inte helt med på varför jämförelsen blir som den blir, det borde bli tvärtom..
			$avspark = date_create($row['MATCHTID']);
			$nutid = date_create("now");
			if ($avspark<$nutid) 
			{
				$startad = "ja";
				$resultat_h = mysqli_query($connection, "SELECT HEMMAMAL FROM RESULTAT WHERE `MATCH-ID`=$matchid");
				$res_answer = mysqli_fetch_assoc($resultat_h);
				$res_h = $res_answer['HEMMAMAL'];

				mysqli_free_result($resultat_h);

				$resultat_b = mysqli_query($connection, "SELECT BORTAMAL FROM RESULTAT WHERE `MATCH-ID`=$matchid");
				$res_answer = mysqli_fetch_assoc($resultat_b);				
				$res_b = $res_answer['BORTAMAL'];

				mysqli_free_result($resultat_b);

				$resultat = $res_h . "-" . $res_b;
			}
			elseif ($avspark>$nutid) 
			{
				$startad = "nej";
			}
			//Lägger till två timmar på avsparkstiden
			date_modify($avspark, '+2 hours');

			//Skriver ut raden för den aktuella matchen
			echo "<script>insertLine('" . date_format($avspark, 'Y-m-d H:i:s') . "','" . $row['HEMMALAG'] . "','" . $row['BORTALAG'] . "','" . $row2['HEMMAMAL_T'] . "-" . $row2['BORTAMAL_T'] . "','" . $matchid . "','" . $row2['ODDS'] . "','" . $startad . "','" . $resultat . "')</script>";

			//Nu ska vi få in de rätta oddsen i tooltipen!
			$filename = "../common/datan.xml";
			$oddsfeed = simplexml_load_file($filename);
			foreach ($oddsfeed->fd->sports->sport->leagues->league->events->event as $match) //Slinga som letar upp alla oddsen till matcherna för användning i tooltipen!
			{
				if ($match->id == $matchid) 
				{
					foreach ($match->periods->period as $oddsfeed2) 
					{
						if ($oddsfeed2->moneyLine->homePrice != "") 
						{
							$odds1 = $oddsfeed2->moneyLine->homePrice;
							$oddsx = $oddsfeed2->moneyLine->drawPrice;
							$odds2 = $oddsfeed2->moneyLine->awayPrice;
						}
					}
				}
			}
			//Skriver ut de rätta oddsen i tooltipen
			echo "<script>addToolTip('" . $matchid . "','" . $odds1 . "','" . $oddsx . "','" . $odds2 . "')</script>";

	    }

	    /* free result set */
	    mysqli_free_result($result);
	}

	/* close connection */
	mysqli_close($connection);


	//Skriver ut vad klockan är nu
	print "klockan är nu: " . date('H:i:s') . " och det är följande datum: " . date('Y-m-d');

?>

<!--
<script type="text/javascript">
	var tooltipoutput = "Här skulle man kunna ha oddsen! <br />1-0: 5,23 | 0-0: 65,12 | 0-1: 9,23<br />2-1: 25,23 | 1-1: 25,42 | 0-2: 19,23<br />2-0: 35,23 | 2-2: 15,41 | 0-3: 29,23<br />osv..";
	$(".nyttips").tooltip({ 
		track: true,
		items: ".nyttips",
		content: tooltipoutput });	
</script>
-->

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
			Poäng/Odds
		</th>
		<th>
			Ändra Tips
		</th>
	</tr>
</table>