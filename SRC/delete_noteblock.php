<?php

	include 'functions.php';
	
	session_start();
	
	if(!isset($_SESSION['rol'])) {
		header('location: default.php');
	}

	if(!isset($_GET['noteblock_id'])) {
		//if there is no GET specified send the user back to home.php
		header('location: home.php');
	} else {
		//first we have to check if the user is allowed to delete the noteblock

		$user_id = $_SESSION['user_id'];
		$block_id = $_GET['noteblock_id'];
		$query = "SELECT * FROM users_has_noteblocks WHERE user_id='$user_id' AND block_id='$block_id'";
		$result = mysqli_query($db, $query)/* or die(mysqli_error($db))*/;
		$count = mysqli_num_rows($result);
		
		if($count != 1) {
			header('location: home.php');
		} else {
			//this has te be done because there is a foreign key in this table,
			//we cant delete the notblock before we have deleted the foreign key
			$query = "SET FOREIGN_KEY_CHECKS=0;";
			$result = mysqli_query($db, $query) or die(mysqli_error($db));
			
			$query = "DELETE FROM users_has_noteblocks WHERE user_id='$user_id' AND block_id='$block_id'";
			$result = mysqli_query($db, $query) or die(mysqli_error($db));
			
			echo $query;
			
			/*
			$query = "DELETE FROM notes WHERE block_id='noteblock_id'";
			$result = mysqli_query($db, $query) or die(mysqli_error($db));
			
			//now the foreign key is deleted, we can delete the noteblock itself
			$query = "DELETE FROM noteblocks WHERE block_id='$block_id'";
			$result = mysqli_query($db, $query) or die(mysqli_error($db));
			*/
			$query = "SET FOREIGN_KEY_CHECKS=1";
			$result = mysqli_query($db, $query) or die(mysqli_error($db));
			header('location: home.php');
		}
	}
	
?>