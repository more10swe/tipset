<?php header("Content-type:text/html;charset=utf-8");?>
<html>
	<head>
		<title>Logga in</title>
	</head>
	<body>
		<div class="main">
			<div class="header">
				<h1>Logga in</h1>
				<br />
			</div>
			<div class="content">
				<form action="../common/checkloginform.php" method="post">
					<div class="contentleft">
					<label for="anvandarnamn">Användarnamn: </label><br />
					<label for="losenord">Lösenord: </label><br />
					</div>
					<div class="contentright">
					<input type="text" name="anvandarnamn" /><br />
					<input type="password" name="losenord" /><br /><br />
					<input type="submit" value="Logga in" /><br />
					</div>
					
				</form>
			</div>
		</div>
	</body>
</html>
