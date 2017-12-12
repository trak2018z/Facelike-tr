<?php
require_once $path."couchdb.php";
require_once $path."datafun.php";

$userDataDbName = 'uzytkownicy_dane';
$userSecurityDbName = 'uzytkownicy_zabezpieczenia';
$facebookAccountDbName = "facebook_konta";
$facebookPhotoDbName = "facebook_zdjecia";

$response = checkcouchdb($path);
if((empty($response)) && ($response != '0')) {
	die ('<p class="error">Nie udało się połączyć z bazą danych.</p>');
}

if(isset($_SESSION) != 1) {
	session_start();
}

require_once "form.php";
require_once "user.php";
require_once "password.php";
require_once "key.php";
require_once "session.php";
?>
