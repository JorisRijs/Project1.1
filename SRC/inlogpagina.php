<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style.css">
</head>
<body style="margin-left: 40px;">
	<?php
		//start sessie en gebruik functions.php
		session_start();
		include 'functions.php';
		
		//als $registering sessie nog niet bestaat maak hem aangeklikt
		//$registering word gebruikt om het registreer alleen te laten zien wanneer het moet
		if(!isset($_SESSION['registering'])){
		//if(!isset($registering)){
			//setcookie('registering', 'false', time() + 120, "/");
			$_SESSION['registering'] = false;
		}
		
		//variabelen voor in form
		$nickname = $password = "";
		$inlogError = "";
		$succes = "";
		$registerError = "";
		
		//wanneer login knop geklikt is
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])){
			$nickname = test_input($_POST['nickname']);
			$password = test_input($_POST['password']);
			
			//geef errors als er niks word ingevuld
			if($nickname ==  ""){
				$inlogError = "Geen naam ingevuld";
			} else if($password ==  ""){
				$inlogError = "Geen wachtwoord ingevuld";
			}
			
			//als alles ingevuld is
			else{	
				$query = "SELECT * FROM users WHERE nickname = '$nickname'";
				$result = mysqli_query($db, $query) or die ("Fout bij queryen");
				$result = mysqli_fetch_assoc($result);
				
				//check of wachtwoord klopt
				//if ($password == $result['password']){
				if(password_verify($password, $result['password'])){
					$_SESSION['nickname'] = $nickname;
					$_SESSION['rol'] = $result['rol']; 
					$_SESSION['user_id'] = $result['user_id']; 
					header('Location: home.php');
				} else {
					$inlogError = 'Geen geldige naam of wachtwoord ingevuld';
				}
			}
		}
		
		//wanneer 1e register knop aangeklikt is
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['startregister'])){
			//setcookie('registering', 'true', time() + 5, "/");
			$_SESSION['registering'] = true;
		}
		
		//wanneer 2e register knop aangeklikt is
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])){
			$regnickname = test_input($_POST['regnickname']);
			$regpassword = test_input($_POST['regpassword']);
			$regpassword2 = test_input($_POST['regpassword2']);
			
			
			if($regpassword != $regpassword2) {
				$registerError = "Wachtwoorden komen niet overeen";
			} else {
			
				//valideer ingevulde naam en password
				if($regnickname == ""){
					$registerError = "Geen naam ingevuld";
				}
				else if($regpassword == "") {
					$registerError = "Geen wachtwoord ingevuld";
				}
				else if(!checkName($regnickname)) {
					$registerError = "Naam moet minstens 3 karakters bevatten";
				}
				else if(!checkPassword($regpassword)) {
					$registerError = "Wachtwoord moet minstens 6 karakters bevatten";
				}	
				else {
					//zet de gegevens in de database
					$regpassword = password_hash($regpassword, PASSWORD_DEFAULT);
					$query = "INSERT INTO users(nickname, password)
					VALUES('$regnickname', '$regpassword')";
					if($result = mysqli_query($db, $query)){
						$succes = "Account is succesvol aangemaakt!";
						//setcookie('registering', 'false', time() + 5, "/");
						$_SESSION['registering'] = false;				
					} else {
						$registerError = "Naam is niet beschikbaar";
					}			
				}
			}
		}
		
		//register cancel knop aangeklikt
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])){
			//setcookie('registering', 'false', time() + 5, "/");
			$_SESSION['registering'] = false;
		}
	?>
	
	<div style='text-align: center; margin-top: 40px;'>
		<fieldset style='background-color: #3d3d3d; padding: 10px; margin: 2px; margin-left: 25%; height: 100%; width: 50%;';>
			<h1 style='color: white;'>ShareNote</h1>
		
			<div class="input-group mb-3" style="width: 80%; margin-top: 50px; text-align: center; display: block; margin-left: auto; margin-right: auto;">
				<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding-left: 30px; padding-right: 30px; padding-bottom: 15px; '>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='post'>
							<h1>Inloggen:</h1>
							<p style='color: red;'><?php echo $inlogError . "<br>";?></p>
							<p style="color: green;"><?php echo $succes . "<br>";?></p>
							Naam:<input type='text' name='nickname' value="<?php echo $nickname;?>">
							<br>
							<br>
							Wachtwoord:<input type='password' name='password'>
							<br>
							<br>
							<input type='submit' name='login' value='Inloggen' class="btn btn-primary">
					</form>
				</fieldset>
			</div>
				
			<div class="input-group mb-3" style="width: 80%; margin-top: 10px; text-align: center; display: block; margin-left: auto; margin-right: auto;">
				<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding-left: 30px; padding-right: 30px; padding-bottom: 10px; padding-top: 10px;'>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='post'>
						<?php
							//dit gedeelte laat of een registerknop zien, en zoniet een register form
							if(!$_SESSION['registering']){
								echo "Nog geen account?";
								echo "<input type='submit' name='startregister' value='Registreren' class='btn btn-primary'>";
							} 
							else{
								include 'register.php';
							}
						?>
					</form>
				</fieldset>
			</div>
		</fieldset>
	</div>
</body>
</html>