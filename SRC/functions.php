<?php 
	//connect met db
	$db = mysqli_connect("localhost", "root", "", "sharenote");
	
	if (mysqli_connect_errno()){
		die("De verbinding met de database is mislukt: " .
		mysqli_connect_error() . " (" .
		mysqli_connect_errno() . ")" );
	}

	function test_input($i){
		$i = htmlspecialchars($i);
		$i = stripslashes($i);
		
		return $i;
	}
	
	function logout(){
		session_destroy();
	}
	
	function checkName($str){
		if(strlen($str) > 2){
			return true;
		} else {
			return false;
		}
	}
	
	function checkPassword($str){
		if (strlen($str) > 5){
			return true;
		} else {
			return false;
		}
	}
	
	function showNote($id, $name, $str, $date, $user, $block){
		echo "<form action='block.php?block_id=$block&note_id=$id' method='post'>";
		echo "<fieldset>";
		if ($name != ""){			
			echo "<legend>$name</legend>";
		}
		echo "$str";
		echo "<br><br>Laatst gewijzigd op: $date";
		echo " Door: $user";
		echo "<input type='submit' value='Wijzigen' name='edit' class='btn btn-primary' style='margin: 5px;'>";
		echo "<input type='submit' value='Verwijderen' name='delete' class='btn btn-primary'>";
		echo "</fieldset>";
		echo "</form>";
	}
?>