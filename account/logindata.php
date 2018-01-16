<?php
//Pobranie danych
if(!isset($_POST['dbName']) || !isset($_POST['documentId']) || !isset($_POST['dataOne']) || !isset($_POST['dataTwo']) || !isset($_POST['status'])) {
	echo 'Nie przesłano zmiennych "dbName", "documentId", "dataOne", "dataTwo"  i "status"';
}
else {
	$dbName = json_decode($_POST['dbName']);
	$documentId = json_decode($_POST['documentId']);
	$dataOne = json_decode($_POST['dataOne']);
	$dataTwo = json_decode($_POST['dataTwo']);
	$statusOld = json_decode($_POST['status']);
}

require 'includes/user.php';
require '../couchdb/couchdb.php';

$czasStart = microtime(true);
set_time_limit(10);	//Limit ustawiony na 10 sekund

do {
	usleep(1000);	//Opóźnienie o 0,001 sekundy
	$result = getuserdata("", $dbName, $documentId, $dataOne);
	$statusNew = $result[$dataTwo];
} while($statusOld == $statusNew);

set_time_limit(0);	//Limit wyłączony
$czasStop = microtime(true);

//Czas przerwy
$czas = $czasStop - $czasStart;
//echo "czas = ".$czas;

return $czas;
?>
