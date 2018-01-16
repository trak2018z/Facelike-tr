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
	$nazwa = "administratora";
}
else {
	$superUser = false;
	$nazwa = "użytkownika";
}

$przekierowanie = false;

//Sprawdzenie przekierowania użytkownika
if($superUser == true && isset($_REQUEST['u'])) {
	$u = 'osoba'.$_REQUEST['u'];
	
	//Upewnij się, że użytkownik istnieje
	if(!checkid($path, $userDataDbName, $u)) {
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
	
	//Przekierowanie użytkownika
	$userId = $u;
	$przekierowanie = true;
	
	//Pobierz dane o użytkowniku
	$userData = data($path, $userDataDbName, $userId);
	$userSecurity = data($path, $userSecurityDbName, $userId);
	$userStatistics = data($path, $userStatisticsDbName, $userId);
	
	//Sprawdzenie czy użytkownik to admin
	if(checkadmin($path, $userSecurityDbName, $userId, $userData)) {
		$superUser = true;
		$nazwa = "administratora";
	}
	else {
		$superUser = false;
		$nazwa = "użytkownika";
	}
}

//Profil użytkownika
$birthDate = date('Y-m-d', strtotime($userData['data_urodzenia']['data_urodzenia_rok'].'-'.$userData['data_urodzenia']['data_urodzenia_miesiac'].'-'.$userData['data_urodzenia']['data_urodzenia_dzien']));
$registrationDate = date('Y-m-d', strtotime($userData['rejestracja']['rejestracja_rok'].'-'.$userData['rejestracja']['rejestracja_miesiac'].'-'.$userData['rejestracja']['rejestracja_dzien']));
$registrationTime = date('H:i:s', strtotime($userData['rejestracja']['rejestracja_godzina'].':'.$userData['rejestracja']['rejestracja_minuta'].':'.$userData['rejestracja']['rejestracja_sekunda']));
$registration = $registrationDate." ".$registrationTime;

//Statystyki użytkownika
if($przekierowanie == true) {
	if((isset($userSecurity['typ'])) && ($userSecurity['typ'] != null || $userSecurity['typ'] != ''))
		$uprawnienia = $userSecurity['typ'];
	else
		$uprawnienia = 'nieprzydzielone';
	
	if(isset($userStatistics['loginstatus'])) {
		$status = $userStatistics['loginstatus']['status'];
		$system = $userStatistics['loginstatus']['client_data']['system'];
		$przegladarka = $userStatistics['loginstatus']['client_data']['przegladarka'];
		$aplikacjaKliencka = $userStatistics['loginstatus']['client_data']['aplikacja_kliencka'];
		$port = $userStatistics['loginstatus']['client_data']['port'];
		$rozdzielczosc = $userStatistics['loginstatus']['client_data']['rozdzielczosc'];
		$adresIp = $userStatistics['loginstatus']['ip_details']['ip'];
		$nazwaHosta = $userStatistics['loginstatus']['ip_details']['hostname'];
		$organizacja = $userStatistics['loginstatus']['ip_details']['organizacja'];
		$szerokoscGeograficzna = $userStatistics['loginstatus']['ip_details']['szerokosc_geograficzna'];
		$dlugoscGeograficzna = $userStatistics['loginstatus']['ip_details']['dlugosc_geograficzna'];
		$kraj = $userStatistics['loginstatus']['ip_details']['kraj'];
		$region = $userStatistics['loginstatus']['ip_details']['region'];
		$miasto = $userStatistics['loginstatus']['ip_details']['miasto'];
		$kodPocztowy = $userStatistics['loginstatus']['ip_details']['kod_pocztowy'];
		
		if(isset($userStatistics['loginstatus']['geolocation_data'])) {
			$szerokoscGeograficznaGeolokalizacja = $userStatistics['loginstatus']['geolocation_data']['szerokosc_geograficzna'];
			$dlugoscGeograficznaGeolokalizacja = $userStatistics['loginstatus']['geolocation_data']['dlugosc_geograficzna'];
			$wysokosc = $userStatistics['loginstatus']['geolocation_data']['wysokosc'];
			$naglowek = $userStatistics['loginstatus']['geolocation_data']['naglowek'];
		}
		else {
			$szerokoscGeograficznaGeolokalizacja = "Brak danych";
			$dlugoscGeograficznaGeolokalizacja = "Brak danych";
			$wysokosc = "Brak danych";
			$naglowek = "Brak danych";
		}
		
		if(isset($userStatistics['loginstatus']['date'])) {
			$eventPointDate = $userStatistics['loginstatus']['date']['rok']."-".$userStatistics['loginstatus']['date']['miesiac']."-".$userStatistics['loginstatus']['date']['dzien'];
			$eventPointTime = $userStatistics['loginstatus']['date']['godzina'].":".$userStatistics['loginstatus']['date']['minuta'].":".$userStatistics['loginstatus']['date']['sekunda'].".".$userStatistics['loginstatus']['date']['mikrosekunda'];
			$eventPointSummerTime = $userStatistics['loginstatus']['date']['czas_letni'] ? "Czas letni" : "Czas zimowy";
			$eventPointData = $userStatistics['loginstatus']['date']['strefa_czasowa']." ".$eventPointSummerTime;
			$eventPoint = $eventPointDate." ".$eventPointTime." ".$eventPointData;
		}
		else {
			$eventPoint = "Brak danych";
		}
	}
	else {
		$status = "Brak danych";
		$system = "Brak danych";
		$przegladarka = "Brak danych";
		$aplikacjaKliencka = "Brak danych";
		$port = "Brak danych";
		$rozdzielczosc = "Brak danych";
		$adresIp = "Brak danych";
		$nazwaHosta = "Brak danych";
		$organizacja = "Brak danych";
		$szerokoscGeograficzna = "Brak danych";
		$dlugoscGeograficzna = "Brak danych";
		$kraj = "Brak danych";
		$region = "Brak danych";
		$miasto = "Brak danych";
		$kodPocztowy = "Brak danych";
		
		$szerokoscGeograficznaGeolokalizacja = "Brak danych";
		$dlugoscGeograficznaGeolokalizacja = "Brak danych";
		$wysokosc = "Brak danych";
		$naglowek = "Brak danych";
		
		$eventPoint = "Brak danych";
	}
}
?>

<script type="text/javascript">
//<![CDATA[
	var wywolac = <?php echo $przekierowanie; ?>;
	
	var min = 10000;
	
	if(wywolac == true) {
		window.onresize = function(event) {
			getHeight('t09', 't10');
		};
		
		var addEvent = function(object, type, callback) {
			if (object == null || typeof(object) == 'undefined') return;
			if (object.addEventListener) {
				object.addEventListener(type, callback, false);
			}
			else if (object.attachEvent) {
				object.attachEvent("on" + type, callback);
			}
			else {
				object["on"+type] = callback;
			}
		};
		
		//Wywołanie zapytania
		//addEvent(window, "resize", function_reference);
	}
	
	function getHeight(id1, id2) {
		var widthnew;
		var response1;
		var response2;
		var h1;
		var h2;
		var end1;
		var end2;
		var hei;
		var komunikat;
		
		response1 = document.getElementById(id1);
		response2 = document.getElementById(id2);
		
		h1 = $("#" + id1).css("height");
		h2 = $("#" + id2).css("height");
		
		end1 = h1.length - 2;
		h1 = h1.substring(0, end1);
		end2 = h2.length - 2;
		h2 = h2.substring(0, end2);
		
		h1 = Number(h1);
		h2 = Number(h2);
		min = Number(min);
		
		if(h1 > h2) {
			if(h2 < min) {
				min = h2;
			}
		}
		else if(h1 < h2) {
			if(h1 < min) {
				min = h1;
			}
		}
		
		if(min < h1 || min < h2) {
			$("#" + id1).css("height", min);
			$("#" + id2).css("height", min);
			
			response1 = document.getElementById(id1);
			response2 = document.getElementById(id2);
			
			h1 = $("#" + id1).css("height");
			h2 = $("#" + id2).css("height");
			
			end1 = h1.length - 2;
			h1 = h1.substring(0, end1);
			end2 = h2.length - 2;
			h2 = h2.substring(0, end2);
			
			h1 = Number(h1);
			h2 = Number(h2);
			min = Number(min);
		}
		
		//komunikat = 'min=' + min + ' => ' + typeof(min) + '\nh1=' + h1 + ' => ' + typeof(h1) + '\nh2=' + h2 + ' => ' + typeof(h2);
		//alert(komunikat);
		
		if(h1 > h2) {
			hei = h1;
			setHeight(id2, hei);
		}
		else if(h1 < h2) {
			hei = h2;
			setHeight(id1, hei);
		}
	}
	
	function setHeight(id, hei) {
		var response = document.getElementById(id);
		
		$("#" + id).css("height", hei);
	}
//]]>
</script>

<div class="container body-content">
	<div class="jumbotron">
		<?php
		if($przekierowanie == false) {
			?>
			<h2>Profil <?php echo $nazwa.' '.$userData['imie'].' '.$userData['nazwisko']; ?></h2>
			<hr />
			<table>
				<tr>
					<td>Imię: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['imie']; ?></font></td>
				</tr>
				<tr>
					<td>Nazwisko: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['nazwisko']; ?></font></td>
				</tr>
				<tr>
					<td>Płeć: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo getplec($userData['plec']); ?></font></td>
				</tr>
				<tr>
					<td>Data urodzenia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $birthDate; ?></font></td>
				</tr>
				<tr>
					<td>Kraj: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['kraj']; ?></font></td>
				</tr>
				<tr>
					<td>Województwo: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['wojewodztwo']; ?></font></td>
				</tr>
				<tr>
					<td>Miasto: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['miasto']; ?></font></td>
				</tr>
				<tr>
					<td>Ulica: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['ulica']; ?></font></td>
				</tr>
				<tr>
					<td>Poczta: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['poczta']; ?></font></td>
				</tr>
				<tr>
					<td>Telefon: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['telefon']; ?></font></td>
				</tr>
				<tr>
					<td>Email: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['mail']; ?></font></td>
				</tr>
				<tr>
					<td>Hasło: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo "*****"; ?></font></td>
				</tr>
				<tr>
					<td>Rejestracja: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $registration; ?></font></td>
				</tr>
			</table>
			<?php
		}
		if($przekierowanie == true) {
			?>
			<h2>Profil <?php echo $nazwa.' '.$userData['imie'].' '.$userData['nazwisko']; ?></h2>
			<hr />
			<table>
				<tr>
					<td>Imię: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['imie']; ?></font></td>
				</tr>
				<tr>
					<td>Nazwisko: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['nazwisko']; ?></font></td>
				</tr>
				<tr>
					<td>Płeć: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo getplec($userData['plec']); ?></font></td>
				</tr>
				<tr>
					<td>Data&nbsp;urodzenia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $birthDate; ?></font></td>
				</tr>
				<tr>
					<td>Kraj: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['kraj']; ?></font></td>
				</tr>
				<tr>
					<td>Województwo: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['wojewodztwo']; ?></font></td>
				</tr>
				<tr>
					<td>Miasto: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['miasto']; ?></font></td>
				</tr>
				<tr>
					<td>Ulica: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['ulica']; ?></font></td>
				</tr>
				<tr>
					<td>Poczta: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['poczta']; ?></font></td>
				</tr>
				<tr>
					<td>Telefon: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['telefon']; ?></font></td>
				</tr>
				<tr>
					<td>Email: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['mail']; ?></font></td>
				</tr>
				<tr>
					<td>Hasło: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo "*****"; ?></font></td>
				</tr>
				<tr>
					<td>Rejestracja: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $registration; ?></font></td>
				</tr>
				<tr>
					<td>Uprawnienia: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $uprawnienia; ?></font></td>
				</tr>
				<tr>
					<td>Status: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $status; ?></font></td>
				</tr>
				<tr>
					<td>Dane&nbsp;klienta: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td></td>
				</tr>
				<tr id="tp01">
					<td>System: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $system; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Przeglądarka: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $przegladarka; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Aplikacja&nbsp;kliencka: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $aplikacjaKliencka; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Port: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $port; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Rozdzielczość: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $rozdzielczosc; ?></font></td>
				</tr>
				<tr>
					<td>Szczegóły&nbsp;adresu&nbsp;IP: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td></td>
				</tr>
				<tr id="tp01">
					<td>Adres&nbsp;IP: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $adresIp; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Nazwa&nbsp;hosta: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $nazwaHosta; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Organizacja: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $organizacja; ?></font></td>
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
				<tr id="tp01">
					<td>Kraj: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $kraj; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Region: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $region; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Miasto: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $miasto; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Kod&nbsp;pocztowy: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $kodPocztowy; ?></font></td>
				</tr>
				<tr>
					<td>Dane&nbsp;geolokalizacyjne: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td></td>
				</tr>
				<tr id="tp01">
					<td>Szerokość&nbsp;geograficzna: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $szerokoscGeograficznaGeolokalizacja; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Długość&nbsp;geograficzna: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $dlugoscGeograficznaGeolokalizacja; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Wysokość: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $wysokosc; ?></font></td>
				</tr>
				<tr id="tp01">
					<td>Nagłówek: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $naglowek; ?></font></td>
				</tr>
				<tr>
					<td>Dokładna&nbsp;data: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $eventPoint; ?></font></td>
				</tr>
			</table>
			<?php
			if(isset($userStatistics['loginstatus']['geolocation_data'])) {
				?>
				<table class="map" style="width: 100%; height: 600px">
					<tr>
						<td><div id="google_map" class="map" style="width: 100%; height: 600px"></div></td>
					</tr>
				</table>
				<?php
			}
		}
		?>
		
		<script type="text/javascript">
		//<![CDATA[
			getHeight('t09', 't10');
		 //]]>
		</script>
		
		<?php
		if($przekierowanie != true) {
			?>
			<br />
			<center>
				<a href="index.php?id=edytujprofil" class="btn btn-primary btn-lg">Edytuj profil &raquo;</a>
			</center>
			<?php
		}
		
		if(isset($userStatistics['loginstatus']['geolocation_data'])) {
			getGeolocationData($path, false, false, true, true, $szerokoscGeograficznaGeolokalizacja, $dlugoscGeograficznaGeolokalizacja);
		}
		?>
	</div>
</div>
