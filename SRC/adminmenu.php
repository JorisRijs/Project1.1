<?php 
//sessie wordt al gestart in home.php, later misschien anders doen
//session_start();
?>
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
		$_SESSION['admin'] = 1;
		include 'menu.php';
	?>
	
	<br>
	<div style='margin-left: 40px; margin-right: 40px;'>
		<?php		
			//alleen admin menu laten zien als de gebruiker rol 1 heeft
			if($_SESSION['rol'] == 1){	
				//laat welkom message met nickname zien
				$nickname = $_SESSION['nickname'];
				echo "<h1 style='text-align: center; margin-bottom: 20px;'>Welkom admin $nickname</h1>";
				
				//Wanneer admin +/- knop ingedrukt word
				if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['admin'])){
					$user_id = $_POST['id'];
					$rol = $_POST['rol'];
					$newrol = 0;
					
					//zorg dat de nieuwe rol altijd een andere rol is
					if($rol == 1){
						$newrol = 0;
					} else {
						$newrol = 1;
					}
					
					//query om rol aan te passen
					$query = "UPDATE users ";
					$query .= "SET rol = $newrol ";
					$query .= "WHERE user_id = $user_id";
					$result = mysqli_query($db, $query);
					//echo "<fieldset>Uitgevoerde query: $query <br> Succes: $result</fieldset>";
				}
				
				//tabel met statistieken
				echo "<table class='table' border='1' width='12%' align='center'>";
				echo "<th colspan='2'><h3 style='text-align: center;'>Statistieken</h3></th>";
				//AANTAL ACCOUNTS
				$query = "SELECT COUNT(user_id) ";
				$query .= "FROM users";
				$result = mysqli_query($db, $query);
				$result = mysqli_fetch_assoc($result);
				$result = $result['COUNT(user_id)'];
				echo "<tr>
						<td><strong>Aantal accounts:</strong></td>
						<td>$result</td>
					 </tr>";
				//AANTAL NOTITIEBLOKKEN
				$query = "SELECT COUNT(block_id) ";
				$query .= "FROM noteblocks";
				$result = mysqli_query($db, $query);
				$result = mysqli_fetch_assoc($result);
				$result = $result['COUNT(block_id)'];
				echo "<tr>
						<td><strong>Aantal Notitieblokken:</strong></td>
						<td>$result</td>
					</tr>";
				//AANTAL NOTITIES
				$query = "SELECT COUNT(note_id) ";
				$query .= "FROM notes";
				$result = mysqli_query($db, $query);
				$result = mysqli_fetch_assoc($result);
				$result = $result['COUNT(note_id)'];
				echo "<tr>
						<td><strong>Aantal Notities:</strong></td>
						<td>$result</td>
					 </tr>";
				
				echo "</table>";
				echo "<br>";
				
				//HIER BEGINT ACCOUNTS TABEL
				$query = "SELECT *";
				$query .= "FROM users";
				$result = mysqli_query($db, $query);
				
				//tabel laten zien met usergegevens uit db
				echo "<table class='table' border='1' width='50%' align='center'>";
				echo "<th scope='col' colspan='5'><h3 style='text-align: center;'>Accounts</h3></th>";
				echo "<tr>
				<td><strong>Gebruikers_id</strong></td>
				<td><strong>Gebruikersnaam</strong></td>
				<td><strong>Wachtwoord</strong></td>
				<td><strong>Admin</strong></td>
				<td><strong>Maak Admin</strong></td>
				</tr>
				<br>";
				
				while ($employee = mysqli_fetch_assoc($result)){
					$user_id = $employee['user_id'];
					$rol = $employee['rol'];
					
					echo "<tr>";
					echo "<td>" . $employee['user_id'] . "</td>";
					echo "<td>" . $employee['nickname'] . "</td>";
					echo "<td>" . $employee['password'] . "</td>";
					echo "<td>" . $employee['rol'] . "</td>";
					echo "<form action='adminmenu.php' method='post'>";
					echo "<input type='hidden' id='id' name='id' value='$user_id'>";
					echo "<input type='hidden' id='rol' name='rol' value='$rol'>";
					echo "<td><input type='submit' name='admin' value='verander'></td>" ;
					echo "</form>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<br>";
				
				//HIER BEGINT NOTITIEEBLOCKS TABEL
				//query om alle gegevens uit de tabel noteblocks op te halen.
				$query = "SELECT * FROM noteblocks ORDER BY block_id ASC";
				$result = mysqli_query($db, $query);
				
				$query2 = "SELECT block_id FROM noteblocks ORDER BY block_id ASC";
				$result2 = mysqli_query($db, $query2);
				$block_ids = array();
				$notecount = array();
				
				while($row = mysqli_fetch_array($result2)) {
					array_push($block_ids, $row['block_id']);
				}
				
				echo "<table class='table' border='1' width = '50%' align='center'>";
				echo "<th colspan = '6'><h3 style='text-align: center;'>Notitieblokken</h3></th>";
				echo "<tr><th>Blok_id</th><th>Blok naam</th><th>Blok beschrijving</th><th>Toegangscode</th><th>Aantal notities</th><th>Verwijder notitieblok</th></tr>";
				
				for ($i = 0; $i < count($block_ids); $i++){
					$id = $block_ids[$i];
					$query3 = "SELECT count(note_id) as 'Aantal notities' FROM notes WHERE block_id = $id";
					$result3 = mysqli_query($db, $query3);
					if($result3) {
						$result3 = mysqli_fetch_assoc($result3);
						$result3 = $result3['Aantal notities'];
						array_push($notecount, $result3);
					}
				}
				
				$i = 0;
				while($option = mysqli_fetch_assoc($result)){
					echo "<tr>";
					echo "<td>". $option['block_id'] ."</td>";
					echo "<td>". $option['block_name'] ."</td>";
					echo "<td>". $option['block_description'] ."</td>";
					echo "<td>". $option['block_key']."</td>";		
					echo "<td>" . $notecount[$i] . "</td>";
					echo "<td><a href='delete_noteblock_admin.php?block_id=" . $option['block_id'] . "'>Verwijder</a></td>";
					echo "</tr>";
					$i++;
				}
				echo "</table>";
				//HIER EINDIGT HET NOTITIEBLOCKS TABEL
				echo "<br>";
				
				//tabel met contact inzendingen
				echo "<table class = 'table' table border='1' width = '50%' align='center'>";
				$query4 = "SELECT * FROM contact ORDER BY contact_id ASC";
				$result4 = mysqli_query($db, $query4);
				
				echo "<th colspan = '5'><h3 style='text-align: center;'>Contact inzendingen</h3></th>";
				echo "<tr><th>Naam</th><th>E-Mail</th><th>Vraag</th><th>Aanmaak datum</th><th>Verwijder contact inzending</th></tr>";
				
				while($option = mysqli_fetch_assoc($result4)){
					echo "<tr>";
					echo "<td>". $option['name'] ."</td>"; 
					echo "<td>". $option['mail_adress'] ."</td>";
					echo "<td>". $option['contact_message'] ."</td>"; 
					echo "<td>". $option['contact_creation_date'] ."</td>";
					echo "<td><a href='delete_contact_admin.php?contact_id=" . $option['contact_id'] . "'>Verwijder</a></td>";
					echo "</tr>";
				}
				echo "</table>";
				
				//Laat de gemiddelde score zien van het feedback formulier
				
				$query = "SELECT ROUND(AVG(score),2) AS score FROM feedback";
				$result = mysqli_query($db, $query) or die(mysqli_error($db));
				
				while($row = mysqli_fetch_array($result)) {
					echo "<p align='center' style='margin-bottom: 70px;'>De gemiddelde score van al het feedback is: " . $row['score'] . "</p>";
				}
			} 
			//als hackerman niet rol 1 heeft laat hem dan kattenplaatjes zien
			else {
				echo "Jij bent geen admin!";
				echo "<br>";
			}
			
		?>
	</div>
	<br>
	<br>
	<?php include 'footer.php';?>
</body>
</html>