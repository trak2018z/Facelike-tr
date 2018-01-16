<?php
function data($path, $userDataDbName, $userId = null)
{
	if($userId === null) {
		$userId = getidfromsession();
	}
	
	$response = getdocument($path, $userDataDbName, $userId);
	
	//$result = string_to_array($response, true);
	//$array = $result[0];
	$array = json_decode($response, true);
	
	return $array;
}

function cleardata($data, $specialchars = true) {
	//Jeśli serwer automatycznie dodaje slashe to je usuwamy
	if(get_magic_quotes_gpc()) {
		$data = stripslashes($data);
	}
	
	$data = trim($data);	//Usuwa białe znaki na początku i na końcu
	$data = addslashes($data);    //Zwraca ciąg znaków, który został zabezpieczony przed niebezpiecznymi znakami (znakiem ucieczki) - te znaki to pojedynczy cudzysłów ( ' ), podwójny cudzysłów ( " ), backslash ( \ ) i NULL.
	$data = escapeshellcmd($data);    //Zwraca ciąg znaków, który został zabezpieczony przed niebezpiecznymi znakami (znakami systemowymi)
	
	if($specialchars) {
		$data = htmlspecialchars($data);  //Konwertuje znaki specjalne na znaczniki HTML - te znaki to handlowe ( & ), pojedynczy cudzysłów ( ' ), podwójny cudzysłów ( " ), mniejsze niż ( < ) i większy niż ( > ).
	}
	
	return $data;
}

function checkid($path, $userDataDbName, $userId)
{
	$responseList = listofdocument($path, $userDataDbName);
	
	$resultList = string_to_array($responseList, true);
	$arrayList = $resultList[0];
	$ile = $arrayList['total_rows'];
	
	if($ile == 0) {
		return false;
	}
	
	for($i=0; $i<$ile; $i++) {
		$documentId = $arrayList['rows'][$i]['id'];
		
		if($documentId == $userId) {
			return true;
		}
	}
	
	return false;
}

function getuseridlist($path, $userSecurityDbName)
{
	$responseList = listofdocument($path, $userSecurityDbName);
	
	$resultList = json_decode($responseList, true);
	$ile = $resultList['total_rows'];
	
	if($ile == 0) {
		return false;
	}
	
	for($i=0; $i<$ile; $i++) {
		$idList[] = $resultList['rows'][$i]['id'];
	}
	
	return $idList;
}

function getuserid($path, $userSecurityDbName, $dataTyp, $data)
{
	$idList = getuseridlist($path, $userSecurityDbName);
	
	if($idList) {
		$ile = count($idList);
		
		for($i=0; $i<$ile; $i++) {
			$documentId = $idList[$i];
			
			$response = getdocument($path, $userSecurityDbName, $documentId);
			
			$result = string_to_array($response, true);
			$array = $result[0];
			$checkData = $array[$dataTyp];
			
			if($checkData == $data) {
				return $documentId;
			}
		}
	}
	
	return false;
}

function checkuserdata($path, $userSecurityDbName, $userId, $dataType, $data, $array = null)
{
	if($array == NULL) {
		$response = getdocument($path, $userSecurityDbName, $userId);
		
		$result = string_to_array($response, true);
		$array = $result[0];
	}
	
	$istnieje = false;
	while(list($klucz, $wartosc) = each($array)) {
		$wartoscTyp = gettype($wartosc);
		
		if($wartoscTyp == "array") {
			$istnieje = checkuserdata($path, $userSecurityDbName, $userId, $dataType, $data, $wartosc);
			
			if($istnieje == true) {
				return true;
			}
		}
		else if($klucz == $dataType) {
			if($wartosc != NULL && $wartosc == $data) {
				return true;
			}
		}
	}
	
	return false;
}

function getuserdata($path, $userSecurityDbName, $documentId, $dataType)
{
	$typ = gettype($dataType);
	
	$response = getdocument($path, $userSecurityDbName, $documentId);
	
	$result = json_decode($response, true);
	
	if($typ == "array") {
		$array = array();
		
		$ile = count($dataType);
		$i = 0;
		
		while(list($klucz, $wartosc) = each($result)) {
			if($klucz == $dataType[$i]) {
				$array[] = $wartosc;
				$i++;
				if($i >= $ile) {
					return $array;
				}
			}
		}
	}
	else {
		while(list($klucz, $wartosc) = each($result)) {
			if($klucz == $dataType) {
				return $wartosc;
			}
		}
	}
	
	return false;
}

function getspecialdata($path, $databaseName, $documentId, $dataType = null)
{
	if($dataType === null) {
		$typ = 'all';
	}
	else {
		$typ = gettype($dataType);
	}
	
	$response = getdocument($path, $databaseName, $documentId);
	
	$result = json_decode($response, true);
	
	if($typ == "all") {
		return $result;
	}
	else if($typ == "array") {
		$array = array();
		
		$ile = count($dataType);
		$i = 0;
		
		while(list($klucz, $wartosc) = each($result)) {
			if($klucz == $dataType[$i]) {
				$array[] = $wartosc;
				$i++;
				if($i >= $ile) {
					return $array;
				}
			}
		}
	}
	else {
		while(list($klucz, $wartosc) = each($result)) {
			if($klucz == $dataType) {
				return $wartosc;
			}
		}
	}
	
	return false;
}

function getusertype($path, $userSecurityDbName, $userId)
{
	$response = getdocument($path, $userSecurityDbName, $userId);
	
	$result = string_to_array($response, true);
	$array = $result[0];
	
	$istnieje = false;
	while(list($klucz, $wartosc) = each($array)) {
		if($klucz == "typ") {
			$istnieje = true;
		}
	}
	
	if($istnieje == true) {
		$type = $array['typ'];
	}
	else {
		$type = "niepotwierdzony";
	}
	
	return $type;
}

function changetype($path, $userSecurityDbName, $userId, $type)
{
	if($type == "użytkownik" || $type == "administrator") {
		$document = array(
			'typ' => $type
		);
		
		$response = updatedocument($path, $userSecurityDbName, $userId, $document);
		
		return $response;
	}
	
	return false;
}

function createloginstatus($status, $update = true, $path = null, $userStatisticsDbName = null, $userId = null)
{
	switch ($status) {
		case 0:
			$opis = "błędne hasło";
			break;
		case 1:
			$opis = "konto nie zostało aktywowane";
			break;
		case 2:
			$opis = "logowanie";
			break;
		case 3:
			$opis = "auto logowanie";
			break;
		case 4:
			$opis = "zalogowany";
			break;
		case 5:
			$opis = "wylogowanie";
			break;
		case 6:
			$opis = "reset hasła";
			break;
	}
	
	if($update) {
		//Deklaracja danych
		$szerokoscGeograficzna = null;
		$dlugoscGeograficzna = null;
		$wysokosc = null;
		$naglowek = null;
		
		if(!isset($_POST['szerokoscGeograficzna']) || !isset($_POST['dlugoscGeograficzna']) || !isset($_POST['wysokosc']) || !isset($_POST['naglowek'])) {
			$additionalData = array(
				'path' => $path,
				'userStatisticsDbName' => $userStatisticsDbName,
				'userId' => $userId,
				'status' => $status,
				'update' => $update
			);
			
			$result = getdocumentrev($path, $userStatisticsDbName, $userId);
			$revision = $result[1];
			if(($do = strpos($revision, '-')) !== false) {}
			$documentRevOld = substr($revision, 0, $do);
			
			$dataStatus = getGeolocationData($path, true, false, false, false, 0.0, 0.0, false, $additionalData);
			
			$result = getdocumentrev($path, $userStatisticsDbName, $userId);
			$revision = $result[1];
			if(($do = strpos($revision, '-')) !== false) {}
			$documentRevNew = substr($revision, 0, $do);
			
			if(($documentRevNew - $documentRevOld) == 0) {
				return $dataStatus;
			}
		}
		else {
			$szerokoscGeograficzna = json_decode($_POST['szerokoscGeograficzna']);
			$dlugoscGeograficzna = json_decode($_POST['dlugoscGeograficzna']);
			$wysokosc = json_decode($_POST['wysokosc']);
			$naglowek = json_decode($_POST['naglowek']);
			
			if((empty($szerokoscGeograficzna)) || ($szerokoscGeograficzna == null) || ($szerokoscGeograficzna == '')) {
				$szerokoscGeograficzna = "Brak danych";
			}
			if((empty($dlugoscGeograficzna)) || ($dlugoscGeograficzna == null) || ($dlugoscGeograficzna == '')) {
				$dlugoscGeograficzna = "Brak danych";
			}
			if((empty($wysokosc)) || ($wysokosc == null) || ($wysokosc == '')) {
				$wysokosc = "Brak danych";
			}
			if((empty($naglowek)) || ($naglowek == null) || ($naglowek == '')) {
				$naglowek = "Brak danych";
			}
		}
		
		$geolocationData = array(
			'szerokosc_geograficzna' => (double)$szerokoscGeograficzna,
			'dlugosc_geograficzna' => (double)$dlugoscGeograficzna,
			'wysokosc' => $wysokosc,
			'naglowek' => $naglowek
		);
		
		$clientData = getClientData();
		
		$details = getIpDetails();
		$ipDetails = checkIpDetails($details);
		
		$clientData2 = serialize($clientData);	//test
		echo "clientData = ".$clientData2;	//test
		$ipDetails2 = serialize($ipDetails);	//test
		echo "ipDetails = ".$ipDetails2;	//test
		$geolocationData2 = serialize($geolocationData);	//test
		echo "geolocationData = ".$geolocationData2;	//test
	}
	else {
		//Pobieranie danych statusu logowania
		$loginStatus = getuserdata($path, $userStatisticsDbName, $userId, 'loginstatus');
		
		$clientData = $loginStatus['client_data'];
		$ipDetails = $loginStatus['ip_details'];
		$geolocationData = $loginStatus['geolocation_data'];
	}
	
	$rok = date('Y');
	$miesiac = date('m');
	$dzien = date('d');
	$godzina = date('H');
	$minuta = date('i');
	$sekunda = date('s');
	$mikrosekunda = (float)microtime(false);
	$mikrosekunda = substr($mikrosekunda, 2);
	$strefaCzasowa = date('P');
	$czasLetni = date('I');
	
	$loginStatus = array(
		'status' => $opis,
		'client_data' => $clientData,
		'ip_details' => $ipDetails,
		'geolocation_data' => $geolocationData,
		'date' => array(
			'rok' => $rok,
			'miesiac' => $miesiac,
			'dzien' => $dzien,
			'godzina' => $godzina,
			'minuta' => $minuta,
			'sekunda' => $sekunda,
			'mikrosekunda' => (int)$mikrosekunda,
			'strefa_czasowa' => $strefaCzasowa,
			'czas_letni' => (boolean)$czasLetni
		)
	);
	
	return $loginStatus;
}

function changeloginstatus($path, $userStatisticsDbName, $userId, $status, $update = true)
{
	if($update) {
		$loginStatus = createloginstatus($status, $update, $path, $userStatisticsDbName, $userId);
		
		if($loginStatus == false) {
			return $loginStatus;
		}
	}
	else {
		$loginStatus = createloginstatus($status, $update, $path, $userStatisticsDbName, $userId);
	}
	
	$document = array(
		'loginstatus' => $loginStatus
	);
	
	$response = updatedocument($path, $userStatisticsDbName, $userId, $document);
	
	return $response;
}

function checkadmin($path, $userSecurityDbName, $userId, $userData)
{
	if($userId == $userData['_id']) {
		$type = "użytkownik";
		$type = getusertype($path, $userSecurityDbName, $userId);
		
		if($type == "administrator") {
			return true;
		}
	}
	
	return false;
}

function checkbusy($path, $userSecurityDbName, $dataTyp, $data)
{
	$idList = getuseridlist($path, $userSecurityDbName);
	
	if($idList) {
		$ile = count($idList);
		
		for($i=0; $i<$ile; $i++) {
			$documentId = $idList[$i];
			
			$response = getdocument($path, $userSecurityDbName, $documentId);
			$array = json_decode($response, true);
			
			$typ = gettype($dataTyp);
			if($typ == "array") {
				$checkData = $array[$dataTyp[0]][$dataTyp[1]];
			}
			else {
				$checkData = $array[$dataTyp];
			}
			
			if($checkData == $data) {
				return true;
			}
		}
	}
	
	return false;
}

function setplec($imie)
{
	$znak = $imie{strlen($imie)-1};
	
	if($znak == "a") {
		$plec = "k";
	}
	else {
		$plec = "m";
	}
	
	return $plec;
}

function getplec($data)
{
	switch ($data) {
		case "m":
			$plec = "mężczyzna";
			break;
		case "k":
			$plec = "kobieta";
			break;
		default:
			$plec = "mężczyzna";
			break;
	}
	
	return $plec;
}

function getmiesiac($numerMiesiaca, $ileLiter = 3)
{
	$miesiace = array(
		1 => "styczń",
		2 => "luty",
		3 => "marzec",
		4 => "kwiecień",
		5 => "maj",
		6 => "czerwiec",
		7 => "lipiec",
		8 => "sierpień",
		9 => "wrzesień",
		10 => "październik",
		11 => "listopad",
		12 => "grudzień"
	);
	
	$miesiac = "";
	$numer = (int)$numerMiesiaca;
	
	if($numer == 10) {
		if($ileLiter == 3) {
			$miesiac = "paź";
		}
	}
	else {
		$miesiac = substr($miesiace[$numer], 0, $ileLiter);
	}
	
	return $miesiac;
}

function auth($path, $userSecurityDbName, $login, $password)
{
	$responseList = listofdocument($path, $userSecurityDbName);
	
	$resultList = string_to_array($responseList, true);
	$arrayList = $resultList[0];
	$ile = $arrayList['total_rows'];
	
	if($ile == 0) {
		return array("1", null);
	}
	
	$stan = 0;
	for($i=0; $i<$ile; $i++) {
		$documentId = $arrayList['rows'][$i]['id'];
		
		$response = getdocument($path, $userSecurityDbName, $documentId);
		
		$result = string_to_array($response, true);
		$array = $result[0];
		$checkLogin = $array['login'];
		
		if($checkLogin == $login) {
			$stan = 1;
			
			$userId = $array['_id'];
			
			$checkPassword = $array['password'];
			
			if(password_verify($password, $checkPassword)) {
				$stan = 2;
			}
		}
	}
	
	if($stan == 0) {
		return array("2", null);
	}
	elseif($stan == 1) {
		return array("3", $userId);
	}
	elseif($stan == 2) {
		return array("4", $userId);
	}
	else {
		return false;
	}
}

function sauth($path, $userId, $userEmail, $userKey, $activationSuperKey)
{
	$superKey = skeyhash($path, $userId, $userEmail, $userKey);
	
	if($activationSuperKey == $superKey) {
		return true;
	}
	
	return false;
}

/*
function getreservedroomlist($path, $reservationDbName)
{
	$reservedIdList = getuseridlist($path, $reservationDbName);
	
	//Sprawdzanie ilu jest wszystkich rezerwacji
	$ile1 = count($reservedIdList);
	
	$reservedRoomIdList = array();
	
	for($i=0; $i<$ile1; $i++) {
		$reservedRoomIdList[$i] = getspecialdata($path, $reservationDbName, $reservedIdList[$i], "sala");
	}
	
	//Sprawdzanie ilu jest wszystkich zarezerwowanych sal
	$ile2 = count($reservedRoomIdList);
	
	//Sprawdzenie zgodności
	if($ile1 != $ile2) {
		return false;
	}
	
	$ileSal = 0;
	$sortedReservedRoomIdList = array();
	
	for($i=0; $i<$ile2; $i++) {
		$l = count($sortedReservedRoomIdList);
		
		if($l == 0) {
			$sortedReservedRoomIdList[$ileSal] = $reservedRoomIdList[$i];
			$ileSal++;
		}
		else {
			$znaleziono = false;
			
			for($j=0; $j<$l; $j++) {
				if($sortedReservedRoomIdList[$j] == $reservedRoomIdList[$i]) {
					$znaleziono = true;
				}
			}
			
			if($znaleziono == false) {
				$sortedReservedRoomIdList[$ileSal] = $reservedRoomIdList[$i];
				$ileSal++;
			}
		}
	}
	
	return array($sortedReservedRoomIdList, $ileSal);
}
*/

function randomString($length, $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWZ0123456789')
{
	$patternLength = strlen($pattern)-1;
	$randomString = "";
	
	for($i=0; $i<$length; $i++) {
		$randomString .= $pattern{rand(0, $patternLength)};
	}
	
	return $randomString;
}

function encodeUrlData($data)
{
	//Kodowanie danych do przesyłu w adresie URL za pomocą metody GET
	$daneDlugosc = strlen($data);
	$szukane = array("!", "@", "#", "?", "&", " ");
	$zastepcze   = array("XXX1XXX", "XXX2XXX", "XXX3XXX", "XXX4XXX", "XXX5XXX", "XXX6XXX");
	
	$encodeData = str_replace($szukane, $zastepcze, $data);
	
	return $encodeData;
}

function decodeUrlData($encodeData)
{
	//Dekodowanie danych po przesłaniu w adresie URL za pomocą metody GET
	$daneDlugosc = strlen($encodeData);
	$szukane   = array("XXX1XXX", "XXX2XXX", "XXX3XXX", "XXX4XXX", "XXX5XXX", "XXX6XXX");
	$zastepcze = array("!", "@", "#", "?", "&", " ");
	
	$data = str_replace($szukane, $zastepcze, $encodeData);
	
	return $data;
}

function getClientData()
{
	$os = php_uname();
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$browser = getBrowser($userAgent);
	$port = $_SERVER['SERVER_PORT'];
	$resolution = getScreenResolution();
	
	$clientData = array(
		'system' => $os,
		'przegladarka' => $browser,
		'aplikacja_kliencka' => $userAgent,
		'port' => (int)$port,
		'rozdzielczosc' => $resolution[0]."x".$resolution[1]
	);
	
	return $clientData;
}

function getIp($ip2long = false)
{
	if($_SERVER['HTTP_CLIENT_IP']) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}
	else if($_SERVER['HTTP_X_FORWARDED_FOR']) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	if($ip2long) {
		$ip = ip2long($ip);
	}
	
	return $ip;
}

function getIpDetails($ip = '')
{
	if((!empty($ip)) && ($ip != null) && ($ip != '')) {
		$ip = $ip.'/';
	}
	
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://ipinfo.io/'.$ip.'json');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	//Sprawdzenie informacji
	$details = json_decode($response);
	
	return $details;
}

function checkIpDetails($details)
{
	$ip = '';
	$hostname = '';
	$organizacja = '';
	$szerokoscGeograficzna = '';
	$dlugoscGeograficzna = '';
	$kraj = '';
	$region = '';
	$miasto = '';
	$kodPocztowy = '';
	
	while(list($k, $v) = each($details)) {
		switch ($k) {
			case 'ip':
				$ip = $v;
				break;
			case 'hostname':
				$hostname = $v;
				break;
			case 'org':
				$organizacja = $v;
				break;
			case 'loc':
				$do = strpos($v, ',');
				$od = $do + 1;
				
				$szerokoscGeograficzna = substr($v, 0, $do);
				$dlugoscGeograficzna = substr($v, $od);
				break;
			case 'country':
				$code = $v;
				
				$kraj = countryCodeToCountry($code);
				break;
			case 'region':
				$region = $v;
				break;
			case 'city':
				$miasto = $v;
				break;
			case 'postal':
				$kodPocztowy = $v;
				break;
			default:
				$dane[$k] = $v;
				break;
		}
	}
	
	$ipDetails = array(
		'ip' => $ip,
		'hostname' => $hostname,
		'organizacja' => $organizacja,
		'szerokosc_geograficzna' => (double)$szerokoscGeograficzna,
		'dlugosc_geograficzna' => (double)$dlugoscGeograficzna,
		'kraj' => $kraj,
		'region' => $region,
		'miasto' => $miasto,
		'kod_pocztowy' => $kodPocztowy
	);
	
	return $ipDetails;
}

function getGeolocationData($path, $saveData = false, $showData = false, $showMap = false, $simulate = false, $latitude = 0.0, $longitude = 0.0, $statement = false, $additionalData = null)
{
	//Ujednolicenie danych
	$additionalData = serialize($additionalData);
	
	//Klucz Google Maps API
	$apiKey = getspecialdata($path, 'facelike', 'google_maps', 'api_key');
	
	?>
	<script async defer src="http://maps.google.com/maps/api/js?key=<?php echo $apiKey; ?>" type="text/javascript"></script>
	
	<script>
	//<![CDATA[
		var saveData = '<?php echo $saveData; ?>';
		var showData = '<?php echo $showData; ?>';
		var showMap = '<?php echo $showMap; ?>';
		var simulate = '<?php echo $simulate; ?>';
		
		function initialiseMap()
		{
			var myOptions = {
				zoom: 4,
				mapTypeControl: true,
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
				navigationControl: true,
				navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			if(showMap == true)
			{
				map = new google.maps.Map(document.getElementById("google_map"), myOptions);
			}
		}
		
		function initialise()
		{
			if(simulate == true)
			{
				var latitude = '<?php echo $latitude; ?>';
				var longitude = '<?php echo $longitude; ?>';
				var statement = '<?php echo $statement; ?>';
				latitude = parseFloat(latitude);
				longitude = parseFloat(longitude);
				
				var locations = new Array({ coords: {
						latitude: latitude,
						longitude: longitude
					} 
				});
				
				if(statement == true)
				{
					alert(JSON.stringify(locations, null, 4));
				}
				
				geoPositionSimulator.init(locations);
			}
			
			if(showData == true)
			{
				if(geoPosition.init())
				{
					document.getElementById('latitude').innerHTML = "Odbieranie danych ...";
					document.getElementById('longitude').innerHTML = "Odbieranie danych ...";
					document.getElementById('altitude').innerHTML = "Odbieranie danych ...";
					document.getElementById('heading').innerHTML = "Odbieranie danych ...";
					geoPosition.getCurrentPosition(showPosition, function() {
							document.getElementById('latitude').innerHTML = "Nie można uzyskać lokalizacji";
							document.getElementById('longitude').innerHTML = "Nie można uzyskać lokalizacji";
							document.getElementById('altitude').innerHTML = "Nie można uzyskać lokalizacji";
							document.getElementById('heading').innerHTML = "Nie można uzyskać lokalizacji";
						},
						{
							enableHighAccuracy:true
					});
				}
				else
				{
					document.getElementById('latitude').innerHTML = "Funkcjonalność niedostępna";
					document.getElementById('longitude').innerHTML = "Funkcjonalność niedostępna";
					document.getElementById('altitude').innerHTML = "Funkcjonalność niedostępna";
					document.getElementById('heading').innerHTML = "Funkcjonalność niedostępna";
				}
			}
			else
			{
				if(geoPosition.init())
				{
					geoPosition.getCurrentPosition(showPosition, function() {}, {enableHighAccuracy:true});
				}
			}
		}
		
		function showPosition(p)
		{
			var latitude = parseFloat( p.coords.latitude );
			var longitude = parseFloat( p.coords.longitude );
			var altitude = parseFloat( p.coords.altitude );
			var heading = parseFloat( p.coords.heading );
			
			if(showData == true)
			{
				document.getElementById('latitude').innerHTML = 'Szerokość geograficzna: ' + latitude;
				document.getElementById('longitude').innerHTML = 'Długość geograficzna: ' + longitude;
				if(isNaN(altitude) || altitude == '' || altitude == null || altitude == undefined)
				{
					document.getElementById('altitude').innerHTML = 'Wysokość: Brak danych';
				}
				else
				{
					document.getElementById('altitude').innerHTML = 'Wysokość: ' + altitude;
				}
				if(isNaN(heading) || heading == '' || heading == null || heading == undefined)
				{
					document.getElementById('heading').innerHTML = 'Nagłówek: Brak danych';
				}
				else
				{
					document.getElementById('heading').innerHTML = 'Nagłówek: ' + heading;
				}
			}
			
			if(showMap == true)
			{
				var pos = new google.maps.LatLng( latitude , longitude);
				map.setCenter(pos);
				map.setZoom(14);
				
				var infowindow = new google.maps.InfoWindow({
					content: "<strong>yes</strong>"
				});
				
				var marker = new google.maps.Marker({
					position: pos,
					map: map,
					title: "Jesteś tutaj"
				});
				
				google.maps.event.addListener(marker, 'click', function() {
				  infowindow.open(map,marker);
				});
			}
			
			if(saveData == true)
			{
				getValue(latitude, longitude, altitude, heading);
			}
		}
		
		function checkSum(successMsg, errorMsg, completeMsg) {
			if(successMsg != null) {
				alert("Success: " + JSON.stringify(successMsg, null, 4));
			}
			if(errorMsg != null) {
				alert("Error: " + JSON.stringify(errorMsg, null, 4));
			}
			if(completeMsg != null) {
				alert("Complete: " + JSON.stringify(completeMsg, null, 4));
			}
		}
		
		function getValue(latitude, longitude, altitude, heading) {
			var statement = '<?php echo $statement; ?>';
			var additionalData = '<?php echo $additionalData; ?>';
			var successMsg = null;
			var errorMsg = null;
			var completeMsg = null;
			
			jQuery(document).ready(function() {
				$.ajax({
					type: "POST",
					//method: "POST",
					url: "account/geolocationdata.php",
					data: {
						szerokoscGeograficzna: JSON.stringify(latitude),
						dlugoscGeograficzna: JSON.stringify(longitude),
						wysokosc: JSON.stringify(altitude),
						naglowek: JSON.stringify(heading),
						<?php
						if($additionalData != null) {
							echo 'additionalData: JSON.stringify(additionalData),';
						}
						else {
							echo 'additionalData: JSON.stringify(null),';
						}
						?>
					},
					dataType: "HTML",	//"HTML"	//"JSON"
					//contentType: "application/json",	//"application/json"
					cache: false,
					beforeSend: function() {
						$(".ajaxLoading").show();
					},
					success: function(data, msg) {
						//Ten fragment wykona się po POMYŚLNYM zakończeniu połączenia
						$(".ajaxLoading").hide();
						//$('#answer').val(data);
						successMsg = msg;
					},
					complete: function(r) {
						//Ten fragment wykona się po ZAKONCZENIU połączenia
						completeMsg = r;
						if(statement == true)
						{
							checkSum(successMsg, errorMsg, completeMsg);
						}
					},
					error: function(data, error) {
						//Ten fragment wykona się w przypadku BŁĘDU
						$(".ajaxLoading").hide();
						console.log(error);
						errorMsg = error;
					}
				});
			});
		}
	 //]]>
	</script>
	<?php
	
	//Komunikat końcowy
	if($saveData == true) {
		return false;
	}
}

function getBrowser($userAgent)
{
	if(preg_match('/MSIE/i', $userAgent) && !preg_match('/Opera/i',$userAgent)) {
		$browser = 'Internet Explorer';
	}
	elseif(preg_match('/Firefox/i', $userAgent)) {
		$browser = 'Mozilla Firefox';
	}
	elseif(preg_match('/Chrome/i', $userAgent)) {
		$browser = 'Google Chrome';
	}
	elseif(preg_match('/Safari/i', $userAgent)) {
		$browser = 'Apple Safari';
	}
	elseif(preg_match('/Opera/i', $userAgent)) {
		$browser = 'Opera';
	}
	elseif(preg_match('/Netscape/i', $userAgent)) {
		$browser = 'Netscape';
	}
	if(preg_match('/Mozilla/i', $userAgent) && !preg_match('/Opera/i',$userAgent)) {
		$browser = 'Internet Explorer';
	}
	else {
		$browser = $userAgent;
	}
	
	return $browser;
}

function setScreenResolution()
{
	//Zapis ciasteczek
	?>
	<script type="text/javascript">
	//<![CDATA[
		//var width = window.screen.availWidth;
		//var height = window.screen.availHeight;
		var width = screen.width;
		var height = screen.height;
		
		function setCookie(cookieName, cookieValue, nDays) {
			var today = new Date();
			var expire = new Date();
			
			if(nDays == null || nDays == 0)
				nDays = 1;
			
			expire.setTime(today.getTime() + (3600000*24*nDays));
			document.cookie = cookieName + "=" + cookieValue + "; expires=" + expire.toGMTString() + "; path=/";
		}
		
		setCookie('w', width, '1');
		setCookie('h', height, '1');
		//]]>
	</script>
	<?php
}

function getScreenResolution()
{
	$width = 0;
	$height = 0;
	
	//Odczyt ciasteczek
	if(isset($_COOKIE['w'])) {
		$width = $_COOKIE['w'];
	}
	if(isset($_COOKIE['h'])) {
		$height = $_COOKIE['h'];
	}
	
	return array($width, $height);
}

function setOneCookie($name, $value, $expiryDate = 1, $expiryTime = 0)
{
	if($expiryDate == 0 && $expiryTime == 0) {
		$expiryDate = 1;
	}
	
	$expire = time() + (3600*24*$expiryDate) + (3600*$expiryTime);
	
	//Zapis ciasteczek
	setcookie($name, $value, $expire);
}

function getOneCookie($name)
{
	$value = 0;
	
	//Odczyt ciasteczek
	if(isset($_COOKIE[$name])) {
		$value = $_COOKIE[$name];
	}
	
	return $value;
}

function getCountriesName()
{
	$countries = [];
	
	$countries = ['Afganistan', 
		'Albania', 
		'Algieria', 
		'Andora', 
		'Angola', 
		'Anguilla', 
		'Antigua i Barbuda', 
		'Antyle Holenderskie', 
		'Arabia Saudyjska', 
		'Argentyna', 
		'Armenia', 
		'Aruba', 
		'Australia', 
		'Austria', 
		'Azerbejdżan', 
		'Bahama', 
		'Bahrajn', 
		'Bangladesz', 
		'Barbados', 
		'Belau', 
		'Belgia', 
		'Belize', 
		'Benin', 
		'Bermudy', 
		'Bhutan', 
		'Białoruś', 
		'Birma', 
		'Mianmar', 
		'Boliwia', 
		'Bośnia i Hercegowina', 
		'Botswana', 
		'Brazylia', 
		'Brunei', 
		'Bułgaria', 
		'Burkina Faso', 
		'Burundi', 
		'Chile', 
		'Chiny', 
		'Chorwacja', 
		'Cypr', 
		'Czad', 
		'Czechy', 
		'Dania', 
		'Demokratyczna Republika Konga', 
		'Zair', 
		'Diego Garcia', 
		'Dominika', 
		'Dominikana', 
		'Dziewicze Wyspy Brytyjskie', 
		'Dziewicze Wyspy Stanów Zjednoczonych', 
		'Dżibuti', 
		'Egipt', 
		'Ekwador', 
		'Erytrea', 
		'Estonia', 
		'Etiopia', 
		'Falklandy', 
		'Fidżi', 
		'Filipiny', 
		'Finlandia', 
		'Francja', 
		'Gabon', 
		'Gambia', 
		'Ghana', 
		'Gibraltar', 
		'Grecja', 
		'Grenlandia', 
		'Gruzja', 
		'Guam', 
		'Gujana', 
		'Gujana Francuska', 
		'Gwadelupa', 
		'Gwatemala', 
		'Gwinea', 
		'Gwinea Bissau', 
		'Gwinea Równikowa', 
		'Haiti', 
		'Hiszpania', 
		'Holandia', 
		'Honduras', 
		'Hongkong', 
		'Indie', 
		'Indonezja', 
		'Irak', 
		'Iran', 
		'Irlandia', 
		'Islandia', 
		'Izrael', 
		'Jamusukro', 
		'Jamajka', 
		'Japonia', 
		'Jemen', 
		'Jordania', 
		'Kajmany', 
		'Kambodża', 
		'Kamerun', 
		'Kanada', 
		'Kanaryjskie Wyspy', 
		'Katar', 
		'Kazachstan', 
		'Kenia', 
		'Kirgistan', 
		'Kiribati', 
		'Kolumbia', 
		'Komory', 
		'Kongo', 
		'Korea Południowa', 
		'Korea Północna', 
		'Kostaryka', 
		'Kuba', 
		'Kuwejt', 
		'Laos', 
		'Lesotho', 
		'Liban', 
		'Liberia', 
		'Libia', 
		'Liechtenstein', 
		'Litwa', 
		'Luksemburg', 
		'Łotwa', 
		'Macedonia', 
		'Madagaskar', 
		'Makau', 
		'Malawi', 
		'Malediwy', 
		'Malezja', 
		'Mali', 
		'Malta', 
		'Mariany Północne', 
		'Saipan', 
		'Maroko', 
		'Martynika', 
		'Mauretania', 
		'Mauritius', 
		'Meksyk', 
		'Mikronezja', 
		'Mołdawia', 
		'Monako', 
		'Mongolia', 
		'Montserrat', 
		'Mozambik', 
		'Namibia', 
		'Nauru', 
		'Nepal', 
		'Niemcy', 
		'Niger', 
		'Nigeria', 
		'Nikaragua', 
		'Niue', 
		'Norfolk', 
		'Norwegia', 
		'Nowa Kaledonia', 
		'Nowa Zelandia', 
		'Oman', 
		'Pakistan', 
		'Panama', 
		'Papua Nowa Gwinea', 
		'Paragwaj', 
		'Peru', 
		'Polinezja Francuska', 
		'Polska', 
		'Portugalia', 
		'Portoryko', 
		'Republika Południowej Afryki', 
		'Republika Środkowoafrykańska', 
		'Reunion', 
		'Rosja', 
		'Rumunia', 
		'Rwanda', 
		'Saint Kitts i Nevis', 
		'Saint Lucia', 
		'Saint Vincent i Grenadyny', 
		'Salwador', 
		'Samoa', 
		'Samoa Amerykańskie', 
		'San Marino', 
		'Senegal', 
		'Seszele', 
		'Sierra Leone', 
		'Singapur', 
		'Słowacja', 
		'Słowenia', 
		'Somalia', 
		'Sri Lanka', 
		'Stany Zjednoczone Ameryki', 
		'Suazi', 
		'Sudan', 
		'Surinam', 
		'Syria', 
		'Szwajcaria', 
		'Szwecja', 
		'Tadżykistan', 
		'Tajlandia', 
		'Tajwan', 
		'Tanzania', 
		'Togo', 
		'Tokelau', 
		'Tonga', 
		'Trynidad i Tobago', 
		'Tunezja', 
		'Turcja', 
		'Turkmenistan', 
		'Tuvalu', 
		'Uganda', 
		'Ukraina', 
		'Urugwaj', 
		'Uzbekistan', 
		'Vanuatu', 
		'Walia', 
		'Watykan', 
		'Wenezuela', 
		'Węgry', 
		'Wielka Brytania', 
		'Wietnam', 
		'Włochy', 
		'Wybrzeże Kości Słoniowej', 
		'Wyspa Świętej Heleny', 
		'Wyspy Cooka', 
		'Wyspy Marshalla', 
		'Wyspy Owcze', 
		'Wyspy Salomona', 
		'Wyspy Świętego Piotra i Mikeleona', 
		'Wyspy Świętego Tomasza i Książęca', 
		'Wyspy Turks i Caicos', 
		'Wyspy Wniebowstąpienia', 
		'Zambia', 
		'Zanzibar', 
		'Zimbabwe', 
		'Zjednoczone Emiraty Arabskie'];
	
	return $countries;
}

function countryCodeToCountry($code)
{
	$code = strtoupper($code);
    $country = '';
	
	switch ($code) {
		//Priorytet
		case 'PL':
			$country = 'Polska';
			break;
		
		//Reszta
		case 'AF':
			$country = 'Afghanistan';
			break;
		case 'AX':
			$country = 'Aland Islands';
			break;
		case 'AL':
			$country = 'Albania';
			break;
		case 'DZ':
			$country = 'Algeria';
			break;
		case 'AS':
			$country = 'American Samoa';
			break;
		case 'AD':
			$country = 'Andorra';
			break;
		case 'AO':
			$country = 'Angola';
			break;
		case 'AI':
			$country = 'Anguilla';
			break;
		case 'AQ':
			$country = 'Antarctica';
			break;
		case 'AG':
			$country = 'Antigua and Barbuda';
			break;
		case 'AR':
			$country = 'Argentina';
			break;
		case 'AM':
			$country = 'Armenia';
			break;
		case 'AW':
			$country = 'Aruba';
			break;
		case 'AU':
			$country = 'Australia';
			break;
		case 'AT':
			$country = 'Austria';
			break;
		case 'AZ':
			$country = 'Azerbaijan';
			break;
		case 'BS':
			$country = 'Bahamas the';
			break;
		case 'BH':
			$country = 'Bahrain';
			break;
		case 'BD':
			$country = 'Bangladesh';
			break;
		case 'BB':
			$country = 'Barbados';
			break;
		case 'BY':
			$country = 'Belarus';
			break;
		case 'BE':
			$country = 'Belgium';
			break;
		case 'BZ':
			$country = 'Belize';
			break;
		case 'BJ':
			$country = 'Benin';
			break;
		case 'BM':
			$country = 'Bermuda';
			break;
		case 'BT':
			$country = 'Bhutan';
			break;
		case 'BO':
			$country = 'Bolivia';
			break;
		case 'BA':
			$country = 'Bosnia and Herzegovina';
			break;
		case 'BW':
			$country = 'Botswana';
			break;
		case 'BV':
			$country = 'Bouvet Island (Bouvetoya)';
			break;
		case 'BR':
			$country = 'Brazil';
			break;
		case 'IO':
			$country = 'British Indian Ocean Territory (Chagos Archipelago)';
			break;
		case 'VG':
			$country = 'British Virgin Islands';
			break;
		case 'BN':
			$country = 'Brunei Darussalam';
			break;
		case 'BG':
			$country = 'Bulgaria';
			break;
		case 'BF':
			$country = 'Burkina Faso';
			break;
		case 'BI':
			$country = 'Burundi';
			break;
		case 'KH':
			$country = 'Cambodia';
			break;
		case 'CM':
			$country = 'Cameroon';
			break;
		case 'CA':
			$country = 'Canada';
			break;
		case 'CV':
			$country = 'Cape Verde';
			break;
		case 'KY':
			$country = 'Cayman Islands';
			break;
		case 'CF':
			$country = 'Central African Republic';
			break;
		case 'TD':
			$country = 'Chad';
			break;
		case 'CL':
			$country = 'Chile';
			break;
		case 'CN':
			$country = 'China';
			break;
		case 'CX':
			$country = 'Christmas Island';
			break;
		case 'CC':
			$country = 'Cocos (Keeling) Islands';
			break;
		case 'CO':
			$country = 'Colombia';
			break;
		case 'KM':
			$country = 'Comoros the';
			break;
		case 'CD':
			$country = 'Congo';
			break;
		case 'CG':
			$country = 'Congo the';
			break;
		case 'CK':
			$country = 'Cook Islands';
			break;
		case 'CR':
			$country = 'Costa Rica';
			break;
		case 'CI':
			$country = 'Cote d\'Ivoire';
			break;
		case 'HR':
			$country = 'Croatia';
			break;
		case 'CU':
			$country = 'Cuba';
			break;
		case 'CY':
			$country = 'Cyprus';
			break;
		case 'CZ':
			$country = 'Czech Republic';
			break;
		case 'DK':
			$country = 'Denmark';
			break;
		case 'DJ':
			$country = 'Djibouti';
			break;
		case 'DM':
			$country = 'Dominica';
			break;
		case 'DO':
			$country = 'Dominican Republic';
			break;
		case 'EC':
			$country = 'Ecuador';
			break;
		case 'EG':
			$country = 'Egypt';
			break;
		case 'SV':
			$country = 'El Salvador';
			break;
		case 'GQ':
			$country = 'Equatorial Guinea';
			break;
		case 'ER':
			$country = 'Eritrea';
			break;
		case 'EE':
			$country = 'Estonia';
			break;
		case 'ET':
			$country = 'Ethiopia';
			break;
		case 'FO':
			$country = 'Faroe Islands';
			break;
		case 'FK':
			$country = 'Falkland Islands (Malvinas)';
			break;
		case 'FJ':
			$country = 'Fiji the Fiji Islands';
			break;
		case 'FI':
			$country = 'Finland';
			break;
		case 'FR':
			$country = 'France, French Republic';
			break;
		case 'GF':
			$country = 'French Guiana';
			break;
		case 'PF':
			$country = 'French Polynesia';
			break;
		case 'TF':
			$country = 'French Southern Territories';
			break;
		case 'GA':
			$country = 'Gabon';
			break;
		case 'GM':
			$country = 'Gambia the';
			break;
		case 'GE':
			$country = 'Georgia';
			break;
		case 'DE':
			$country = 'Germany';
			break;
		case 'GH':
			$country = 'Ghana';
			break;
		case 'GI':
			$country = 'Gibraltar';
			break;
		case 'GR':
			$country = 'Greece';
			break;
		case 'GL':
			$country = 'Greenland';
			break;
		case 'GD':
			$country = 'Grenada';
			break;
		case 'GP':
			$country = 'Guadeloupe';
			break;
		case 'GU':
			$country = 'Guam';
			break;
		case 'GT':
			$country = 'Guatemala';
			break;
		case 'GG':
			$country = 'Guernsey';
			break;
		case 'GN':
			$country = 'Guinea';
			break;
		case 'GW':
			$country = 'Guinea-Bissau';
			break;
		case 'GY':
			$country = 'Guyana';
			break;
		case 'HT':
			$country = 'Haiti';
			break;
		case 'HM':
			$country = 'Heard Island and McDonald Islands';
			break;
		case 'VA':
			$country = 'Holy See (Vatican City State)';
			break;
		case 'HN':
			$country = 'Honduras';
			break;
		case 'HK':
			$country = 'Hong Kong';
			break;
		case 'HU':
			$country = 'Hungary';
			break;
		case 'IS':
			$country = 'Iceland';
			break;
		case 'IN':
			$country = 'India';
			break;
		case 'ID':
			$country = 'Indonesia';
			break;
		case 'IR':
			$country = 'Iran';
			break;
		case 'IQ':
			$country = 'Iraq';
			break;
		case 'IE':
			$country = 'Ireland';
			break;
		case 'IM':
			$country = 'Isle of Man';
			break;
		case 'IL':
			$country = 'Israel';
			break;
		case 'IT':
			$country = 'Italy';
			break;
		case 'JM':
			$country = 'Jamaica';
			break;
		case 'JP':
			$country = 'Japan';
			break;
		case 'JE':
			$country = 'Jersey';
			break;
		case 'JO':
			$country = 'Jordan';
			break;
		case 'KZ':
			$country = 'Kazakhstan';
			break;
		case 'KE':
			$country = 'Kenya';
			break;
		case 'KI':
			$country = 'Kiribati';
			break;
		case 'KP':
			$country = 'Korea';
			break;
		case 'KR':
			$country = 'Korea';
			break;
		case 'KW':
			$country = 'Kuwait';
			break;
		case 'KG':
			$country = 'Kyrgyz Republic';
			break;
		case 'LA':
			$country = 'Lao';
			break;
		case 'LV':
			$country = 'Latvia';
			break;
		case 'LB':
			$country = 'Lebanon';
			break;
		case 'LS':
			$country = 'Lesotho';
			break;
		case 'LR':
			$country = 'Liberia';
			break;
		case 'LY':
			$country = 'Libyan Arab Jamahiriya';
			break;
		case 'LI':
			$country = 'Liechtenstein';
			break;
		case 'LT':
			$country = 'Lithuania';
			break;
		case 'LU':
			$country = 'Luxembourg';
			break;
		case 'MO':
			$country = 'Macao';
			break;
		case 'MK':
			$country = 'Macedonia';
			break;
		case 'MG':
			$country = 'Madagascar';
			break;
		case 'MW':
			$country = 'Malawi';
			break;
		case 'MY':
			$country = 'Malaysia';
			break;
		case 'MV':
			$country = 'Maldives';
			break;
		case 'ML':
			$country = 'Mali';
			break;
		case 'MT':
			$country = 'Malta';
			break;
		case 'MH':
			$country = 'Marshall Islands';
			break;
		case 'MQ':
			$country = 'Martinique';
			break;
		case 'MR':
			$country = 'Mauritania';
			break;
		case 'MU':
			$country = 'Mauritius';
			break;
		case 'YT':
			$country = 'Mayotte';
			break;
		case 'MX':
			$country = 'Mexico';
			break;
		case 'FM':
			$country = 'Micronesia';
			break;
		case 'MD':
			$country = 'Moldova';
			break;
		case 'MC':
			$country = 'Monaco';
			break;
		case 'MN':
			$country = 'Mongolia';
			break;
		case 'ME':
			$country = 'Montenegro';
			break;
		case 'MS':
			$country = 'Montserrat';
			break;
		case 'MA':
			$country = 'Morocco';
			break;
		case 'MZ':
			$country = 'Mozambique';
			break;
		case 'MM':
			$country = 'Myanmar';
			break;
		case 'NA':
			$country = 'Namibia';
			break;
		case 'NR':
			$country = 'Nauru';
			break;
		case 'NP':
			$country = 'Nepal';
			break;
		case 'AN':
			$country = 'Netherlands Antilles';
			break;
		case 'NL':
			$country = 'Netherlands the';
			break;
		case 'NC':
			$country = 'New Caledonia';
			break;
		case 'NZ':
			$country = 'New Zealand';
			break;
		case 'NI':
			$country = 'Nicaragua';
			break;
		case 'NE':
			$country = 'Niger';
			break;
		case 'NG':
			$country = 'Nigeria';
			break;
		case 'NU':
			$country = 'Niue';
			break;
		case 'NF':
			$country = 'Norfolk Island';
			break;
		case 'MP':
			$country = 'Northern Mariana Islands';
			break;
		case 'NO':
			$country = 'Norway';
			break;
		case 'OM':
			$country = 'Oman';
			break;
		case 'PK':
			$country = 'Pakistan';
			break;
		case 'PW':
			$country = 'Palau';
			break;
		case 'PS':
			$country = 'Palestinian Territory';
			break;
		case 'PA':
			$country = 'Panama';
			break;
		case 'PG':
			$country = 'Papua New Guinea';
			break;
		case 'PY':
			$country = 'Paraguay';
			break;
		case 'PE':
			$country = 'Peru';
			break;
		case 'PH':
			$country = 'Philippines';
			break;
		case 'PN':
			$country = 'Pitcairn Islands';
			break;
		case 'PT':
			$country = 'Portugal, Portuguese Republic';
			break;
		case 'PR':
			$country = 'Puerto Rico';
			break;
		case 'QA':
			$country = 'Qatar';
			break;
		case 'RE':
			$country = 'Reunion';
			break;
		case 'RO':
			$country = 'Romania';
			break;
		case 'RU':
			$country = 'Russian Federation';
			break;
		case 'RW':
			$country = 'Rwanda';
			break;
		case 'BL':
			$country = 'Saint Barthelemy';
			break;
		case 'SH':
			$country = 'Saint Helena';
			break;
		case 'KN':
			$country = 'Saint Kitts and Nevis';
			break;
		case 'LC':
			$country = 'Saint Lucia';
			break;
		case 'MF':
			$country = 'Saint Martin';
			break;
		case 'PM':
			$country = 'Saint Pierre and Miquelon';
			break;
		case 'VC':
			$country = 'Saint Vincent and the Grenadines';
			break;
		case 'WS':
			$country = 'Samoa';
			break;
		case 'SM':
			$country = 'San Marino';
			break;
		case 'ST':
			$country = 'Sao Tome and Principe';
			break;
		case 'SA':
			$country = 'Saudi Arabia';
			break;
		case 'SN':
			$country = 'Senegal';
			break;
		case 'RS':
			$country = 'Serbia';
			break;
		case 'SC':
			$country = 'Seychelles';
			break;
		case 'SL':
			$country = 'Sierra Leone';
			break;
		case 'SG':
			$country = 'Singapore';
			break;
		case 'SK':
			$country = 'Slovakia (Slovak Republic)';
			break;
		case 'SI':
			$country = 'Slovenia';
			break;
		case 'SB':
			$country = 'Solomon Islands';
			break;
		case 'SO':
			$country = 'Somalia, Somali Republic';
			break;
		case 'ZA':
			$country = 'South Africa';
			break;
		case 'GS':
			$country = 'South Georgia and the South Sandwich Islands';
			break;
		case 'ES':
			$country = 'Spain';
			break;
		case 'LK':
			$country = 'Sri Lanka';
			break;
		case 'SD':
			$country = 'Sudan';
			break;
		case 'SR':
			$country = 'Suriname';
			break;
		case 'SJ':
			$country = 'Svalbard & Jan Mayen Islands';
			break;
		case 'SZ':
			$country = 'Swaziland';
			break;
		case 'SE':
			$country = 'Sweden';
			break;
		case 'CH':
			$country = 'Switzerland, Swiss Confederation';
			break;
		case 'SY':
			$country = 'Syrian Arab Republic';
			break;
		case 'TW':
			$country = 'Taiwan';
			break;
		case 'TJ':
			$country = 'Tajikistan';
			break;
		case 'TZ':
			$country = 'Tanzania';
			break;
		case 'TH':
			$country = 'Thailand';
			break;
		case 'TL':
			$country = 'Timor-Leste';
			break;
		case 'TG':
			$country = 'Togo';
			break;
		case 'TK':
			$country = 'Tokelau';
			break;
		case 'TO':
			$country = 'Tonga';
			break;
		case 'TT':
			$country = 'Trinidad and Tobago';
			break;
		case 'TN':
			$country = 'Tunisia';
			break;
		case 'TR':
			$country = 'Turkey';
			break;
		case 'TM':
			$country = 'Turkmenistan';
			break;
		case 'TC':
			$country = 'Turks and Caicos Islands';
			break;
		case 'TV':
			$country = 'Tuvalu';
			break;
		case 'UG':
			$country = 'Uganda';
			break;
		case 'UA':
			$country = 'Ukraine';
			break;
		case 'AE':
			$country = 'United Arab Emirates';
			break;
		case 'GB':
			$country = 'United Kingdom';
			break;
		case 'US':
			$country = 'United States of America';
			break;
		case 'UM':
			$country = 'United States Minor Outlying Islands';
			break;
		case 'VI':
			$country = 'United States Virgin Islands';
			break;
		case 'UY':
			$country = 'Uruguay, Eastern Republic of';
			break;
		case 'UZ':
			$country = 'Uzbekistan';
			break;
		case 'VU':
			$country = 'Vanuatu';
			break;
		case 'VE':
			$country = 'Venezuela';
			break;
		case 'VN':
			$country = 'Vietnam';
			break;
		case 'WF':
			$country = 'Wallis and Futuna';
			break;
		case 'EH':
			$country = 'Western Sahara';
			break;
		case 'YE':
			$country = 'Yemen';
			break;
		case 'ZM':
			$country = 'Zambia';
			break;
		case 'ZW':
			$country = 'Zimbabwe';
			break;
		
		//Standardowy wybór
		default:
			$country = $code;
			break;
	}
	
	return $country;
}
?>
