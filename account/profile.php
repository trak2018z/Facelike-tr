<?php
$path = "./couchdb/";	//Ścieżka operacyjna

//Upewnij się że użytkownik jest zalogowany
if(!checksession()) {
	exit(header('Location: index.php'));
	echo '<p class="error">Przykro nam, ale ta strona jest dostępna tylko dla zalogowanych użytkowników.</p>';
	
	die;
}

$userId = getidfromsession();

//Upewnij się, że użytkownik istnieje
if(!checkid($path, $userDataDbName, $userId)) {
	exit(header('Location: index.php'));
	echo '<p class="error">Przykro nam, ale użytkownik o podanym identyfikatorze nie istnieje.</p>';
	
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
		exit(header('Location: index.php'));
		echo '<p class="error">Przykro nam, ale użytkownik o podanym identyfikatorze nie istnieje.</p>';
		
		die;
	}
	
	//Przekierowanie użytkownika
	$userId = $u;
	$przekierowanie = true;
	
	//Pobierz dane o użytkowniku
	$userData = data($path, $userDataDbName, $userId);
	$userStatistics = data($path, $userSecurityDbName, $userId);
	
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
	if((isset($userStatistics['typ'])) && ($userStatistics['typ'] != null || $userStatistics['typ'] != ''))
		$uprawnienia = $userStatistics['typ'];
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
		<br />
		<?php
		if($przekierowanie == false) {
			?>
			<table id="t07">
				<caption>Profil <?php echo $nazwa.' '.$userData['imie'].' '.$userData['nazwisko']; ?></caption>
				<tr>
					<td>Imię</td>
					<td><?php echo $userData['imie'] ?></td>
				</tr>
				<tr>
					<td>Nazwisko</td>
					<td><?php echo $userData['nazwisko'] ?></td>
				</tr>
				<tr>
					<td>Płeć</td>
					<td><?php echo getplec($userData['plec']) ?></td>
				</tr>
				<tr>
					<td>Data urodzenia</td>
					<td><?php echo $birthDate ?></td>
				</tr>
				<tr>
					<td>Kraj</td>
					<td><?php echo $userData['adres']['kraj'] ?></td>
				</tr>
				<tr>
					<td>Województwo</td>
					<td><?php echo $userData['adres']['wojewodztwo'] ?></td>
				</tr>
				<tr>
					<td>Miasto</td>
					<td><?php echo $userData['adres']['miasto'] ?></td>
				</tr>
				<tr>
					<td>Ulica</td>
					<td><?php echo $userData['adres']['ulica'] ?></td>
				</tr>
				<tr>
					<td>Poczta</td>
					<td><?php echo $userData['adres']['poczta'] ?></td>
				</tr>
				<tr>
					<td>Telefon</td>
					<td><?php echo $userData['telefon'] ?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?php echo $userData['mail'] ?></td>
				</tr>
				<tr>
					<td>Hasło</td>
					<td><?php echo "*****" ?></td>
				</tr>
				<tr>
					<td>Rejestracja</td>
					<td><?php echo $registration ?></td>
				</tr>
			</table>
			<?php
		}
		if($przekierowanie == true) {
			?>
			<br /><br />
			<div id="t08ramka">
			<table id="t08">
				<caption>Profil <?php echo $nazwa.' '.$userData['imie'].' '.$userData['nazwisko']; ?></caption>
				<tr>
					<td>
						<table id="t09">
							<tr>
								<td>Imię</td>
								<td><?php echo $userData['imie'] ?></td>
							</tr>
							<tr>
								<td>Nazwisko</td>
								<td><?php echo $userData['nazwisko'] ?></td>
							</tr>
							<tr>
								<td>Płeć</td>
								<td><?php echo getplec($userData['plec']) ?></td>
							</tr>
							<tr>
								<td>Data urodzenia</td>
								<td><?php echo $birthDate ?></td>
							</tr>
							<tr>
								<td>Kraj</td>
								<td><?php echo $userData['adres']['kraj'] ?></td>
							</tr>
							<tr>
								<td>Województwo</td>
								<td><?php echo $userData['adres']['wojewodztwo'] ?></td>
							</tr>
							<tr>
								<td>Miasto</td>
								<td><?php echo $userData['adres']['miasto'] ?></td>
							</tr>
							<tr>
								<td>Ulica</td>
								<td><?php echo $userData['adres']['ulica'] ?></td>
							</tr>
							<tr>
								<td>Poczta</td>
								<td><?php echo $userData['adres']['poczta'] ?></td>
							</tr>
							<tr>
								<td>Telefon</td>
								<td><?php echo $userData['telefon'] ?></td>
							</tr>
							<tr>
								<td>Email</td>
								<td><?php echo $userData['mail'] ?></td>
							</tr>
							<tr>
								<td>Hasło</td>
								<td><?php echo "*****" ?></td>
							</tr>
							<tr>
								<td>Rejestracja</td>
								<td><?php echo $registration ?></td>
							</tr>
							<tr>
								<td>Uprawnienia</td>
								<td><?php echo $uprawnienia ?></td>
							</tr>
						</table>
					</td>
					<td>
						<table id="t10">
							<tr>
								<td>Status logowania</td>
								<td></td>
							</tr>
							<tr id="tp01">
								<td>Status</td>
								<td><?php echo $status ?></td>
							</tr>
							<tr id="tp01">
								<td>Dane klienta</td>
								<td></td>
							</tr>
							<tr id="tp02">
								<td>System</td>
								<td><?php echo $system ?></td>
							</tr>
							<tr id="tp02">
								<td>Przeglądarka</td>
								<td><?php echo $przegladarka ?></td>
							</tr>
							<tr id="tp02">
								<td>Aplikacja kliencka</td>
								<td><?php echo $aplikacjaKliencka ?></td>
							</tr>
							<tr id="tp02">
								<td>Port</td>
								<td><?php echo $port ?></td>
							</tr>
							<tr id="tp02">
								<td>Rozdzielczość</td>
								<td><?php echo $rozdzielczosc ?></td>
							</tr>
							<tr id="tp01">
								<td>Szczegóły adresu IP</td>
								<td></td>
							</tr>
							<tr id="tp02">
								<td>Adres IP</td>
								<td><?php echo $adresIp ?></td>
							</tr>
							<tr id="tp02">
								<td>Nazwa hosta</td>
								<td><?php echo $nazwaHosta ?></td>
							</tr>
							<tr id="tp02">
								<td>Organizacja</td>
								<td><?php echo $organizacja ?></td>
							</tr>
							<tr id="tp02">
								<td>Szerokość geograficzna</td>
								<td><?php echo $szerokoscGeograficzna ?></td>
							</tr>
							<tr id="tp02">
								<td>Długość geograficzna</td>
								<td><?php echo $dlugoscGeograficzna ?></td>
							</tr>
							<tr id="tp02">
								<td>Kraj</td>
								<td><?php echo $kraj ?></td>
							</tr>
							<tr id="tp02">
								<td>Region</td>
								<td><?php echo $region ?></td>
							</tr>
							<tr id="tp02">
								<td>Miasto</td>
								<td><?php echo $miasto ?></td>
							</tr>
							<tr id="tp02">
								<td>Kod pocztowy</td>
								<td><?php echo $kodPocztowy ?></td>
							</tr>
							<tr id="tp01">
								<td>Dokładna data</td>
								<td><?php echo $eventPoint ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
			<?php
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
			<br /><br />
			<center>
				<a href="index.php?id=edytujprofil" class="submit-edit_button">Edytuj profil</a>
			</center>
			<?php
		}
		?>
	</div>
</div>
