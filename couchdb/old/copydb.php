<?php
$dbNameSource = $dbName;
if((empty($dbNameTarget)) && ($dbNameTarget != '0')) {
	$dbNameTarget = $dbNameSource.'_backup';
}
$dbName = $dbNameTarget;
include("createdb.php");

$ch = curl_init();

$dane['source'] = $dbNameSource;
$dane['target'] = $dbNameTarget;
$payload = json_encode($dane);

curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/_replicate');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-type: application/json',
	'Accept: */*'
));

$response = curl_exec($ch);

curl_close($ch);
?>