<?php
function postRegister($path, $userSecurityDbName, $userDataDbName)
{
	if ($_POST) {
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			
			$ileMax = max($ileOne, $ileTwo);
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
			$document = array(
				'_id' => $documentId,
				'login' => $login,
				'password' => $password,
				'typ' => 'niepotwierdzony',
				'mkey' => $mKey
			);
			
			//Zapis nowego dokumentu z informacjami o użytkowniku do bazy danych
			$response = createdocument($path, $userDataDbName, $userDocument);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createOne = array_key_exists('error', $array);
			
			//Zapis nowego dokumentu z wrażliwymi informacjami o użytkowniku do bazy danych
			$response = createdocument($path, $userSecurityDbName, $document);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createTwo = array_key_exists('error', $array);
			
			if($createOne == true || $createTwo == true) {
				$error = "Wystąpił błąd przy rejestrowaniu użytkownika";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
					<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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
					<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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

function postLogin($path, $userSecurityDbName)
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
			changeloginstatus($path, $userSecurityDbName, $auth[1], 0);
		}
		else {
			$type = "niepotwierdzony";
			$type = getusertype($path, $userSecurityDbName, $auth[1]);
			if($type == "niepotwierdzony") {
				$errors[] = 'Konto nie zostało jeszcze aktywowane';
				
				//Zmiana statusu logowania
				changeloginstatus($path, $userSecurityDbName, $auth[1], 1);
			}
		}
		
		if(empty($errors)) {
			//Logowanie
			createsession($auth[1], $path);
			
			//Zmiana statusu logowania
			changeloginstatus($path, $userSecurityDbName, $auth[1], 2);
			
			exit(header('Location: index.php'));
			//echo '<p class="success">Zostałeś zalogowany. Możesz przejść na <a href="index.php">stronę główną</a></p>';
		}
		else {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
				<div class="error-box"><?php echo $error; ?></div>
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
					<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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
					<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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

function postChangePassword($path, $userSecurityDbName, $userDataDbName)
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$document = array(
				'password' => $password,
				'loginstatus' => $loginStatus,
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
			
			//Zapisanie nowego hasła i tablicy ratunkowej do bazy danych
			$response = updatedocument($path, $userSecurityDbName, $userId, $document);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$updateOk = array_key_exists('ok', $array);
			$updateError = array_key_exists('error', $array);
			
			if($updateOk == true && $updateError == false) {
				//Auto logowanie
				createsession($userId, $path);
				
				//Zmiana statusu logowania
				changeloginstatus($path, $userSecurityDbName, $userId, 3);
				
				header('Location: index.php');	//Przekierowanie
				//echo '<p class="success">Zostałeś zalogowany. Możesz przejść na <a href="index.php">stronę główną</a></p>';
			}
			else {
				$komunikaty[] = 'Błąd przy zmianie hasła użytkownika';
				$komunikaty[] = 'Skontaktuj się z administratorem systemu w celu uzyskania pomocy';
				
				?>
				<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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
	if ($_POST) {
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$document = array();
			
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
				$document['typ'] = 'niepotwierdzony';
				$document['mkey'] = $mKey;
			}
			if(!empty($password)) {
				$logout = true;
				
				$password = password_hash($password, PASSWORD_BCRYPT);	//hashowanie (solenie) hasła
				
				$document['password'] = $password;
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
			if(!empty($document)) {
				//Zapis dokumentu z wrażliwymi informacjami o użytkowniku do bazy danych
				$response = updatedocument($path, $userSecurityDbName, $userId, $document);
				
				$result = string_to_array($response[1], true);
				$array = $result[0];
				
				$updateTwoOk = array_key_exists('ok', $array);
				$updateTwoError = array_key_exists('error', $array);
			}
			
			if(($updateOneOk == false && $updateTwoOk == false) || ($updateOneError == true || $updateTwoError == true)) {
				$error = "Wystąpił błąd podczas aktualizacji danych użytkownika";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
						<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
						<?php
					}
					else if($typKomunikatu == 'error') {
						?>
						<div class="validation_error-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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
					<div class="validation_success-box"><?php foreach ($komunikaty as $komunikat) { echo $komunikat."<br />"; } ?></div>
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

function postAddRoom($path, $roomDbName)
{
	if ($_POST) {
		//Zczytanie danych
		$nazwa = cleardata($_POST['nazwa']);
		$typ = cleardata($_POST['typ']);
		$pojemnosc = cleardata($_POST['pojemnosc']);
		$dlugosc = cleardata($_POST['dlugosc']);
		$szerokosc = cleardata($_POST['szerokosc']);
		$wysokosc = cleardata($_POST['wysokosc']);
		$tablica = cleardata($_POST['tablica']);
		$flipchart = cleardata($_POST['flipchart']);
		$projektor = cleardata($_POST['projektor']);
		$glosniki = !isset($_REQUEST['glosniki']) ? "" : cleardata($_REQUEST['glosniki']);
		$mikrofony = !isset($_REQUEST['mikrofony']) ? "" : cleardata($_REQUEST['mikrofony']);
		$przewodowy = !isset($_REQUEST['przewodowy']) ? "" : cleardata($_REQUEST['przewodowy']);
		$bezprzewodowy = !isset($_REQUEST['bezprzewodowy']) ? "" : cleardata($_REQUEST['bezprzewodowy']);
		$kraj = cleardata($_POST['kraj']);
		$wojewodztwo = cleardata($_POST['wojewodztwo']);
		$miasto = cleardata($_POST['miasto']);
		$ulica = cleardata($_POST['ulica']);
		$poczta = cleardata($_POST['poczta']);
		$zdjecie = cleardata($_POST['zdjecie']);
		$sciezka = cleardata($_POST['sciezka']);
		$rok = cleardata($_POST['rok']);
		$miesiac = cleardata($_POST['miesiac']);
		$dzien = cleardata($_POST['dzien']);
		$godzina = cleardata($_POST['godzina']);
		$minuta = cleardata($_POST['minuta']);
		$sekunda = cleardata($_POST['sekunda']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($nazwa) || empty($typ) || empty($pojemnosc) || empty($dlugosc) ||
		   empty($szerokosc) || empty($wysokosc) || empty($kraj) || empty($wojewodztwo) ||
		   empty($miasto) || empty($ulica) || empty($poczta) || empty($zdjecie) ||
		   empty($sciezka) || empty($rok) || empty($miesiac) || empty($dzien) ||
		   empty($godzina) || empty($minuta) || empty($sekunda)) {
			$errors[] = 'Proszę wypełnić wszystkie pola';
		}
		
		if(!empty($tablica)) {
			//Sprawdź czy podana przez użytkownika ilość tablic nie jest za mała
			if($tablica < 0) {
				$errors[] = 'Podana ilość tablic jest ujemna';
			}
			
			//Sprawdź czy podana przez użytkownika ilość tablic nie jest za duża
			if($tablica > 100) {
				$errors[] = 'Podany ilość tablic jest za duża';
			}
		}
		
		if(!empty($flipchart)) {
			//Sprawdź czy podana przez użytkownika ilość flipchartów nie jest za mała
			if($flipchart < 0) {
				$errors[] = 'Podana ilość flipchartów jest ujemna';
			}
			
			//Sprawdź czy podana przez użytkownika ilość flipchartów nie jest za duża
			if($flipchart > 100) {
				$errors[] = 'Podany ilość flipchartów jest za duża';
			}
		}
		
		if(!empty($projektor)) {
			//Sprawdź czy podana przez użytkownika ilość projektorów nie jest za mała
			if($projektor < 0) {
				$errors[] = 'Podana ilość projektorów jest ujemna';
			}
			
			//Sprawdź czy podana przez użytkownika ilość projektorów nie jest za duża
			if($projektor > 100) {
				$errors[] = 'Podany ilość projektorów jest za duża';
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$responseList = listofdocument($path, $roomDbName);	//Wyznaczanie nowego ID dokumentu
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileMax = 0;
			for($i=0; $i<$ile; $i++) {
				$name = $arrayList['rows'][$i]['id'];
				$numer = substr($name, 4);
				$ileMax = max($ileMax, $numer);
			}
			
			$documentId = 'sala'.($ileMax + 1);
			
			//Tworzenie pustego dokumentu
			$wyposazenie = array();
			
			//Zapisywanie dokumentu
			if($tablica > 0) {
				$wyposazenie['tablica'] = (int)$tablica;
			}
			if($flipchart > 0) {
				$wyposazenie['flipchart'] = (int)$flipchart;
			}
			if($projektor > 0) {
				$wyposazenie['projektor'] = (int)$projektor;
			}
			if($glosniki == "głośniki" && $mikrofony == "mikrofony") {
				$wyposazenie['naglosnienie'] = "glosniki i mikrofony";
			}
			else if($glosniki == "głośniki" && $mikrofony == "") {
				$wyposazenie['naglosnienie'] = "glosniki";
			}
			else if($glosniki == "" && $mikrofony == "mikrofony") {
				$wyposazenie['naglosnienie'] = "mikrofony";
			}
			if($przewodowy == "przewodowy" && $bezprzewodowy == "bezprzewodowy") {
				$wyposazenie['internet'] = "przewodowy i bezprzewodowy";
			}
			else if($przewodowy == "przewodowy" && $bezprzewodowy == "") {
				$wyposazenie['internet'] = "przewodowy";
			}
			else if($przewodowy == "" && $bezprzewodowy == "bezprzewodowy") {
				$wyposazenie['internet'] = "bezprzewodowy";
			}
			
			//Tworzenie nowego dokumentu
			$document = array(
				'_id' => $documentId,
				'nazwa' => $nazwa,
				'typ' => $typ,
				'pojemnosc' => (int)$pojemnosc,
				'wymiary' => array(
					'dlugosc' => (float)$dlugosc,
					'szerokosc' => (float)$szerokosc,
					'wysokosc' => (float)$wysokosc
				),
				'wyposazenie' => $wyposazenie,
				'adres' => array(
					'kraj' => $kraj,
					'wojewodztwo' => $wojewodztwo,
					'miasto' => $miasto,
					'ulica' => $ulica,
					'poczta' => $poczta
				),
				'rejestracja' => array(
					'rok' => (int)$rok,
					'miesiac' => (int)$miesiac,
					'dzien' => (int)$dzien,
					'godzina' => (int)$godzina,
					'minuta' => (int)$minuta,
					'sekunda' => (int)$sekunda
				)
			);
			
			//Zmiana ukośników ze ścieżki
			$sciezka = str_replace('^\\', '/', $sciezka);
			$sciezka = str_replace('\\', '/', $sciezka);
			$sciezka = str_replace('//', '/', $sciezka);
			
			if($sciezka != 'C:/fakepath/'.$zdjecie) {
				$poz = strripos($sciezka, '/');
				
				$repository = substr($sciezka, 0, $poz+1);
				$attachment = substr($sciezka, $poz+1);
			}
			else {
				$repository = 'C:/';
				$attachment = $zdjecie;
			}
			
			//Zapis nowego dokumentu z informacjami o sali do bazy danych
			$response = createdocument($path, $roomDbName, $document);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createOne = array_key_exists('error', $array);
			
			//Zapis załącznika o sali do bazy danych
			$response = createdocumentwithattachment($path, $roomDbName, $documentId, $repository, $attachment);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$createTwo = array_key_exists('error', $array);
			
			if($createOne == true || $createTwo == true) {
				$error = "Wystąpił błąd przy rejestrowaniu sali";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
				$komunikat = 'Sala o nazwie '.$nazwa.' została poprawnie zarejestrowany';
				
				?>
				<div class="success-box"><?php echo $komunikat; ?></div>
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
							window.location.href = 'index.php?id=dodajsale';
						}
					);
					//]]>
				</script>
				<?php
			}
		}
	}
}

function postEditRoom($path, $roomDbName)
{
	if ($_POST) {
		//Zczytanie danych
		$s = cleardata($_POST['s']);
		$nazwa = cleardata($_POST['nazwa']);
		$typ = cleardata($_POST['typ']);
		$pojemnosc = cleardata($_POST['pojemnosc']);
		$dlugosc = cleardata($_POST['dlugosc']);
		$szerokosc = cleardata($_POST['szerokosc']);
		$wysokosc = cleardata($_POST['wysokosc']);
		$tablica = !isset($_REQUEST['tablica']) ? 0 : cleardata($_REQUEST['tablica']);
		$flipchart = !isset($_REQUEST['flipchart']) ? 0 : cleardata($_REQUEST['flipchart']);
		$projektor = !isset($_REQUEST['projektor']) ? 0 : cleardata($_REQUEST['projektor']);
		$glosniki = !isset($_REQUEST['glosniki']) ? "" : cleardata($_REQUEST['glosniki']);
		$mikrofony = !isset($_REQUEST['mikrofony']) ? "" : cleardata($_REQUEST['mikrofony']);
		$przewodowy = !isset($_REQUEST['przewodowy']) ? "" : cleardata($_REQUEST['przewodowy']);
		$bezprzewodowy = !isset($_REQUEST['bezprzewodowy']) ? "" : cleardata($_REQUEST['bezprzewodowy']);
		$kraj = cleardata($_POST['kraj']);
		$wojewodztwo = cleardata($_POST['wojewodztwo']);
		$miasto = cleardata($_POST['miasto']);
		$ulica = cleardata($_POST['ulica']);
		$poczta = cleardata($_POST['poczta']);
		$zdjecie = cleardata($_POST['zdjecie']);
		$sciezka = cleardata($_POST['sciezka']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($nazwa) && empty($typ) && empty($pojemnosc) && empty($dlugosc) &&
		   empty($szerokosc) && empty($wysokosc) && ($tablica == "") && ($flipchart == "") &&
		   ($projektor == "") && empty($glosniki) && empty($mikrofony) && empty($przewodowy) &&
		   empty($bezprzewodowy) && empty($kraj) && empty($wojewodztwo) && empty($miasto) &&
		   empty($ulica) && empty($poczta) && empty($zdjecie) && empty($sciezka)) {
			$errors[] = 'Należy podać dane do edycji';
		}
		
		//Sprawdzenie przekierowania sali
		if(isset($_REQUEST['s'])) {
			$s = $_REQUEST['s'];
			
			//Nazwa dokumentu
			$documentId = 'sala'.$s;
			
			//Upewnij się, że sala istnieje
			if(!checkid($path, $roomDbName, $documentId)) {
				$errors[] = 'Przykro nam, ale sala o podanym identyfikatorze nie istnieje';
			}
			
			//Pobierz dane o sali
			$roomData = data($path, $roomDbName, $documentId);
		}
		else {
			$errors[] = 'Niewłaściwy adres';
		}
		
		if(!empty($tablica)) {
			//Sprawdź czy podana przez użytkownika ilość tablic nie jest za mała
			if($tablica < 0) {
				$errors[] = 'Podana ilość tablic jest ujemna';
			}
			
			//Sprawdź czy podana przez użytkownika ilość tablic nie jest za duża
			if($tablica > 100) {
				$errors[] = 'Podany ilość tablic jest za duża';
			}
		}
		
		if(!empty($flipchart)) {
			//Sprawdź czy podana przez użytkownika ilość flipchartów nie jest za mała
			if($flipchart < 0) {
				$errors[] = 'Podana ilość flipchartów jest ujemna';
			}
			
			//Sprawdź czy podana przez użytkownika ilość flipchartów nie jest za duża
			if($flipchart > 100) {
				$errors[] = 'Podany ilość flipchartów jest za duża';
			}
		}
		
		if(!empty($projektor)) {
			//Sprawdź czy podana przez użytkownika ilość projektorów nie jest za mała
			if($projektor < 0) {
				$errors[] = 'Podana ilość projektorów jest ujemna';
			}
			
			//Sprawdź czy podana przez użytkownika ilość projektorów nie jest za duża
			if($projektor > 100) {
				$errors[] = 'Podany ilość projektorów jest za duża';
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$zmiana = false;
			$dane = false;
			$zalacznik = false;
			
			$updateOneOk = false;
			$updateOneError = false;
			$updateTwoOk = false;
			$updateTwoError = false;
			
			//Tworzenie pustych dokumentów
			$wyposazenie = Null;
			$document = array();
			
			//Sprawdzanie wyposażenia
			$tablicaObecna = !isset($roomData['wyposazenie']['tablica']) ? 0 : $roomData['wyposazenie']['tablica'];
			$flipchartObecna = !isset($roomData['wyposazenie']['flipchart']) ? 0 : $roomData['wyposazenie']['flipchart'];
			$projektorObecna = !isset($roomData['wyposazenie']['projektor']) ? 0 : $roomData['wyposazenie']['projektor'];
			$naglosnienieObecna = !isset($roomData['wyposazenie']['naglosnienie']) ? "" : $roomData['wyposazenie']['naglosnienie'];
			$internetObecna = !isset($roomData['wyposazenie']['internet']) ? "" : $roomData['wyposazenie']['internet'];
			switch ($naglosnienieObecna) {
				case "glosniki i mikrofony":
					$glosnikiObecna = 'głośniki';
					$mikrofonyObecna = 'mikrofony';
					break;
				case "glosniki":
					$glosnikiObecna = 'głośniki';
					$mikrofonyObecna = '';
					break;
				case "mikrofony":
					$glosnikiObecna = '';
					$mikrofonyObecna = 'mikrofony';
					break;
				default:
					$glosnikiObecna = '';
					$mikrofonyObecna = '';
					break;
			}
			switch ($internetObecna) {
				case "przewodowy i bezprzewodowy":
					$przewodowyObecna = 'przewodowy';
					$bezprzewodowyObecna = 'bezprzewodowy';
					break;
				case "przewodowy":
					$przewodowyObecna = 'przewodowy';
					$bezprzewodowyObecna = '';
					break;
				case "bezprzewodowy":
					$przewodowyObecna = '';
					$bezprzewodowyObecna = 'bezprzewodowy';
					break;
				default:
					$przewodowyObecna = '';
					$bezprzewodowyObecna = '';
					break;
			}
			
			//Zapisywanie dokumentu z wyposażeniem
			if(($tablica != $tablicaObecna) || ($flipchart != $flipchartObecna) || ($projektor != $projektorObecna) ||
			   ($glosniki != $glosnikiObecna) || ($mikrofony != $mikrofonyObecna) || ($przewodowy != $przewodowyObecna) ||
			   ($bezprzewodowy != $bezprzewodowyObecna)) {
				if($tablica > 0) {
					$wyposazenie['tablica'] = (int)$tablica;
				}
				if($flipchart > 0) {
					$wyposazenie['flipchart'] = (int)$flipchart;
				}
				if($projektor > 0) {
					$wyposazenie['projektor'] = (int)$projektor;
				}
				if($glosniki == "głośniki" && $mikrofony == "mikrofony") {
					$wyposazenie['naglosnienie'] = "glosniki i mikrofony";
				}
				else if($glosniki == "głośniki" && $mikrofony == "") {
					$wyposazenie['naglosnienie'] = "glosniki";
				}
				else if($glosniki == "" && $mikrofony == "mikrofony") {
					$wyposazenie['naglosnienie'] = "mikrofony";
				}
				if($przewodowy == "przewodowy" && $bezprzewodowy == "bezprzewodowy") {
					$wyposazenie['internet'] = "przewodowy i bezprzewodowy";
				}
				else if($przewodowy == "przewodowy" && $bezprzewodowy == "") {
					$wyposazenie['internet'] = "przewodowy";
				}
				else if($przewodowy == "" && $bezprzewodowy == "bezprzewodowy") {
					$wyposazenie['internet'] = "bezprzewodowy";
				}
				
				$zmiana = true;
			}
			
			//Zapisywanie dokumentu
			if(!empty($nazwa)) {
				$document['nazwa'] = $nazwa;
			}
			else {
				//Pobieranie nazwy
				$nazwa = $roomData['nazwa'];
			}
			if(!empty($typ)) {
				$document['typ'] = $typ;
			}
			else {
				//Pobieranie typu
				$typ = $roomData['typ'];
			}
			if(!empty($pojemnosc)) {
				$document['pojemnosc'] = (int)$pojemnosc;
			}
			else {
				//Pobieranie liczby miejsc
				$pojemnosc = $roomData['pojemnosc'];
			}
			if((!empty($dlugosc)) || (!empty($szerokosc)) || (!empty($wysokosc))) {
				if(!empty($dlugosc)) {
					$document['wymiary']['dlugosc'] = (float)$dlugosc;
				}
				else {
					$document['wymiary']['dlugosc'] = $roomData['wymiary']['dlugosc'];
				}
				if(!empty($szerokosc)) {
					$document['wymiary']['szerokosc'] = (float)$szerokosc;
				}
				else {
					$document['wymiary']['szerokosc'] = $roomData['wymiary']['szerokosc'];
				}
				if(!empty($wysokosc)) {
					$document['wymiary']['wysokosc'] = (float)$wysokosc;
				}
				else {
					$document['wymiary']['wysokosc'] = $roomData['wymiary']['wysokosc'];
				}
			}
			if($zmiana) {
				$document['wyposazenie'] = $wyposazenie;
			}
			if((!empty($kraj)) || (!empty($wojewodztwo)) || (!empty($miasto)) || (!empty($ulica)) || (!empty($poczta))) {
				if(!empty($kraj)) {
					$document['adres']['kraj'] = $kraj;
				}
				else {
					$document['adres']['kraj'] = $roomData['adres']['kraj'];
				}
				if(!empty($wojewodztwo)) {
					$document['adres']['wojewodztwo'] = $wojewodztwo;
				}
				else {
					$document['adres']['wojewodztwo'] = $roomData['adres']['wojewodztwo'];
				}
				if(!empty($miasto)) {
					$document['adres']['miasto'] = $miasto;
				}
				else {
					$document['adres']['miasto'] = $roomData['adres']['miasto'];
				}
				if(!empty($ulica)) {
					$document['adres']['ulica'] = $ulica;
				}
				else {
					$document['adres']['ulica'] = $roomData['adres']['ulica'];
				}
				if(!empty($poczta)) {
					$document['adres']['poczta'] = $poczta;
				}
				else {
					$document['adres']['poczta'] = $roomData['adres']['poczta'];
				}
			}
			if($document != Null) {
				$dane = true;
			}
			
			if((!empty($zdjecie)) && (!empty($sciezka))) {
				//Zmiana ukośników ze ścieżki
				$sciezka = str_replace('^\\', '/', $sciezka);
				$sciezka = str_replace('\\', '/', $sciezka);
				$sciezka = str_replace('//', '/', $sciezka);
				
				if($sciezka != 'C:/fakepath/'.$zdjecie) {
					$poz = strripos($sciezka, '/');
					
					$repository = substr($sciezka, 0, $poz+1);
					$attachment = substr($sciezka, $poz+1);
				}
				else {
					$repository = 'C:/';
					$attachment = $zdjecie;
				}
				
				$zalacznik = true;
			}
			
			//Dokument z informacjami o sali
			if($dane) {
				//Zapis dokumentu z informacjami o sali do bazy danych
				$response = updatedocument($path, $roomDbName, $documentId, $document);
				
				$result = string_to_array($response[1], true);
				$array = $result[0];
				
				$updateOneOk = array_key_exists('ok', $array);
				$updateOneError = array_key_exists('error', $array);
			}
			
			//Załącznik do dokumentu z informacjami o sali
			if($zalacznik) {
				//Zapis załącznika o sali do bazy danych
				$response = createdocumentwithattachment($path, $roomDbName, $documentId, $repository, $attachment);
				
				$result = string_to_array($response, true);
				$array = $result[0];
				
				$updateTwoOk = array_key_exists('ok', $array);
				$updateTwoError = array_key_exists('error', $array);
			}
			
			//Sprawdzanie czy dane lub załącznik zostały zmienione
			if($dane || $zalacznik) {
				$updateOneOk = true;
				$updateOneError = false;
				$updateTwoOk = true;
				$updateTwoError = false;
			}
			
			if(($document == Null && !$zalacznik) || ($updateOneOk == false && $updateTwoOk == false) || ($updateOneError == true || $updateTwoError == true)) {
				$errors[] = "Wystąpił błąd podczas aktualizacji danych sali";
				
				if(!$dane && !$zalacznik) {
					$errors[] = "Nie podano żadnych danych do zmiany";
				}
				
				?>
				<div class="validation_error-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
				$komunikat = 'Dane sali o nazwie <b>'.$nazwa.'</b> zostały poprawnie zmienione';
				
				$adres = '';
				?>
				<div class="success-box"><?php echo $komunikat; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					var s = '<?php echo $s; ?>';
					
					var adres = 'index.php?id=edytujsale&s=' + s;
					
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

function postDeleteRoom($path, $roomDbName)
{
	if ($_POST) {
		//Zczytanie danych
		$s = cleardata($_POST['s']);
		$nazwa = cleardata($_POST['nazwa']);
		$wygenerowanyKod = cleardata($_POST['wkod']);
		$kod = cleardata($_POST['kod']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($s) || empty($nazwa) || empty($wygenerowanyKod) || empty($kod)) {
			$errors[] = 'Nie przesłano wszystkich danych';
		}
		
		//Sprawdzenie przekierowania sali
		if(isset($_REQUEST['s'])) {
			$s = $_REQUEST['s'];
			
			//Nazwa dokumentu
			$documentId = 'sala'.$s;
			
			//Upewnij się, że sala istnieje
			if(!checkid($path, $roomDbName, $documentId)) {
				$errors[] = 'Przykro nam, ale sala o podanym identyfikatorze nie istnieje';
			}
			
			//Pobierz dane o sali
			$roomData = data($path, $roomDbName, $documentId);
		}
		else {
			$errors[] = 'Niewłaściwy adres';
		}
		
		if(!empty($wygenerowanyKod) && !empty($kod)) {
			//Sprawdź czy podany przez użytkownika kod jest prawidłowy
			if($wygenerowanyKod != $kod) {
				$errors[] = 'Podany kod jest nieprawidłowy';
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$deleteOk = false;
			$deleteError = false;
			
			//Usuń dokumentu z informacjami o sali do bazy danych
			$response = deletedocument($path, $roomDbName, $documentId);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			
			$deleteOk = array_key_exists('ok', $array);
			$deleteError = array_key_exists('error', $array);
			
			if($deleteOk == false || $deleteError == true) {
				$error = "Wystąpił błąd przy usuwania sali";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
				$komunikat = 'Sala o nazwie <b>'.$nazwa.'</b> została poprawnie usunięta';
				
				?>
				<div class="success-box"><?php echo $komunikat; ?></div>
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
							window.location.href = 'index.php?id=listasal';
						}
					);
					//]]>
				</script>
				<?php
			}
		}
	}
}

function postAddReservation($path, $userSecurityDbName, $userData, $roomDbName, $reservationDbName, $serviceDbName)
{
	if ($_POST) {
		//Zczytanie danych
		$nazwa = cleardata($_POST['nazwa']);
		$sala = cleardata($_POST['sala']);
		$osoba = cleardata($_POST['osoba']);
		$od = cleardata($_POST['od']);
		$do = cleardata($_POST['do']);
		$komentarz = cleardata($_POST['komentarz']);
		$maxDlugosc = cleardata($_POST['max']);
		$rok = cleardata($_POST['rok']);
		$miesiac = cleardata($_POST['miesiac']);
		$dzien = cleardata($_POST['dzien']);
		$godzina = cleardata($_POST['godzina']);
		$minuta = cleardata($_POST['minuta']);
		$sekunda = cleardata($_POST['sekunda']);
		
		//Pobieranie informacji
		$idServiceList = getuseridlist($path, $serviceDbName);
		
		//Sprawdzanie ile jest wszystkich usług
		$ileU = count($idServiceList);
		$licznik = 0;
		
		//Tworzenie pustego dokumentu
		$uslugiWszystkie = array();
		
		//Zczytanie dodatkowych danych
		for($i=0; $i<$ileU; $i++) {
			$numer = $i+1;
			
			//Nazwa dokumentu
			$serviceId = 'usluga'.$numer;
			
			//Upewnij się, że usługa istnieje
			if(checkid($path, $serviceDbName, $serviceId)) {
				$uslugiWszystkie[$licznik] = !isset($_REQUEST[$serviceId]) ? "" : cleardata($_REQUEST[$serviceId]);
				
				$licznik++;
			}
		}
		
		//Tablica błędów
		$errors = array();
		
		//Data rezerwacji
		$odRok = (int)substr($od, 0, 4);
		$odMiesiac = (int)substr($od, 5, 2);
		$odDzien = (int)substr($od, 8, 2);
		$odGodzina = (int)substr($od, 11, 2);
		$odMinuta = (int)substr($od, 14, 2);
		$odSekunda = 0;
		
		$doRok = (int)substr($do, 0, 4);
		$doMiesiac = (int)substr($do, 5, 2);
		$doDzien = (int)substr($do, 8, 2);
		$doGodzina = (int)substr($do, 11, 2);
		$doMinuta = (int)substr($do, 14, 2);
		$doSekunda = 0;
		
		//Podstawowa walidacja formularza
		if(empty($nazwa) || empty($sala) || empty($osoba) || empty($odRok) ||
		   empty($odMiesiac) || empty($odDzien) || empty($doRok) || empty($doMiesiac) ||
		   empty($doDzien) || empty($maxDlugosc) || empty($rok) || empty($miesiac) ||
		   empty($dzien)) {
			$errors[] = 'Proszę wypełnić wszystkie pola';
		}
		
		if(!empty($ileU) || !empty($licznik)) {
			//Sprawdź czy liczba usług się zgadza
			if($ileU != $licznik) {
				$errors[] = 'Podana liczba usług się nie zgadza';
			}
		}
		
		if(!empty($maxDlugosc)) {
			//Sprawdź czy podana maksymalna długość jest poprawna
			if(!preg_match('/^([0-9]){1,}$/i', $maxDlugosc)) {
				$errors[] = 'Podana maksymalna długość jest nie poprawna';
			}
			
			$maxDlugosc = (int)$maxDlugosc;
		}
		
		$od =  mktime($odGodzina, $odMinuta, $odSekunda, $odMiesiac, $odDzien, $odRok);
		$do =  mktime($doGodzina, $doMinuta, $doSekunda, $doMiesiac, $doDzien, $doRok);
		
		//Sprawdź czy podane daty rezerwacji się zgadzają
		if($od >= $do) {
			$errors[] = 'Podane daty rezerwacji się nie zgadzają';
		}
		
		if(!empty($komentarz)) {
			//Sprawdź czy podany przez użytkownika komentarz nie jest za długi
			if(strlen($komentarz) > 500) {
				$errors[] = 'Podany komentarz jest za długi';
			}
			
			//Usuwanie znaków nowej linii z komentarza
			$komentarz = preg_replace("'\n|\r\n|\r'si", "<br />", $komentarz);
			$komentarz = str_replace('<br />^<br />', '<br />', $komentarz);
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$createOk = false;
			$createError = false;
			
			//Wyznaczanie nowego ID dokumentu
			$responseList = listofdocument($path, $reservationDbName);
			
			$resultList = string_to_array($responseList, true);
			$arrayList = $resultList[0];
			$ile = $arrayList['total_rows'];
			
			$ileMax = 0;
			for($i=0; $i<$ile; $i++) {
				$name = $arrayList['rows'][$i]['id'];
				$numer = substr($name, 10);
				$ileMax = max($ileMax, $numer);
			}
			
			$documentId = 'rezerwacja'.($ileMax + 1);
			
			$od = strpos($sala, ":");
			$temp = substr($sala, $od+2);
			$do = strpos($temp, "=");
			$salaNazwa = substr($temp, 0, $do-1);
			
			//Pobieranie informacji
			$idRoomList = getuseridlist($path, $roomDbName);
			
			//Sprawdzanie ile jest wszystkich sal
			$ileSal = count($idRoomList);
			
			if($ileSal > 0) {
				for($i=0; $i<$ileSal; $i++) {
					$numer = $i+1;
					
					//Nazwa dokumentu
					$roomId = 'sala'.$numer;
					
					$roomData = getspecialdata($path, $roomDbName, $roomId, "nazwa");
					
					if($roomData == $salaNazwa) {
						$sala = $roomId;
					}
				}
			}
			
			//Tworzenie pustego dokumentu
			$uslugi = array();
			
			$licznik = 1;
			
			//Zapisywanie dokumentu
			for($i=0; $i<$ileU; $i++) {
				//Sprawdzanie, czy usługa została wybrana
				if($uslugiWszystkie[$i] != null || $uslugiWszystkie[$i] != '') {
					$uslugi[$licznik] = $uslugiWszystkie[$i];
					
					$licznik++;
				}
			}
			
			$userId = getidfromsession();
			
			//Sprawdź czy użytkownik to admin
			if(checkadmin($path, $userSecurityDbName, $userId, $userData)) {
				$superUser = true;
				$status = 'potwierdzona';
			}
			else {
				$superUser = false;
				$status = 'niepotwierdzona';
			}
			
			//Tworzenie nowego dokumentu
			$document = array(
				'_id' => $documentId,
				'nazwa' => $nazwa,
				'sala' => $sala,
				'osoba' => $osoba,
				'uslugi' => $uslugi,
				'dodanie' => array(
					'rok' => (int)$rok,
					'miesiac' => (int)$miesiac,
					'dzien' => (int)$dzien,
					'godzina' => (int)$godzina,
					'minuta' => (int)$minuta,
					'sekunda' => (int)$sekunda
				),
				'rezerwacja' => array(
					'od' => array(
						'rok' => (int)$odRok,
						'miesiac' => (int)$odMiesiac,
						'dzien' => (int)$odDzien,
						'godzina' => (int)$odGodzina,
						'minuta' => (int)$odMinuta,
						'sekunda' => (int)$odSekunda
					),
					'do' => array(
						'rok' => (int)$doRok,
						'miesiac' => (int)$doMiesiac,
						'dzien' => (int)$doDzien,
						'godzina' => (int)$doGodzina,
						'minuta' => (int)$doMinuta,
						'sekunda' => (int)$doSekunda
					)
				),
				'status' => $status,
				'komentarz' => $komentarz
			);
			
			//Zapis nowego dokumentu z informacjami o rezerwacji do bazy danych
			$response = createdocument($path, $reservationDbName, $document);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			
			$createOk = array_key_exists('ok', $array);
			$createError = array_key_exists('error', $array);
			
			if($createOk == false || $createError == true) {
				$error = "Wystąpił błąd przy rejestrowaniu rezerwacji";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
				$komunikat = 'Rezerwacja o nazwie '.$nazwa.' została poprawnie zarejestrowany';
				
				if($superUser == true) {
					$id = 'dodajrezerwacjezaawansowany';
				}
				else {
					$id = 'dodajrezerwacje';
				}
				
				?>
				<div class="success-box"><?php echo $komunikat; ?></div>
				<script type="text/javascript">
				//<![CDATA[
					var id = '<?php echo $id; ?>';
					
					var adres = 'index.php?id=' + id;
					
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

function postCancelReservation($path, $reservationDbName)
{
	if ($_POST) {
		//Zczytanie danych
		$r = cleardata($_POST['r']);
		$nazwa = cleardata($_POST['nazwa']);
		$wygenerowanyKod = cleardata($_POST['wkod']);
		$kod = cleardata($_POST['kod']);
		
		//Tablica błędów
		$errors = array();
		
		//Podstawowa walidacja formularza
		if(empty($r) || empty($nazwa) || empty($wygenerowanyKod) || empty($kod)) {
			$errors[] = 'Nie przesłano wszystkich danych';
		}
		
		//Sprawdzenie przekierowania rezerwacji
		if(isset($_REQUEST['r'])) {
			$r = $_REQUEST['r'];
			
			//Nazwa dokumentu
			$documentId = 'rezerwacja'.$r;
			
			//Upewnij się, że rezerwacja istnieje
			if(!checkid($path, $reservationDbName, $documentId)) {
				header('Location: index.php');
				echo '<p class="error">Przykro nam, ale rezerwacja o podanym identyfikatorze nie istnieje.</p>';
				
				die;
			}
			
			//Pobierz dane o rezerwacji
			$reservationData = data($path, $reservationDbName, $documentId);
		}
		else {
			$errors[] = 'Niewłaściwy adres';
		}
		
		if(!empty($wygenerowanyKod) && !empty($kod)) {
			//Sprawdź czy podany przez użytkownika kod jest prawidłowy
			if($wygenerowanyKod != $kod) {
				$errors[] = 'Podany kod jest nieprawidłowy';
			}
		}
		
		if(!empty($errors)) {
			//Jeśli wystąpiły jakieś błędy, to je pokaż
			?>
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$cancelOk = false;
			$cancelError = false;
			
			//Tworzenie pustego dokumentu
			$document = array();
			
			//Zapisywanie dokumentu
			$document['status'] = "anulowana";
			
			//Zapis dokumentu z informacjami o rezerwacji do bazy danych
			$response = updatedocument($path, $reservationDbName, $documentId, $document);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$cancelOk = array_key_exists('ok', $array);
			$cancelError = array_key_exists('error', $array);
			
			if($cancelOk == false || $cancelError == true) {
				$error = "Wystąpił błąd przy anulowaniu rezerwacji";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
				$komunikat = 'Rezerwacja o nazwie <b>'.$nazwa.'</b> została poprawnie anulowana';
				
				?>
				<div class="success-box"><?php echo $komunikat; ?></div>
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
							window.location.href = 'index.php?id=listarezerwacji';
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
	if ($_POST) {
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
				<div class="error-box"><?php echo $error; ?></div>
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
				<div class="validation_success-box"><?php echo $komunikat; ?></div>
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
	if ($_POST) {
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
	if ($_POST) {
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
			<div class="validation_warning-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
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
			$response = updatedocument($path, 'sziroom', 'config', $document);
			
			$result = string_to_array($response[1], true);
			$array = $result[0];
			
			$updateOk = array_key_exists('ok', $array);
			$updateError = array_key_exists('error', $array);
			
			if($updateOk == false || $updateError == true) {
				$error = "Wystąpił błąd podczas aktualizacji ustawień systemu";
				
				?>
				<div class="error-box"><?php echo $error; ?></div>
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
				<div class="validation_success-box"><?php echo $komunikat; ?></div>
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
