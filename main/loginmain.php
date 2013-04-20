<?php header("Content-type:text/html;charset=utf-8");?>
<html>
	<head>
		<title>Logga in</title>
		<link rel="icon" href="../favicon.ico">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width"> <!-- iPhone använder sig av viewport. -->

        <link rel="stylesheet" href="../css/bootstrap.css"> <!-- Bootstrap är en css från twitter, lite för styling. Kanske inte nödvändig. -->

        <link rel="stylesheet" href="../css/bootstrap-responsive.css"> <!-- Samma som ovan. -->
        
        <script src="../js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script> <!-- Lite importerat javascript. -->

		<link href="../css/trontastic/jquery-ui-1.10.2.custom.css" rel="stylesheet"><!-- Ett jQuery-tema som jag laddade ner. Coolt. -->

		<script src="../js/jquery-1.9.1.js"></script> <!-- Själva jQuery. -->
		<script src="../js/jquery-ui-1.10.2.custom.js"></script> <!-- jQuery UI. -->

		<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700' rel='stylesheet' type='text/css'><!-- Custom-font -->

		<link rel="stylesheet" href="../custom/main.css"> <!-- Den personliga CSSen. -->

		<script src="../custom/main.js"></script> <!-- Personliga javascript. -->
	</head>
	<body>
		<div id="all">
			<div id="content">
				<div id="header"><h1 class="ui-accordion-header">Logga in på Tipssidan</h1></div>
				<div id="logincontent">
				<form action="checkloginform.php" method="post">

						<div id="loginleft">
							<label for="anvandarnamn">Användarnamn: </label><br />
							<label for="losenord">Lösenord: </label>
						</div>
						<div id="loginright">
							<input type="text" name="anvandarnamn" /><br />
							<input type="password" name="losenord" /><br /><br />
						
							<input class="btn btn-large" style="float:right;" type="submit" value="Logga in!" /><br />
						</div>
				
				</form>
			</div>
		</div>
	</body>
</html>
