<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).
?>
<script type="text/javascript">
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

	function removeComment(kommentarid){
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
			tabort = "<a href='JavaScript:removeComment("+kommentarid+")'>Ta bort</a> - ";
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
	<h2>NYHETER<br />
	(XML-feed)</h2>
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
<div id="standings">
	<h2>TABELL<br />
	(Simplare tabell)</h2>
</div>