<?php
$ch = curl_init();

$payload = json_encode($document);

curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$document['_id']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');	/*PUT or POST*/
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