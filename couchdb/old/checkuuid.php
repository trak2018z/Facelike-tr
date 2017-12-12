<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/_uuids');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-type: application/json',
	'Accept: */*'
));

$response = curl_exec($ch);
$_response = json_decode($response, true);

$UUID = $_response['uuids'];

curl_close($ch);
?>