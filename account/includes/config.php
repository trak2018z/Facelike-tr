<?php
require_once $path."couchdb.php";
require_once $path."datafun.php";

$userDataDbName = 'uzytkownicy_dane';
$userSecurityDbName = 'uzytkownicy_zabezpieczenia';
$userStatisticsDbName = 'uzytkownicy_statystyki';
$facebookAccountDbName = "facebook_konta";
$facebookPhotoDbName = "facebook_zdjecia";

$response = checkcouchdb($path);
if((empty($response)) && ($response != '0')) {
	die ('<div class="error-box">Nie udało się połączyć z bazą danych.</div>');
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
