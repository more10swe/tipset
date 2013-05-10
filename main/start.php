<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).
	header("Content-type:text/html;charset=utf-8");
?>


<script type="text/javascript">

	function ajaxLoadNews(){
		var ajaxRequest = getXMLHttp();
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				xmlDoc=ajaxRequest.responseXML;
				posts=xmlDoc.getElementsByTagName("post");
				for (i=0;i<posts.length;i++){				
					heading=posts[i].getElementsByTagName("heading")[0].childNodes[0].nodeValue;					
					image=posts[i].getElementsByTagName("image")[0].childNodes[0].nodeValue;					
					body=posts[i].getElementsByTagName("body")[0].childNodes[0].nodeValue;					
					author=posts[i].getElementsByTagName("author")[0].childNodes[0].nodeValue;
					datetime=posts[i].getElementsByTagName("datetime")[0].childNodes[0].nodeValue;
					insertNews(heading,image,body,author,datetime);
					//insertComment(namn,text,tidsstampel,kommentator,tipparid,kommentarid,true);
				}
			}
		}
		ajaxRequest.open("GET", "../custom/news.xml", true);
		//ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxRequest.send();
	}

	function insertLine(plats,namn,antalratt,poang)
	{
		//Script som placerar in rätt värde i rätt td för startade matcher
		$("#ministandingstable").append("<tr class='ministandingstablerow'><td>"+plats+"</td><td style='font-size:9pt; font-weight:bold;'>"+namn+"</td><td>"+antalratt+"</td><td><b>"+poang+"</b></td></tr>");

	}

	function ajaxLoadComments(){
		var ajaxRequest = getXMLHttp();
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				xmlDoc=ajaxRequest.responseXML;
				kommentarer=xmlDoc.getElementsByTagName("kommentar");
				for (i=0;i<kommentarer.length;i++){					
					namn=kommentarer[i].getElementsByTagName("namn")[0].childNodes[0].nodeValue;					
					text=kommentarer[i].getElementsByTagName("text")[0].childNodes[0].nodeValue;					
					tidsstampel=kommentarer[i].getElementsByTagName("tidsstampel")[0].childNodes[0].nodeValue;
					kommentator=kommentarer[i].getElementsByTagName("kommentator")[0].childNodes[0].nodeValue;					
					tipparid=kommentarer[i].getElementsByTagName("tipparid")[0].childNodes[0].nodeValue;
					kommentarid=kommentarer[i].getElementsByTagName("kommentarid")[0].childNodes[0].nodeValue;					
					insertComment(namn,text,tidsstampel,kommentator,tipparid,kommentarid,true);
				}
			}
		}
		ajaxRequest.open("POST", "commentfunctions.php", true);
		ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxRequest.send("what=get");
	}

	function ajaxPostComment(){
		var ajaxRequest = getXMLHttp();
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				xmlDoc=ajaxRequest.responseXML;
				kommentar=xmlDoc.getElementsByTagName("kommentar");
				namn=kommentar[0].getElementsByTagName("namn")[0].childNodes[0].nodeValue;
				text=kommentar[0].getElementsByTagName("text")[0].childNodes[0].nodeValue;
				tidsstampel=kommentar[0].getElementsByTagName("tidsstampel")[0].childNodes[0].nodeValue;
				tipparid=kommentar[0].getElementsByTagName("tipparid")[0].childNodes[0].nodeValue;
				kommentarid=kommentar[0].getElementsByTagName("kommentarid")[0].childNodes[0].nodeValue;
				insertComment(namn,text,tidsstampel,tipparid,tipparid,kommentarid,false);
				$('#commentinput').val("Säg något kul!");
			}
		}
		var comment = document.getElementById('commentinput').value;
		ajaxRequest.open("POST", "commentfunctions.php", true);
		ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxRequest.send("what=post&comment="+comment);
	}


	function ajaxRemoveComment(kommentarid){
		var konfirmation=confirm("Är du säker?");
		if (konfirmation==true){
			var ajaxRequest = getXMLHttp();
			ajaxRequest.onreadystatechange = function(){
				if(ajaxRequest.readyState == 4){
					xmlDoc=ajaxRequest.responseXML;
					authorized=xmlDoc.getElementsByTagName("authorized")[0].childNodes[0].nodeValue;
					if(authorized == "true"){
						$('#'+kommentarid+'').hide('fast');
					}				
				}
			}
			ajaxRequest.open("POST", "commentfunctions.php", true);
			ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxRequest.send("what=remove&kommentarid="+kommentarid);
		}
	}
		
	function insertComment(namn, text, tidsstampel, kommentator, tipparid, kommentarid, append){			
		tabort = "";
		if(tipparid == kommentator){
			tabort = "<a href='JavaScript:ajaxRemoveComment("+kommentarid+")'>Ta bort</a> - ";
		}
		
		div = "<div class='comment' id='"+kommentarid+"'><b>"+namn+":</b> "+text
				+"<p id='commentdate'>"+tabort+tidsstampel+"</p>"
				+"</div>";
		if(append==true){
			$("#comments").append(div);
		}
		else if (append==false){
			$("#comments").prepend(div);
			$("#"+kommentarid).hide();
			$("#"+kommentarid).show('fast');
		}
		div = "<div class='comment' id='"+kommentarid+"'><b>"+namn+":</b> "+text
				+"<p class='commentdate'>"+tabort+tidsstampel+"</p>"
				+"</div>";
		if(append==true){
			$("#comments").append(div);
		}
		else if (append==false){
			$("#comments").prepend(div);
			$("#"+kommentarid).hide();
			$("#"+kommentarid).show('fast');
		}
	}
	
	function insertNews(heading,image,body,author,datetime){
		if(image.length<5){
		//alert("'"+image+"'");
			image = "http://s6.favim.com/orig/65/cute-kitten-cute-kitten-pictures-cute-kittens-cutest-kitten-Favim.com-574362.jpg";
		}
		div = "<div class='news'><div class='newsimg' style='background-image:url("+image+")'>"
				+"<div class='newsheading'><h1 class='news'>"+heading+"</h1></div></div>"
				+"<div class='newstext'>"
				+"<p class='news'>"+body+"</p>"
				+"<p class='commentdate'>"+author+" - "+datetime+"</p>"
				+"</div>"
				+"<hr class='news'/>"
				+"</div>";
		$("#newsfeed").append(div);
	}
		
	$('#commentinput').focus(function() {
		if($('#commentinput').val() == "Säg något kul!"){
			$('#commentinput').val("");
		}
	});
	
	$('#commentinput').focusout(function() {
		if($('#commentinput').val() == ""){
			$('#commentinput').val("Säg något kul!");
		}
	});
</script>

<div id="newsfeed">
	<script type="text/javascript">
		ajaxLoadNews();
	</script>
</div>
<div id="discussion">
	<script type="text/javascript">
		ajaxLoadComments();
	</script>
	<div id="commentformdiv">
		<form id="commentform" name="commentform" onkeypress="return event.keyCode!=13">
			<textarea rows="2" name="comment" id="commentinput" placeholder="Säg något kul!"/>
			<input id="commentsubmit" class="btn" type="button" name="commentsubmit" value="Skicka!" onClick="ajaxPostComment()" />
		</form>
	</div>
	<div id="comments"></div>
</div>

<?php
	/*
	Vill hämta namn och poäng för mini-tabellen. Kanske också antal rätt.
	*/

	//inkluderar hemliga saker
	include '../secretstuff.php';

	//För att förtydliga vad som skickas in i connect-funktionen
	$dbname = $user;

	// koppla upp mot databasen
	$connection = mysqli_connect($link, $user, $pass, $dbname);
	if (mysqli_connect_errno()) {
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$kollapoang = mysqli_query($connection, "SELECT NAMN, TOTALPOANG, ANTALRATT FROM TIPPARE ORDER BY TOTALPOANG DESC");
	$plats = 0;

	while ($kollapoang_row = mysqli_fetch_assoc($kollapoang))
	{
		$plats = $plats + 1;
		
		echo "<script>insertLine('" . $plats . "','" . utf8_encode($kollapoang_row['NAMN']) . "','" . $kollapoang_row['ANTALRATT'] . "','" . $kollapoang_row['TOTALPOANG'] . "')</script>";
	}
?>

<div id="standings">
	<h2>Mini-tabell</h2>
	<table id="ministandingstable">
		<tr id="ministandingstablehead">
			<th>
				Pl.
			</th>
			<th>
				Namn
			</th>
			<th>
				Rätt
			</th>
			<th>
				Poäng
			</th>
		</tr>
	</table>
</div>
