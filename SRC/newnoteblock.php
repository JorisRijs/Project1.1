<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
	<?php
		session_start();
		include('functions.php');
		$_SESSION['newnoteblock'] = 1;
		$_SESSION['addnoteblock'] = 0;
		$_SESSION['contact'] = 0;
		$_SESSION['feedback'] = 0;
		$_SESSION['admin'] = 0;
		include 'menu.php';
	?>
	<?php	
		$nameErr = "";
		$name = "";
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Aanmaken'])) {
			include "woordenboek.php";
			$num1 = RAND(0, count($woord1) - 1);
			$key = $woord1[$num1];
			$num2 = RAND(0, count($woord2) - 1);
			$key .= $woord2[$num2];
			$num3 = RAND(0, count($woord2) - 1);
			$key .= $woord2[$num3];

			$block_name = $_POST['block_name'];
			$block_desc = $_POST['block_description'];
			$user_id = $_SESSION['user_id'];
			
			//als er geen bloknaam is opgegeven wordt er een foutmelding gegeven.
			if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Aanmaken'])) {
				$name = test_input($_POST["block_name"]);
				//check of naam wel ingevuld is
				if (empty($name)) {
					$nameErr = "*Naam is verplicht";
				//check of naam uit letters en spatie bestaat
				} else if (!preg_match("/^[a-zA-Z ]*$/",$name)){
					$nameErr = "Enkel letters en spaties zijn toegestaan"; 
				//wanneer de naam goed is ingevuld
				} else {
					//query om de gegevens van het formulier in de database te zetten.
				
					$query = "INSERT INTO noteblocks (block_name, block_key, block_description, block_owner_id) VALUES('$block_name', '$key', '$block_desc', $user_id)";
					
					$result = mysqli_query($db, $query) or die(mysqli_error($db));
					
					$query1 = "SELECT block_id FROM noteblocks WHERE block_key = '$key'";
					
					$result = mysqli_query($db, $query1) or die(mysqli_error($db));
					
					while($row = mysqli_fetch_array($result)) {
						$block_id = $row['block_id'];
					}
					
					$query2 = "INSERT INTO users_has_noteblocks VALUES($user_id, $block_id) ";
					
					$result = mysqli_query($db, $query2) or die(mysqli_error($db));
					header("Location: block.php?block_id=$block_id");
				}
			}
		}
		
		include("footer.php");
	?>

	<div class="input-group mb-3" style="width: 400px; margin-left: 40px; margin-top: 50px; text-align: center;">
		<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding-left: 30px; padding-right: 30px;'>
			<form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h2>Maak een nieuwe Notitieblok:</h2>
				<?php
					if(isset($error)){
						echo "<br>";
						echo "<p style='color:red;'>" . $error . "</p>";
					}
				?>
				<br>
				<p>Notitieblok naam:<p>
				<p style='color: red'><?php echo $nameErr;?></p>
				<input name="block_name" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
				<br />
				<p>Beschrijving (optioneel):<p>
				<input name="block_description" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
				<br />
				<input type = "submit" name = 'Aanmaken'  type="button" class="btn btn-primary" value='Aanmaken'>
			</form>
		</fieldset>
	</div>

</body>
</html>