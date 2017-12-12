<?php
include("listofdocument.php");

require_once "datafun.php";
$result = string_to_array($response, true);
$array = $result[0];
$ile = $array['total_rows'];

$istnieje = false;
for($i=0; $i<$ile; $i++) {
	$name = $array['rows'][$i]['id'];
	if($name == $documentIdTarget) {
		$istnieje = true;
	}
}

if($istnieje == true) {
	$documentIdSource = $documentId;
	$documentId = $documentIdTarget;
	include("checkdocumentrev.php");
	$documentId = $documentIdSource;
	
	$documentIdTarget = $documentIdTarget.'?rev='.$revision;
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'COPY');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Destination:'.$documentIdTarget
));

$response = curl_exec($ch);

curl_close($ch);
?>