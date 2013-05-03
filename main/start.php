<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).
?>
<script type="text/javascript">
	function ajaxLoadComments(){
		var ajaxRequest = getXMLHttp();
		// Create a function that will receive data sent from the server
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
					insertComment(namn,text,tidsstampel,kommentator,tipparid,true);
				}
			}
		}
		var what = "get";
		var queryString = "?what=" + what;
		ajaxRequest.open("GET", "commentfunctions.php" + queryString, false);
		ajaxRequest.send(null);
	}

	function ajaxPostComment(){
		var ajaxRequest = getXMLHttp();
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function(){
			if(ajaxRequest.readyState == 4){
				xmlDoc=ajaxRequest.responseXML;
				allt=xmlDoc.getElementsByTagName("allt");
				namn=allt[0].getElementsByTagName("namn")[0].childNodes[0].nodeValue;
				text=allt[0].getElementsByTagName("text")[0].childNodes[0].nodeValue;
				tidsstampel=allt[0].getElementsByTagName("tidsstampel")[0].childNodes[0].nodeValue;
				tipparid=allt[0].getElementsByTagName("tipparid")[0].childNodes[0].nodeValue;
				insertComment(namn,text,tidsstampel,tipparid,tipparid,false);
			}
		}
		var what = "post";
		var comment = document.getElementById('commentinput').value;
		var queryString = "?comment=" + comment +"&what="+what;
		ajaxRequest.open("GET", "commentfunctions.php" + queryString, false);
		ajaxRequest.send(null); 
	}

	function removeComment()
		{
			alert("Denna är inte implementerad än!!");
		}
		
	function insertComment(namn, text, tidsstampel, kommentator, tipparid, append)
		{			
			tabort = "";
			if(tipparid == kommentator){
				tabort = "<a href='JavaScript:removeComment()'>Ta bort</a> - ";
			}
			
			div = "<div id='comment'><b>"+namn+":</b> "+text
					+"<p id='commentdate'>"+tabort+tidsstampel+"</p>"
					+"</div>";
			if(append==true){
				$("#comments").append(div);
			}
			else if (append==false){
				$("#comments").prepend(div);
				//$("#"+tidsstampel).hide();
				//$("#"+tidsstampel).show('fast');
			}
		}
		
	$('#commentinput').focus(function() {
		if($('#commentinput').val() == "Säg något kul!"){
			$('#commentinput').val("");
		}
	});
	
	$('#commentinput').focusout(function() {
		//alert('Handler for .focus() called.');
		if($('#commentinput').val() == ""){
			$('#commentinput').val("Säg något kul!");
		}
	});
</script>

<div id="newsfeed">
	<h2>NYHETER<br />
	(XML-feed)</h2>
</div>
<div id="discussion">
	<script type="text/javascript">
		ajaxLoadComments();
	</script>
	<div id="formdiv">
		<form name="commentform">
			<input type="text" name="comment" id="commentinput" value="Säg något kul!"/>
			<input type="button" name="commentsubmit" value="Skicka!" onClick="ajaxPostComment()"></input>
		</form>
	</div>
	<div id="comments"></div>
</div>
<div id="standings">
	<h2>TABELL<br />
	(Simplare tabell)</h2>
</div>