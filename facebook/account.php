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

//Upewnij się, że użytkownik to admin
if(!checkadmin($path, $userSecurityDbName, $userId, $userData)) {
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
	echo '<div class="error-box">Przykro nam, ale nie posiadasz wystarczających uprawnień.</div>';
	
	die;
}

//Sprawdzenie przekierowania konta
if(isset($_REQUEST['k'])) {
	$k = $_REQUEST['k'];
	
	//Nazwa dokumentu
	$facebookAccountId = 'konto'.$k;
	
	//Upewnij się, że konto istnieje
	if(!checkid($path, $facebookAccountDbName, $facebookAccountId)) {
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
		echo '<div class="error-box">Przykro nam, ale konto Facebook-a o podanym identyfikatorze nie istnieje.</div>';
		
		die;
	}
	
	//Pobierz dane o koncie
	$facebookAccountData = data($path, $facebookAccountDbName, $facebookAccountId);
}
else {
	echo '<br><br>';
	echo '<div class="error-box">Niewłaściwy adres.</div>';
	
	die;
}

//Informacje o koncie
if($facebookAccountData['tytul'] != null || $facebookAccountData['tytul'] != '') {
	$nazwa = $facebookAccountData['tytul'].' '.$facebookAccountData['imie'].' '.$facebookAccountData['nazwisko'];
}
else {
	$nazwa = $facebookAccountData['imie'].' '.$facebookAccountData['nazwisko'];
}
$birthDate = date('Y-m-d', strtotime($facebookAccountData['data_urodzenia']['data_urodzenia_rok'].'-'.$facebookAccountData['data_urodzenia']['data_urodzenia_miesiac'].'-'.$facebookAccountData['data_urodzenia']['data_urodzenia_dzien']));
$wzrost = ($facebookAccountData['wzrost']/100).' m';
$waga = $facebookAccountData['waga'].' kg';
if(isset($facebookAccountData['geolocation_data'])) {
	$szerokoscGeograficzna = $facebookAccountData['geolocation_data']['szerokosc_geograficzna'];
	$dlugoscGeograficzna = $facebookAccountData['geolocation_data']['dlugosc_geograficzna'];
}
else {
	$szerokoscGeograficzna = "Brak danych";
	$dlugoscGeograficzna = "Brak danych";
}
$registrationDate = date('Y-m-d', strtotime($facebookAccountData['rejestracja']['rejestracja_rok'].'-'.$facebookAccountData['rejestracja']['rejestracja_miesiac'].'-'.$facebookAccountData['rejestracja']['rejestracja_dzien']));
$registrationTime = date('H:i:s', strtotime($facebookAccountData['rejestracja']['rejestracja_godzina'].':'.$facebookAccountData['rejestracja']['rejestracja_minuta'].':'.$facebookAccountData['rejestracja']['rejestracja_sekunda']));
$registration = $registrationDate." ".$registrationTime;

//Statystyki konta
if((isset($facebookAccountData['logowanie']['typ'])) && ($facebookAccountData['logowanie']['typ'] != null || $facebookAccountData['logowanie']['typ'] != '')) {
	$typ = $facebookAccountData['logowanie']['typ'];
}
else {
	$typ = 'nieprzydzielony';
}
if(isset($facebookAccountData['statystyki'])) {
	$status = $facebookAccountData['statystyki']['status'];
	$iloscUzyc = $facebookAccountData['statystyki']['ilosc_uzyc'];
}
else {
	$status = "Brak danych";
	$iloscUzyc = "Brak danych";
}

//Załączniki
if((isset($facebookAccountData['_attachments'])) && ($facebookAccountData['_attachments'] != null || $facebookAccountData['_attachments'] != '')) {
	$zalacznik = $facebookAccountData['_attachments'];
	while(list($klucz, $wartosc) = each($zalacznik)) {
		$typ = gettype($wartosc);
		if($typ == "array") {
			$plik = $klucz;
		}
	}
}
else {
	$plik = null;
}
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Konto <?php echo $nazwa; ?></h2>
		<hr />
		<table>
			<tr>
				<td>UUID: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['uuid']; ?></font></td>
			</tr>
			<tr>
				<td>Tytuł: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['tytul']; ?></font></td>
			</tr>
			<tr>
				<td>Imię: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['imie']; ?></font></td>
			</tr>
			<tr>
				<td>Nazwisko: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['nazwisko']; ?></font></td>
			</tr>
			<tr>
				<td>Nazwisko&nbsp;panieńskie: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['nazwisko_panienskie']; ?></font></td>
			</tr>
			<tr>
				<td>Płeć: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo getplec($facebookAccountData['plec']); ?></font></td>
			</tr>
			<tr>
				<td>Data&nbsp;urodzenia: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $birthDate; ?></font></td>
			</tr>
			<tr>
				<td>Kraj: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['adres']['kraj']; ?></font></td>
			</tr>
			<tr>
				<td>Miasto: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['adres']['miasto']; ?></font></td>
			</tr>
			<tr>
				<td>Ulica: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['adres']['ulica'].' '.$facebookAccountData['adres']['numer_domu']; ?></font></td>
			</tr>
			<tr>
				<td>Poczta: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['adres']['poczta']; ?></font></td>
			</tr>
			<tr>
				<td>Dane geolokalizacyjne: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td></td>
			</tr>
			<tr id="tp01">
				<td>Szerokość&nbsp;geograficzna: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $szerokoscGeograficzna; ?></font></td>
			</tr>
			<tr id="tp01">
				<td>Długość&nbsp;geograficzna: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $dlugoscGeograficzna; ?></font></td>
			</tr>
			<tr>
				<td>Firma: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['firma']; ?></font></td>
			</tr>
			<tr>
				<td>Wzrost: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $wzrost; ?></font></td>
			</tr>
			<tr>
				<td>Waga: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $waga; ?></font></td>
			</tr>
			<tr>
				<td>Włosy: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['wlosy']; ?></font></td>
			</tr>
			<tr>
				<td>Oczy: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['oczy']; ?></font></td>
			</tr>
			<tr>
				<td>Grupa&nbsp;krwi: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['krew']; ?></font></td>
			</tr>
			<tr>
				<td>Sport: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['sport']; ?></font></td>
			</tr>
			<tr>
				<td>Ulubiony&nbsp;kolor: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['kolor']; ?></font></td>
			</tr>
			<tr>
				<td>Telefony: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td></td>
			</tr>
			<tr id="tp01">
				<td>Telefon&nbsp;komórkowy: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['telefon']['telefon_komorkowy']; ?></font></td>
			</tr>
			<tr id="tp01">
				<td>Telefon&nbsp;stacjonarny: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['telefon']['telefon_stacjonarny']; ?></font></td>
			</tr>
			<tr>
				<td>Email: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo '<a href="'.$facebookAccountData['mail']['email_url'].'" target="_blank">'.$facebookAccountData['mail']['email'].'</a>'; ?></font></td>
			</tr>
			<tr>
				<td>Dane logowania: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td></td>
			</tr>
			<tr id="tp01">
				<td>Login: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['logowanie']['login']; ?></font></td>
			</tr>
			<tr id="tp01">
				<td>Hasło: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $facebookAccountData['logowanie']['password']; ?></font></td>
			</tr>
			<tr id="tp01">
				<td>Typ&nbsp;konta: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $typ; ?></font></td>
			</tr>
			<tr>
				<td>Rejestracja: </td>
				<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
				<td><font color="black"><?php echo $registration; ?></font></td>
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
		<?php
		if($plik != null) {
			?>
			<table class="account-picture">
				<tr>
					<td>
						<div class="account-picture">
							<?php
							$address = get_address($path);
							if(strpos($address, "127.0.0.1") == true) {
								$address = "";
							}
							echo '<img src="'.$address.'images/account/'.$facebookAccountId.'/'.$plik.'" alt="Konto '.$nazwa.'" style="max-width: 1000px; width: expression(this.width > 1000 ? 1000: true);" />';
							unset($address);
							?>
						</div>
					</td>
				</tr>
			</table>
			<?php
		}
		?>
	</div>
</div>
