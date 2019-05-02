<?php
		session_start();
		include('functions.php');
		$_SESSION['newnoteblock'] = 0;
		$_SESSION['addnoteblock'] = 0;
		$_SESSION['contact'] = 0;
		$_SESSION['feedback'] = 1;
		$_SESSION['admin'] = 0;
		include 'menu.php';
?>

<?php
	error_reporting(0);
	$message = "";
	$error_message = "";

	if(!isset($_SESSION['user_id'])) {
		header('location: default.php');
	}

	if(isset($_POST['submit'])) {
		$score = $_POST['select'];
		$user_id = $_SESSION['user_id'];
		
		//check if the user already has given some feedback
		$query = "SELECT * FROM feedback WHERE user_id='$user_id'";
		$result = mysqli_query($db, $query);
		$count = mysqli_num_rows($result);
		
		if($count != 0) {
			$error_message = "Je hebt al een beoordeling gegeven.";
		} else {
			$query = "INSERT INTO feedback (user_id, score) VALUES ('$user_id', '$score')";
			$result = mysqli_query($db, $query);
			
			$message = "Bedankt voor je feedback.";
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
	<div style='margin-left: 40px; margin-top: 50px;'>
		<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding-left: 30px; text-align: center; padding-right: 30px; width: 35%; padding-bottom: 20px;'>
			<h3>Hoe tevreden bent u met deze website?</h3>
			<p style='color: red;'><?php echo $error_message; ?></p>
			<p style='color: green;'><?php echo $message; ?></p>
			<form method="post" action="feedback.php">
				<p>Selecteer een score, 1 voor heel slecht en 5 voor heel goed</p>
				<select name="select">
					<option value="1">1</option>	
					<option value="2">2</option>	
					<option value="3">3</option>	
					<option value="4">4</option>	
					<option value="5">5</option>
				</select>
				<input type="submit" name="submit" class="btn btn-primary">
				<a href="home.php">Terug</a>
			</form>
		</fieldset>
	</div>
</body>