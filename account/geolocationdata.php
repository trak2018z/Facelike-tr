<?php
//Pobranie danych
if(!isset($_POST['szerokoscGeograficzna']) || !isset($_POST['dlugoscGeograficzna']) || !isset($_POST['wysokosc']) || !isset($_POST['naglowek']) || !isset($_POST['additionalData'])) {
	echo 'Nie przesłano zmiennych "szerokoscGeograficzna", "dlugoscGeograficzna", "wysokosc", "naglowek" i "additionalData"';
}
else {
	$szerokoscGeograficzna = json_decode($_POST['szerokoscGeograficzna']);
	$dlugoscGeograficzna = json_decode($_POST['dlugoscGeograficzna']);
	$wysokosc = json_decode($_POST['wysokosc']);
	$naglowek = json_decode($_POST['naglowek']);
	$additionalData = json_decode($_POST['additionalData']);
	
	if($additionalData != null) {
		$additionalData = unserialize($additionalData);
	}
}

require 'includes/user.php';
require '../couchdb/couchdb.php';
$response = changeloginstatus("", $additionalData['userStatisticsDbName'], $additionalData['userId'], $additionalData['status'], $additionalData['update']);

//Ujednolicenie danych
$response = serialize($response);
//echo "response = ".$response;

return $response;
?>
