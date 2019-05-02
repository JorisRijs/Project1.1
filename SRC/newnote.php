<!DOCTYPE html>
<html>
<body>		
	<div class="input-group mb-3" style="width: 400px; margin-left: 0px; margin-top: 10px;">
		<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding: 30px; padding-bottom: 15px;'>	
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?block_id=$block_id");?>" method='post'>
				Titel (niet verplicht):
				<br>
				<input type='text' name='title'>
				<br>
				<br>
				Tekst:
				<br>
				<textarea name='text' name ='text'></textarea>
				<br>
				<br>
				<input type='submit' name='submitnote' value='Bevestigen' class="btn btn-primary" style='text-align: center;'>
			</form>
		</fieldset>
	</div>
</body>
</html>