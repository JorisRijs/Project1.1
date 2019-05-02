<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>
	<?php
		session_start();
		include('functions.php');
		$_SESSION['newnoteblock'] = 0;
		$_SESSION['addnoteblock'] = 1;
		$_SESSION['contact'] = 0;
		$_SESSION['feedback'] = 0;
		$_SESSION['admin'] = 0;
		include 'menu.php';
	?>
		<br>
		<div class="input-group mb-3" style="width: 400px; margin-left: 40px; margin-top: 50px; text-align: center;">
			<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding-left: 30px; padding-right: 30px;'>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method='post'>
					<h2>Voeg een bestaand notitieblok toe:</h2>
					<p>Notitieblok toegangscode:<p><br>
					<input name="key" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
					<br />
					<input type ="submit" name ='submit'  type="button" class="btn btn-primary" value='Toevoegen'>
			
					<?php	
						//als toevoegen knop word geklikt
						if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
							echo "<fieldset>";
							
							if (isset($_POST['key'])){
								//check of key wel ingevuld is
								$key = $_POST['key'];
								$_SESSION['key'] = test_input($_POST['key']);
								if ($key == '' OR $key == null){
									$error = "Er moet een toegangscode worden ingevoerd<br>";
								} else {
									//haal alle gegevens op van de goeie noteblock
									$query = "SELECT *
											FROM `noteblocks`
											WHERE block_key = '$key'";
									$result = mysqli_query($db, $query);
									
									//als query results geeft/key klopt laat gegevens zien
									if($block = mysqli_fetch_assoc($result)){
										echo "<p>Naam: <input type='text' value='" . $block['block_name'] . "' readonly></input></p><br>";
										echo "<p>Beschrijving: <input type='text' value='" . $block['block_description'] . "' readonly></input></p><br>";
										
										//query om naam van de block eigenaar te vinden
										$owner_id = $block['block_owner_id'];
										$query2 = "SELECT nickname ";
										$query2 .= "FROM users ";
										$query2 .= "WHERE user_id = $owner_id";
										$result2 = mysqli_query($db, $query2);
										
										//laat de block eigenaar zien
										while($owner = mysqli_fetch_assoc($result2)){
											echo "<p>Eigenaar: <input type='text' value='" . $owner['nickname'] . "' readonly></input></p>";
										}
										
										//laat 2 knoppen zien
										//mogelijk form nodig
										echo "<br><br><br>Weet je zeker dat je deze notitieblok toe wilt voegen?<br><br>";
										echo "<input type='submit' name='ja' value='Ja'>";
										echo " ";
										echo "<input type='submit' name='nee' value='Nee'>";
									}
									//als query geen results geeft/key niet klopt
									else {
										$error = "Dit is geen geldige toegangscode!";
									}						
								}
							}
							else {
								$error = "Er is iets fout gegaan, probeer opnieuw";
							}		
							echo "</fieldset>";
						}
						
						//als ja knop geklikt is
						if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ja'])) {
							//haal alle gegevens op van de goeie noteblock
							if(isset($_SESSION['key'])){
								$key = $_SESSION['key'];
								
								$query = "SELECT *
										FROM `noteblocks`
										WHERE block_key = '$key'";
								$result = mysqli_query($db, $query);
										
								//als query results geeft/key klopt
								if($block = mysqli_fetch_assoc($result)){
									$user_id = $_SESSION['user_id'];
									$block_id = $block['block_id'];
									$block_name = $block['block_name'];
									
									$query = "SELECT * 
											FROM `users_has_noteblocks` 
											WHERE user_id = $user_id AND block_id = $block_id";
									$result = mysqli_query($db, $query);
									
									if($userhasblock = mysqli_fetch_assoc($result)){
										$error = "Notitieblok $block_name is al toegevoegd";
									} else {
										$query = "INSERT INTO `users_has_noteblocks`
											(user_id, block_id)
											VALUES('$user_id', '$block_id')";
										$result = mysqli_query($db, $query);
										if ($result){
											echo "<p>Notitieblok $block_name is toegevoegd</p>";	
										} else {
											$error = "Er is iets fout gegaan, probeer opnieuw";
										}
									}
								} else {
									$error = "Er is iets fout gegaan, probeer opnieuw";
								}			
							}	
						}
						
						if(isset($error)){
							echo "<p style='color:red;'>" . $error . "</p>";
						}
					?>
				</form>
		</fieldset>
		</fieldset>
	</div>
	<br>
	<br>
	<?php include 'footer.php';?>
</body>
</html>