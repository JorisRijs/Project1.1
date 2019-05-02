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
	<br>
	<div style="margin-left: 40px;">
	<?php		
		//als variabele $block_id aan de link is meegegeven
		if(isset($_GET['block_id'])){
			$block_id = $_GET['block_id'];
			$user_id = $_SESSION['user_id'];
			$owner = false;
			
			$query = "SELECT * ";
			$query .= "FROM users_has_noteblocks ";
			$query .= "WHERE block_id = $block_id";
			$result = mysqli_query($db, $query);
			
			while ($row = mysqli_fetch_assoc($result)){
				if ($row['user_id'] == $user_id){
					$owner = true;
				}
			}
			
			if ($owner == false){
				die("Dit is niet jouw blok");
			}
			
			$query = "SELECT * ";
			$query .= "FROM noteblocks ";
			$query .= "WHERE block_id = $block_id";
			$result = mysqli_query($db, $query);
			
			if ($block = mysqli_fetch_assoc($result)){	
				$block_name = $block['block_name'];
			}
			
		//wanneer de variabele niet is meegegeven
		} else {
			die("Er is iets mis gegaan, probeer opnieuw");
		}
		
		if(isset($_GET['note_id'])){
			$note_id = $_GET['note_id'];
		}
		
		//laat naam van de block zien
		echo "<h1>$block_name</h1>";
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitnote'])){
			//gegevens ophalen
			$naam = $_SESSION['nickname'];
			$title = test_input($_POST['title']);
			$text = test_input($_POST['text']);
			$text = nl2br($text);
			
			//gegevens aan database toevoegen
			$query = "INSERT INTO notes(note_name, note_text, block_id, note_update_user)
						VALUES('$title', '$text', $block_id, '$naam')";
			$result = mysqli_query($db, $query);
			
			//refresh pagina (als dit niet gebeurd word de submitnote actie bij iedere f5 uitgevoerd, en krijg je dus steeds meer van dezelfde notitie)
			header("Location: block.php?block_id=$block_id");
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['newnote'])){
			include('newnote.php');
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])){
			include('deletenote.php');
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])){
			$query = "SELECT note_text, note_name
					FROM notes
					WHERE note_id = $note_id;";
			$result = mysqli_query($db, $query);
			
			if ($result){				
				$note = mysqli_fetch_assoc($result);
				$temptext = $note['note_text'];
				$temptext = str_replace("<br />", '', $temptext);
				$tempname = $note['note_name'];
					
				include('editnote.php');
			} else {
				die("Er is iets fout gegaan, probeer opnieuw");
			}
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteconfirm'])){
			$delId = $_POST['delId'];
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editconfirm'])){
			$editId = $_POST['editId'];
		}
	?>
	
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?block_id=$block_id");?>" method='post'>
		<input type='submit' name='newnote' value='Maak een nieuwe notitie' class="btn btn-primary">
		<br>
		<br>
	</form>
	
	<?php	
		//query om note gegevens op te halen
		$query = "SELECT *
					FROM notes
					WHERE block_id = $block_id;";
		$result = mysqli_query($db, $query);
		
		while($notes = mysqli_fetch_assoc($result)){
			//gegevens ophalen
			$id = $notes['note_id'];
			$name = $notes['note_name'];
			$text = $notes['note_text'];
			$date = $notes['note_update_date'];
			$user = $notes['note_update_user'];
			
			if (isset($delId) && $delId == $id){
				$query2 = "DELETE FROM `notes` WHERE `note_id` = $id";
				$result2 = mysqli_query($db, $query2);
			} else if(isset($editId) && $editId == $id){
				$newtitle = test_input($_POST['edittitle']);
				$newtext = test_input($_POST['edittext']);
				$newtext = nl2br($newtext);
				$date = getdate();
				$date = $date['year'] . "-" . $date['mon']. "-" . $date['mday'] . " " . $date['hours'] . ":" . $date['minutes'] . ":" . $date['seconds'];
				$naam = $_SESSION['nickname'];
				
				$query3 = "UPDATE notes ";
				$query3 .= "SET note_name = '$newtitle', note_text = '$newtext', note_update_date = '$date', note_update_user='$naam' ";
				$query3 .= "WHERE note_id = $id";
				$result3 = mysqli_query($db, $query3);

				header("Location: block.php?block_id=$block_id");
			} else {	
				echo "<div style='background-color:#f2ff68; width: 800px; border: 3px solid #d2e23b; padding-left: 10px; margin-top: 10px;'>";
				showNote($id, $name, $text, $date, $user, $block_id);
				echo "</div>";
			}
		}
		
	?>
	</div>	
	<?php include 'footer.php'; ?>
<br><br><br><br><br><br><br>
</body>
</html>