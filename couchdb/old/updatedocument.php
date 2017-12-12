<?php
include("checkdocumentrev.php");

$ch = curl_init();

require_once "datafun.php";
$result = string_to_array($response, true);
$oldDocument = $result[0];
$updateDocument = array_merge($oldDocument, $document);

$payload = json_encode($updateDocument);

//curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_user.':'.$db_pass.'@'.$db_server.':'.$db_potr.'/'.$db_name.'/'.$document_id.'?rev='.$revision);
curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId.'?rev='.$revision);
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