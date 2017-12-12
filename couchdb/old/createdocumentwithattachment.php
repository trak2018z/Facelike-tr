<?php
include("checkdocumentrev.php");

$ch = curl_init();

require_once "datafun.php";
$contentType = mime_content_type($repository, $attachment);

$payload = file_get_contents($repository.$attachment);

curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId.'/'.$attachment.'?rev='.$revision);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
//curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-type: '.$contentType,
	'Accept: */*'
));

$response = curl_exec($ch);

curl_close($ch);
?>