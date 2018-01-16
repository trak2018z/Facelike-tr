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

//Pobieranie dodatkowych informacji
if(isset($_REQUEST['r'])) {
	$nazwa = (isset($_REQUEST['n']) ? $_REQUEST['n'] : null);
	$link = (isset($_REQUEST['l']) ? $_REQUEST['l'] : null);
	$userId = (isset($_REQUEST['u']) ? $_REQUEST['u'] : null);
	$photoId = (isset($_REQUEST['pid']) ? $_REQUEST['pid'] : null);
	$photoUser = (isset($_REQUEST['pu']) ? $_REQUEST['pu'] : null);
	$photoDescription = (isset($_REQUEST['pd']) ? $_REQUEST['pd'] : null);
	$photoUrl = (isset($_REQUEST['purl']) ? $_REQUEST['purl'] : null);
	$dodanieRok = (isset($_REQUEST['drok']) ? $_REQUEST['drok'] : null);
	$dodanieMiesiac = (isset($_REQUEST['dmie']) ? $_REQUEST['dmie'] : null);
	$dodanieDzien = (isset($_REQUEST['ddzi']) ? $_REQUEST['ddzi'] : null);
	$dodanieGodzina = (isset($_REQUEST['dgodz']) ? $_REQUEST['dgodz'] : null);
	$dodanieMinuta = (isset($_REQUEST['dmin']) ? $_REQUEST['dmin'] : null);
	$dodanieSekunda = (isset($_REQUEST['dsek']) ? $_REQUEST['dsek'] : null);
	$response = $_REQUEST['r'];
	
	$nazwa = decodeUrlData($nazwa);
	$link = decodeUrlData($link);
	$photoUser = decodeUrlData($photoUser);
	$photoDescription = decodeUrlData($photoDescription);
	$photoUrl = decodeUrlData($photoUrl);
}
else {
	$nazwa = null;
	$link = null;
	$userId = null;
	$photoId = null;
	$photoUser = null;
	$photoDescription = null;
	$photoUrl = null;
	$dodanieRok = null;
	$dodanieMiesiac = null;
	$dodanieDzien = null;
	$dodanieGodzina = null;
	$dodanieMinuta = null;
	$dodanieSekunda = null;
	$response = null;
}
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Potwierdź zdjęcie z Facebook-a</h2>
		<hr />
		<form method="post" action="addphotoconfirm.php" id="dodajzdjeciepotwierdz" onload="formField(this);">
			<table>
				<tr>
					<td>Nazwa: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $nazwa; ?></font></td>
				</tr>
				<tr>
					<td>Link: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $link; ?></font></td>
				</tr>
				<tr>
					<td>Id&nbsp;zdjęcia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $photoId; ?></font></td>
				</tr>
				<tr>
					<td>Właściciel&nbsp;zdjęcia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $photoUser; ?></font></td>
				</tr>
				<tr>
					<td>Opis&nbsp;zdjęcia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $photoDescription; ?></font></td>
				</tr>
				<tr>
					<td>Link&nbsp;zdjęcia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $photoUrl; ?></font></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><img src="<?php echo $photoUrl; ?>" alt="<?php echo $photoDescription; ?>"></td>
				</tr>
			</table>
			<table style="width: 100%">
				<tr>
					<td>
						<input type="hidden" name="nazwa" value="<?php echo $nazwa; ?>" />
						<input type="hidden" name="link" value="<?php echo $link; ?>" />
						<input type="hidden" name="user" value="<?php echo $userId; ?>" />
						<input type="hidden" name="photo_id" value="<?php echo $photoId; ?>" />
						<input type="hidden" name="photo_user" value="<?php echo $photoUser; ?>" />
						<input type="hidden" name="photo_description" value="<?php echo $photoDescription; ?>" />
						<input type="hidden" name="photo_url" value="<?php echo $photoUrl; ?>" />
						<input type="hidden" name="rok" value="<?php echo $dodanieRok; ?>" />
						<input type="hidden" name="miesiac" value="<?php echo $dodanieMiesiac; ?>" />
						<input type="hidden" name="dzien" value="<?php echo $dodanieDzien; ?>" />
						<input type="hidden" name="godzina" value="<?php echo $dodanieGodzina; ?>" />
						<input type="hidden" name="minuta" value="<?php echo $dodanieMinuta; ?>" />
						<input type="hidden" name="sekunda" value="<?php echo $dodanieSekunda; ?>" />
						<input type="hidden" name="id" value="dodajzdjeciepotwierdz" />
						<br />
						<center>
							<input type="submit" class="btn btn-primary btn-lg" value="Potwierdź zdjęcie &raquo;" onclick="getPath()" />
						</center>
						<br />
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
