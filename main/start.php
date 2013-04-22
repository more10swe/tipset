<?php
	session_start(); // NEVER forget this!
	$_SESSION['sida'] = "start.php"; //Kommer ihåg vilken sida man var på (om man vill refresha).
?>
<div id="newsfeed">
	<h2>NYHETER<br />
	(XML-feed)</h2>
</div>
<div id="discussion">
	<h2>DISKUSSION<br />
	Användare kan posta små inlägg.<br />
	Man kan bara posta när man är inloggad.<br />
	Inloggade användare kan tabort sina egna inlägg.<br />
	Kan behövas en databas för inläggen.<br />
	Bild, tidsstämpel och text publiceras.</h2>
</div>
<div id="standings">
	<h2>TABELL<br />
	(Simplare tabell)</h2>
</div>