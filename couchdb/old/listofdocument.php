<?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/_all_docs');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-type: application/json',
	'Accept: */*'
));

$response = curl_exec($ch);

curl_close($ch);
?>