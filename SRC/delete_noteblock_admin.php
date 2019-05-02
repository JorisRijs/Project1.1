<?php

	include 'functions.php';

	session_start();

	if(!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
		header('location: home.php');
	} else {
		$block_id = $_GET['block_id']; 
		
		$query = "SET FOREIGN_KEY_CHECKS=0;";
		$result = mysqli_query($db, $query) or die(mysqli_error($db));
			
		$query = "DELETE FROM noteblocks WHERE block_id='$block_id'";
		$result = mysqli_query($db, $query) or die(mysqli_error($db));
		
		$query = "DELETE FROM notes WHERE block_id='$block_id'";
		$result = mysqli_query($db, $query) or die(mysqli_error($db));
		
		$query = "SET FOREIGN_KEY_CHECKS=1;";
		$result = mysqli_query($db, $query) or die(mysqli_error($db));
		
		header('location: adminmenu.php');
						
	}
	