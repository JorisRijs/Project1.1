<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='post'>
		<h1>Registreren:</h1>
		<p style="color: red;"><?php echo $registerError . "<br>";?></p>
		Naam:<input type='text' name='regnickname'>
		<br>
		<br>
		Wachtwoord:<input type='password' name='regpassword'>
		<br>
		<br>
		Wachtwoord herhalen:<input type='password' name='regpassword2'>
		<br>
		<br>
		<input type='submit' name='register' value='Registreren' class="btn btn-primary">
		<input type='submit' name='cancel' value='Annuleren' class="btn btn-primary">
	</form>
</body>
</html>
