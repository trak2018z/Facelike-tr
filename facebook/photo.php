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

//Sprawdzenie przekierowania zdjęcia
if(isset($_REQUEST['z'])) {
	$z = $_REQUEST['z'];
	
	//Nazwa dokumentu
	$facebookPhotoId = 'zdjecie'.$z;
	
	//Upewnij się, że zdjęcie istnieje
	if(!checkid($path, $facebookPhotoDbName, $facebookPhotoId)) {
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
		echo '<div class="error-box">Przykro nam, ale zdjęcie o podanym identyfikatorze nie istnieje.</div>';
		
		die;
	}
	
	//Pobierz dane o zdjęciu
	$facebookPhotoData = data($path, $facebookPhotoDbName, $facebookPhotoId);
}
else {
	echo '<br><br>';
	echo '<div class="error-box">Niewłaściwy adres.</div>';
	
	die;
}

//Informacje o zdjęciu
$nazwa = $facebookPhotoData['nazwa'];
$userId = $facebookPhotoData['user_id'];
$photoDescription = $facebookPhotoData['photo_description'];
$photoUrl = $facebookPhotoData['photo_url'];
$dodanieDate = date('Y-m-d', strtotime($facebookPhotoData['dodanie']['dodanie_rok'].'-'.$facebookPhotoData['dodanie']['dodanie_miesiac'].'-'.$facebookPhotoData['dodanie']['dodanie_dzien']));
$dodanieTime = date('H:i:s', strtotime($facebookPhotoData['dodanie']['dodanie_godzina'].':'.$facebookPhotoData['dodanie']['dodanie_minuta'].':'.$facebookPhotoData['dodanie']['dodanie_sekunda']));
$dodanie = $dodanieDate." ".$dodanieTime;

//Pobierz dane o użytkowniku
$userData = getuserdata($path, $userDataDbName, $userId, array("imie", "nazwisko"));

$l = strlen($userId);
$u = substr($userId, 5, $l);

//Statystyki zdjęcia
if(isset($facebookPhotoData['statystyki'])) {
	$status = $facebookPhotoData['statystyki']['status'];
	$iloscUzyc = $facebookPhotoData['statystyki']['ilosc_uzyc'];
}
else {
	$status = "Brak danych";
	$iloscUzyc = "Brak danych";
}
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Zdjęcie <?php echo $nazwa; ?></h2>
		<hr />
		<table>
			<tr>
				<td>Nazwa: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $nazwa; ?></font></td>
			</tr>
			<tr>
				<td>Link: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookPhotoData['link']; ?></font></td>
			</tr>
			<tr>
				<td>Użytkownik: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td>
					<?php
					if($superUser == true) {
						echo '<font color="black"><a href="index.php?id=profilzaawansowany&u='.$u.'">'.$userData[0].' '.$userData[1].'</a></font>';
					}
					else {
						echo '<font color="black">'.$userData[0].' '.$userData[1].'</font>';
					}
					?>
				</td>
			</tr>
			<tr>
				<td>Id&nbsp;zdjęcia: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookPhotoData['photo_id']; ?></font></td>
			</tr>
			<tr>
				<td>Właściciel&nbsp;zdjęcia: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookPhotoData['photo_user']; ?></font></td>
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
				<td>Dodanie: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $dodanie; ?></font></td>
			</tr>
			<tr>
				<td>Statystyki: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td></td>
			</tr>
			<tr id="tp01">
				<td>Status&nbsp;konta: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $status; ?></font></td>
			</tr>
			<tr id="tp01">
				<td>Ilość&nbsp;użyć&nbsp;konta: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $iloscUzyc; ?></font></td>
			</tr>
		</table>
		<table style="width: 100%">
			<tr>
				<td>
					<br />
					<center>
					<img src="<?php echo $photoUrl; ?>" alt="<?php echo $photoDescription; ?>">
					</center>
				</td>
			</tr>
		</table>
	</div>
</div>
