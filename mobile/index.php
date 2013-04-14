<!DOCTYPE html>
<!--
<?php
	//include '../data.php';
?>
-->
<html>
    <head>
        <meta charset="utf-8">
        <title>Test av Pinnacle API</title>
        
        <meta name="description" content="Detta är en testsida, fan va fett!"> <!-- Typ bara beskrivning. -->
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

			<h2 class="demoHeaders">Testarsidan, va.</h2>
			<button onClick="xml_parse('name')">Lista på lag</button>
			<button onClick="xml_parse('homePrice')">Lista på hemmaodds</button>
			<button onClick="xml_parse('startDateTime')">Lista på tider</button>

			<h3>
				<div id="xmlrespons">Här finns inget att se, hihi.</div>
			</h3>
		</div>
	</body>
</html>