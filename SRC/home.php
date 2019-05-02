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
		$_SESSION['contact'] = 0;
		$_SESSION['feedback'] = 0;
		$_SESSION['admin'] = 0;
		include 'menu.php';
	?>
	<div style='margin-left: 40px;'>	
		<br>
		<h1><?php echo "Welkom " . $_SESSION['nickname'];?></h1>
		<br>
		
		<?php
			//session vars
			$user_id = $_SESSION['user_id'];
			$nickname = $_SESSION['nickname'];
		
			$query = "SELECT first_visit ";
			$query .= "FROM `users` ";
			$query .= "WHERE user_id = $user_id";
			$result = mysqli_query($db, $query);
			
			if ($result){
				$first = mysqli_fetch_assoc($result);
				if ($first['first_visit'] == 1){
					echo "<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding: 10px; width: 60%'>";
					echo "Welkom op ShareNote<br>";
					echo "Dit is je eerste keer op de home-pagina!<br>";
					echo "Druk op 'Nieuwe notitieblok maken' om je eerste notitieblok aan te maken!<br>";
					echo "Gebruik de knop 'Bestaand notitieblok toevoegen' om een gedeelde notitieblok toe te voegen<br>";
					echo "</fieldset>";
					$query = "UPDATE users ";
					$query .= "SET first_visit = 0 ";
					$query .= "WHERE user_id = $user_id";
					$result = mysqli_query($db, $query);
				}	
			}
		?>
		
		<h3>Dit zijn al je notities:</h3>
		<?php
			//query om te kijken welke noteblocks de user heeft toegevoegd
			$query = "SELECT * ";
			$query .= "FROM `users_has_noteblocks` ";
			$query .= "WHERE user_id = $user_id";
			$result = mysqli_query($db, $query);
			$count = mysqli_num_rows($result);
		
			if($count == 0) {
				echo "<p>Je hebt nog geen notitieblok gemaakt. Waar wacht je op?</p>";
			}

			while ($userBlocks = mysqli_fetch_assoc($result)){
				//voor elke toegevoegde noteblock gegevens ophalen
				$block_id = $userBlocks['block_id'];
				$query2 = "SELECT * ";
				$query2 .= "FROM noteblocks ";
				$query2 .= "WHERE block_id = $block_id";
				$result2 = mysqli_query($db, $query2);
		
		
				while ($block = mysqli_fetch_assoc($result2)){	
					//de gegevens laten zien
					echo "<ul class='border border-primary' style='width: 40%; float: left; margin-left: 10px;'>";
					//als op een blok word geklikt, open block.php en neem de variable $block_id mee
					//echo "<div class='border border-primary'>";
					echo "<li><a href ='block.php?block_id=$block_id'><p>" . $block['block_name'] . "</p></a></li>";
					//echo "<li><i>Gemaakt op: " . $block['block_creation_date'] . "</li>";
					echo "<li>Toegangscode: " . $block['block_key'] . "</li>";
					echo "<li><a href='delete_noteblock.php?noteblock_id=" . $block['block_id'] . "'>Verwijder uit mijn notities</a></li>";
					
					$block_id = $block['block_id'];
					
					//query om naam van de block eigenaar te vinden
					$owner_id = $block['block_owner_id'];
					$query3 = "SELECT nickname ";
					$query3 .= "FROM users ";
					$query3 .= "WHERE user_id = $owner_id";
					$result3 = mysqli_query($db, $query3);
					
					//laat de block eigenaar zien
					while($owner = mysqli_fetch_assoc($result3)){
						echo "<li>Eigenaar: " . $owner['nickname'] . "</li>";
			
						echo "</ul>";
					}
					
				}
				
			}
		?>
	</div>
	<br>
	<br>
	<br>
	<br>
	<?php include 'footer.php';?>
</body>
</html>