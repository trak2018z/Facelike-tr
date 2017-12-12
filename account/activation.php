<?php
$path = "./couchdb/";	//Ścieżka operacyjna

require 'includes/config.php';

//Pobranie id użytkownika do aktywacji
$activationUserId = null;
if(isset($_GET['userid'])) {
	$activationUserId = $_GET['userid'];
	$activationUserId = base64_decode($activationUserId);
}

//Pobranie adresu mail do aktywacji
$activationEmail = null;
if(isset($_GET['email'])) {
	$activationEmail = $_GET['email'];
	$activationEmail = base64_decode($activationEmail);
}

//Pobranie klucza aktywacyjnego
$activationKey = null;
if(isset($_GET['key'])) {
	$activationKey = $_GET['key'];
}

//Pobranie klucza administracyjnego
$activationSuperKey = null;
if(isset($_GET['skey'])) {
	$activationSuperKey = $_GET['skey'];
}

//Podstawowa walidacja formularza
if(empty($activationUserId) || empty($activationEmail) || empty($activationKey)) {
	$errors[] = 'Błędny link';
}
else {
	//Sprawdzanie danych
	$istniejeId = checkid($path, $userDataDbName, $activationUserId);
	
	if($istniejeId == false) {
		$errors[] = 'Błąd przy sprawdzaniu użytkownika';
	}
	else {
		$type = "";
		$type = getusertype($path, $userSecurityDbName, $activationUserId);
		if($type !== "niepotwierdzony") {
			$errors[] = 'Błąd przy sprawdzaniu statusu konta użytkownika';
		}
		else {
			$istniejeEmail = checkuserdata($path, $userDataDbName, $activationUserId, 'mail', $activationEmail);
			$istniejeKey = checkuserdata($path, $userSecurityDbName, $activationUserId, 'mkey', $activationKey);
			
			if($istniejeEmail == false || $istniejeKey == false) {
				$errors[] = 'Błąd przy sprawdzaniu danych użytkownika';
			}
			else {
				$type = "użytkownik";
				if(!empty($activationSuperKey)) {
					if(sauth($path, $activationUserId, $activationEmail, $activationKey, $activationSuperKey)) {
						$type = "administrator";
					}
					
					//Niszczenie zmiennych
					unset($activationSuperKey);
				}
				
				$zmianaTypu = changetype($path, $userSecurityDbName, $activationUserId, $type);
				
				if($zmianaTypu == false) {
					$errors[] = 'Błąd przy zmianie statusu konta użytkownika';
				}
				else {
					$type = "";
					$type = getusertype($path, $userSecurityDbName, $activationUserId);
					if($type == "niepotwierdzony") {
						$errors[] = 'Nie udało się zmienić statusu konta użytkownika';
					}
				}
			}
		}
	}
}

if(empty($errors)) {
	//Auto logowanie
	createsession($activationUserId, $path);	//Zapisujemy ID użytkownika do sesji i oznaczamy go jako zalogowanego
	
	changeloginstatus($path, $userSecurityDbName, $activationUserId, 3);	//Zmiana statusu logowania
	
	header('Location: index.php');	//Przekierowanie
	//echo '<p class="success">Zostałeś zalogowany. Możesz przejść na <a href="index.php">stronę główną</a></p>';
}
else {
	//Jeśli wystąpiły jakieś błędy, to je pokaż
	foreach ($errors as $error) {
		echo '<p class="error">'.$error.'</p>';
	}
}
?>
