<?php
function postRegister($path, $userSecurityDbName, $userStatisticsDbName, $userDataDbName)
{
	if($_POST) {
		//Zczytanie danych
		$imie = cleardata($_POST['imie']);
		$nazwisko = cleardata($_POST['nazwisko']);
		$plec = cleardata($_POST['plec']);
		$dataUrodzenia = cleardata($_POST['data_urodzenia']);
		$kraj = cleardata($_POST['kraj']);
		$wojewodztwo = cleardata($_POST['wojewodztwo']);
		$miasto = cleardata($_POST['miasto']);
		$ulica = cleardata($_POST['ulica']);
		$poczta = cleardata($_POST['poczta']);
		$telefon = cleardata($_POST['telefon']);
		$email = cleardata($_POST['email']);
		$login = cleardata($_POST['login']);
		$password = cleardata($_POST['password'], false);
		$passwordVerify = cleardata($_POST['password_v'], false);
		$rejestracjaRok = cleardata($_POST['rejestracja_rok']);
		$rejestracjaMiesiac = cleardata($_POST['rejestracja_miesiac']);
		$rejestracjaDzien = cleardata($_POST['rejestracja_dzien']);
		$rejestracjaGodzina = cleardata($_POST['rejestracja_godzina']);
		$rejestracjaMinuta = cleardata($_POST['rejestracja_minuta']);
		$rejestracjaSekunda = cleardata($_POST['rejestracja_sekunda']);
		
		//Tablica błędów
		$errors = array();
		
		//Tablica z zakazanymi loginami
		$loginVerify = array(
			'admin', 'Admin', 'ADMIN',
			'administrator', 'Administrator', 'ADMINISTRATOR',
			'root', 'Root', 'ROOT'
		);
		
		//Data urodzenia
		$dataUrodzeniaRok = substr($dataUrodzenia, 0, 4);
		$dataUrodzeniaMiesiac = substr($dataUrodzenia, 5, 2);
		$dataUrodzeniaDzien = substr($dataUrodzenia, 8, 2);
		
		//Data rejestracji
		$rejestracjaData = (int)$rejestracjaRok."-".(int)$rejestracjaMiesiac."-".(int)$rejestracjaDzien;
		$rejestracjaCzas = (int)$rejestracjaGodzina.":".(int)$rejestracjaMinuta.":".(int)$rejestracjaSekunda;
		
		//Podstawowa walidacja formularza
		if(empty($imie) || empty($nazwisko) || empty($plec) || empty($dataUrodzeniaRok) ||
		   empty($dataUrodzeniaMiesiac) || empty($dataUrodzeniaDzien) || empty($kraj) || empty($wojewodztwo) ||
		   empty($miasto) || empty($ulica) || empty($poczta) || empty($telefon) ||
		   empty($email) || empty($login) || empty($password) || empty($passwordVerify) ||
		   empty($rejestracjaRok) || empty($rejestracjaMiesiac) || empty($rejestracjaDzien) || empty($rejestracjaGodzina) ||
		   empty($rejestracjaMinuta) || empty($rejestracjaSekunda)) {
			$errors[] = 'Proszę wypełnić wszystkie pola';
		}
		
		//Sprawdź czy użytkownik jest pełnoletni
		$lata = $rejestracjaRok - $dataUrodzeniaRok;
		$miesiace = $rejestracjaMiesiac - $dataUrodzeniaMiesiac;
		$dni = $rejestracjaDzien - $dataUrodzeniaDzien;
		$wiek = $lata + ($miesiace / 12) + ($dni / 365);
		if($wiek < 18) {
			$errors[] = 'Użytkownik jest niepełnoletni';
		}
		
		//Sprawdź czy podany przez użytkownika adres e-mail jest poprawny
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Podany adres e-mail jest niepoprawny';
		}
		
		//Sprawdź czy podany przez użytkownika login nie jest zakazany
		$ile = count($loginVerify);
		for($i=0; $i<$ile; $i++) {
			if($login == $loginVerify[$i]) {
				$errors[] = 'Podany login jest niepoprawny';
			}
		}
		
		//Sprawdź czy podane przez użytkownika adres e-mail lub login nie są zajęte
		//$checkEmail = checkbusy($path, $userDataDbName, "mail", $email);
		$checkLogin = checkbusy($path, $userSecurityDbName, "login", $login);
		
		/*if($checkEmail > 0) {
			$errors[] = 'Podany adres e-mail jest już używany';
		}*/
		if($checkLogin > 0) {
			$errors[] = 'Podany login jest już zajęty';
		}
		
		if($password != $passwordVerify) {
			$errors[] = 'Podane hasła się nie zgadzają';
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$password = password_hash($password, PASSWORD_BCRYPT);	//hashowanie (solenie) hasła
			
			//Wyznaczanie nowego ID dokumentu
			$responseList = listofdocument($path, $userDataDbName);
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileOne = 0;
			for($i=0; $i<$ile; $i++) {
				$nazwa = $arrayList['rows'][$i]['id'];
				$numer = substr($nazwa, 5);
				$ileOne = max($ileOne, $numer);
			}
			
			$responseList = listofdocument($path, $userSecurityDbName);
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileTwo = 0;
			for($i=0; $i<$ile; $i++) {
				$nazwa = $arrayList['rows'][$i]['id'];
				$numer = substr($nazwa, 5);
				$ileTwo = max($ileTwo, $numer);
			}
			
			$responseList = listofdocument($path, $userStatisticsDbName);
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileThree = 0;
			for($i=0; $i<$ile; $i++) {
				$nazwa = $arrayList['rows'][$i]['id'];
				$numer = substr($nazwa, 5);
				$ileThree = max($ileThree, $numer);
			}
			
			$ileMax = max(max($ileOne, $ileTwo), $ileThree);
			$documentId = 'osoba'.($ileMax + 1);
			
			//Usuwanie spacji z numeru telefonu
			$telefon = str_replace(' ', '', $telefon);
			
			//Utworzenie kodu autoryzacyjnego
			$mKey = mkeyhash($documentId, $imie, $nazwisko, $dataUrodzenia, $rejestracjaData, $rejestracjaCzas);
			
			//Tworzenie nowego dokumentu
			$userDocument = array(
				'_id' => $documentId,
				'imie' => $imie,
				'nazwisko' => $nazwisko,
				'plec' => $plec,
				'data_urodzenia' => array(
					'data_urodzenia_rok' => (int)$dataUrodzeniaRok,
					'data_urodzenia_miesiac' => (int)$dataUrodzeniaMiesiac,
					'data_urodzenia_dzien' => (int)$dataUrodzeniaDzien
				),
				'adres' => array(
					'kraj' => $kraj,
					'wojewodztwo' => $wojewodztwo,
					'miasto' => $miasto,
					'ulica' => $ulica,
					'poczta' => $poczta
				),
				'telefon' => (int)$telefon,
				'mail' => $email,
				'rejestracja' => array(
					'rejestracja_rok' => (int)$rejestracjaRok,
					'rejestracja_miesiac' => (int)$rejestracjaMiesiac,
					'rejestracja_dzien' => (int)$rejestracjaDzien,
					'rejestracja_godzina' => (int)$rejestracjaGodzina,
					'rejestracja_minuta' => (int)$rejestracjaMinuta,
					'rejestracja_sekunda' => (int)$rejestracjaSekunda
				)
			);
			$securityDocument = array(
				'_id' => $documentId,
				'login' => $login,
				'password' => $password,
				'typ' => 'niepotwierdzony',
				'mkey' => $mKey
			);
			$statisticsDocument = array(
				'_id' => $documentId
			);
			
			//Zapis nowego dokumentu z informacjami o użytkowniku do bazy danych
			$response = createdocument($path, $userDataDbName, $userDocument);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createOne = array_key_exists('error', $array);
			
			//Zapis nowego dokumentu z wrażliwymi informacjami o użytkowniku do bazy danych
			$response = createdocument($path, $userSecurityDbName, $securityDocument);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createTwo = array_key_exists('error', $array);
			
			//Zapis nowego dokumentu ze statystykami o użytkowniku do bazy danych
			$response = createdocument($path, $userStatisticsDbName, $statisticsDocument);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createThree = array_key_exists('error', $array);
			
			if($createOne == true || $createTwo == true || $createThree == true) {
				$error = "Wystąpił błąd przy rejestrowaniu użytkownika";
				
				?>
				<div class="error-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php echo $error; ?>\n',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Tablica komunikatów
				$komunikaty = array();
				
				//Typ komunikatu
				$typKomunikatu = 'success';
				
				//Kodowanie danych
				$zakodowanyId = base64_encode($documentId);
				$zakodowanyEmail = base64_encode($email);
				
				//Generowanie linku aktywacyjnego
				$linkAktywacyjny = 'http://localhost/facelike/index.php?id=aktywacja&userid='.$zakodowanyId.'&email='.$zakodowanyEmail.'&key='.$mKey;
				
				//Dane do wiadomości mail
				$tematWiadomosci = 'Facelike - Autoryzacja';
				$trescHTML = '<!DOCTYPE html>
					<html>
					<head>
						<style>
						.mailBar {
							font: 400 normal 12px/1.5em Arial, sans-serif;
							width: 650px;
							margin: 0px 0px 0px 0px;
							padding: 0px 10px 0px 10px;
							position: absolute;
							top: 0;
							left: 0;
							z-index: 1;
							background: #ebebeb;
							background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(0%, #fff), color-stop(100%, #ebebeb));
							background-image: -webkit-linear-gradient(#fff, #ebebeb);
							background-image: -moz-linear-gradient(#fff, #ebebeb);
							background-image: -o-linear-gradient(#fff, #ebebeb);
							background-image: -ms-linear-gradient(#fff, #ebebeb);
							filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#ebebeb);
							background-image: linear-gradient(#fff, #ebebeb);
						}
						.mailBar a {
							color: #414141;
							border-bottom: 1px dotted #aeaeae;
						}
						.mailBar a:hover {
							color: #aeaeae;
						}
						.mailBar p {
							margin: 2px 0px 2px 0px;
							line-height: 1;
						}
						.mailBarWrap {
							height: 250px;
							margin: 0px 0px 0px 25px;
							padding: 0px 0px 0px 0px;
						}
						.mailBarWrap img {
							border: 0;
							border-style: none;
							border-radius: 15px;
						}
						#mailBarLogo {
							background-image: url("facelike_logo.jpg");
							background-repeat: no-repeat;
							width: 592px;
							height: 247px;
						}
						.mailBarH1 {
							color: #ff6805;
							font-size: 24px;
						}
						.mailBarH2 {
							color: #3399ff;
							font-size: 18px;
						}
						.mailJumbotron {
							padding: 23px;
							margin-bottom: 0px;
							border-radius: 20px;
						}
						</style>
					</head>
					<body>
						<div class="mailBar mailJumbotron">
							<div class="mailBarWrap">
								<img id="mailBarLogo" src="#" alt="">
							</div>
							<br />
							<h1 class="mailBarH1"><center><b>Aktywacja konta</b></center></h2>
							<br />
							<hr />
							<br />
							<h2 class="mailBarH2">Dzie&#324; dobry,</h2>
							<br />
							Jest to wiadomo&#347;&#263; autoryzacyjna wys&#322;ana z serwisu <b><i>Facelike</i></b> i jest ona przeznaczona dla u&#380;ytkownika <b>'.$imie.'&nbsp;'.$nazwisko.'</b>.<br />
							Je&#347;li wiadomo&#347;&#263; trafi&#322;a do Ciebie przez pomy&#322;k&#281; prosz&#281; j&#261; usun&#261;&#263;.<br /><br />
							Je&#347;li wiadomo&#347;&#263; jest przeznaczona dla Ciebie, w celu aktywacji konta przejd&#378; pod wskazany link:<br />
							<a target="_blank" href="'.$linkAktywacyjny.'">'.$linkAktywacyjny.'</a><br /><br /><br />
							Z powa&#380;aniem,<br />
							Facelike<br />
						</div>
					</body>
					</html>';
				$trescNonHTML = 'Witam, jest to wiadomość autoryzacyjna wysłana z serwisu Facelike i jest ona przeznaczona dla użytkownika '.$imie.' '.$nazwisko.'. Jeśli wiadomość trafiła do Ciebie przez pomyłkę proszę ją usunąć. Jeśli wiadomość jest przeznaczona dla Ciebie, w celu aktywacji konta przejdź pod wskazany link: '.$linkAktywacyjny.' Z poważaniem, Facelike';
				
				//Zdjęcie w wiadomości mail
				$wystapienieZdjecia = true;
				$sciezkaZdjecia = 'images/facelike_logo.jpg';
				$nazwaZdjecia = 'Facelike logo';
				$plikZdjecia = 'facelike_logo.jpg';
				$kodowanieZdjecia = 'base64';
				$typZdjecia = 'image/jpeg';
				$pozycjaZdjecia = 'inline';
				
				//Inicjalizacja mailer-a
				require 'mailer/config.php';
				
				//Wysłanie maila
				$potwierdzenie = sendmail($adresNadawcyAuthorization, $nazwaNadawcyAuthorization, $hasloNadawcyAuthorization, $email, $imie.' '.$nazwisko, true, $tematWiadomosci, $trescHTML, $trescNonHTML, 'mail', $wystapienieZdjecia, $sciezkaZdjecia, $nazwaZdjecia, $plikZdjecia, $kodowanieZdjecia, $typZdjecia, $pozycjaZdjecia);
				
				if($potwierdzenie) {
					$komunikaty[] = 'Użytkownik o loginie '.$login.' został poprawnie zarejestrowany';
					$komunikaty[] = 'Wiadomość e-mail została wysłana na adres '.$email;
					$komunikaty[] = 'Postępuj zgodnie z instrukcją w celu aktywacji konta';
				}
				else {
					$typKomunikatu = 'error';
					
					$komunikaty[] = 'Błąd przy wysyłaniu wiadomości na adres '.$email;
					$komunikaty[] = 'Skontaktuj się z administratorem systemu w celu uzyskania pomocy';
				}
				
				if($typKomunikatu == 'success') {
					?>
					<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: null,
								text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat; ?>\n<?php } ?>',
								type: '<?php echo $typKomunikatu ?>',
								timer: 5000,
								showConfirmButton: false,
								html: true
							},
							function() {
								window.location.href = 'index.php';
							}
						);
						//]]>
					</script>
					<?php
				}
				else if($typKomunikatu == 'error') {
					?>
					<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: 'Uwaga',
								text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat; ?>\n<?php } ?>',
								type: '<?php echo $typKomunikatu ?>',
								confirmButtonColor: '#DD6B55',
								closeOnConfirm: false
							},
							function(isConfirm) {
								if(isConfirm) {
									history.back();
								}
							}
						);
						//]]>
					</script>
					<?php
				}
			}
		}
	}
}

function postLogin($path, $userSecurityDbName, $userStatisticsDbName)
{
	if($_POST) {
		//Zczytanie danych
		$login = cleardata($_POST['login']);
		$password = cleardata($_POST['password'], false);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($login) && empty($password)) {
			$errors[] = 'Należy wypełnić wszystkie pola';
		}
		elseif(empty($login)) {
			$errors[] = 'Należy podać nazwę użytkownika';
		}
		elseif(empty($password)) {
			$errors[] = 'Należy podać hasło';
		}
		
		$auth = array("0", null);
		$auth = auth($path, $userSecurityDbName, $login, $password);
		if($auth[0] == "0") {
			$errors[] = 'Błąd przy sprawdzaniu użytkownika';
		}
		elseif($auth[0] == "1") {
			$errors[] = 'Błąd przy sprawdzaniu użytkownika';
		}
		elseif($auth[0] == "2") {
			$errors[] = 'Użytkownik o podanym loginie i haśle nie istnieje';
		}
		elseif($auth[0] == "3") {
			$errors[] = 'Użytkownik o podanym loginie i haśle nie istnieje';
			
			//Zmiana statusu logowania
			changeloginstatus($path, $userStatisticsDbName, $auth[1], 0);
		}
		else {
			$type = "niepotwierdzony";
			$type = getusertype($path, $userSecurityDbName, $auth[1]);
			if($type == "niepotwierdzony") {
				$errors[] = 'Konto nie zostało jeszcze aktywowane';
				
				//Zmiana statusu logowania
				changeloginstatus($path, $userStatisticsDbName, $auth[1], 1);
			}
		}
		
		if(empty($errors)) {
			//Logowanie
			createsession($auth[1], $path);
			
			//Deklaracja zmiennej
			$status = getdatafromarray(getuserdata($path, $userStatisticsDbName, $auth[1], "loginstatus"), "status");
			
			//Zmiana statusu logowania
			changeloginstatus($path, $userStatisticsDbName, $auth[1], 2);
			
			//Pasek postępu
			?>
			<div class="loginLoading" style="width: 100%; position: absolute; top: 140px; left: 0px; z-index: 100;">
				<div class="container body-content">
					<div class="jumbotron" style="padding-top: 170px; padding-bottom: 170px;">
						<div class="progress">
							<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
						</div>
					</div>
				</div>
			</div>
			<?php
			
			//Przekierowanie
			if(headers_sent()) {
				?>
				<script type="text/javascript">
				//<![CDATA[
					function reload() {
						location.replace('index.php');
					}
					
					function getStatus() {
						var dbName = '<?php echo $userStatisticsDbName; ?>';
						var documentId = '<?php echo $auth[1]; ?>';
						var dataOne = "loginstatus";
						var dataTwo = "status";
						var status = '<?php echo $status; ?>';
						
						jQuery(document).ready(function() {
							$.ajax({
								type: "POST",
								url: "account/logindata.php",
								data: {
									dbName: JSON.stringify(dbName),
									documentId: JSON.stringify(documentId),
									dataOne: JSON.stringify(dataOne),
									dataTwo: JSON.stringify(dataTwo),
									status: JSON.stringify(status),
								},
								dataType: "HTML",	//"HTML"	//"JSON"
								cache: false,
								beforeSend: function() {
									$(".loginLoading").show();
								},
								success: function(data, msg) {
									//Ten fragment wykona się po POMYŚLNYM zakończeniu połączenia
									$(".loginLoading").hide();
								},
								complete: function(r) {
									//Ten fragment wykona się po ZAKONCZENIU połączenia
									reload();
								},
								error: function(data, error) {
									//Ten fragment wykona się w przypadku BŁĘDU
									$(".loginLoading").hide();
									console.log(error);
								}
							});
						});
					}
					
					getStatus();
				 //]]>
				</script>
				<?php
			}
			else{
				exit(header('Location: index.php'));
			}
			//echo '<div class="success-box">Zostałeś zalogowany. Możesz przejść na <a href="index.php">stronę główną</a></div>';
		}
		else {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'error',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
	}
}

function postRecoverAccount($path, $userSecurityDbName, $userDataDbName)
{
	if($_POST) {
		//Zczytanie danych
		$email = cleardata($_POST['email']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($email)) {
			$errors[] = 'Należy podać adres e-mail';
		}
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Podany adres e-mail jest niepoprawny';
		}
		
		//Sprawdź czy podane przez użytkownika adres e-mail istnieje w bazie danych
		$userId = getuserid($path, $userDataDbName, "mail", $email);
		
		if($userId == false) {
			$errors[] = 'Nie znaleziono użytkownika z podanym adresem e-mail';
		}
		else {
			$type = "niepotwierdzony";
			$type = getusertype($path, $userSecurityDbName, $userId);
			if($type == "niepotwierdzony") {
				$errors[] = 'Konto nie zostało jeszcze aktywowane';
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$updateOk = false;
			$updateError = false;
			
			$imie = getuserdata($path, $userDataDbName, $userId, 'imie');
			$nazwisko = getuserdata($path, $userDataDbName, $userId, 'nazwisko');
			
			//Pobranie numerów rev z bazy danych
			$one = getdocumentrev($path, $userSecurityDbName, $userId);
			$revOne = $one[1];
			
			$two = getdocumentrev($path, $userDataDbName, $userId);
			$revTwo = $two[1];
			
			//Pobranie aktualnej daty i czasu
			$rok = date('Y');
			$miesiac = date('m');
			$dzien = date('d');
			$godzina = date('H');
			$minuta = date('i');
			$sekunda = date('s');
			
			//Tworzenie  aktualnej daty i czasu
			$aktualnaData = $rok.'-'.$miesiac.'-'.$dzien;
			$aktualnyCzas = $godzina.':'.$minuta.':'.$sekunda;
			
			//Utworzenie klucza ratunkowego
			$rKey = rkeyhash($userId, $email, $revOne, $revTwo, $aktualnaData, $aktualnyCzas);
			
			//Tworzenie dokumentu
			$document = array(
				'recovery' => array(
					'rkey' => $rKey,
					'rok' => $rok,
					'miesiac' => $miesiac,
					'dzien' => $dzien,
					'godzina' => $godzina,
					'minuta' => $minuta,
					'sekunda' => $sekunda
				)
			);
			
			//Zapisanie klucza ratunkowego do bazy danych
			$response = updatedocument($path, $userSecurityDbName, $userId, $document);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$updateOk = array_key_exists('ok', $array);
			$updateError = array_key_exists('error', $array);
			
			if($updateOk == false || $updateError == true) {
				$error = "Wystąpił błąd przy tworzeniu klucza ratunkowego";
				
				?>
				<div class="error-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php echo $error; ?>\n',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Tablica komunikatów
				$komunikaty = array();
				
				//Typ komunikatu
				$typKomunikatu = 'success';
				
				//Kodowanie danych
				$zakodowanyId = base64_encode($userId);
				$zakodowanyEmail = base64_encode($email);
				
				//Generowanie linku ratunkowego
				$linkAktywacyjny = 'http://localhost/facelike/index.php?id=zmianahasla&userid='.$zakodowanyId.'&email='.$zakodowanyEmail.'&key='.$rKey;
				
				//Dane do wiadomości mail
				$tematWiadomosci = 'Facelike - Odzyskiwanie konta';
				$trescHTML = '<!DOCTYPE html>
					<html lang="pl">
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<meta charset="utf-8" />
						<style>
						.mailBar {
							font: 400 normal 12px/1.5em Arial, sans-serif;
							width: 650px;
							margin: 0px 0px 0px 0px;
							padding: 0px 10px 0px 10px;
							position: absolute;
							top: 0;
							left: 0;
							z-index: 1;
							background: #ebebeb;
							background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(0%, #fff), color-stop(100%, #ebebeb));
							background-image: -webkit-linear-gradient(#fff, #ebebeb);
							background-image: -moz-linear-gradient(#fff, #ebebeb);
							background-image: -o-linear-gradient(#fff, #ebebeb);
							background-image: -ms-linear-gradient(#fff, #ebebeb);
							filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#ebebeb);
							background-image: linear-gradient(#fff, #ebebeb);
						}
						.mailBar a {
							color: #414141;
							border-bottom: 1px dotted #aeaeae;
						}
						.mailBar a:hover {
							color: #aeaeae;
						}
						.mailBar p {
							margin: 2px 0px 2px 0px;
							line-height: 1;
						}
						.mailBarWrap {
							height: 250px;
							margin: 0px 0px 0px 25px;
							padding: 0px 0px 0px 0px;
						}
						.mailBarWrap img {
							border: 0;
							border-style: none;
							border-radius: 15px;
						}
						#mailBarLogo {
							background-image: url("facelike_logo.jpg");
							background-repeat: no-repeat;
							width: 592px;
							height: 247px;
						}
						.mailBarH1 {
							color: #ff3300;
							font-size: 24px;
						}
						.mailBarH2 {
							color: #3399ff;
							font-size: 18px;
						}
						.mailJumbotron {
							padding: 23px;
							margin-bottom: 0px;
							border-radius: 20px;
						}
						</style>
					</head>
					<body>
						<div class="mailBar mailJumbotron">
							<div class="mailBarWrap">
								<img id="mailBarLogo" src="#" alt="">
							</div>
							<br />
							<h1 class="mailBarH1"><center><b>Przypomnienie has&#322;a</b></center></h2>
							<br />
							<hr />
							<br />
							<h2 class="mailBarH2">Dzie&#324; dobry,</h2>
							<br />
							Tw&#243;j adres e-mail zosta&#322; podany w formularzu przypominania has&#322;a. Je&#347;li nie pami&#281;tasz has&#322;a do swojego konta na <b><i>Facelike</i></b>, wejd&#378; na poni&#380;szy link, a b&#281;dziesz m&#243;g&#322; je zmieni&#263;:<br /><br />
							<center><a target="_blank" href="'.$linkAktywacyjny.'">Kliknij tutaj, aby zresetowa&#263; has&#322;o do swojego konta na Facelike</a></center><br />
							Je&#347;li nie wejdziesz na podany adres Twoje has&#322;o nie zostanie zmienione i b&#281;dziesz m&#243;g&#322; si&#281; zalogowa&#263; do Twojego konta na <b><i>Facelike</i></b> tak, jak do tej pory.<br /><br /><br />
							Dzi&#281;kujemy,<br />
							Facelike<br />
						</div>
					</body>
					</html>';
				$trescNonHTML = 'Przypomnienie hasła   Dzień dobry  Twój adres e-mail został podany w formularzu przypominania hasła. Jeśli nie pamiętasz hasła do swojego konta na Facelike, wejdź na poniższy link, a będziesz mógł je zmienić: '.$linkAktywacyjny.' Jeśli nie wejdziesz na podany adres Twoje hasło nie zostanie zmienione i będziesz mógł się zalogować do Twojego konta na Facelike tak, jak do tej pory.  Dziękujemy';
				
				//Zdjęcie w wiadomości mail
				$wystapienieZdjecia = true;
				$sciezkaZdjecia = 'images/facelike_logo.jpg';
				$nazwaZdjecia = 'Facelike logo';
				$plikZdjecia = 'facelike_logo.jpg';
				$kodowanieZdjecia = 'base64';
				$typZdjecia = 'image/jpeg';
				$pozycjaZdjecia = 'inline';
				
				//Inicjalizacja mailer-a
				require 'mailer/config.php';
				
				//Wysłanie maila
				$potwierdzenie = sendmail($adresNadawcyRecovery, $nazwaNadawcyRecovery, $hasloNadawcyRecovery, $email, $imie.' '.$nazwisko, true, $tematWiadomosci, $trescHTML, $trescNonHTML, 'mail', $wystapienieZdjecia, $sciezkaZdjecia, $nazwaZdjecia, $plikZdjecia, $kodowanieZdjecia, $typZdjecia, $pozycjaZdjecia);
				
				if($potwierdzenie) {
					$komunikaty[] = 'Wiadomość e-mail została wysłana na adres '.$email;
					$komunikaty[] = 'Postępuj zgodnie z instrukcją w cel odzyskania dostępu do konta';
				}
				else {
					$typKomunikatu = 'error';
					
					$komunikaty[] = 'Błąd przy wysyłaniu wiadomości na adres '.$email;
					$komunikaty[] = 'Skontaktuj się z administratorem systemu w celu uzyskania pomocy';
				}
				
				if($typKomunikatu == 'success') {
					?>
					<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: null,
								text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat; ?>\n<?php } ?>',
								type: '<?php echo $typKomunikatu ?>',
								timer: 5000,
								showConfirmButton: false,
								html: true
							},
							function() {
								window.location.href = 'index.php';
							}
						);
						//]]>
					</script>
					<?php
				}
				else if($typKomunikatu == 'error') {
					?>
					<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: 'Uwaga',
								text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat; ?>\n<?php } ?>',
								type: '<?php echo $typKomunikatu ?>',
								confirmButtonColor: '#DD6B55',
								closeOnConfirm: false
							},
							function(isConfirm) {
								if(isConfirm) {
									history.back();
								}
							}
						);
						//]]>
					</script>
					<?php
				}
			}
		}
	}
}

function postChangePassword($path, $userSecurityDbName, $userStatisticsDbName, $userDataDbName)
{
	if($_POST) {
		//Zczytanie danych
		$userId = cleardata($_POST['userid']);
		$email = cleardata($_POST['email']);
		$rKey = cleardata($_POST['key']);
		$password = cleardata($_POST['password'], false);
		$passwordVerify = cleardata($_POST['password_v'], false);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($userId) && empty($email) && empty($rKey) && empty($password) && empty($passwordVerify)) {
			$errors[] = 'Należy wypełnić wszystkie pola';
		}
		
		//Sprawdzanie danych
		$istniejeId = checkid($path, $userDataDbName, $userId);
		$istniejeEmail = checkuserdata($path, $userDataDbName, $userId, 'mail', $email);
		$istniejeKey = checkuserdata($path, $userSecurityDbName, $userId, 'rkey', $rKey);
		
		if($istniejeId == false || $istniejeEmail == false || $istniejeKey == false) {
			$errors[] = 'Błąd przy sprawdzaniu danych użytkownika';
		}
		
		if($password != $passwordVerify) {
			$errors[] = 'Podane hasła się nie zgadzają';
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$updateOneOk = false;
			$updateOneError = false;
			$updateTwoOk = false;
			$updateTwoError = false;
			
			//Tablica komunikatów
			$komunikaty = array();
			
			//hashowanie (solenie) hasła
			$password = password_hash($password, PASSWORD_BCRYPT);
			
			//Utworzenie statusu logowania
			$loginStatus = createloginstatus(6);
			
			//Utworzenie pustego klucza ratunkowego
			$rKey = NULL;
			
			//Pobieranie daty i czasu utworzenia linku ratunkowego
			$recovery = getuserdata($path, $userSecurityDbName, $userId, 'recovery');
			
			//Tworzenie dokumentu
			$securityDocument = array(
				'password' => $password,
				'recovery' => array(
					'rkey' => $rKey,
					'rok' => $recovery['rok'],
					'miesiac' => $recovery['miesiac'],
					'dzien' => $recovery['dzien'],
					'godzina' => $recovery['godzina'],
					'minuta' => $recovery['minuta'],
					'sekunda' => $recovery['sekunda']
				)
			);
			$statisticsDocument = array(
				'loginstatus' => $loginStatus
			);
			
			//Zapisanie nowego hasła i tablicy ratunkowej do bazy danych
			$response = updatedocument($path, $userSecurityDbName, $userId, $securityDocument);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$updateOneOk = array_key_exists('ok', $array);
			$updateOneError = array_key_exists('error', $array);
			
			//Zapis dokumentu ze statystykami o użytkowniku do bazy danych
			$response = updatedocument($path, $userStatisticsDbName, $userId, $statisticsDocument);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$updateTwoOk = array_key_exists('ok', $array);
			$updateTwoError = array_key_exists('error', $array);
			
			if($updateOneOk == true && $updateTwoOk == true && $updateOneError == false && $updateTwoError == false) {
				//Auto logowanie
				createsession($userId, $path);
				
				//Zmiana statusu logowania
				changeloginstatus($path, $userStatisticsDbName, $userId, 3);
				
				exit(header('Location: index.php'));	//Przekierowanie
				//echo '<div class="success-box">Zostałeś zalogowany. Możesz przejść na <a href="index.php">stronę główną</a></div>';
			}
			else {
				$komunikaty[] = 'Błąd przy zmianie hasła użytkownika';
				$komunikaty[] = 'Skontaktuj się z administratorem systemu w celu uzyskania pomocy';
				
				?>
				<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat; ?>\n<?php } ?>',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
		}
	}
}

function postEditProfile($path, $userSecurityDbName, $userDataDbName, $userData)
{
	if($_POST) {
		//Zczytanie danych
		$imie = cleardata($_POST['imie']);
		$nazwisko = cleardata($_POST['nazwisko']);
		$plec = cleardata($_POST['plec']);
		$kraj = cleardata($_POST['kraj']);
		$wojewodztwo = cleardata($_POST['wojewodztwo']);
		$miasto = cleardata($_POST['miasto']);
		$ulica = cleardata($_POST['ulica']);
		$poczta = cleardata($_POST['poczta']);
		$telefon = cleardata($_POST['telefon']);
		$email = cleardata($_POST['email']);
		$passwordOld = cleardata($_POST['password_o'], false);
		$password = cleardata($_POST['password'], false);
		$passwordVerify = cleardata($_POST['password_v'], false);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($imie) && empty($nazwisko) && empty($plec) && empty($kraj) &&
		   empty($wojewodztwo) && empty($miasto) && empty($ulica) && empty($poczta) &&
		   empty($telefon) && empty($email) && empty($passwordOld) && empty($password) &&
		   empty($passwordVerify)) {
			$errors[] = 'Należy podać dane do edycji';
		}
		
		$userId = getidfromsession();
		
		if(!empty($email)) {
			//Sprawdź czy podany przez użytkownika adres e-mail jest poprawny
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = 'Podany adres e-mail jest niepoprawny';
			}
			
			//Sprawdź czy podany przez użytkownika adres e-mail nie jest zajęty
			$checkEmail = checkbusy($path, $userDataDbName, "mail", $email);
			
			if($checkEmail > 0) {
				$errors[] = 'Podany adres e-mail jest już używany';
			}
		}
		
		if(empty($passwordOld) && (!empty($password) || !empty($passwordVerify))) {
			$errors[] = 'Proszę podać obecne hasło';
		}
		else if((!empty($passwordOld) && !empty($password) && empty($passwordVerify)) ||
		   (!empty($passwordOld) && empty($password) && !empty($passwordVerify))) {
			$errors[] = 'Proszę powtórzyć hasło';
		}
		else if(!empty($passwordOld) && !empty($password) && !empty($passwordVerify)) {
			$passwordOld = password_hash($passwordOld, PASSWORD_BCRYPT);	//hashowanie (solenie) hasła
			$currentPassword = getuserdata($path, $userSecurityDbName, $userId, "password");
			
			if(password_verify($passwordOld, $currentPassword)) {
				$errors[] = 'Podane obecne hasło jest błędne';
			}
			
			if($password != $passwordVerify) {
				$errors[] = 'Podane nowe hasła nie zgadzają się ze sobą';
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$logout = false;
			
			$updateOneOk = false;
			$updateOneError = false;
			$updateTwoOk = false;
			$updateTwoError = false;
			
			//Tworzenie pustych dokumentów
			$userDocument = array();
			$securityDocument = array();
			
			//Zapisywanie dokumentów
			if(!empty($imie)) {
				$userDocument['imie'] = $imie;
			}
			else {
				//Pobieranie imienia
				$imie = $userData['imie'];
			}
			if(!empty($nazwisko)) {
				$userDocument['nazwisko'] = $nazwisko;
			}
			else {
				//Pobieranie nazwiska
				$nazwisko = $userData['nazwisko'];
			}
			if(!empty($plec)) {
				$userDocument['plec'] = $plec;
			}
			if((!empty($kraj)) || (!empty($wojewodztwo)) || (!empty($miasto)) || (!empty($ulica)) || (!empty($poczta))) {
				if(!empty($kraj)) {
					$userDocument['adres']['kraj'] = $kraj;
				}
				else {
					$userDocument['adres']['kraj'] = $userData['adres']['kraj'];
				}
				if(!empty($wojewodztwo)) {
					$userDocument['adres']['wojewodztwo'] = $wojewodztwo;
				}
				else {
					$userDocument['adres']['wojewodztwo'] = $userData['adres']['wojewodztwo'];
				}
				if(!empty($miasto)) {
					$userDocument['adres']['miasto'] = $miasto;
				}
				else {
					$userDocument['adres']['miasto'] = $userData['adres']['miasto'];
				}
				if(!empty($ulica)) {
					$userDocument['adres']['ulica'] = $ulica;
				}
				else {
					$userDocument['adres']['ulica'] = $userData['adres']['ulica'];
				}
				if(!empty($poczta)) {
					$userDocument['adres']['poczta'] = $poczta;
				}
				else {
					$userDocument['adres']['poczta'] = $userData['adres']['poczta'];
				}
			}
			if(!empty($telefon)) {
				//Usuwanie spacji z numeru telefonu
				$telefon = str_replace(' ', '', $telefon);
				
				$userDocument['telefon'] = $telefon;
			}
			if(!empty($email)) {
				$logout = true;
				
				//Pobieranie danych
				$dataUrodzenia = $userData['data_urodzenia']['data_urodzenia_rok']."-".$userData['data_urodzenia']['data_urodzenia_miesiac']."-".$userData['data_urodzenia']['data_urodzenia_dzien'];
				$rejestracjaData = $userData['rejestracja']['rejestracja_rok']."-".$userData['rejestracja']['rejestracja_miesiac']."-".$userData['rejestracja']['rejestracja_dzien'];
				$rejestracjaCzas = $userData['rejestracja']['rejestracja_godzina'].":".$userData['rejestracja']['rejestracja_minuta'].":".$userData['rejestracja']['rejestracja_sekunda'];
				
				//Utworzenie kodu autoryzacyjnego
				$mKey = mkeyhash($userId, $imie, $nazwisko, $dataUrodzenia, $rejestracjaData, $rejestracjaCzas);
				
				$userDocument['mail'] = $email;
				$securityDocument['typ'] = 'niepotwierdzony';
				$securityDocument['mkey'] = $mKey;
			}
			if(!empty($password)) {
				$logout = true;
				
				$password = password_hash($password, PASSWORD_BCRYPT);	//hashowanie (solenie) hasła
				
				$securityDocument['password'] = $password;
			}
			
			//Dokument z informacjami o użytkowniku
			if(!empty($userDocument)) {
				//Zapis dokumentu z informacjami o użytkowniku do bazy danych
				$response = updatedocument($path, $userDataDbName, $userId, $userDocument);
				
				$result = string_to_array($response[1], true);
				$array = $result[0];
				
				$updateOneOk = array_key_exists('ok', $array);
				$updateOneError = array_key_exists('error', $array);
			}
			
			//Dokument z wrażliwymi informacjami o użytkowniku
			if(!empty($securityDocument)) {
				//Zapis dokumentu z wrażliwymi informacjami o użytkowniku do bazy danych
				$response = updatedocument($path, $userSecurityDbName, $userId, $securityDocument);
				
				$result = string_to_array($response[1], true);
				$array = $result[0];
				
				$updateTwoOk = array_key_exists('ok', $array);
				$updateTwoError = array_key_exists('error', $array);
			}
			
			if(($updateOneOk == false && $updateTwoOk == false) || ($updateOneError == true || $updateTwoError == true)) {
				$error = "Wystąpił błąd podczas aktualizacji danych użytkownika";
				
				?>
				<div class="error-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php echo $error; ?>\n',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Tablica komunikatów
				$komunikaty = array();
				
				//Typ komunikatu
				$typKomunikatu = 'success';
				
				if(!isset($mKey)) {
					$komunikaty[] = 'Informacje użytkownika <b>'.$imie.' '.$nazwisko.'</b> zostały poprawnie zmienione';
				}
				else {
					//Kodowanie danych
					$zakodowanyId = base64_encode($userId);
					$zakodowanyEmail = base64_encode($email);
					
					//Generowanie linku aktywacyjnego
					$linkAktywacyjny = 'http://localhost/facelike/index.php?id=aktywacja&userid='.$zakodowanyId.'&email='.$zakodowanyEmail.'&key='.$mKey;
					
					//Dane do wiadomości mail
					$tematWiadomosci = 'Facelike - Autoryzacja';
					$trescHTML = '<!DOCTYPE html>
						<html>
						<head>
							<style>
							.mailBar {
								font: 400 normal 12px/1.5em Arial, sans-serif;
								width: 650px;
								margin: 0px 0px 0px 0px;
								padding: 0px 10px 0px 10px;
								position: absolute;
								top: 0;
								left: 0;
								z-index: 1;
								background: #ebebeb;
								background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(0%, #fff), color-stop(100%, #ebebeb));
								background-image: -webkit-linear-gradient(#fff, #ebebeb);
								background-image: -moz-linear-gradient(#fff, #ebebeb);
								background-image: -o-linear-gradient(#fff, #ebebeb);
								background-image: -ms-linear-gradient(#fff, #ebebeb);
								filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#ebebeb);
								background-image: linear-gradient(#fff, #ebebeb);
							}
							.mailBar a {
								color: #414141;
								border-bottom: 1px dotted #aeaeae;
							}
							.mailBar a:hover {
								color: #aeaeae;
							}
							.mailBar p {
								margin: 2px 0px 2px 0px;
								line-height: 1;
							}
							.mailBarWrap {
								height: 250px;
								margin: 0px 0px 0px 25px;
								padding: 0px 0px 0px 0px;
							}
							.mailBarWrap img {
								border: 0;
								border-style: none;
								border-radius: 15px;
							}
							#mailBarLogo {
								background-image: url("facelike_logo.jpg");
								background-repeat: no-repeat;
								width: 592px;
								height: 247px;
							}
							.mailBarH1 {
								color: #ff6805;
								font-size: 24px;
							}
							.mailBarH2 {
								color: #3399ff;
								font-size: 18px;
							}
							.mailJumbotron {
								padding: 23px;
								margin-bottom: 0px;
								border-radius: 20px;
							}
							</style>
						</head>
						<body>
							<div class="mailBar mailJumbotron">
								<div class="mailBarWrap">
									<img id="mailBarLogo" src="#" alt="">
								</div>
								<br />
								<h1 class="mailBarH1"><center><b>Aktywacja konta</b></center></h2>
								<br />
								<hr />
								<br />
								<h2 class="mailBarH2">Dzie&#324; dobry,</h2>
								<br />
								Jest to wiadomo&#347;&#263; autoryzacyjna wys&#322;ana z serwisu <b><i>Facelike</i></b> i jest ona przeznaczona dla u&#380;ytkownika <b>'.$imie.'&nbsp;'.$nazwisko.'</b>.<br />
								Je&#347;li wiadomo&#347;&#263; trafi&#322;a do Ciebie przez pomy&#322;k&#281; prosz&#281; j&#261; usun&#261;&#263;.<br /><br />
								Je&#347;li wiadomo&#347;&#263; jest przeznaczona dla Ciebie, w celu aktywacji konta przejd&#378; pod wskazany link:<br />
								<a target="_blank" href="'.$linkAktywacyjny.'">'.$linkAktywacyjny.'</a><br /><br /><br />
								Z powa&#380;aniem,<br />
								Facelike<br />
							</div>
						</body>
						</html>';
					$trescNonHTML = 'Witam, jest to wiadomość autoryzacyjna wysłana z serwisu Facelike i jest ona przeznaczona dla użytkownika '.$imie.' '.$nazwisko.'. Jeśli wiadomość trafiła do Ciebie przez pomyłkę proszę ją usunąć. Jeśli wiadomość jest przeznaczona dla Ciebie, w celu aktywacji konta przejdź pod wskazany link: '.$linkAktywacyjny.' Z poważaniem, Facelike';
					
					//Zdjęcie w wiadomości mail
					$wystapienieZdjecia = true;
					$sciezkaZdjecia = 'images/facelike_logo.jpg';
					$nazwaZdjecia = 'Facelike logo';
					$plikZdjecia = 'facelike_logo.jpg';
					$kodowanieZdjecia = 'base64';
					$typZdjecia = 'image/jpeg';
					$pozycjaZdjecia = 'inline';
					
					//Inicjalizacja mailer-a
					require 'mailer/config.php';
					
					//Wysłanie maila
					$potwierdzenie = sendmail($adresNadawcyAuthorization, $nazwaNadawcyAuthorization, $hasloNadawcyAuthorization, $email, $imie.' '.$nazwisko, true, $tematWiadomosci, $trescHTML, $trescNonHTML, 'mail', $wystapienieZdjecia, $sciezkaZdjecia, $nazwaZdjecia, $plikZdjecia, $kodowanieZdjecia, $typZdjecia, $pozycjaZdjecia);
					
					if($potwierdzenie) {
						$komunikaty[] = 'Wiadomość e-mail została wysłana na adres '.$email;
						$komunikaty[] = 'Postępuj zgodnie z instrukcją w celu aktywacji konta';
					}
					else {
						$typKomunikatu = 'error';
						
						$komunikaty[] = 'Błąd przy wysyłaniu wiadomości na adres '.$email;
						$komunikaty[] = 'Skontaktuj się z administratorem systemu w celu uzyskania pomocy';
					}
				}
				
				if($logout == true) {
					$komunikaty[] = 'Użytkownik zostanie za chwilę wylogowany';
					
					if($typKomunikatu == 'success') {
						?>
						<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
						<?php
					}
					else if($typKomunikatu == 'error') {
						?>
						<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
						<?php
					}
					?>
					
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: null,
								text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?>',
								type: '<?php echo $typKomunikatu ?>',
								timer: 5000,
								showConfirmButton: false,
								html: true
							},
							function() {
								//Wyloguj
								<?php session_destroy(); ?>
								
								window.location.href = 'index.php';
							}
						);
						//]]>
					</script>
					<?php
				}
				else {
					?>
					<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat."<br />"; } ?></div>
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: null,
								text: '<?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?>',
								type: '<?php echo $typKomunikatu ?>',
								timer: 5000,
								showConfirmButton: false,
								html: true
							},
							function() {
								window.location.href = 'index.php?id=edytujprofil';
							}
						);
						//]]>
					</script>
					<?php
				}
			}
		}
	}
}

function postAddFacebookAccount($path, $userStatisticsDbName, $userData, $facebookAccountDbName)
{
	if($_POST) {
		echo "<br /><br /><br /><br /><br />";    //test
		echo "TEST<br />";    //test
		
		//Liczba kroków dla paska postępu
		$pasekLiczbaKrokow = 8;
		
		//Pasek postępu
		?>
		<div class="register_account_loading" style="width: 100%; position: absolute; top: 140px; left: 0px; z-index: 100;">
			<div class="container body-content">
				<div class="jumbotron" style="padding-top: 170px; padding-bottom: 170px;">
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="register_account_loading_bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
					</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
		//<![CDATA[
			function showProgressBar() {
				$(".register_account_loading").show();
			}
			
			function changeProgressBar() {
				var pasekLiczbaKrokow = Number('<?php echo $pasekLiczbaKrokow; ?>');
				var element = document.getElementById('register_account_loading_bar');
				var postepObecny = Number(element.getAttribute("aria-valuenow"));
				
				var krok = 100 / pasekLiczbaKrokow;
				var postepNowy = postepObecny + krok;
				var procent = postepNowy + "%";
				
				element.setAttribute("aria-valuenow", postepNowy);
				element.setAttribute("style", "width: " + procent + ";");
			}
			
			function hideProgressBar() {
				$(".register_account_loading").hide();
			}
			
			showProgressBar();
			changeProgressBar();
		 //]]>
		</script>
		<?php
		
		//Zczytanie danych
		$tytul = !isset($_REQUEST['tytul']) ? "" : cleardata($_REQUEST['tytul']);
		$imie = cleardata($_POST['imie']);
		$nazwisko = cleardata($_POST['nazwisko']);
		$nazwiskoPanienskie = cleardata($_POST['nazwisko_panienskie']);
		$plec = cleardata($_POST['plec']);
		$dataUrodzenia = cleardata($_POST['data_urodzenia']);
		$kraj = cleardata($_POST['kraj']);
		$miasto = cleardata($_POST['miasto']);
		$ulica = cleardata($_POST['ulica']);
		$numerDomu = cleardata($_POST['numer_domu']);
		$poczta = cleardata($_POST['poczta']);
		$firma = cleardata($_POST['firma']);
		$wzrost = cleardata($_POST['wzrost']);
		$waga = cleardata($_POST['waga']);
		$wlosy = cleardata($_POST['wlosy']);
		$oczy = cleardata($_POST['oczy']);
		$krew = cleardata($_POST['krew']);
		$sport = cleardata($_POST['sport']);
		$kolor = cleardata($_POST['kolor']);
		$szerokoscGeograficzna = cleardata($_POST['szerokosc_geograficzna']);
		$dlugoscGeograficzna = cleardata($_POST['dlugosc_geograficzna']);
		$telefonKomorkowy = cleardata($_POST['telefon_komorkowy']);
		$telefonStacjonarny = cleardata($_POST['telefon_stacjonarny']);
		$email = cleardata($_POST['email']);
		$login = cleardata($_POST['login']);
		$password = cleardata($_POST['password'], false);
		$passwordVerify = cleardata($_POST['password_v'], false);
		$zdjecie = cleardata($_POST['zdjecie']);
		$sciezka = cleardata($_POST['sciezka']);
		$uuid = cleardata($_POST['uuid']);
		$dataUrl = cleardata($_POST['data_url']);
		$emailUrl = cleardata($_POST['email_url']);
		$rejestracjaRok = cleardata($_POST['rok']);
		$rejestracjaMiesiac = cleardata($_POST['miesiac']);
		$rejestracjaDzien = cleardata($_POST['dzien']);
		$rejestracjaGodzina = cleardata($_POST['godzina']);
		$rejestracjaMinuta = cleardata($_POST['minuta']);
		$rejestracjaSekunda = cleardata($_POST['sekunda']);
		
		//Tablica błędów
		$errors = array();
		
		//Data urodzenia
		$dataUrodzeniaRok = substr($dataUrodzenia, 0, 4);
		$dataUrodzeniaMiesiac = substr($dataUrodzenia, 5, 2);
		$dataUrodzeniaDzien = substr($dataUrodzenia, 8, 2);
		
		//Data rejestracji
		$rejestracjaData = (int)$rejestracjaRok."-".(int)$rejestracjaMiesiac."-".(int)$rejestracjaDzien;
		$rejestracjaCzas = (int)$rejestracjaGodzina.":".(int)$rejestracjaMinuta.":".(int)$rejestracjaSekunda;
		
		//Podstawowa walidacja formularza
		if(empty($imie) || empty($nazwisko) || empty($nazwiskoPanienskie) || empty($plec) ||
		   empty($dataUrodzeniaRok) || empty($dataUrodzeniaMiesiac) || empty($dataUrodzeniaDzien) || empty($kraj) ||
		   empty($miasto) || empty($ulica) || empty($numerDomu) || empty($poczta) ||
		   empty($wzrost) || empty($waga) || empty($wlosy) || empty($oczy) ||
		   empty($krew) || empty($sport) || empty($kolor) || empty($szerokoscGeograficzna) ||
		   empty($dlugoscGeograficzna) || empty($telefonKomorkowy) || empty($email) || empty($login) ||
		   empty($password) || empty($passwordVerify) || empty($uuid) || empty($dataUrl) ||
		   empty($emailUrl) || empty($rejestracjaRok) || empty($rejestracjaMiesiac) || empty($rejestracjaDzien) ||
		   ($rejestracjaGodzina == null) || ($rejestracjaMinuta == null) || ($rejestracjaSekunda == null)) {
			$errors[] = 'Proszę wypełnić wszystkie pola';
		}
		
		//Pasek postępu
		?>
		<script type="text/javascript">
		//<![CDATA[
			changeProgressBar();
		 //]]>
		</script>
		<?php
		
		//Sprawdź czy użytkownik jest pełnoletni
		$lata = $rejestracjaRok - $dataUrodzeniaRok;
		$miesiace = $rejestracjaMiesiac - $dataUrodzeniaMiesiac;
		$dni = $rejestracjaDzien - $dataUrodzeniaDzien;
		$wiek = $lata + ($miesiace / 12) + ($dni / 365);
		if($wiek < 18) {
			$errors[] = 'Użytkownik jest niepełnoletni';
		}
		
		//Sprawdź czy podany przez użytkownika adres e-mail jest poprawny
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'Podany adres e-mail jest niepoprawny';
		}
		
		//Sprawdź czy podane przez użytkownika adres e-mail lub login nie są zajęte
		$checkEmail = checkbusy($path, $facebookAccountDbName, array("mail", "email"), $email);
		$checkLogin = checkbusy($path, $facebookAccountDbName, array("logowanie", "login"), $login);
		
		if($checkEmail > 0) {
			//$errors[] = 'Podany adres e-mail jest już używany';
		}
		if($checkLogin > 0) {
			//$errors[] = 'Podany login jest już zajęty';
		}
		
		if($password != $passwordVerify) {
			$errors[] = 'Podane hasła się nie zgadzają';
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Pasek postępu
			?>
			<script type="text/javascript">
			//<![CDATA[
				changeProgressBar();
			 //]]>
			</script>
			<?php
			
			//Jeżeli nie ma błędów to przechodzimy dalej
			$responseList = listofdocument($path, $facebookAccountDbName);	//Wyznaczanie nowego ID dokumentu
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileMax = 0;
			for($i=0; $i<$ile; $i++) {
				$name = $arrayList['rows'][$i]['id'];
				$numer = substr($name, 5);
				$ileMax = max($ileMax, $numer);
			}
			
			$documentId = 'konto'.($ileMax + 1);
			
			$accountId = "";
			
			//Tworzenie nowego dokumentu
			$accountDocument = array(
				'_id' => $documentId,
				'uuid' => $uuid,
				'tytul' => $tytul,
				'imie' => $imie,
				'nazwisko' => $nazwisko,
				'nazwisko_panienskie' => $nazwiskoPanienskie,
				'plec' => $plec,
				'data_urodzenia' => array(
					'data_urodzenia_rok' => (int)$dataUrodzeniaRok,
					'data_urodzenia_miesiac' => (int)$dataUrodzeniaMiesiac,
					'data_urodzenia_dzien' => (int)$dataUrodzeniaDzien
				),
				'adres' => array(
					'kraj' => $kraj,
					'miasto' => $miasto,
					'ulica' => $ulica,
					'numer_domu' => $numerDomu,
					'poczta' => $poczta
				),
				'firma' => $firma,
				'wzrost' => $wzrost,
				'waga' => $waga,
				'wlosy' => $wlosy,
				'oczy' => $oczy,
				'krew' => $krew,
				'sport' => $sport,
				'kolor' => $kolor,
				'telefon' => array(
					'telefon_komorkowy' => $telefonKomorkowy,
					'telefon_stacjonarny' => $telefonStacjonarny
				),
				'mail' => array(
					'email' => $email,
					'email_url' => $emailUrl
				),
				'logowanie' => array(
					'id' => $accountId,
					'login' => $login,
					'password' => $password,
					'typ' => 'niepotwierdzony'
				),
				'statystyki' => array(
					'status' => 'utworzone',
					'ilosc_uzyc' => 0
				),
				'geolocation_data' => array(
					'szerokosc_geograficzna' => $szerokoscGeograficzna,
					'dlugosc_geograficzna' => $dlugoscGeograficzna
				),
				'rejestracja' => array(
					'rejestracja_rok' => (int)$rejestracjaRok,
					'rejestracja_miesiac' => (int)$rejestracjaMiesiac,
					'rejestracja_dzien' => (int)$rejestracjaDzien,
					'rejestracja_godzina' => (int)$rejestracjaGodzina,
					'rejestracja_minuta' => (int)$rejestracjaMinuta,
					'rejestracja_sekunda' => (int)$rejestracjaSekunda
				)
			);
			
			//Zmiana ukośników ze ścieżki
			$sciezka = str_replace('^\\', '/', $sciezka);
			$sciezka = str_replace('\\', '/', $sciezka);
			$sciezka = str_replace('//', '/', $sciezka);
			
			if(!empty($sciezka) && !empty($zdjecie)) {
				if($sciezka != 'C:/fakepath/'.$zdjecie) {
					$poz = strripos($sciezka, '/');
					
					$repository = substr($sciezka, 0, $poz+1);
					$attachment = substr($sciezka, $poz+1);
				}
				else {
					$repository = 'C:/';
					$attachment = $zdjecie;
				}
			}
			else {
				$repository = null;
				$attachment = null;
			}
			
			//Pasek postępu
			?>
			<script type="text/javascript">
			//<![CDATA[
				changeProgressBar();
			 //]]>
			</script>
			<?php
			
			//Zapis nowego dokumentu z informacjami o koncie na facebook-u do bazy danych
			/*$response = createdocument($path, $facebookAccountDbName, $accountDocument);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createOne = array_key_exists('error', $array);
			*/
			$createOne = false;		//test
			
			//Zapis załącznika o koncie na facebook-u do bazy danych
			if(($repository != null) && ($attachment != null)) {
				//$response = createdocumentwithattachment($path, $facebookAccountDbName, $documentId, $repository, $attachment);
				
				$result = string_to_array($response, true);
				$array = $result[0];
				$createTwo = array_key_exists('error', $array);
			}
			else {
				$createTwo = false;
			}
			
			if($createOne == true || $createTwo == true) {
				$errors[] = 'Wystąpił błąd przy rejestrowaniu konta użytkownika';
				$errors[] = 'Błąd przy zapisie nowego konta do bazy danych';
				
				?>
				<div class="validation_error-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Pasek postępu
				?>
				<script type="text/javascript">
				//<![CDATA[
					changeProgressBar();
				 //]]>
				</script>
				<?php
				
				//Dodanie funkcji facebook-a
				require 'facebook/datafun.php';
				
				echo "dataUrl = ".$dataUrl."<br />";	//test
				echo "password = ".$password."<br />";	//test
				echo "emailUrl = ".$emailUrl."<br />";	//test
				$registerData = registerFacebookAccount($path, $userStatisticsDbName, $userData['_id'], $imie, $nazwisko, $plec, $dataUrodzeniaRok, $dataUrodzeniaMiesiac, $dataUrodzeniaDzien, $telefonKomorkowy, $email, $login, $password);
				//echo "registerData = ".serialize($registerData)."<br />";	//test
				//$registerData = json_decode($registerData);	//test
				foreach($registerData as $key => $value) {
					echo $key." => ".$value."<br />";	//test
				}
				$registerStatus = $registerData[0];
				$registerStatus = true;		//test
				
				//Pasek postępu
				?>
				<script type="text/javascript">
				//<![CDATA[
					changeProgressBar();
				 //]]>
				</script>
				<?php
				
				$activateData = activateFacebookAccount($path, $userStatisticsDbName, $documentId, $email, $emailUrl);
				//echo "activateData = ".serialize($activateData)."<br />";	//test
				foreach($activateData as $key => $value) {
					echo $key." => ".$value."<br />";	//test
				}
				$activateStatus = $activateData[0];
				$accountId = $activateData[5];
				$activateStatus = true;		//test
				
				if($registerStatus == false || $activateStatus == false) {
					$errors[] = 'Wystąpił błąd przy rejestrowaniu konta użytkownika';
					$errors[] = 'Błąd przy aktywacji nowego konta';
					
					?>
					<div class="validation_error-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
					<script type="text/javascript">
					//<![CDATA[
						swal( {
								title: 'Uwaga',
								text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
								type: 'error',
								confirmButtonColor: '#DD6B55',
								closeOnConfirm: false
							},
							function(isConfirm) {
								if(isConfirm) {
									history.back();
								}
							}
						);
						//]]>
					</script>
					<?php
				}
				else {
					//Pasek postępu
					?>
					<script type="text/javascript">
					//<![CDATA[
						changeProgressBar();
					 //]]>
					</script>
					<?php
					
					//zmianie statusu konta Facebook-a
					$document = array(
						'logowanie' => array(
							'id' => $accountId,
							'login' => $login,
							'password' => $password,
							'typ' => 'potwierdzony'
						)
					);
					
					//$zmianaTypu = updatedocument($path, $facebookAccountDbName, $documentId, $document);
					$zmianaTypu = true;		//test
					
					if($zmianaTypu == false) {
						$errors[] = 'Wystąpił błąd przy rejestrowaniu konta użytkownika';
						$errors[] = 'Błąd przy zmianie typu nowego konta w bazie danych';
						
						?>
						<div class="validation_error-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
						<script type="text/javascript">
						//<![CDATA[
							swal( {
									title: 'Uwaga',
									text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
									type: 'error',
									confirmButtonColor: '#DD6B55',
									closeOnConfirm: false
								},
								function(isConfirm) {
									if(isConfirm) {
										history.back();
									}
								}
							);
							//]]>
						</script>
						<?php
					}
					else {
						//Pasek postępu
						?>
						<script type="text/javascript">
						//<![CDATA[
							changeProgressBar();
						 //]]>
						</script>
						<?php
						
						$komunikat = 'Konto użytkownika '.$imie.' '.$nazwisko.' zostało poprawnie zarejestrowane';
						
						?>
						<div class="success-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat; ?></div>
						<script type="text/javascript">
						//<![CDATA[
							/*swal( {
									title: null,
									text: '<?php echo $komunikat; ?>\n',
									type: 'success',
									timer: 5000,
									showConfirmButton: false,
									html: true
								},
								function() {
									hideProgressBar();
									window.location.href = 'index.php?id=dodajkonto';
								}
							);*/
							//]]>
						</script>
						<?php
					}
				}
			}
		}
	}
}

function postAddFacebookPhoto($path, $userStatisticsDbName, $userData, $facebookAccountDbName)
{
	if($_POST) {
		//Liczba kroków dla paska postępu
		$pasekLiczbaKrokow = 4;
		
		//Pasek postępu
		?>
		<div class="add_photo_loading" style="width: 100%; position: absolute; top: 140px; left: 0px; z-index: 100;">
			<div class="container body-content">
				<div class="jumbotron" style="padding-top: 170px; padding-bottom: 170px;">
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="add_photo_loading_bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
					</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
		//<![CDATA[
			function showProgressBar() {
				$(".add_photo_loading").show();
			}
			
			function changeProgressBar() {
				var pasekLiczbaKrokow = Number('<?php echo $pasekLiczbaKrokow; ?>');
				var element = document.getElementById('add_photo_loading_bar');
				var postepObecny = Number(element.getAttribute("aria-valuenow"));
				
				var krok = 100 / pasekLiczbaKrokow;
				var postepNowy = postepObecny + krok;
				var procent = postepNowy + "%";
				
				element.setAttribute("aria-valuenow", postepNowy);
				element.setAttribute("style", "width: " + procent + ";");
			}
			
			function hideProgressBar() {
				$(".add_photo_loading").hide();
			}
			
			showProgressBar();
			changeProgressBar();
		 //]]>
		</script>
		<?php
		
		//Zczytanie danych
		$nazwa = cleardata($_POST['nazwa']);
		$link = $_POST['link'];
		$userId = cleardata($_POST['user']);
		$dodanieRok = cleardata($_POST['rok']);
		$dodanieMiesiac = cleardata($_POST['miesiac']);
		$dodanieDzien = cleardata($_POST['dzien']);
		$dodanieGodzina = cleardata($_POST['godzina']);
		$dodanieMinuta = cleardata($_POST['minuta']);
		$dodanieSekunda = cleardata($_POST['sekunda']);
		
		//Tablica błędów
		$errors = array();
		
		//Data dodania
		$dodanieData = (int)$dodanieRok."-".(int)$dodanieMiesiac."-".(int)$dodanieDzien;
		$dodanieCzas = (int)$dodanieGodzina.":".(int)$dodanieMinuta.":".(int)$dodanieSekunda;
		
		//Podstawowa walidacja formularza
		if(empty($nazwa) || empty($link) || empty($userId) || empty($dodanieRok) ||
		   empty($dodanieMiesiac) || empty($dodanieDzien) || ($dodanieGodzina == null) || ($dodanieMinuta == null) ||
		   ($dodanieSekunda == null)) {
			$errors[] = 'Proszę wypełnić wszystkie pola';
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Pasek postępu
			?>
			<script type="text/javascript">
			//<![CDATA[
				changeProgressBar();
			 //]]>
			</script>
			<?php
			
			//Dodanie funkcji facebook-a
			require 'facebook/datafun.php';
			
			//Pobranie danych logowania
			$accountData = getspecialdata($path, $facebookAccountDbName, 'konto1', array('mail', 'logowanie'));
			$email = $accountData[0]['email'];
			$accountId =  $accountData[1]['id'];
			$login = $accountData[1]['login'];
			$password = $accountData[1]['password'];
			
			$autologin = false;
			if($autologin == true) {
				$loginData = loginFacebookAccount($path, $userStatisticsDbName, $userData['_id'], $accountId, $email, $login, $password);
				
				$loginStatus = $loginData[0];
			}
			else {
				$loginStatus = true;
			}
			
			//Pasek postępu
			?>
			<script type="text/javascript">
			//<![CDATA[
				changeProgressBar();
			 //]]>
			</script>
			<?php
			
			$addData = addFacebookPhoto($path, $userStatisticsDbName, $userId, $link);
			$addStatus = $addData[0];
			$photoId = $addData[2];
			$photoUser = $addData[3];
			$photoDescription = $addData[4];
			$photoUrl = $addData[5];
			$addStatus = true;    //test
			
			if($loginStatus == false || $addStatus == false) {
				$errors[] = 'Wystąpił błąd przy dodawaniu zdjęcia';
				$errors[] = 'Błąd przy pobieraniu informacji o zdjęciu';
				
				?>
				<div class="validation_error-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Pasek postępu
				?>
				<script type="text/javascript">
				//<![CDATA[
					changeProgressBar();
				 //]]>
				</script>
				<?php
				
				//Jeżeli nie ma błędów to przechodzimy dalej
				$response = true;
				
				//Wysłanie hasła
				?>
				<script type="text/javascript">
				//<![CDATA[
					var n = '<?php echo encodeUrlData($nazwa); ?>';
					var l = '<?php echo encodeUrlData($link); ?>';
					var u = '<?php echo $userId; ?>';
					var pid = '<?php echo $photoId; ?>';
					var pu = '<?php echo encodeUrlData($photoUser); ?>';
					var pd = '<?php echo encodeUrlData($photoDescription); ?>';
					var purl = '<?php echo encodeUrlData($photoUrl); ?>';
					var drok = '<?php echo $dodanieRok; ?>';
					var dmie = '<?php echo $dodanieMiesiac; ?>';
					var ddzi = '<?php echo $dodanieDzien; ?>';
					var dgodz = '<?php echo $dodanieGodzina; ?>';
					var dmin = '<?php echo $dodanieMinuta; ?>';
					var dsek = '<?php echo $dodanieSekunda; ?>';
					var r = '<?php echo $response; ?>';
					
					var adres = 'index.php?id=dodajzdjeciepotwierdz&n=' + n + '&l=' + l + '&u=' + u + '&pid=' + pid + '&pu=' + pu + '&pd=' + pd + '&purl=' + purl + '&drok=' + drok + '&dmie=' + dmie + '&ddzi=' + ddzi + '&dgodz=' + dgodz + '&dmin=' + dmin + '&dsek=' + dsek + '&r=' + r;
					
					window.location.href = adres;
					//]]>
				</script>
				<?php
			}
		}
	}
}

function postAddFacebookPhotoConfirm($path, $facebookPhotoDbName)
{
	if($_POST) {
		//Liczba kroków dla paska postępu
		$pasekLiczbaKrokow = 4;
		
		//Pasek postępu
		?>
		<div class="add_photo_loading" style="width: 100%; position: absolute; top: 140px; left: 0px; z-index: 100;">
			<div class="container body-content">
				<div class="jumbotron" style="padding-top: 170px; padding-bottom: 170px;">
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="add_photo_loading_bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
					</div>
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
		//<![CDATA[
			function showProgressBar() {
				$(".add_photo_loading").show();
			}
			
			function changeProgressBar() {
				var pasekLiczbaKrokow = Number('<?php echo $pasekLiczbaKrokow; ?>');
				var element = document.getElementById('add_photo_loading_bar');
				var postepObecny = Number(element.getAttribute("aria-valuenow"));
				
				var krok = 100 / pasekLiczbaKrokow;
				var postepNowy = postepObecny + krok;
				var procent = postepNowy + "%";
				
				element.setAttribute("aria-valuenow", postepNowy);
				element.setAttribute("style", "width: " + procent + ";");
			}
			
			function hideProgressBar() {
				$(".add_photo_loading").hide();
			}
			
			showProgressBar();
			changeProgressBar();
		 //]]>
		</script>
		<?php
		
		//Zczytanie danych
		$nazwa = cleardata($_POST['nazwa']);
		$link = $_POST['link'];
		$userId = cleardata($_POST['user']);
		$photoId = cleardata($_POST['photo_id']);
		$photoUser = cleardata($_POST['photo_user']);
		$photoDescription = cleardata($_POST['photo_description']);
		$photoUrl = $_POST['photo_url'];
		$dodanieRok = cleardata($_POST['rok']);
		$dodanieMiesiac = cleardata($_POST['miesiac']);
		$dodanieDzien = cleardata($_POST['dzien']);
		$dodanieGodzina = cleardata($_POST['godzina']);
		$dodanieMinuta = cleardata($_POST['minuta']);
		$dodanieSekunda = cleardata($_POST['sekunda']);
		
		//Tablica błędów
		$errors = array();
		
		//Data dodania
		$dodanieData = (int)$dodanieRok."-".(int)$dodanieMiesiac."-".(int)$dodanieDzien;
		$dodanieCzas = (int)$dodanieGodzina.":".(int)$dodanieMinuta.":".(int)$dodanieSekunda;
		
		//Podstawowa walidacja formularza
		if(empty($nazwa) || empty($link) || empty($userId) || empty($photoId) ||
		   empty($photoUser) || empty($photoDescription) || empty($photoUrl) || empty($dodanieRok) ||
		   empty($dodanieMiesiac) || empty($dodanieDzien) || ($dodanieGodzina == null) || ($dodanieMinuta == null) ||
		   ($dodanieSekunda == null)) {
			$errors[] = 'Proszę wypełnić wszystkie pola';
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Pasek postępu
			?>
			<script type="text/javascript">
			//<![CDATA[
				changeProgressBar();
			 //]]>
			</script>
			<?php
			
			//Jeżeli nie ma błędów to przechodzimy dalej
			$responseList = listofdocument($path, $facebookPhotoDbName);	//Wyznaczanie nowego ID dokumentu
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileMax = 0;
			for($i=0; $i<$ile; $i++) {
				$name = $arrayList['rows'][$i]['id'];
				$numer = substr($name, 7);
				$ileMax = max($ileMax, $numer);
			}
			
			$documentId = 'zdjecie'.($ileMax + 1);
			
			//Tworzenie nowego dokumentu
			$photoDocument = array(
				'_id' => $documentId,
				'nazwa' => $nazwa,
				'link' => $link,
				'user_id' => $userId,
				'photo_id' => $photoId,
				'photo_user' => $photoUser,
				'photo_description' => $photoDescription,
				'photo_url' => $photoUrl,
				'statystyki' => array(
					'status' => 'dodane',
					'ilosc_uzyc' => 0
				),
				'dodanie' => array(
					'dodanie_rok' => (int)$dodanieRok,
					'dodanie_miesiac' => (int)$dodanieMiesiac,
					'dodanie_dzien' => (int)$dodanieDzien,
					'dodanie_godzina' => (int)$dodanieGodzina,
					'dodanie_minuta' => (int)$dodanieMinuta,
					'dodanie_sekunda' => (int)$dodanieSekunda
				)
			);
			
			//Pasek postępu
			?>
			<script type="text/javascript">
			//<![CDATA[
				changeProgressBar();
			 //]]>
			</script>
			<?php
			
			//Zapis nowego dokumentu z informacjami o zdjęciu na facebook-u do bazy danych
			$response = createdocument($path, $facebookPhotoDbName, $photoDocument);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$create = array_key_exists('error', $array);
			
			if($create == true) {
				$errors[] = 'Wystąpił błąd przy dodawaniu zdjęcia';
				$errors[] = 'Błąd przy zapisie informacji o zdjęciu do bazy danych';
				
				?>
				<div class="validation_error-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Pasek postępu
				?>
				<script type="text/javascript">
				//<![CDATA[
					changeProgressBar();
				 //]]>
				</script>
				<?php
				
				$komunikat = 'Zdjęcie '.$nazwa.' zostało poprawnie dodane';
				
				?>
				<div class="success-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: null,
							text: '<?php echo $komunikat; ?>\n',
							type: 'success',
							timer: 5000,
							showConfirmButton: false,
							html: true
						},
						function() {
							hideProgressBar();
							window.location.href = 'index.php?id=dodajzdjecie';
						}
					);
					//]]>
				</script>
				<?php
			}
		}
	}
}

function postSendTextMessage()
{
	if($_POST) {
		//Zczytanie danych
		$maxDlugosc = cleardata($_POST['max']);
		$path = cleardata($_POST['path']);
		$nazwaOdbiorcy = cleardata($_POST['nazwa']);
		$krajOdbiorcy = cleardata($_POST['kraj']);
		$numerOdbiorcy = cleardata($_POST['telefon']);
		$wiadomosc = cleardata($_POST['wiadomosc']);
		$typ = cleardata($_POST['typ']);
		$zaPomocaClickatell = cleardata($_POST['c']);
		$zaPomocaMailera = cleardata($_POST['m']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($maxDlugosc) && empty($path) && empty($nazwaOdbiorcy) && empty($krajOdbiorcy) &&
		   empty($numerOdbiorcy) && empty($wiadomosc) && empty($typ) && empty($zaPomocaClickatell) &&
		   empty($zaPomocaMailera)) {
			$errors[] = 'Należy podać dane do wysłania wiadomości';
		}
		
		if(!empty($maxDlugosc)) {
			//Sprawdź czy podana maksymalna długość jest poprawna
			if(!preg_match('/^([0-9]){1,}$/i', $maxDlugosc)) {
				$errors[] = 'Podana maksymalna długość jest nie poprawna';
			}
			
			$maxDlugosc = (int)$maxDlugosc;
		}
		
		if(!empty($nazwaOdbiorcy)) {
			//Sprawdź czy podana przez użytkownika nazwa odbiorcy nie jest za długa
			if(strlen($nazwaOdbiorcy) > 20) {
				$errors[] = 'Podana nazwa odbiorcy jest za długa';
			}
		}
		
		if(!empty($numerOdbiorcy)) {
			//Usuwanie spacji z numeru telefonu
			$numerOdbiorcy = str_replace(' ', '', $numerOdbiorcy);
			
			//Sprawdź czy podany przez użytkownika numer odbiorcy nie jest za krótki
			if(strlen($numerOdbiorcy) < 9) {
				$errors[] = 'Podany numer odbiorcy jest za krótki';
			}
			
			//Sprawdź czy podany przez użytkownika numer odbiorcy nie jest za długi
			if(strlen($numerOdbiorcy) > 15) {
				$errors[] = 'Podany numer odbiorcy jest za długi';
			}
			
			//Sprawdź czy podany przez użytkownika numer odbiorcy jest poprawny
			if(!preg_match('/^([0-9]){9,15}$/i', $numerOdbiorcy)) {
				$errors[] = 'Podany numer odbiorcy jest nie poprawny';
			}
		}
		
		if(!empty($wiadomosc)) {
			//Sprawdź czy podana przez użytkownika wiadomość nie jest za długa
			if(strlen($wiadomosc) > 500) {
				$errors[] = 'Podana wiadomość jest za długa';
			}
			
			//Usuwanie znaków nowej linii z wiadomości
			$tekst = preg_replace("'\n|\r\n|\r'si", "<br />", $wiadomosc);
			$tekst = str_replace('<br />^<br />', '<br />', $tekst);
			$wiadomosc = preg_replace("'\n|\r\n|\r'si", "<br />", $wiadomosc);
			$wiadomosc = str_replace('<br />^<br />', ' ', $wiadomosc);
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$response = null;
			
			//Inicjalizacja mailer-a
			require 'mailer/config.php';
			
			//Wysłanie wiadomości
			$response = sendsms($path, $adresNadawcySms, $nazwaNadawcySms, $hasloNadawcySms, $numerOdbiorcy, $nazwaOdbiorcy, $krajOdbiorcy, $wiadomosc, $typ, $zaPomocaClickatell, $zaPomocaMailera);
			
			if($response == null || $response == '' || $response == false) {
				$error = "Wystąpił błąd podczas wysyłania wiadomości";
				
				?>
				<div class="error-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php echo $error; ?>\n',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				//Pobranie aktualnej daty i czasu
				$rok = date('Y');
				$miesiac = date('m');
				$dzien = date('d');
				$godzina = date('H');
				$minuta = date('i');
				$sekunda = date('s');
				
				//Tworzenie  aktualnej daty i czasu
				$aktualnaData = $rok.'-'.$miesiac.'-'.$dzien;
				$aktualnyCzas = $godzina.':'.$minuta.':'.$sekunda;
				
				$komunikat = 'Wiadomość została wysłana';
				
				?>
				<div class="validation_success-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					var d = '<?php echo $aktualnaData; ?>';
					var c = '<?php echo $aktualnyCzas; ?>';
					var n = '<?php echo $nazwaOdbiorcy; ?>';
					var t = '<?php echo $numerOdbiorcy; ?>';
					var w = '<?php echo $tekst; ?>';
					var r = '<?php echo $response; ?>';
					
					var adres = 'index.php?id=testbramkisms&d=' + d + '&c=' + c + '&n=' + n + '&t=' + t + '&w=' + w + '&r=' + r;
					
					swal( {
							title: null,
							text: '<?php echo $komunikat; ?>\n',
							type: 'success',
							timer: 5000,
							showConfirmButton: false,
							html: true
						},
						function() {
							window.location.href = adres;
						}
					);
					//]]>
				</script>
				<?php
			}
		}
	}
}

function postAdvancedDatabaseEdit($path) {
	if($_POST) {
		//Zczytanie danych
		$haslo = cleardata($_POST['haslo']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($haslo)) {
			$errors[] = 'Należy podać hasło';
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$response = true;
			
			//Wysłanie hasła
			?>
			<script type="text/javascript">
			//<![CDATA[
				var h = '<?php echo $haslo; ?>';
				var r = '<?php echo $response; ?>';
				
				var adres = 'index.php?id=zaawansowanaedycjabazydanych&h=' + h + '&r=' + r;
				
				window.location.href = adres;
				//]]>
			</script>
			<?php
		}
	}
}

function postEditAdminSettings($path)
{
	if($_POST) {
		//Zczytanie danych podstawowych
		$ile = cleardata($_POST['ile']);
		
		//Zczytanie danych
		$daneNazwa = [];
		$daneWartosc = [];
		for($i=0; $i<$ile; $i++) {
			$numer = $i+1;
			
			$zmienna = cleardata($_POST['vn'.$numer]);
			$wartosc = cleardata($_POST['vc'.$numer]);
			
			$daneNazwa[$i] = $zmienna;
			$daneWartosc[$i] = $wartosc;
			//${$zmienna} = $wartosc;
			//echo '$'.$zmienna.'='.${$zmienna}.'<br />';
		}
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		$blad = false;
		for($i=0; $i<$ile; $i++) {
			if(empty($daneNazwa[$i]) && empty($daneWartosc[$i])) {
				$blad = true;
			}
		}
		if($blad == true) {
			$errors[] = 'Należy podać dane do edycji';
		}
		
		//Dodatkowa walidacja formularza
		for($i=0; $i<$ile; $i++) {
			switch ($daneNazwa[$i]) {
				case '_rev':
					break;
				case 'auto_logout_time':
					if($daneWartosc[$i] < 5 || $daneWartosc[$i] > 60) {
						$errors[] = 'Podana wartość czasu automatycznego wylogowania jest nieprawidłowa';
					}
					break;
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error."<br />"; } ?></div>
			<script type="text/javascript">
			//<![CDATA[
				swal( {
						title: 'Uwaga',
						text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
						type: 'warning',
						confirmButtonColor: '#DD6B55',
						closeOnConfirm: false
					},
					function(isConfirm) {
						if(isConfirm) {
							history.back();
						}
					}
				);
				//]]>
			</script>
			<?php
		}
		else {
			//Jeżeli nie ma błędów to przechodzimy dalej
			$updateOk = false;
			$updateError = false;
			
			//Tworzenie pustego dokumentu
			$document = array();
			
			//Zapisywanie dokumentu
			for($i=0; $i<$ile; $i++) {
				if(!empty($daneNazwa[$i]) && !empty($daneWartosc[$i])) {
					$document[$daneNazwa[$i]] = $daneWartosc[$i];
				}
			}
			
			//Pobranie aktualnej daty i czasu
			$rok = date('Y');
			$miesiac = date('m');
			$dzien = date('d');
			$godzina = date('H');
			$minuta = date('i');
			$sekunda = date('s');
			$mikrosekunda = (float)microtime(false);
			$mikrosekunda = substr($mikrosekunda, 2);
			$strefaCzasowa = date('P');
			$czasLetni = date('I');
			
			//Tworzenie  aktualnej daty i czasu
			$document['change_date'] = array(
				'rok' => $rok,
				'miesiac' => $miesiac,
				'dzien' => $dzien,
				'godzina' => $godzina,
				'minuta' => $minuta,
				'sekunda' => $sekunda,
				'mikrosekunda' => (int)$mikrosekunda,
				'strefa_czasowa' => $strefaCzasowa,
				'czas_letni' => (boolean)$czasLetni
			);
			
			//Zapis dokumentu z wrażliwymi ustawieniami systemu do bazy danych
			$response = updatedocument($path, 'facelike', 'config', $document);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$updateOk = array_key_exists('ok', $array);
			$updateError = array_key_exists('error', $array);
			
			if($updateOk == false || $updateError == true) {
				$error = "Wystąpił błąd podczas aktualizacji ustawień systemu";
				
				?>
				<div class="error-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$error; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: 'Uwaga',
							text: '<?php echo $error; ?>\n',
							type: 'error',
							confirmButtonColor: '#DD6B55',
							closeOnConfirm: false
						},
						function(isConfirm) {
							if(isConfirm) {
								history.back();
							}
						}
					);
					//]]>
				</script>
				<?php
			}
			else {
				$komunikat = 'Ustawienia systemu zostały poprawnie zmienione';
				
				?>
				<div class="validation_success-box"><?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$komunikat; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					swal( {
							title: null,
							text: '<?php echo $komunikat; ?>\n',
							type: 'success',
							timer: 5000,
							showConfirmButton: false,
							html: true
						},
						function() {
							window.location.href = 'index.php?id=ustawieniaadministratora';
						}
					);
					//]]>
				</script>
				<?php
			}
		}
	}
}
?>
