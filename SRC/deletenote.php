<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style.css">
</head>
<body>	
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?block_id=$block_id");?>" method='post'>
		<fieldset>
			Weet je zeker dat je deze notitie wilt verwijderen?
			<br>
			<input type='hidden' name='delId' value='<?php echo $_GET['note_id'];?>'>
			<input type ="submit" name ='deleteconfirm'  type="button" class="btn btn-danger" value='Notitie verwijderen'>
		</fieldset>
	</form>
</body>
</html>