<?php
function checkcouchdb($path)
{
	//Wczytanie zawartości pliku "config.php"
	require $path."config.php";
	
	//Inicjalizacja nowej sesji cURL i utworzenie dla niej uchwytu "$ch"
	$ch = curl_init();
	
	//Ustawienie opcji na danym uchwycie "$ch" sesji cURL
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	//Wykonanie danej sesji cURL, zwrócenie jej wyniku i zapisanie go do zmiennej "$response"
	$response = curl_exec($ch);
	
	//Zamknięcie sesji cURL, zwolnienie wszystkich zasobów i usunięcie jej uchwytu "$ch"
	curl_close($ch);
	
	//Zwrócenie przez funkcje zawartości zmiennej "$response"
	return $response;
}

function checkuuid($path)
{
	require $path."config.php";
	
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
	
	return array($response, $UUID);
}

function listofdb($path)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/_all_dbs');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function listofdocument($path, $dbName)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/_all_docs');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function listofdocumentrev($path, $dbName, $documentId)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId.'?revs=true');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function detailedlistofdocumentrev($path, $dbName, $documentId)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId.'?revs_info=true');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function getdb($path, $dbName)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function getdocument($path, $dbName, $documentId)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function getdocumentrev($path, $dbName, $documentId)
{
	$response = getdocument($path, $dbName, $documentId);
	
	$dlugosc = strlen($response);
	if(($poz = strpos($response, '_rev')) !== false) {
		$od = $poz + 7;
	} else if(($poz = strpos($response, 'rev')) !== false) {
		$od = $poz + 6;
	}
	$temp = substr($response, $od);
	if(($do = strpos($temp, '"')) !== false) {}
	$revision = substr($temp, 0, $do);
	
	/*TEST
	echo $response."<br>";
	echo $dlugosc."<br>";
	echo $poz."<br>";
	echo $od."<br>";
	echo $temp."<br>";
	echo $do."<br>";
	echo $revision."<br>";
	*/
	
	return array($response, $revision);
}

function createdb($path, $dbName)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function createdocument($path, $dbName, $document)
{
	require $path."config.php";
	
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
	
	return $response;
}

function createdocumentwithattachment($path, $dbName, $documentId, $repository, $attachment)
{
	require $path."config.php";
	
	$data = getdocumentrev($path, $dbName, $documentId);
	$revision = $data[1];
	
	$ch = curl_init();
	
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
	
	return $response;
}

function updatedocument($path, $dbName, $documentId, $document)
{
	require $path."config.php";
	
	$data = getdocumentrev($path, $dbName, $documentId);
	$responseOld = $data[0];
	$revision = $data[1];
	
	$ch = curl_init();
	
	$oldDocument = json_decode($responseOld, true);
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
	
	$responseNew = curl_exec($ch);
	
	curl_close($ch);
	
	return array($responseOld, $responseNew);
}

function deletedb($path, $dbName)
{
	require $path."config.php";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function deletedocument($path, $dbName, $documentId)
{
	require $path."config.php";
	
	$data = getdocumentrev($path, $dbName, $documentId);
	$revision = $data[1];
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/'.$dbName.'/'.$documentId.'?rev='.$revision);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	return $response;
}

function copydb($path, $dbName, $dbNameTarget)
{
	require $path."config.php";
	
	if((empty($dbNameTarget)) && ($dbNameTarget != '0')) {
		$dbNameTarget = $dbName.'_backup';
	}
	$responseNew = createdb($dbNameTarget);
	
	$ch = curl_init();
	
	$data['source'] = $dbName;
	$data['target'] = $dbNameTarget;
	$payload = json_encode($data);
	
	curl_setopt($ch, CURLOPT_URL, $db_protocol.$db_server.':'.$db_potr.'/_replicate');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $db_user.':'.$db_pass);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
		'Accept: */*'
	));
	
	$responseCopy = curl_exec($ch);
	
	curl_close($ch);
	
	return array($responseNew, $responseCopy);
}

function copydocument($path, $dbName, $documentId, $documentIdTarget)
{
	require $path."config.php";
	
	$responseList = listofdocument($path, $dbName);
	
	$result = string_to_array($responseList, true);
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
		$data = getdocumentrev($path, $dbName, $documentIdTarget);
		$revision = $data[1];
		
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
	
	$responseCopy = curl_exec($ch);
	
	curl_close($ch);
	
	return array($responseList, $responseCopy);
}
?>