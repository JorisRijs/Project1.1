<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
	<?php
		session_start();
		include('functions.php');
		$_SESSION['newnoteblock'] = 0;
		$_SESSION['addnoteblock'] = 0;
		$_SESSION['contact'] = 1;
		$_SESSION['feedback'] = 0;
		$_SESSION['admin'] = 0;
		include 'menu.php';
	?>
	<?php	
		$nameErr = $emailErr = $opmerkErr = "";
		$name = $email = $opmerkingen = $message = "";
		//error_reporting(null);
		//$message = "";
		//$error_message = "";
	?>
		
	<?php

		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['insturen'])) {
			$contact_message = $_POST['message'];
			$name = test_input($_POST['Name']);
			$user_id = $_SESSION['user_id'];
			$mail_adress = test_input($_POST['email']);	
			$error = false;
			
			//check naam		
			if (empty($name)) {
				//check if name isnt empty
				$nameErr = "Naam is verplicht";
				$error = true;
			} else if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
				// check if name only contains letters and whitespace
				$nameErr = "Enkel letters en spaties zijn toegestaan"; 
				$error = true;
			}
			  
			//check email
			if (empty($_POST["email"])) {
				//check if email isnt empty
				$emailErr = "Email is verplicht";
				$error = true;
			} else if (!filter_var($mail_adress, FILTER_VALIDATE_EMAIL)){
				// check if e-mail address is well-formed
				$emailErr = "Onjuist email formaat"; 
				$error = true;
			}
			  
			if (empty($_POST["message"])) {
				$opmerkErr = "Een vraag is verplicht";
				$error = true;
			} else {
				$opmerkingen = test_input($_POST["message"]);
			}
				
			if (!$error){
				$query = "INSERT INTO contact (user_id, contact_message, mail_adress, name) VALUES ($user_id, '$contact_message','$mail_adress', '$name')";
				$result = mysqli_query($db, $query);
				
				$message = "Bedankt voor het stellen van je vraag.";
			}
		}
		
		echo "<br/><br/>";
		
		$user_id = $_SESSION['user_id'];
		



	?>
	
	<div class="input-group mb-3" style="width: 400px; margin-left: 40px; margin-top: 20px; text-align: center;">
		<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding-left: 30px; padding-right: 30px; padding-bottom: 15px;'>
			<form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h2>Neem contact met ons op:</h2>
				<p><?php echo $message;?></p>
				Naam:
				<p style='color:red;'><?php echo $nameErr;?></p>
				<input type="text" name="Name">
				<br>
				
				E-mail:
				<p style='color:red;'><?php echo $emailErr;?></p>
				<input type ="text" name = "email">
				<br>

				Vraag:
				<p style='color:red;'><?php echo $opmerkErr;?></p>
				<textarea name="message" rows="6" cols="25"></textarea>
				<br>
				
				<input type="submit" name="insturen" value = "Insturen" class="btn btn-primary">
				<input type="reset" value="Velden leeghalen" class="btn btn-primary">
			 
				<a href="home.php">Terug</a>
		</form>
		</fieldset>
	</div>
	
	<?php 		
		//laat tabel zien met uw inzendingen
		echo "<table style='margin-left: 40px;' class = 'table' border='1' width = '50%' align='center'>";
		$query4 = "SELECT * FROM contact WHERE user_id = $user_id ORDER BY contact_id ASC";
		$result4 = mysqli_query($db, $query4);
		
		echo "<th colspan = '6'><h3>Uw inzendingen</h3></th>";
		echo "<tr><th>Vraag</th><th>Aanmaak datum</th></tr>";
		
		while($option = mysqli_fetch_assoc($result4)){
			echo "<tr>";
			echo "<td>". $option['contact_message'] ."</td>";
			//echo "<td>". $option['contact_creation_date'] ."</td>"; 
			echo "</tr>";
		}
		echo "</table>";
	?>
</body>
</html>