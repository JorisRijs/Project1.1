<!DOCTYPE html>
<html>
<body>		
	<div class="input-group mb-3" style="width: 400px; margin-left: 0px; margin-top: 10px;">
		<fieldset style='background-color: #f5f5f5; border: 2px solid #d1d1d1; padding: 30px; padding-bottom: 15px;'>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?block_id=$block_id");?>" method='post'>
				Titel (niet verplicht):
				<br>
				<input type='text' name='edittitle' value='<?php echo $tempname;?>'>
				<br>
				<br>
				Tekst:
				<br>
				<textarea name ='edittext'><?php echo $temptext;?></textarea>
				<br>
				<br>
				<input type='hidden' name='editId' value='<?php echo $_GET['note_id'];?>'>
				<input type='submit' name='editconfirm' value='Notitie aanpassen' class="btn btn-primary" style='text-align: center;'>
			</form>
		</fieldset>
	</div>
</body>
</html>