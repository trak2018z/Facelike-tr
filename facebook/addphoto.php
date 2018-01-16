<?php
$path = "./couchdb/";	//Ścieżka operacyjna

//Upewnij się że użytkownik jest zalogowany
if(!checksession()) {
	if(headers_sent()) {
		?>
		<script type="text/javascript">
		//<![CDATA[
			location.replace('index.php');
		 //]]>
		</script>
		<?php
	}
	else{
		exit(header('Location: index.php'));
	}
	echo '<div class="error-box">Przykro nam, ale ta strona jest dostępna tylko dla zalogowanych użytkowników.</div>';
	
	die;
}

$userId = getidfromsession();

//Upewnij się, że użytkownik istnieje
if(!checkid($path, $userDataDbName, $userId)) {
	if(headers_sent()) {
		?>
		<script type="text/javascript">
		//<![CDATA[
			location.replace('index.php');
		 //]]>
		</script>
		<?php
	}
	else{
		exit(header('Location: index.php'));
	}
	echo '<div class="error-box">Przykro nam, ale użytkownik o podanym identyfikatorze nie istnieje.</div>';
	
	die;
}

//Sprawdzenie czy użytkownik to admin
if(checkadmin($path, $userSecurityDbName, $userId, $userData)) {
	$superUser = true;
}
else {
	$superUser = false;
}
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Dodaj zdjęcie z Facebook-a</h2>
		<hr />
		<form method="post" action="addphoto.php" id="dodajzdjecie" onload="formField(this);">
			<table>
				<tr>
					<td>
						<label for="tytul">Nazwa:</label>
					</td>
					<td>
						<input class="form-add_photo_field" type="text" name="nazwa" id="nazwa" class="i01" placeholder="nazwa" size="25" maxlength="100" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="imie">Link:</label>
					</td>
					<td>
						<input class="form-add_photo_field" type="text" name="link" id="link" class="i01" placeholder="link" size="25" maxlength="1000" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
						<input type="hidden" name="user" value="<?php echo $userId; ?>" />
						<input type="hidden" name="rok" value="<?php echo date("Y"); ?>" />
						<input type="hidden" name="miesiac" value="<?php echo date("n"); ?>" />
						<input type="hidden" name="dzien" value="<?php echo date("j"); ?>" />
						<input type="hidden" name="godzina" value="<?php echo date("G"); ?>" />
						<input type="hidden" name="minuta" value="<?php echo date("i"); ?>" />
						<input type="hidden" name="sekunda" value="<?php echo date("s"); ?>" />
						<input type="hidden" name="id" value="dodajzdjecie" />
						<br />
						<center>
							<input type="submit" class="btn btn-primary btn-lg" value="Dodaj zdjęcie &raquo;" onclick="getPath()" />
						</center>
						<br />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
