<?php

	include 'functions.php';

	session_start();

	if(!isset($_SESSION['user_id']) || $_SESSION['rol'] != 1) {
		header('location: home.php');
	} else {
		$contact_id = $_GET['contact_id'];
		
		$query = "DELETE FROM contact WHERE contact_id='$contact_id'";
		$result = mysqli_query($db, $query) or die(mysqli_error($db));
		
		header('location: adminmenu.php');
	}
?>