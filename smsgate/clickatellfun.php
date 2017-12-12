<?php
function getaccountbalance($apiTokenClickatellDev)
{
	//Pobieranie informacji o stanie konta
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/rest/account/balance');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Type: application/json',
		'Authorization: bearer '.$apiTokenClickatellDev,
		'Accept: application/json'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	//Sprawdzenie stanu konta
	$result = json_decode($response, true);
	
	$balance = 0;
	$dataExists = array_key_exists('data', $result);
	if($dataExists == true) {
		$balanceExists = array_key_exists('balance', $result['data']);
		if($balanceExists == true) {
			$balance = $result['data']['balance'];
		}
	}
	
	return $balance;
}

function getcoverage($prefiks, $numerOdbiorcy, $apiTokenClickatellDev)
{
	//Pobieranie informacji o zasięgu stacji nadawczej
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/rest/coverage/'.$prefiks.$numerOdbiorcy);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Type: application/json',
		'Authorization: bearer '.$apiTokenClickatellDev,
		'Accept: application/json'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	//Sprawdzenie zasięgu stacji nadawczej
	$result = json_decode($response, true);
	
	$routable = false;
	$minimumCharge = 0;
	$dataExists = array_key_exists('data', $result);
	if($dataExists == true) {
		$routableExists = array_key_exists('routable', $result['data']);
		if($routableExists == true) {
			$routable = $result['data']['routable'];
		}
		$minimumChargeExists = array_key_exists('minimumCharge', $result['data']);
		if($minimumChargeExists == true) {
			$minimumCharge = $result['data']['minimumCharge'];
		}
	}
	
	return array($routable, $minimumCharge);
}

function sendmessage($prefiks, $numerOdbiorcy, $wiadomosc, $typ, $apiTokenClickatellDev)
{
	//Wysyłanie wiadomości
	$ch = curl_init();
	
	$document = array(
		'text' => $wiadomosc,
		'to' => array(
			$prefiks.$numerOdbiorcy
		),
		'type' => $typ
	);
	
	$payload = json_encode($document);
	
	curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/rest/message');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Type: application/json',
		'Authorization: bearer '.$apiTokenClickatellDev,
		'Accept: application/json'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	//Sprawdzenie czy wiadomość została wysłana
	$result = json_decode($response, true);
	
	$accepted = false;
	$messageId = 0;
	$dataExists = array_key_exists('data', $result);
	if($dataExists == true) {
		$messageExists = array_key_exists('message', $result['data']);
		if($messageExists == true) {
			$acceptedExists = array_key_exists('accepted', $result['data']['message'][0]);
			if($acceptedExists == true) {
				$accepted = $result['data']['message'][0]['accepted'];
			}
			$messageIdExists = array_key_exists('apiMessageId', $result['data']['message'][0]);
			if($messageIdExists == true) {
				$messageId = $result['data']['message'][0]['apiMessageId'];
			}
		}
	}
	
	return array($accepted, $messageId);
}

function getmessagestatus($messageId, $apiTokenClickatellDev)
{
	//Pobieranie statusu wysłanej wiadomości
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/rest/message/'.$messageId);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Type: application/json',
		'Authorization: bearer '.$apiTokenClickatellDev,
		'Accept: application/json'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	//Sprawdzenie statusu wysłanej wiadomości
	$result = json_decode($response, true);
	
	$messageStatus = 0;
	$messageDescription = "";
	$dataExists = array_key_exists('data', $result);
	if($dataExists == true) {
		$messageStatusExists = array_key_exists('messageStatus', $result['data']);
		if($messageStatusExists == true) {
			$messageStatus = $result['data']['messageStatus'];
		}
		$descriptionExists = array_key_exists('description', $result['data']);
		if($descriptionExists == true) {
			$messageDescription = $result['data']['description'];
		}
	}
	
	return array($messageStatus, $messageDescription);
}
?>
