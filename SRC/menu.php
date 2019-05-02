<?php
	if(!isset($_SESSION['user_id'])) {
		header('location: default.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<style>
		html {
			position: relative;
			min-height: 100%;
		}
		body {
			margin-bottom: 60px;
		}
		.footer {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 60px;
			line-height: 60px;
			background-color: #f5f5f5;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="Home.php">ShareNote</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="newnoteblock.php"><?php
								if(isset($_SESSION['newnoteblock']) AND $_SESSION['newnoteblock'] == 1){
									echo "<i style='color: white;'>Nieuw notitieblok maken</i>";
								} else {
									echo "Nieuw notitieblok maken";
								}?></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="addnoteblock.php"><?php
								if(isset($_SESSION['addnoteblock']) AND $_SESSION['addnoteblock'] == 1){
									echo "<i style='color: white;'>Bestaand notitieblok toevoegen</i>";
								} else {
									echo "Bestaand notitieblok toevoegen";
								}?></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="contact.php"><?php
								if(isset($_SESSION['contact']) AND $_SESSION['contact'] == 1){
									echo "<i style='color: white;'>Contact</i>";
								} else {
									echo "Contact";
								}?></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="feedback.php"><?php
								if(isset($_SESSION['feedback']) AND $_SESSION['feedback'] == 1){
									echo "<i style='color: white;'>Feedback</i>";
								} else {
									echo "Feedback";
								}?></a>
      </li>
		<?php	
			if($_SESSION['rol'] == 1) {
		?>
		<li class="nav-item">
			<a class="nav-link" href="adminmenu.php"><?php
				if(isset($_SESSION['admin']) AND $_SESSION['admin'] == 1){
					echo "<i style='color: white;'>Admin</i>";
				} else {
					echo "Admin";
				}?></a>
		</li>
		<?php
			}
		?>
		<li class="nav-item">
			<a class="nav-link" href="logout.php">Uitloggen</a>
		</li>
    </ul>
  </div>
</nav>
</body>
</html>