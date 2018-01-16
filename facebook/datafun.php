<?php
use \Statickidz\GoogleTranslate;

function generateNewData()
{
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://api.namefake.com/polish-poland/random/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Type: text/html',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	
	//Sprawdzenie typu
	$typ = gettype($response);
	
	//Sprawdzenie informacji
	if($typ == "array") {
		$generatedData = serialize($response);
	}
	else {
		$generatedData = $response;
	}
	
	//Szukanie przekierowania
	if (preg_match('/(href)/', $generatedData, $match)) {
		$od = strpos($generatedData, "href");
		$temp = substr($generatedData, $od+6);
		$do = strpos($temp, ">");
		$adres = substr($temp, 0, $do-1);
	}
	else {
		$adres = $redirect;
	}
	
	return array($adres, $generatedData);
}

function polishCharactersCorrection($data)
{
	$znaki = array(
		"\u0100" => "Ā",
		"\u0101" => "ā",
		"\u0102" => "Ă",
		"\u0103" => "ă",
		"\u0104" => "Ą",
		"\u0105" => "ą",
		"\u0106" => "Ć",
		"\u0107" => "ć",
		"\u0108" => "Ĉ",
		"\u0109" => "ĉ",
		"\u010A" => "Ċ",
		"\u010B" => "ċ",
		"\u010C" => "Č",
		"\u010D" => "č",
		"\u010E" => "Ď",
		"\u010F" => "ď",
		"\u0110" => "Đ",
		"\u0111" => "đ",
		"\u0112" => "Ē",
		"\u0113" => "ē",
		"\u0114" => "Ĕ",
		"\u0115" => "ĕ",
		"\u0116" => "Ė",
		"\u0117" => "ė",
		"\u0118" => "Ę",
		"\u0119" => "ę",
		"\u011A" => "Ě",
		"\u011B" => "ě",
		"\u011C" => "Ĝ",
		"\u011D" => "ĝ",
		"\u011E" => "Ğ",
		"\u011F" => "ğ",
		"\u0120" => "Ġ",
		"\u0121" => "ġ",
		"\u0122" => "Ģ",
		"\u0123" => "ģ",
		"\u0124" => "Ĥ",
		"\u0125" => "ĥ",
		"\u0126" => "Ħ",
		"\u0127" => "ħ",
		"\u0128" => "Ĩ",
		"\u0129" => "ĩ",
		"\u012A" => "Ī",
		"\u012B" => "ī",
		"\u012C" => "Ĭ",
		"\u012D" => "ĭ",
		"\u012E" => "Į",
		"\u012F" => "į",
		"\u0130" => "İ",
		"\u0131" => "ı",
		"\u0132" => "Ĳ",
		"\u0133" => "ĳ",
		"\u0134" => "Ĵ",
		"\u0135" => "ĵ",
		"\u0136" => "Ķ",
		"\u0137" => "ķ",
		"\u0138" => "ĸ",
		"\u0139" => "Ĺ",
		"\u013A" => "ĺ",
		"\u013B" => "Ļ",
		"\u013C" => "ļ",
		"\u013D" => "Ľ",
		"\u013E" => "ľ",
		"\u013F" => "Ŀ",
		"\u0140" => "ŀ",
		"\u0141" => "Ł",
		"\u0142" => "ł",
		"\u0143" => "Ń",
		"\u0144" => "ń",
		"\u0145" => "Ņ",
		"\u0146" => "ņ",
		"\u0147" => "Ň",
		"\u0148" => "ň",
		"\u0149" => "ŉ",
		"\u014A" => "Ŋ",
		"\u014B" => "ŋ",
		"\u014C" => "Ō",
		"\u014D" => "ō",
		"\u014E" => "Ŏ",
		"\u014F" => "ŏ",
		"\u0150" => "Ő",
		"\u0151" => "ő",
		"\u0152" => "Œ",
		"\u0153" => "œ",
		"\u0154" => "Ŕ",
		"\u0155" => "ŕ",
		"\u0156" => "Ŗ",
		"\u0157" => "ŗ",
		"\u0158" => "Ř",
		"\u0159" => "ř",
		"\u015A" => "Ś",
		"\u015B" => "ś",
		"\u015C" => "Ŝ",
		"\u015D" => "ŝ",
		"\u015E" => "Ş",
		"\u015F" => "ş",
		"\u0160" => "Š",
		"\u0161" => "š",
		"\u0162" => "Ţ",
		"\u0163" => "ţ",
		"\u0164" => "Ť",
		"\u0165" => "ť",
		"\u0166" => "Ŧ",
		"\u0167" => "ŧ",
		"\u0168" => "Ũ",
		"\u0169" => "ũ",
		"\u016A" => "Ū",
		"\u016B" => "ū",
		"\u016C" => "Ŭ",
		"\u016D" => "ŭ",
		"\u016E" => "Ů",
		"\u016F" => "ů",
		"\u0170" => "Ű",
		"\u0171" => "ű",
		"\u0172" => "Ų",
		"\u0173" => "ų",
		"\u0174" => "Ŵ",
		"\u0175" => "ŵ",
		"\u0176" => "Ŷ",
		"\u0177" => "ŷ",
		"\u0178" => "Ÿ",
		"\u0179" => "Ź",
		"\u017A" => "ź",
		"\u017B" => "Ż",
		"\u017C" => "ż",
		"\u017D" => "Ž",
		"\u017E" => "ž",
		"\u017F" => "ſ",
		
		"\u00f3" => "ó",
		"\u0141" => "Ł",
		"\u015a" => "Ś",
		"\u015b" => "ś",
		"\u017a" => "ź",
		"\u017b" => "Ż",
		"\u017c" => "ż",
		"\u2212" => "-",
		
		"inż. inż." => "inż.",
		"mgr mgr" => "mgr",
		"dr dr" => "dr",
		"doc. doc." => "doc.",
		"\/" => "/"
	);
	
	$i = 0;
	foreach($znaki as $key => $value) {
		$znakiPrzed[$i] = $key;
		$znakiPo[$i] = $value;
		$i++;
	}
	
	$ile = count($znaki);
	for($i = 0; $i < $ile; $i++) {
		$data = str_replace($znakiPrzed[$i], $znakiPo[$i], $data);
	}
	
	return $data;
}

function translate($path, $tekst, $jezykTekstu = "en", $jezykDocelowy = "pl")
{
	//Klucz Google Translate API
	$apiKey = getspecialdata($path, 'facelike', 'google_translate', 'api_key');
	
	require_once "translate/src/GoogleTranslate.php";
	
	$trans = new GoogleTranslate();
	$response = $trans->translate($jezykTekstu, $jezykDocelowy, $tekst);
	
	//Pobieranie informacji o języku
	/*$jezykWykrycie = "%E6%9D%8E%E5%85%8B%E5%BC%B7%E6%AD%A4%E8%A1%8C%E5%B0%87%E5%95%9F%E5%8B%95%E4%B8%AD%E5%8A%A0%20%E7%B8%BD%E7%90%86%E5%B9%B4%E5%BA%A6%E5%B0%8D%E8%A9%B1%E6%A9%9F%E5%88%B6%2C%E8%88%87%20%E5%8A%A0%E6%8B%BF%E5%A4%A7%E7%B8%BD%E7%90%86%E6%9D%9C%E9%AD%AF%E5%A4%9A%E8%88%89%E8%A1%8C%20%E5%85%A9%E5%9C%8B%E7%B8%BD%E7%90%86%E9%A6%96%E6%AC%A1%E5%B9%B4%E5%BA%A6%E5%B0%8D%20%E8%A9%B1%E3%80%82";
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'https://translation.googleapis.com/language/translate/v2/detect/?key='.$apiKey.'&q='.$jezykWykrycie);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Length: 0',
		'Content-Type: application/json',
		'Accept: * /*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);
	
	if($response != null && $response != "") {
		$jezykTekstu = $response;
	}
	
	//Pobieranie informacji o tekście
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'https://translation.googleapis.com/language/translate/v2/?q='.$tekst.'&source='.$jezykTekstu.'&target='.$jezykDocelowy.'&key='.$apiKey);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'Content-Length: 0',
		'Content-Type: application/json',
		'Accept: * /*'
	));
	
	$response = curl_exec($ch);
	
	curl_close($ch);*/
	
	//Sprawdzenie typu
	$typ = gettype($response);
	
	//Sprawdzenie informacji
	if($typ == "array") {
		$dane = serialize($response);
	}
	else {
		$dane = $response;
	}
	
	return $dane;
}

function registerFacebookAccount($path, $userStatisticsDbName, $documentId, $imie, $nazwisko, $plec, $dataUrodzeniaRok, $dataUrodzeniaMiesiac, $dataUrodzeniaDzien, $telefonKomorkowy, $email, $login, $password)
{
	$status = false;
	$adres = "";
	$rok = (int)$dataUrodzeniaRok;
	$miesiac = (int)$dataUrodzeniaMiesiac;
	$dzien = (int)$dataUrodzeniaDzien;
	
	//Aplikacja kliencka
	$loginstatus = getspecialdata($path, $userStatisticsDbName, $documentId, 'loginstatus');
	$aplikacjaKliencka = $loginstatus['client_data']['aplikacja_kliencka'];
	
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://facebook.com/reg/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'User-Agent: '.$aplikacjaKliencka,
		//'Content-Length: 0',
		//'Keep-Alive: 115',
		//'Connection: keep-alive',
		'Content-Type: text/html',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	
	
	
	$dane = serialize($response);
	$adres = $redirect;
	
	//<input name="firstname" value="" id="u_0_m">
	//<input name="lastname" value="" id="u_0_o">
	//<input name="reg_email__" id="u_0_r">
	//<input type="text" class="inputtext _58mg _5dba _2ph-" data-type="text" name="reg_email_confirmation__" aria-required="1" placeholder="" aria-label="Wprowadź ponownie adres e-mail" id="u_0_t" aria-describedby="js_al">
	//<input name="reg_passwd__" id="u_0_y">
	//<select name="birthday_day" id="day"><option value="0">Dzień</option><option value="1">1</option> ... <option value="27" selected="1">27</option> ... <option value="30">30</option><option value="31">31</option></select>
	//<select name="birthday_month" id="month"><option value="0">Miesiąc</option><option value="1">sty</option> ... <option value="11">lis</option><option value="12" selected="1">gru</option></select>
	//<select name="birthday_year" id="year"><option value="0">Rok</option><option value="2017">2017</option><option value="2016">2016</option> ... <option value="1992" selected="1">1992</option> ... <option value="1906">1906</option><option value="1905">1905</option></select>
	//<span data-type="radio" data-name="gender_wrapper" id="u_0_12"> ... <input type="radio" name="sex" value="1" id="u_0_8"> ... <input type="radio" name="sex" value="2" id="u_0_9">
	//<button name="websubmit" id="u_0_14">Utwórz konto</button>
	$szukaneImie = 'name="firstname"';
	$szukaneNazwisko = 'name="lastname"';
	$szukaneMail = 'name="reg_email__"';
	$szukaneMailPotwierdzenie = 'name="reg_email_confirmation__"';
	$szukaneHaslo = 'name="reg_passwd__"';
	$szukaneDataUrodzeniaDzien = 'name="birthday_day"';
	$szukaneDataUrodzeniaMiesiac = 'name="birthday_month"';
	$szukaneDataUrodzeniaRok = 'name="birthday_year"';
	$szukanePlecWybor = 'name="gender_wrapper"';
	$szukanePlec = 'name="sex"';
	$szukanePrzycisk = 'name="websubmit"';
	$szukaneKoniecInput = '>';
	$szukaneKoniecSelect = '/select';
	$szukaneKoniecSpan = '/span';
	$szukaneKoniecButton = '/button';
	
	$pozycjaImie = stripos($dane, $szukaneImie);
	$temp = substr($dane, $pozycjaImie);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecInput);
	$daneImie = substr($dane, $pozycjaImie, $pozycjaKoniec+1);
	$dane = preg_replace('/'.$szukaneImie.'.value=""/', $szukaneImie.' value='.$imie, $dane);
	
	$pozycjaNazwisko = stripos($dane, $szukaneNazwisko);
	$temp = substr($dane, $pozycjaNazwisko);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecInput);
	$daneNazwisko = substr($dane, $pozycjaNazwisko, $pozycjaKoniec+1);
	$dane = preg_replace('/'.$szukaneNazwisko.'.value=""/', $szukaneNazwisko.' value='.$nazwisko, $dane);
	
	$pozycjaMail = stripos($dane, $szukaneMail);
	$temp = substr($dane, $pozycjaMail);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecInput);
	$daneMail = substr($dane, $pozycjaMail, $pozycjaKoniec+1);
	$dane = preg_replace('/'.$szukaneMail.'/', $szukaneMail.' value='.$email, $dane);
	
	$pozycjaMailPotwierdzenie = stripos($dane, $szukaneMailPotwierdzenie);
	$temp = substr($dane, $pozycjaMailPotwierdzenie);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecInput);
	$daneMailPotwierdzenie = substr($dane, $pozycjaMailPotwierdzenie, $pozycjaKoniec+1);
	$dane = preg_replace('/class="hidden_elem" id="u_0_s"/', 'class="_5-ah" id="u_0_s"', $dane);
	$dane = preg_replace('/style="opacity:.1e-05/', 'style="opacity: 1', $dane);
	$dane = preg_replace('/'.$szukaneMailPotwierdzenie.'/', $szukaneMailPotwierdzenie.' value='.$email, $dane);
	
	$pozycjaHaslo = stripos($dane, $szukaneHaslo);
	$temp = substr($dane, $pozycjaHaslo);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecInput);
	$daneHaslo = substr($dane, $pozycjaHaslo, $pozycjaKoniec+1);
	$dane = preg_replace('/'.$szukaneHaslo.'/', $szukaneHaslo.' value='.$password, $dane);
	
	$pozycjaDataUrodzeniaDzien = stripos($dane, $szukaneDataUrodzeniaDzien);
	$temp = substr($dane, $pozycjaDataUrodzeniaDzien);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecSelect);
	$daneDataUrodzeniaDzien = substr($dane, $pozycjaDataUrodzeniaDzien, $pozycjaKoniec+1);
	$dane = preg_replace('/selected="1">.{2}</', '>XX<', $dane);
	$dane = preg_replace('/option value="'.$dzien.'">'.$dzien.'</', 'option value='.$dzien.' selected="1">'.$dzien.'<', $dane);
	
	$pozycjaDataUrodzeniaMiesiac = stripos($dane, $szukaneDataUrodzeniaMiesiac);
	$temp = substr($dane, $pozycjaDataUrodzeniaMiesiac);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecSelect);
	$daneDataUrodzeniaMiesiac = substr($dane, $pozycjaDataUrodzeniaMiesiac, $pozycjaKoniec+1);
	$dane = preg_replace('/selected="1">.{3}</', '>XXX<', $dane);
	$dane = preg_replace('/option value="'.$miesiac.'">'.getmiesiac($miesiac).'</', 'option value='.$miesiac.' selected="1">'.getmiesiac($miesiac).'<', $dane);
	
	$pozycjaDataUrodzeniaRok = stripos($dane, $szukaneDataUrodzeniaRok);
	$temp = substr($dane, $pozycjaDataUrodzeniaRok);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecSelect);
	$daneDataUrodzeniaRok = substr($dane, $pozycjaDataUrodzeniaRok, $pozycjaKoniec+1);
	$dane = preg_replace('/selected="1">.{4}</', '>XXXX<', $dane);
	$dane = preg_replace('/option value="'.$rok.'">'.$rok.'</', 'option value='.$rok.' selected="1">'.$rok.'<', $dane);
	
	$pozycjaPlec = stripos($dane, $szukanePlecWybor);
	$temp = substr($dane, $pozycjaPlec);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecInput);
	$danePlec = substr($dane, $pozycjaPlec, $pozycjaKoniec+1);
	if($plec == "k") {
		$dane = preg_replace('/'.$szukanePlec.' value="1"/', $szukanePlec.' value="1" checked', $dane);
	}
	else {
		$dane = preg_replace('/'.$szukanePlec.' value="2"/', $szukanePlec.' value="2" checked', $dane);
	}
	
	$pozycjaPrzycisk = stripos($dane, $szukanePrzycisk);
	$temp = substr($dane, $pozycjaPrzycisk);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecButton);
	$danePrzycisk = substr($dane, $pozycjaPrzycisk, $pozycjaKoniec);
	
	//Wysłanie danych
	$status = true;
	
	
	
	return array($status, $adres, $daneImie, $daneNazwisko, $daneMail, $daneMailPotwierdzenie, $daneHaslo, $daneDataUrodzeniaDzien, $daneDataUrodzeniaMiesiac, $daneDataUrodzeniaRok, $danePlec, $danePrzycisk, $dane);
}

function activateFacebookAccount($path, $userStatisticsDbName, $documentId, $email, $emailUrl)
{
	$status = false;
	$adres = "";
	$wiadomoscAdres = "";
	$kod = "";
	$danePrzycisk = "";
	$accountId = "";
	
	//Aplikacja kliencka
	$loginstatus = getspecialdata($path, $userStatisticsDbName, $documentId, 'loginstatus');
	$aplikacjaKliencka = $loginstatus['client_data']['aplikacja_kliencka'];
	
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $emailUrl.'/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'User-Agent: '.$aplikacjaKliencka,
		'Content-Type: text/html',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	
	$dane = serialize($response);
	$adres = $redirect;
	
	$szukaneSerwis = '//';
	$szukaneKoniecSerwis = '/';
	$szukaneMail = 'id="email-table"';
	$szukaneWiadomosc = 'Twój kod potwierdzający konto na Facebooku';
	$szukaneLink = '<a href="';
	$szukaneKoniecLink = '"';
	
	$pozycjaSerwis = stripos($adres, $szukaneSerwis);
	$temp = substr($adres, $pozycjaSerwis+2);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecSerwis);
	$serwis = substr($adres, $pozycjaSerwis+2, $pozycjaKoniec);
	
	$pozycjaMail = stripos($dane, $szukaneMail);
	$temp = substr($dane, $pozycjaMail);
	$pozycjaKoniec = stripos($temp, $szukaneWiadomosc);
	$daneMail = substr($dane, $pozycjaMail, $pozycjaKoniec);
	$pozycjaLink = strripos($daneMail, $szukaneLink);
	$temp = substr($daneMail, $pozycjaLink+9);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecLink);
	$link = substr($daneMail, $pozycjaLink+9, $pozycjaKoniec);
	
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://'.$serwis.$link.'/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'User-Agent: '.$aplikacjaKliencka,
		'Content-Type: text/html',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	
	$wiadomoscDane = serialize($response);
	$wiadomoscAdres = $redirect;
	
	$szukaneTekst = 'Może być konieczne wprowadzenie tego kodu potwierdzającego';
	$szukaneKoniecSrodek = '</center>';
	$szukaneKomorka = '>';
	$szukaneKoniecKomorka = '</td>';
	$szukanePrzycisk = 'Potwierdź swoje konto';
	$szukaneKoniecPrzycisk = '</a>';
	
	$szukaneLink = '<a href="';
	$szukaneKoniecLink = '"';
	//<a href="//to-email.com/l/aHR0cHM6Ly93d3cuZmFjZWJvb2suY29tL24vP2NvbmZpcm1lbWFpbC5waHAmZT1hbml0YS5ub3dha293c2slNDB0YXJtYS5tbCZjPTMwMjU2JmN1aWQ9QVloLXNIV1hMcjZvX1NpM1NtajdId29XNW9kUTRlempiRDdBUDQ2TGdBSHpCcEc5YnMwRzZGQ1ZnaGlLbUIzMFF0QWdETGRpRzdhYjlHQkhoclBmdm5fZzZpd0wyc09ydGdfc1JTc2dXSDZJc2pZRHZ6QWlYdkJMX01wenBLTkl5RUEmbWVkaXVtPWVtYWlsJm1pZD01NjFmZGQ1NWU1OGMxRzVhZjg5OGQxNDY5Nkc1NjFmZTFlZjQ1YjkzRzNjMiZuX209YW5pdGEubm93YWtvd3NrJTQwdGFybWEubWw=" style="color: #3b5998; text-decoration: none; display: block" rel="nofollow" target="_blank"><center><font size="3"><span style="font-family: Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif; white-space: nowrap; font-weight: bold; vertical-align: middle; color: #ffffff; text-shadow: 0 -1px 0 #415686; font-size: 14px; line-height: 14px">Potwierdź&nbsp;swoje&nbsp;konto</span></font></center></a>
	//<center><font size="3"><span style="font-family: Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif; white-space: nowrap; font-weight: bold; vertical-align: middle; color: #ffffff; text-shadow: 0 -1px 0 #415686; font-size: 14px; line-height: 14px">Potwierdź swoje konto</span></font></center>
	
	$pozycjaTekst = stripos($wiadomoscDane, $szukaneTekst);
	$temp = substr($wiadomoscDane, $pozycjaTekst);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecSrodek);
	$temp = substr($wiadomoscDane, $pozycjaTekst, $pozycjaKoniec);
	$pozycjaKoniec = strripos($temp, $szukaneKoniecKomorka);
	$temp = substr($wiadomoscDane, $pozycjaTekst, $pozycjaKoniec);
	$pozycjaKomorka = strripos($temp, $szukaneKomorka);
	$kod = substr($temp, $pozycjaKomorka+1);
	
	$pozycjaPrzycisk = stripos($wiadomoscDane, $szukanePrzycisk);
	$temp = substr($wiadomoscDane, $pozycjaPrzycisk);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecPrzycisk);
	$temp = substr($wiadomoscDane, 0, $pozycjaPrzycisk+$pozycjaKoniec);
	$pozycjaLink = strripos($temp, $szukaneLink);
	$temp = substr($temp, $pozycjaLink+9);
	$pozycjaKoniec = stripos($temp, $szukaneKoniecLink);
	$danePrzycisk = substr($temp, 0, $pozycjaKoniec);
	
	
	
	return array($status, $adres, $wiadomoscAdres, $kod, $danePrzycisk, $accountId, $wiadomoscDane);
}

function loginFacebookAccount($path, $userStatisticsDbName, $documentId, $accountId, $email, $login, $password)
{
	$status = false;
	$adres = "";
	$dane = "";
	
	//Ustawienie ciasteczka logowania
	$ciasteczkoNazwa = 'c_user';
	$ciasteczkoWartosc = $accountId;
	$ciasteczkoWaznosc = 90;
	
	//Zapis ciasteczka logowania
	?>
	<script type="text/javascript">
	//<![CDATA[
		var name = '<?php echo $ciasteczkoNazwa; ?>';
		var value = '<?php echo $ciasteczkoWartosc; ?>';
		var expire = '<?php echo $ciasteczkoWaznosc; ?>';
		
		function setCookie(cookieName, cookieValue, nDays) {
			var today = new Date();
			var expire = new Date();
			
			if(nDays == null || nDays == 0)
				nDays = 1;
			
			expire.setTime(today.getTime() + (3600000*24*nDays));
			document.cookie = cookieName + "=" + cookieValue + "; expires=" + expire.toGMTString() + "; path=/";
		}
		
		setCookie('datr', 'ippTWk1jYJurvwGiqNNDgttW', expire);    //test
		setCookie('xs', '25%3AaE-wPCNIc2x1Cg%3A2%3A1515427809%3A-1%3A-1', expire);    //test
		setCookie(name, value, expire);
		//]]>
	</script>
	<?php
	
	/**/
	?>
	<script>
		require("TimeSlice").guard(function() {(require("ServerJSDefine")).handleDefines([["BootloaderConfig",[],{"jsRetries":[200,500],"jsRetryAbortNum":2,"jsRetryAbortTime":5,"payloadEndpointURI":"https:\/\/www.facebook.com\/ajax\/haste-response\/","assumeNotNonblocking":false,"assumePermanent":false,"skipEndpoint":true},329],["CSSLoaderConfig",[],{"timeout":5000,"modulePrefix":"BLCSS:","loadEventSupported":true},619],["CookieCoreConfig",[],{"a11y":{},"act":{},"c_user":{},"ddid":{"p":"\/deferreddeeplink\/","t":2419200},"dpr":{"t":604800},"js_ver":{"t":604800},"locale":{"t":604800},"lh":{"t":604800},"m_pixel_ratio":{"t":604800},"noscript":{},"pnl_data2":{"t":2},"presence":{},"rdir":{},"sW":{},"sfau":{},"wd":{"t":604800},"x-referer":{},"x-src":{"t":1}},2104],["CurrentCommunityInitialData",[],{},490],["CurrentUserInitialData",[],{"USER_ID":"100023762241174","ACCOUNT_ID":"100023762241174","NAME":"Anita Nowak","SHORT_NAME":"Anita","IS_MESSENGER_ONLY_USER":false},270],["DTSGInitialData",[],{"token":"AQFX0_k_KuQa:AQHwPXMp42Jn"},258],["ISB",[],{},330],["LSD",[],{},323],["SiteData",[],{"server_revision":3559954,"client_revision":3559954,"tier":"","push_phase":"C3","pkg_cohort":"EXP4:home_page_pkg","pkg_cohort_key":"__pc","haste_site":"www","be_mode":1,"be_key":"__be","is_rtl":false,"spin":2,"__spin_r":3559954,"__spin_b":"trunk","__spin_t":1515424482,"vip":"31.13.81.36"},317],["SprinkleConfig",[],{"param_name":"jazoest"},2111],["URLFragmentPreludeConfig",[],{"incorporateQuicklingFragment":true,"hashtagRedirect":true},137],["BigPipeExperiments",[],{"link_images_to_pagelets":false,"enable_bigpipe_plugins":false},907],["EventConfig",[],{"sampling":{"bandwidth":0,"play":0,"playing":0,"progress":0,"pause":0,"ended":0,"seeked":0,"seeking":0,"waiting":0,"loadedmetadata":0,"canplay":0,"selectionchange":0,"change":0,"timeupdate":2000000,"adaptation":0,"focus":0,"blur":0,"load":0,"error":0,"message":0,"abort":0,"storage":0,"scroll":200000,"mousemove":20000,"mouseover":10000,"mouseout":10000,"mousewheel":1,"MSPointerMove":10000,"keydown":0.1,"click":0.02,"mouseup":0.02,"__100ms":0.001,"__default":5000,"__min":100,"__interactionDefault":200,"__eventDefault":100000},"page_sampling_boost":1,"interaction_regexes":{"BlueBarAccountChevronMenu":" _5lxs(?: .*)?$","BlueBarHomeButton":" _bluebarLinkHome__interaction-root(?: .*)?$","BlueBarProfileLink":" _1k67(?: .*)?$","ReactComposerSproutMedia":" _1pnt(?: .*)?$","ReactComposerSproutAlbum":" _1pnu(?: .*)?$","ReactComposerSproutNote":" _3-9x(?: .*)?$","ReactComposerSproutLocation":" _1pnv(?: .*)?$","ReactComposerSproutActivity":" _1pnz(?: .*)?$","ReactComposerSproutPeople":" _1pn-(?: .*)?$","ReactComposerSproutLiveVideo":" _5tv7(?: .*)?$","ReactComposerSproutMarkdown":" _311p(?: .*)?$","ReactComposerSproutFormattedText":" _mwg(?: .*)?$","ReactComposerSproutSticker":" _2vri(?: .*)?$","ReactComposerSproutSponsor":" _5t5q(?: .*)?$","ReactComposerSproutEllipsis":" _1gr3(?: .*)?$","ReactComposerSproutContactYourRepresentative":" _3cnv(?: .*)?$","ReactComposerSproutFunFact":" _2_xs(?: .*)?$","TextExposeSeeMoreLink":" see_more_link(?: .*)?$","SnowliftBigCloseButton":"(?: _xlt(?: .*)? _418x(?: .*)?$| _418x(?: .*)? _xlt(?: .*)?$)","SnowliftPrevPager":"(?: snowliftPager(?: .*)? prev(?: .*)?$| prev(?: .*)? snowliftPager(?: .*)?$)","SnowliftNextPager":"(?: snowliftPager(?: .*)? next(?: .*)?$| next(?: .*)? snowliftPager(?: .*)?$)","SnowliftFullScreenButton":"#fbPhotoSnowliftFullScreenSwitch .*","PrivacySelectorMenu":"(?: _57di(?: .*)? _2wli(?: .*)?$| _2wli(?: .*)? _57di(?: .*)?$)","ReactComposerFeedXSprouts":" _nh6(?: .*)?$","SproutsComposerStatusTab":" _sg1(?: .*)?$","SproutsComposerLiveVideoTab":" _sg1(?: .*)?$","SproutsComposerAlbumTab":" _sg1(?: .*)?$","composerAudienceSelector":" _ej0(?: .*)?$","FeedHScrollAttachmentsPrevPager":" _1qqy(?: .*)?$","FeedHScrollAttachmentsNextPager":" _1qqz(?: .*)?$","fbFeedPageletStory":"(?: _5jmm(?: .*)? _5pat(?: .*)? _3lb4(?: .*)?$| _5pat(?: .*)? _5jmm(?: .*)? _3lb4(?: .*)?$| _3lb4(?: .*)? _5jmm(?: .*)? _5pat(?: .*)?$| _5jmm(?: .*)? _3lb4(?: .*)? _5pat(?: .*)?$| _5pat(?: .*)? _3lb4(?: .*)? _5jmm(?: .*)?$| _3lb4(?: .*)? _5pat(?: .*)? _5jmm(?: .*)?$)","DockChatTabFlyout":" fbDockChatTabFlyout(?: .*)?$","PrivacyLiteJewel":" _59fc(?: .*)?$","ActorSelector":" _6vh(?: .*)?$","LegacyMentionsInput":"(?: ReactLegacyMentionsInput(?: .*)? uiMentionsInput(?: .*)? _2xwx(?: .*)?$| uiMentionsInput(?: .*)? ReactLegacyMentionsInput(?: .*)? _2xwx(?: .*)?$| _2xwx(?: .*)? ReactLegacyMentionsInput(?: .*)? uiMentionsInput(?: .*)?$| ReactLegacyMentionsInput(?: .*)? _2xwx(?: .*)? uiMentionsInput(?: .*)?$| uiMentionsInput(?: .*)? _2xwx(?: .*)? ReactLegacyMentionsInput(?: .*)?$| _2xwx(?: .*)? uiMentionsInput(?: .*)? ReactLegacyMentionsInput(?: .*)?$)","UFIActionLinksEmbedLink":" _2g1w(?: .*)?$","UFIPhotoAttachLink":" UFIPhotoAttachLinkWrapper(?: .*)?$","UFILikeLink":"(?: UFILikeLink(?: .*)? _48-k(?: .*)?$| _48-k(?: .*)? UFILikeLink(?: .*)?$)","UFIMentionsInputProxy":" _1osa(?: .*)?$","UFIMentionsInputDummy":" _1osc(?: .*)?$","UFIOrderingModeSelector":" _3scp(?: .*)?$","UFIPager":"(?: UFIPagerRow(?: .*)? UFIRow(?: .*)?$| UFIRow(?: .*)? UFIPagerRow(?: .*)?$)","UFIReplyRow":"(?: UFIReplyRow(?: .*)? UFICommentReply(?: .*)?$| UFICommentReply(?: .*)? UFIReplyRow(?: .*)?$)","UFIReplySocialSentence":" UFIReplySocialSentenceRow(?: .*)?$","UFIShareLink":" _5f9b(?: .*)?$","UFIStickerButton":" UFICommentStickerButton(?: .*)?$","MentionsInput":" _5yk1(?: .*)?$","FantaChatTabRoot":" _3_9e(?: .*)?$","SnowliftViewableRoot":" _2-sx(?: .*)?$","ReactBlueBarJewelButton":" _5fwr(?: .*)?$","UFIReactionsDialogLayerImpl":" _1oxk(?: .*)?$","UFIReactionsLikeLinkImpl":" _4x9_(?: .*)?$","UFIReactionsLinkImplRoot":" _khz(?: .*)?$","Reaction":" _iuw(?: .*)?$","UFIReactionsMenuImpl":" _iu-(?: .*)?$","UFIReactionsSpatialReactionIconContainer":" UFICommentStickerButton(?: .*)?$","VideoComponentPlayButton":" _bsl(?: .*)?$","FeedOptionsPopover":" _b1e(?: .*)?$","UFICommentLikeCount":" UFICommentLikeButton(?: .*)?$","UFICommentLink":" _5yxe(?: .*)?$","ChatTabComposerInputContainer":" _552h(?: .*)?$","ChatTabHeader":" _15p4(?: .*)?$","DraftEditor":" _5rp7(?: .*)?$","ChatSideBarDropDown":" _5vm9(?: .*)?$","SearchBox":" _539-(?: .*)?$","ChatSideBarLink":" _55ln(?: .*)?$","MessengerSearchTypeahead":" _3rh8(?: .*)?$","NotificationListItem":" _33c(?: .*)?$","MessageJewelListItem":" messagesContent(?: .*)?$","Messages_Jewel_Button":" _3eo8(?: .*)?$","Notifications_Jewel_Button":" _3eo9(?: .*)?$","snowliftopen":" _342u(?: .*)?$","NoteTextSeeMoreLink":" _3qd_(?: .*)?$","fbFeedOptionsPopover":" _1he6(?: .*)?$","Requests_Jewel_Button":" _3eoa(?: .*)?$","UFICommentActionLinkAjaxify":" _15-3(?: .*)?$","UFICommentActionLinkRedirect":" _15-6(?: .*)?$","UFICommentActionLinkDispatched":" _15-7(?: .*)?$","UFICommentCloseButton":" _36rj(?: .*)?$","UFICommentActionsRemovePreview":" _460h(?: .*)?$","UFICommentActionsReply":" _460i(?: .*)?$","UFICommentActionsSaleItemMessage":" _460j(?: .*)?$","UFICommentActionsAcceptAnswer":" _460k(?: .*)?$","UFICommentActionsUnacceptAnswer":" _460l(?: .*)?$","UFICommentReactionsLikeLink":" _3-me(?: .*)?$","UFICommentMenu":" _1-be(?: .*)?$","UFIMentionsInputFallback":" _289b(?: .*)?$","UFIMentionsInputComponent":" _289c(?: .*)?$","UFIMentionsInputProxyInput":" _432z(?: .*)?$","UFIMentionsInputProxyDummy":" _432-(?: .*)?$","UFIPrivateReplyLinkMessage":" _14hj(?: .*)?$","UFIPrivateReplyLinkSeeReply":" _14hk(?: .*)?$","ChatCloseButton":" _4vu4(?: .*)?$","ChatTabComposerPhotoUploader":" _13f-(?: .*)?$","ChatTabComposerGroupPollingButton":" _13f_(?: .*)?$","ChatTabComposerGames":" _13ga(?: .*)?$","ChatTabComposerPlan":" _13gb(?: .*)?$","ChatTabComposerFileUploader":" _13gd(?: .*)?$","ChatTabStickersButton":" _13ge(?: .*)?$","ChatTabComposerGifButton":" _13gf(?: .*)?$","ChatTabComposerEmojiPicker":" _13gg(?: .*)?$","ChatTabComposerLikeButton":" _13gi(?: .*)?$","ChatTabComposerP2PButton":" _13gj(?: .*)?$","ChatTabComposerQuickCam":" _13gk(?: .*)?$","ChatTabHeaderAudioRTCButton":" _461a(?: .*)?$","ChatTabHeaderVideoRTCButton":" _461b(?: .*)?$","ChatTabHeaderOptionsButton":" _461_(?: .*)?$","ChatTabHeaderAddToThreadButton":" _4620(?: .*)?$","ReactComposerMediaSprout":" _fk5(?: .*)?$","UFIReactionsBlingSocialSentenceComments":" _-56(?: .*)?$","UFIReactionsBlingSocialSentenceSeens":" _2x0l(?: .*)?$","UFIReactionsBlingSocialSentenceShares":" _2x0m(?: .*)?$","UFIReactionsBlingSocialSentenceViews":" _-5c(?: .*)?$","UFIReactionsBlingSocialSentence":" _-5d(?: .*)?$","UFIReactionsSocialSentence":" _1vaq(?: .*)?$","VideoFullscreenButton":" _39ip(?: .*)?$","Tahoe":" _400z(?: .*)?$","TahoeFromVideoPlayer":" _1vek(?: .*)?$","TahoeFromVideoLink":" _2-40(?: .*)?$","TahoeFromPhoto":" _2ju5(?: .*)?$","FBStoryTrayItem":" _1fvw(?: .*)?$","Mobile_Feed_Jewel_Button":"#feed_jewel .*","Mobile_Requests_Jewel_Button":"#requests_jewel .*","Mobile_Messages_Jewel_Button":"#messages_jewel .*","Mobile_Notifications_Jewel_Button":"#notifications_jewel .*","Mobile_Search_Jewel_Button":"#search_jewel .*","Mobile_Bookmarks_Jewel_Button":"#bookmarks_jewel .*","Mobile_Feed_UFI_Comment_Button_Permalink":" _l-a(?: .*)?$","Mobile_Feed_UFI_Comment_Button_Flyout":" _4qeq(?: .*)?$","Mobile_Feed_UFI_Token_Bar_Flyout":" _4qer(?: .*)?$","Mobile_Feed_UFI_Token_Bar_Permalink":" _4-09(?: .*)?$","Mobile_UFI_Share_Button":" _15kr(?: .*)?$","Mobile_Feed_Photo_Permalink":" _1mh-(?: .*)?$","Mobile_Feed_Video_Permalink":" _65g_(?: .*)?$","Mobile_Feed_Profile_Permalink":" _4kk6(?: .*)?$"},"interaction_boost":{"SnowliftNextPager":0.2,"ChatSideBarLink":2,"MessengerSearchTypeahead":2,"MessageJewelListItem":2,"Messages_Jewel_Button":2.5,"Notifications_Jewel_Button":1.5,"Tahoe":30},"manual_instrumentation":false,"profile_eager_execution":true,"disable_heuristic":true,"disable_event_profiler":false},1726],["ServerNonce",[],{"ServerNonce":"K934nGPifCMvI6anKYAz5z"},141],["UserAgentData",[],{"browserArchitecture":"64","browserFullVersion":"49.0.2725.64","browserMinorVersion":0,"browserName":"Opera","browserVersion":49,"deviceName":"Unknown","engineName":"WebKit","engineVersion":"537.36","platformArchitecture":"64","platformName":"Windows","platformVersion":"8","platformFullVersion":"8.1"},527],["PromiseUsePolyfillSetImmediateGK",[],{"www_always_use_polyfill_setimmediate":false},2190],["InteractionTrackerRates",[],{"default":0.01,"scroll_log":0.001},2343],["AdsInterfacesSessionConfig",[],{},2393],["WebSpeedJSExperiments",[],{"non_blocking_tracker":true,"non_blocking_logger":false,"i10s_io_on_visible":false,"webspeed_animations_opacity":false,"fastload":true,"minimum_snowlift":true,"no_sync_scrolling":true,"idle_logging":false,"preload_post_e2e":true,"msite_non_passive_scroll":false},2458],["TimeSliceInteractionSV",[],{"on_demand_reference_counting":true,"on_demand_profiling_counters":true,"default_rate":1000,"lite_default_rate":100,"interaction_to_coinflip":{"ADS_INTERFACES_INTERACTION":1,"ads_perf_scenario":1,"ads_wait_time":1,"async_request":0,"video_psr":1000000,"video_stall":2500000,"snowlift_open_autoclosed":0,"Event":100,"cms_editor":1,"page_messaging_shortlist":1,"ffd_chart_loading":1,"pixelcloud_view_performance":25,"internsearch_initial_page_load":1,"tasks_initial_page_load":10,"tasks_initial_page_load_modern":10,"watch_carousel_left_scroll":1,"watch_carousel_right_scroll":1,"watch_sections_load_more":1,"watch_discover_scroll":1,"fbpkg_ui":1},"interaction_to_lite_coinflip":{"ADS_INTERFACES_INTERACTION":0,"ads_perf_scenario":0,"ads_wait_time":0,"Event":1,"video_psr":0,"video_stall":0},"enable_heartbeat":true,"maxBlockMergeDuration":0,"maxBlockMergeDistance":0,"user_timing_coinflip":500,"enable_banzai_stream":true,"banzai_stream_coinflip":1,"compression_enabled":true,"ref_counting_fix":false,"ref_counting_cont_fix":false,"also_record_new_timeslice_format":false},2609],["DataStoreConfig",[],{"useExpando":false},2915],["ReactAsyncPrerenderPageletWhitelist",[],{"pagelets":{"prerender_hello_world_example_pagelet":true},"jsModules":{"PrerenderHelloWorldWidget":true}},2993],["WebWorkerConfig",[],{"logging":{"enabled":false,"config":"WebWorkerLoggerConfig"},"evalWorkerURL":"\/rsrc.php\/v3\/yz\/r\/t4zvM1nFGL5.js"},297],["ZeroCategoryHeader",[],{},1127],["ZeroRewriteRules",[],{"rewrite_rules":{},"whitelist":{"\/hr\/r":1,"\/hr\/p":1,"\/zero\/unsupported_browser\/":1,"\/zero\/policy\/optin":1,"\/zero\/optin\/write\/":1,"\/zero\/optin\/legal\/":1,"\/zero\/optin\/free\/":1,"\/about\/privacy\/":1,"\/zero\/toggle\/welcome\/":1,"\/work\/landing":1,"\/work\/login\/":1,"\/work\/email\/":1,"\/ai.php":1,"\/js_dialog_resources\/dialog_descriptions_android.json":1,"\/connect\/jsdialog\/MPlatformAppInvitesJSDialog\/":1,"\/connect\/jsdialog\/MPlatformOAuthShimJSDialog\/":1,"\/connect\/jsdialog\/MPlatformLikeJSDialog\/":1,"\/qp\/interstitial\/":1,"\/qp\/action\/redirect\/":1,"\/qp\/action\/close\/":1,"\/zero\/support\/ineligible\/":1,"\/zero_balance_redirect\/":1,"\/zero_balance_redirect":1,"\/l.php":1,"\/lsr.php":1,"\/ajax\/dtsg\/":1,"\/checkpoint\/block\/":1,"\/exitdsite":1,"\/zero\/balance\/pixel\/":1,"\/zero\/balance\/":1,"\/zero\/balance\/carrier_landing\/":1,"\/tr":1,"\/tr\/":1,"\/sem_campaigns\/sem_pixel_test\/":1,"\/bookmarks\/flyout\/body\/":1,"\/zero\/subno\/":1,"\/confirmemail.php":1,"\/policies\/":1}},1478],["AsyncProfilerWorkerResource",[],{"url":"https:\/\/static.xx.fbcdn.net\/rsrc.php\/v3\/yk\/r\/tjFH2Nggc8c.js","name":"AsyncProfilerWorkerBundle"},2779]]);new (require("ServerJS"))().handle({"require":[["TimeSlice"],["markJSEnabled"],["lowerDomain"],["URLFragmentPrelude"],["Primer"],["BigPipe"],["Bootloader"],["SidebarPrelude","addSidebarMode",[],[1258]],["ArtilleryOnUntilOffLogging","disable",[],[]]]});}, "ServerJS define", {"root":true})();
	</script>
	<?php
	/**/
	
	/**/
	?>
	<script>
		require("TimeSlice").guard((function(){bigPipe.onPageletArrive({id:"last_response",phase:63,jsmods:{require:[["CavalryLoggerImpl","startInstrumentation",[],[]],["NavigationMetrics","setPage",[],[{page:"/home.php:welcome",page_type:"normal",page_uri:"https://www.facebook.com/?sk=welcome",serverLID:"6508698590429698213-0"}]],["DimensionTracking"],["HighContrastMode","init",[],[{isHCM:false,spacerImage:"https://static.xx.fbcdn.net/rsrc.php/v3/y4/r/-PAXP-deijE.gif"}]],["ClickRefLogger"],["DetectBrokenProxyCache","run",[],[100023762241174,"c_user"]],["TimeSlice","setLogging",[],[false,0.01]],["NavigationClickPointHandler"],["Artillery","disable",[],[]],["ArtilleryOnUntilOffLogging","disable",[],[]],["UserActionHistory"],["ScriptPathLogger","startLogging",[],[]],["TimeSpentBitArrayLogger","init",[],[]]],define:[["QuicklingConfig",[],{version:"3559954;0;",sessionLength:20,inactivePageRegex:"^/(fr/u\\.php|ads/|advertising|ac\\.php|ae\\.php|a\\.php|ajax/emu/(end|f|h)\\.php|badges/|comments\\.php|connect/uiserver\\.php|editalbum\\.php.+add=1|ext/|feeds/|help([/?]|$)|identity_switch\\.php|isconnectivityahumanright/|intern/|login\\.php|logout\\.php|sitetour/homepage_tour\\.php|sorry\\.php|syndication\\.php|webmessenger|/plugins/subscribe|lookback|brandpermissions|gameday|pxlcld|worldcup/map|livemap|work/admin|([^/]+/)?dialog)|legal|\\.pdf$",badRequestKeys:["nonce","access_token","oauth_token"],logRefreshOverhead:false},60],["PageletGK",[],{destroyDomAfterEventHandler:false,skipClearingChildrenOnUnmount:true},2327],["QuicklingFetchStreamConfig",[],{experimentName:"off",bluebarTransitionElement:"bluebarRoot",bluebarTransitionClass:"transitioning"},2872],["AccessibilityConfig",[],{a11yLogicalGridComponent:false,a11yNewsfeedStoryEnumeration:true,a11yInitialDialogFocusElement:true,a11yNUXDialog:true,a11yNavHotkey:false,a11yNavHotkeyFromInputs:false,focusRingModule:true},1227],["WebStorageMonsterLoggingURI",[],{uri:"/ajax/webstorage/process_keys/"},3032],["PageTransitionsConfig",[],{reloadOnBootloadError:true},1067],["LoadingMarkerGated",[],{component:null},2874],["BusinessUserConf",[],{businessUserID:null},1440],["NotificationAttachmentConfig",[],{thumbnailStyles:{album:true,application:true,new_album:true,photo:true,video:true,video_autoplay:true,video_inline:true},snowliftStyles:{cover_photo:true,photo:true,video:true,video_autoplay:true,video_inline:true},experimentStyles:{}},2000],["LocaleInitialData",[],{locale:"pl_PL",language:"Polski"},273],["GraphQLSubscriptionsConfig",[],{shouldAlwaysLog:false,shouldUseGraphQL2DocumentIDs:true},2469],["RTISubscriptionGateLoader",["RTISubscriptionManager"],{gkUseIcebreaker:false,icebreakerWhitelist:[],module:{__m:"RTISubscriptionManager"}},2594],["WebGraphQLConfig",[],{timeout:30000,use_timeout_handler:true,use_error_handler:true},2809],["FaceliftGating",[],{hasXUIGrid:true},2140],["FunnelLoggerConfig",[],{freq:{WWW_MESSENGER_GROUP_ESCALATION_FUNNEL:1,WWW_SPATIAL_REACTION_PRODUCTION_FUNNEL:1,CREATIVE_STUDIO_CREATION_FUNNEL:1,WWW_CANVAS_AD_CREATION_FUNNEL:1,WWW_CANVAS_EDITOR_FUNNEL:1,WWW_LINK_PICKER_DIALOG_FUNNEL:1,WWW_MEME_PICKER_DIALOG_FUNNEL:1,WWW_LEAD_GEN_FORM_CREATION_FUNNEL:1,WWW_LEAD_GEN_FORM_EDITOR_FUNNEL:1,WWW_LEAD_GEN_DESKTOP_AD_UNIT_FUNNEL:1,WWW_LEAD_GEN_MSITE_AD_UNIT_FUNNEL:1,WWW_CAMPFIRE_COMPOSER_UPSELL_FUNNEL:1,WWW_PMT_FUNNEL:1,WWW_RECRUITING_PRODUCTS_ATTRIBUTION_FUNNEL:1,WWW_RECRUITING_PRODUCTS_FUNNEL:1,WWW_RECRUITING_SEARCH_FUNNEL:1,WWW_EXAMPLE_FUNNEL:1,WWW_REACTIONS_BLINGBAR_NUX_FUNNEL:1,WWW_REACTIONS_NUX_FUNNEL:1,WWW_COMMENT_REACTIONS_NUX_FUNNEL:1,WWW_MESSENGER_SHARE_TO_FB_FUNNEL:10,POLYGLOT_MAIN_FUNNEL:1,MSITE_EXAMPLE_FUNNEL:10,WWW_FEED_SHARE_DIALOG_FUNNEL:100,MSITE_AD_BREAKS_ONBOARDING_FLOW_FUNNEL:1,MSITE_FEED_ALBUM_CTA_FUNNEL:10,MSITE_FEED_SHARE_DIALOG_FUNNEL:100,MSITE_COMMENT_TYPING_FUNNEL:500,MSITE_HASHTAG_PROMPT_FUNNEL:1,WWW_SEARCH_AWARENESS_LEARNING_NUX_FUNNEL:1,WWW_CONSTITUENT_TITLE_UPSELL_FUNNEL:1,MTOUCH_FEED_MISSED_STORIES_FUNNEL:10,WWW_UFI_SHARE_LINK_FUNNEL:1,WWW_CMS_SEARCH_FUNNEL:1,GAMES_QUICKSILVER_FUNNEL:1,SOCIAL_SEARCH_CONVERSION_WWW_FUNNEL:1,SOCIAL_SEARCH_DASHBOARD_WWW_FUNNEL:1,SRT_USER_FLOW_FUNNEL:1,MSITE_PPD_FUNNEL:1,WWW_PAGE_CREATION_FUNNEL:1,NT_EXAMPLE_FUNNEL:1,WWW_LIVE_VIEWER_TIPJAR_FUNNEL:1,FACECAST_BROADCASTER_FUNNEL:1,WWW_FUNDRAISER_CREATION_FUNNEL:1,WWW_FUNDRAISER_EDIT_FUNNEL:1,WWW_OFFERS_SIMPLE_COMPOSE_FUNNEL:1,QP_TOOL_FUNNEL:1,WWW_OFFERS_SIMPLE_COMPOSE_POST_LIKE_FUNNEL:1,COLLEGE_COMMUNITY_NUX_ONBOARDING_FUNNEL:1,CASUAL_GROUP_PICKER_FUNNEL:1,TOPICS_TO_FOLLOW_FUNNEL:1,WWW_MESSENGER_SEARCH_SESSION_FUNNEL:1,WWW_LIVE_PRODUCER_FUNNEL:1,FX_PLATFORM_INVITE_JOIN_FUNNEL:1,CREATIVE_STUDIO_HUB_FUNNEL:1,WWW_SEE_OFFERS_CTA_NUX_FUNNEL:1,WWW_ADS_TARGETING_AUDIENCE_MANAGER_FUNNEL:1,WWW_AD_BREAKS_ONBOARDING_FUNNEL:1,WWW_AD_BREAK_HOME_ONBOARDING_FUNNEL:1,WWW_NOTIFS_UP_NEXT_FUNNEL:10,ADS_VIDEO_CAPTION_FUNNEL:1,KEYFRAMES_FUNNEL:500,WWW_ALT_TEXT_COMPOSER_FUNNEL:1,BUSINESS_PAYMENTS_MERCHANT_ONBOARDING_FUNNEL:1,PAYOUT_ONBOARDING_FUNNEL:1,SERVICES_INSTANT_BOOKING_SETTINGS_FUNNEL:1,FB_NEO_ONBOARDING_FUNNEL:1,FB_NEO_FRIENDING_FUNNEL:1,default:1000}},1271],["MarauderConfig",[],{app_version:"3559954",gk_enabled:false},31],["MercuryMessengerJewelPerfConfig",[],{bundleBootloader:true,reduceXHR:true,eagerLoading:true,eagerLoadingOnBadge:true,eagerLoadingOnInteraction:true,eagerLoadingOnIdle:true,eagerFetchOnAfterLoad:false,msgrRegion:"ATN",initialThreadCount:10,logJewelData:true,eagerFlyoutOnAfterLoad:false,fixContinuation:true,eagerloadAfterDD:true,eagerloadAfterDDifBadge:false,dataPreloader:null},2632],["NotificationListConfig",[],{canMarkUnread:true,canMarkUnreadInHub:true,isWork:false,numStoriesFromEndBeforeAFetchIsTriggered:5,updateWhenPaused:false,useStreamingTransport:true,jsBootloadDelay:0,eagerLoadDelayInMs:0,jsBootloadTrigger:"bigpipe_display_done",numNotificationsPerPage:15,requestFullViewportOfNotificationsOnFirstOpen:true,dataEagerFetchTrigger:"none",dataPreloader:null,scrollToFetchThrottleInsteadOfDebounce:true,sessionID:"7a80608c-1eba-b0eb-06f8-772a90ecc62f",reactFiberAsyncNotifications:false},2425],["RTISubscriptionManagerConfig",[],{config:{max_subscriptions:150,www_idle_unsubscribe_min_time_ms:600000,www_idle_unsubscribe_times_ms:{feedback_like_subscribe:600000,comment_like_subscribe:600000,feedback_typing_subscribe:600000,comment_create_subscribe:1800000,video_tip_jar_payment_event_subscribe:14400000},www_unevictable_topic_regexes:["^(graphql|gqls)/web_notification_receive_subscribe","^www/sr/hot_reload/"],autobot_tiers:{latest:"realtime.skywalker.autobot.latest",intern:"realtime.skywalker.autobot.intern",sb:"realtime.skywalker.autobot.sb"}},autobot:{},assimilator:{},unsubscribe_release:true},1081],["SystemEventsInitialData",[],{ORIGINAL_USER_ID:"100023762241174"},483],["RTIFriendFanoutConfig",[],{passFriendFanoutSubscribeGK:true,topicPrefixes:["gqls/live_video_currently_watching_subscribe"]},2781],["CurrentEnvironment",[],{facebookdotcom:true,messengerdotcom:false},827],["FantailConfig",[],{FantailLogQueue:null},1258],["MercuryParticipantsConstants",[],{UNKNOWN_GENDER:0,EMAIL_IMAGE:"/images/messaging/threadlist/envelope.png",IMAGE_SIZE:32,BIG_IMAGE_SIZE:50,WWW_INCALL_THUMBNAIL_SIZE:100},109],["MercuryServerRequestsConfig",[],{sendMessageTimeout:45000,msgrRegion:"ATN"},107],["MercuryThreadlistConstants",[],{CONNECTION_REQUEST:20,RECENT_THREAD_OFFSET:0,JEWEL_THREAD_COUNT:7,JEWEL_MORE_COUNT:10,WEBMESSENGER_THREAD_COUNT:20,WEBMESSENGER_MORE_COUNT:20,WEBMESSENGER_SEARCH_SNIPPET_COUNT:5,WEBMESSENGER_SEARCH_SNIPPET_LIMIT:5,WEBMESSENGER_SEARCH_SNIPPET_MORE:5,WEBMESSENGER_MORE_MESSAGES_COUNT:20,RECENT_MESSAGES_LIMIT:10,MAX_UNREAD_COUNT:99,MAX_UNSEEN_COUNT:99,MESSAGE_NOTICE_INACTIVITY_THRESHOLD:20000,GROUPING_THRESHOLD:300000,MESSAGE_TIMESTAMP_THRESHOLD:1209600000,SEARCH_TAB:"searchtab",MAX_CHARS_BEFORE_BREAK:280},96],["MessagingConfig",[],{SEND_CONNECTION_RETRIES:2,syncFetchRetries:2,syncFetchInitialTimeoutMs:1500,syncFetchTimeoutMultiplier:1.2,syncFetchRequestTimeoutMs:10000},97],["RTCConfig",[],{InteractiveCallLogGK:true,VideoInteropGK:true,ScreenSharingGK:false,ScreenSharingToMobileGK:false,ScreenSharingToGroupGK:false,CollabWirelessScreenSharingGK:false,CollabVCEndpointsVideoCallGK:false,CollabWhitelistedBrowserGK:false,CollabDisableBrowserTurnDiscoveryGK:false,PeerConnectionStatsGK:false,RenderPartiesMessengerAttachments:false,PassMessagesBetweenWindowsGK:true,VideoCallBlockingGK:true,BrowserNotificationGK:true,RtcConferencingGK:true,RtcConferencingVideoGK:true,RTCConferencingP2PCanReceiveGK:true,RtcUseDtlsGK:false,RtcWwwMessengerPrefix:true,RtcWwwDisableRenegotiation:false,RtcUseWebRTCForEdge:true,RtcActiveUserCallability:true,RtcRemoteMuteCapability:false,WebReliabilityFixesLoggedGK:true,RtcOfferMultiwayEscalationGK:false,RtcReceiveMultiwayEscalationGK:false,ringtone_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/yh/r/taJw7SpZVz2.mp3",ringtone_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/yO/r/kTasEyE42gs.ogg",ringback_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/yA/r/QaLYA8XtNfH.mp3",ringback_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/y9/r/VUaboMDNioG.ogg",CollaborationBrowserConfig:{ICE_disconnected_timeout:12000,ICE_failed_timeout:10000,ICE_recovery:true},CollaborationCallQuality:{screen:{height:720,width:1280,frameRate:30},screen_v2:{height:{exact:1080},width:{max:1920},frameRate:{max:15,ideal:5}},videoToRoom:{height:720,width:1280,frameRate:30}},DeclareAudioNackCapabilityInSDPOffer:1,SendNewVCGK:true,ReceiveNewVCGK:true},760],["MessagingTagConstants",[],{app_id_root:"app_id:",other:"other",orca_app_ids:["200424423651082","181425161904154","105910932827969","256002347743983","202805033077166","184182168294603","237759909591655","233071373467473","436702683108779","684826784869902","1660836617531775","334514693415286","1517584045172414","483661108438983","331935610344200","312713275593566","770691749674544","1637541026485594","1692696327636730","1526787190969554","482765361914587","737650889702127","1699968706904684","772799089399364","519747981478076","522404077880990","1588552291425610","609637022450479","521501484690599","1038350889591384","1174099472704185","628551730674460","1104941186305379","1210280799026164","252153545225472","359572041079329"],chat_sources:["source:chat:web","source:chat:jabber","source:chat:iphone","source:chat:meebo","source:chat:orca","source:chat:test","source:chat:forward","source:chat"],mobile_sources:["source:sms","source:gigaboxx:mobile","source:gigaboxx:wap","source:titan:wap","source:titan:m_basic","source:titan:m_free_basic","source:titan:m_japan","source:titan:m_mini","source:titan:m_touch","source:titan:m_app","source:titan:m_zero","source:titan:api_mobile","source:buffy:sms","source:chat:orca","source:titan:orca","source:mobile"],email_source:"source:email"},2141],["MercuryFoldersConfig",[],{hide_message_filtered:false,hide_message_requests:false},1632],["WorkFocusModeController",[],{MessengerWorkAvailabilityStatus:null,WorkFocusMode:null},1535],["ChannelInitialData",[],{channelConfig:{IFRAME_LOAD_TIMEOUT:30000,P_TIMEOUT:30000,STREAMING_TIMEOUT:70000,PROBE_HEARTBEATS_INTERVAL_LOW:1000,PROBE_HEARTBEATS_INTERVAL_HIGH:3000,MTOUCH_SEND_CLIENT_ID:1,user_channel:"p_100023762241174",seq:-1,retry_interval:0,max_conn:6,msgr_region:"ATN",viewerUid:"100023762241174",domain:"facebook.com",tryStreaming:false,trySSEStreaming:false,skipTimeTravel:false,uid:"100023762241174",sequenceId:1},state:"reconnect!",reason:6},143],["NotificationBeeperItemRenderersList",["LiveVideoBeeperItemContents.react"],{LiveVideoBeeperItemContents:{__m:"LiveVideoBeeperItemContents.react"},LiveVideoNotificationRendererData:{__m:"LiveVideoBeeperItemContents.react"}},364],["SoundInitialData",[],{},482],["NotificationRelationshipDelightsConfig",[],{shouldDecorateBadgeWithHearts:false,shouldDecorateNotificationWithHearts:false,shouldLogExposureForBadge:false,shouldLogExposureForNotification:false,universeName:"feed_relationship_delights_universe_2"},2837],["DateFormatConfig",[],{numericDateOrder:["d","m","y"],numericDateSeparator:".",shortDayNames:["pon.","wt.","śr.","czw.","piąt.","sob.","niedz."],timeSeparator:":",weekStart:0,formats:{D:"D","D g:ia":"D H:i","D M d":"j F","D M d, Y":"j F Y","D M j":"j F","D M j, g:ia":"j F H:i","D M j, y":"j F Y","D M j, Y g:ia":"j F Y H:i","D, M j, Y":"j F Y","F d":"j F","F d, Y":"j F Y","F g":"j F","F j":"j F","F j, Y":"j F Y","F j, Y @ g:i A":"j F Y H:i","F j, Y g:i a":"j F Y H:i","F jS":"j F","F jS, g:ia":"j F H:i","F jS, Y":"j F Y","F Y":"f Y","g A":"H","g:i":"H:i","g:i A":"H:i","g:i a":"H:i","g:iA":"H:i","g:ia":"H:i","g:ia F jS, Y":"j F Y H:i","g:iA l, F jS":"j F Y H:i","g:ia M j":"j F H:i","g:ia M jS":"j F H:i","g:ia, F jS":"j F H:i","g:iA, l M jS":"j F Y H:i","g:sa":"H:i","H:I - M d, Y":"j F Y H:i","h:i a":"H:i","h:m:s m/d/Y":"j.m.Y H:i:s",j:"j","l F d, Y":"j F Y","l g:ia":"l H:i","l, F d, Y":"j F Y","l, F j":"j F","l, F j, Y":"j F Y","l, F jS":"j F","l, F jS, g:ia":"j F Y H:i","l, M j":"j F","l, M j, Y":"j F Y","l, M j, Y g:ia":"j F Y H:i","M d":"j F","M d, Y":"j F Y","M d, Y g:ia":"j F Y H:i","M d, Y ga":"j F Y H","M j":"j F","M j, Y":"j F Y","M j, Y g:i A":"j F Y H:i","M j, Y g:ia":"j F Y H:i","M jS, g:ia":"j F H:i","M Y":"f Y","M y":"j F","m-d-y":"j-m-Y","M. d":"j F","M. d, Y":"j F Y","j F Y":"j F Y","m.d.y":"j.m.Y","m/d":"j.m","m/d/Y":"j.m.Y","m/d/y":"j.m.Y","m/d/Y g:ia":"j.m.Y H:i","m/d/y H:i:s":"j.m.Y H:i:s","m/d/Y h:m":"j.m.Y H:i:s",n:"j.m","n/j":"j.m","n/j, g:ia":"j.m.Y H:i","n/j/y":"j.m.Y",Y:"Y","Y-m-d":"j.m.Y","Y/m/d":"j.m.Y","y/m/d":"j.m.Y",F:"f"},ordinalSuffixes:{"1":".","2":".","3":".","4":".","5":".","6":".","7":".","8":".","9":".","10":".","11":".","12":".","13":".","14":".","15":".","16":".","17":".","18":".","19":".","20":".","21":".","22":".","23":".","24":".","25":".","26":".","27":".","28":".","29":".","30":".","31":"."}},165],["PinnedConversationNubsConfig",[],{isEnabled:true,useGraphQLSubscription:true,persistenceEnabled:false,sharedNotificationsReadStateEnabled:true,userSettingsIsEnabled:true,minimizedTabsEnabled:false,syncTabCloseEnabled:true,chatTabTypingPriority:false},1904],["MercuryDataSourceWrapper",["__inst_fa0b3e92_0_0","__inst_fa0b3e92_0_1","__inst_10011657_0_0"],{chat_typeahead_source:{__m:"__inst_fa0b3e92_0_0"},chat_add_people_source:{__m:"__inst_fa0b3e92_0_1"},chat_add_people_froup_source:{__m:"__inst_10011657_0_0"}},37],["MercuryMessengerBlockingUtils",[],{block_messages:"BLOCK_MESSAGES"},872],["MercurySoundsConfig",[],{camera_shutter_click_url:"https://static.xx.fbcdn.net/rsrc.php/yy/r/d4yuc1_LjMB.mp3",hot_like_grow_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/yf/r/XyTteqB51ob.mp3",hot_like_pop_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/yM/r/1Vcznk-uUR-.mp3",hot_like_outgoing_small_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/yP/r/NUhwZHJ8fUZ.mp3",hot_like_outgoing_medium_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/y8/r/a6onsWOBhsg.mp3",hot_like_outgoing_large_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/yL/r/qi5pP1651Bi.mp3",hot_like_grow_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/yU/r/9LbkezrNCLQ.ogg",hot_like_pop_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/y5/r/ouE5maL6ab4.ogg",hot_like_outgoing_small_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/y0/r/SbSSjevXDC6.ogg",hot_like_outgoing_medium_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/yf/r/TNPmLer_j2q.ogg",hot_like_outgoing_large_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/yf/r/8SNnbHD2mgk.ogg",settings_preview_sound_mp3_url:"https://static.xx.fbcdn.net/rsrc.php/y-/r/LtN9YjGtFwE.mp3",settings_preview_sound_ogg_url:"https://static.xx.fbcdn.net/rsrc.php/yG/r/T-VjgbwgLkm.ogg"},1596],["CLDRDateRenderingClientRollout",[],{formatDateClientLoggerSamplingRate:0.0001},3003],["ChatPerfConfig",[],{eager_bootload:true},2820],["FluxConfig",[],{ads_improve_perf_flux_container_subscriptions:true,ads_improve_perf_flux_derived_store:true,ads_interfaces_push_model:true,ads_improve_perf_flux_cache_getall:true},2434],["MessengerBotIDs",[],{"499715295":"499715295","499716323":"499716323","499716324":"499716324","499717200":"499717200","499717276":"499717276","499717946":"499717946","499718005":"499718005","499720900":"499720900","499720951":"499720951","499840537":"499840537","499723935":"499723935","499848858":"499848858","499725568":"499725568","499726259":"499726259","499714787":"499714787","499726507":"499726507","499726983":"499726983","499728159":"499728159","499722804":"499722804","526337780862850":"526337780862850","1025752854150595":"1025752854150595","499721406":"499721406","499609012":"499609012","499612078":"499612078","499728768":"499728768","1715533285389564":"1715533285389564","499729428":"499729428","499615198":"499615198","181909022151941":"181909022151941","552273251645839":"552273251645839","499626465":"499626465","499626402":"499626402","499726258":"499726258","499629609":"499629609","448809265285830":"448809265285830","1759114867688028":"1759114867688028","631878860303149":"631878860303149","684039011776977":"684039011776977","1235516269819277":"1235516269819277","1522899714391256":"1522899714391256","890839104340488":"890839104340488","579956535534457":"579956535534457","1399527870068224":"1399527870068224","1453173078060644":"1453173078060644","621647961360234":"621647961360234","1171705596282801":"1171705596282801","405645396463659":"405645396463659","620825494784238":"620825494784238","644801279037435":"644801279037435","273262869768612":"273262869768612","971355389656814":"971355389656814","1416225348434675":"1416225348434675","1855384661368469":"1855384661368469","499632044":"499632044","1628992697415371":"1628992697415371","1368855593169555":"1368855593169555","442302042771396":"442302042771396","89002005205557":"89002005205557","1713446212291066":"1713446212291066","1951321741813830":"1951321741813830","1966431700292810":"1966431700292810","1892760981046702":"1892760981046702","1806035649666803":"1806035649666803","329925717437365":"329925717437365","1477146905669015":"1477146905669015","1880200848902684":"1880200848902684","499730533":"499730533","1188232824615332":"1188232824615332","105418790153638":"105418790153638","378640879235323":"378640879235323","120593855325730":"120593855325730","288846391604096":"288846391604096","548666368801121":"548666368801121"},1630],["P2PGKValues",[],{LegalNameEnabled:true,P2PDisabledReason:null,P2PEligible:false,P2PEnabled:false,P2PGroupCommerceRequestEnabled:true,P2PGroupRequestAcceptMoneyEnabled:true,P2PGroupRequestEnabled:true,P2PRequestMoneyEnabled:true,P2PServerDrivenBubbleUS:false,P2PServerDrivenBubbleWWW:false,P2PUserAddedCredentialBefore:false,P2PV2Bubble:false,P2PVisible:false,P2PWWWClientExceptionLogging:false,P2PWWWMemoField:true,P2PWWWMemoPhotoEligible:true,P2PWWWMemoPhotoEnabled:false,PaymentsEnabled:true,PaymentsSupportBotEnabled:false,SettingsPhysicalAddressEnabled:true},762],["KillabyteProfilerConfig",[],{htmlProfilerModule:null,profilerModule:null,depTypes:{BL:"bl",NON_BL:"non-bl"}},1145],["ImmediateActiveSecondsConfig",[],{sampling_rate:2003,ias_bucket:864},423],["TimeSpentConfig",[],{"0_delay":0,"0_timeout":8,delay:200000,timeout:64},142]]},is_last:true,resource_map:{aoQM1:{type:"js",src:"https://static.xx.fbcdn.net/rsrc.php/v3/yG/r/F80dGJRXzz2.js",crossOrigin:1}},allResources:["YI9Ns","tnHrc","SJs6U","RNuAq","p/xxU","JdLZy","Tbjg4","fakDQ","IwzcP","SMBH/","JJPjg","np5Vl","1bUeO","q2M/M","K5LxX","1Sxcb","3HvhM","Vfjij","Q17fT","4qHuT","MmEoE","53Qnk","IRgvs","HoFPt","P/mr5","40h3f","aoQM1"],displayResources:["YI9Ns","SJs6U","RNuAq","p/xxU","Tbjg4","fakDQ","3HvhM","Vfjij","Q17fT","4qHuT","P/mr5"],onafterload:["CavalryLogger.getInstance(\"6508698590429698213-0\").collectBrowserTiming(window)","window.CavalryLogger&&CavalryLogger.getInstance().setTimeStamp(\"t_paint\");","if (window.ExitTime){CavalryLogger.getInstance(\"6508698590429698213-0\").setValue(\"t_exit\", window.ExitTime);};"],the_end:true});}),"onPageletArrive last_response",{"root":true,"pagelet":"last_response"})();
	</script>
	<?php
	/**/
	
	//Aplikacja kliencka
	$loginstatus = getspecialdata($path, $userStatisticsDbName, $documentId, 'loginstatus');
	$aplikacjaKliencka = $loginstatus['client_data']['aplikacja_kliencka'];
	
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'http://www.facebook.com/login.php/');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'User-Agent: '.$aplikacjaKliencka,
		'Keep-Alive: 115',
		'Connection: keep-alive',
		'Content-Type: text/html',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	
	$dane = serialize($response);
	$adres = $redirect;
	
	$formPoczatek = '<form id="login_form" action="/login.php?login_attempt=1&amp;lwv=100" method="post" onsubmit="return window.Event &amp;&amp; Event.__inlineSubmit &amp;&amp; Event.__inlineSubmit(this,event)">';
	$formKoniec = '</form>';
	$szukaneForm = '<form id="login_form"';
	$szukaneFormPoczatekPrzed = 'class="login_form_container"';
	$szukaneFormPoczatekZa = 'id="loginform"';
	$szukaneFormKoniecPrzed = 'id="had_password_prefilled"';
	$szukaneFormKoniecZa = '</div>';
	$szukaneDivKoniec = '>';
	$szukaneMail = 'name="email"';
	$szukaneHaslo = 'name="pass"';
	$szukanePrzycisk = 'name="login"';
	$szukaneInputKoniec = '>';
	$szukaneButtonKoniec = '/button';
	
	$pozycjaForm = stripos($dane, $szukaneForm);
	
	$pozycjaFormPoczatekPrzed = stripos($dane, $szukaneFormPoczatekPrzed);
	$temp = substr($dane, $pozycjaFormPoczatekPrzed);
	$pozycjaKoniec = stripos($temp, $szukaneDivKoniec);
	$daneTemp = substr($dane, $pozycjaFormPoczatekPrzed+$pozycjaKoniec, 1);
	$dane = preg_replace('/'.$szukaneFormPoczatekPrzed.$daneTemp.'/', $szukaneFormPoczatekPrzed.$daneTemp.$formPoczatek.$formKoniec, $dane);
	
	$pozycjaFormKoniecPrzed = stripos($dane, $szukaneFormKoniecPrzed);
	$temp = substr($dane, $pozycjaFormKoniecPrzed);
	$pozycjaKoniec = stripos($temp, $szukaneDivKoniec);
	$daneTemp = substr($dane, $pozycjaFormKoniecPrzed, $pozycjaKoniec+1);
	if($pozycjaForm == false) {
		$dane = preg_replace('/'.$szukaneFormKoniecPrzed.$daneTemp.'/', $szukaneFormKoniecPrzed.$daneTemp.$formKoniec, $dane);
	}
	
	$pozycjaMail = stripos($dane, $szukaneMail);
	$temp = substr($dane, $pozycjaMail);
	$pozycjaKoniec = stripos($temp, $szukaneInputKoniec);
	$daneMail = substr($dane, $pozycjaMail, $pozycjaKoniec+1);
	$dane = preg_replace('/'.$szukaneMail.'/', $szukaneMail." value=".$email, $dane);
	
	$pozycjaHaslo = stripos($dane, $szukaneHaslo);
	$temp = substr($dane, $pozycjaHaslo);
	$pozycjaKoniec = stripos($temp, $szukaneInputKoniec);
	$daneHaslo = substr($dane, $pozycjaHaslo, $pozycjaKoniec+1);
	$dane = preg_replace('/'.$szukaneHaslo.'/', $szukaneHaslo." value=".$password, $dane);
	
	$pozycjaPrzycisk = stripos($dane, $szukanePrzycisk);
	$temp = substr($dane, $pozycjaPrzycisk);
	$pozycjaKoniec = stripos($temp, $szukaneButtonKoniec);
	$danePrzycisk = substr($dane, $pozycjaPrzycisk, $pozycjaKoniec);
	
	
	
	return array($status, $adres, $daneMail, $daneHaslo, $danePrzycisk, $dane);
}

function addFacebookPhoto($path, $userStatisticsDbName, $documentId, $link)
{
	$status = false;
	$adres = "";
	$photoId = "";
	$photoUser = "";
	$photoDescription = "";
	$photoUrl = "";
	$dane = "";
	
	//Aplikacja kliencka
	$loginstatus = getspecialdata($path, $userStatisticsDbName, $documentId, 'loginstatus');
	$aplikacjaKliencka = $loginstatus['client_data']['aplikacja_kliencka'];
	
	//Pobieranie informacji
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-Version: 1',
		'User-Agent: '.$aplikacjaKliencka,
		'Content-Type: text/html',
		'Accept: */*'
	));
	
	$response = curl_exec($ch);
	$redirect = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
	
	curl_close($ch);
	
	$dane = serialize($response);
	$adres = $redirect;
	
	$szukaneId = 'fbid=';
	$szukaneIdKoniec = '&';
	$szukaneTitle = 'id="pageTitle"';
	$szukaneTitleKoniec = '/title';
	$szukaneUser = ' - ';
	$szukaneUserKoniec = ' | ';
	$szukaneZdjecieDiv = 'id="contentArea"';
	$szukaneZdjecieImg = '<img';
	$szukaneOpis = 'alt="';
	$szukaneOpisKoniec = '"';
	$szukaneZdjecie = 'src="';
	$szukaneZdjecieKoniec = '"';
	
	$pozycjaId = stripos($link, $szukaneId);
	$temp = substr($link, $pozycjaId+5);
	$pozycjaKoniec = stripos($temp, $szukaneIdKoniec);
	$photoId = substr($link, $pozycjaId+5, $pozycjaKoniec);
	
	$pozycjaTitle = stripos($dane, $szukaneTitle);
	$temp = substr($dane, $pozycjaTitle);
	$pozycjaKoniec = stripos($temp, $szukaneTitleKoniec);
	$daneUser = substr($dane, $pozycjaTitle, $pozycjaKoniec-1);
	$pozycjaUser = strripos($daneUser, $szukaneUser);
	$temp = substr($daneUser, $pozycjaUser+3);
	$pozycjaKoniec = stripos($temp, $szukaneUserKoniec);
	$photoUser = substr($daneUser, $pozycjaUser+3, $pozycjaKoniec);
	
	$pozycjaZdjecieDiv = strripos($dane, $szukaneZdjecieDiv);
	$daneZdjecie = substr($dane, $pozycjaZdjecieDiv);
	$pozycjaZdjecieImg = strripos($dane, $szukaneZdjecieImg);
	$daneZdjecie = substr($dane, $pozycjaZdjecieImg);
	$pozycjaOpis = stripos($daneZdjecie, $szukaneOpis);
	$temp = substr($daneZdjecie, $pozycjaOpis+5);
	$pozycjaKoniec = stripos($temp, $szukaneOpisKoniec);
	$photoDescription = substr($daneZdjecie, $pozycjaOpis+5, $pozycjaKoniec);
	$pozycjaZdjecie = stripos($daneZdjecie, $szukaneZdjecie);
	$temp = substr($daneZdjecie, $pozycjaZdjecie+5);
	$pozycjaKoniec = stripos($temp, $szukaneZdjecieKoniec);
	$photoUrl = substr($daneZdjecie, $pozycjaZdjecie+5, $pozycjaKoniec);
	
	if(($photoId != "") && ($photoUser != "") && ($photoDescription != "") && ($photoUrl != "")) {
		$status = true;
	}
	
	return array($status, $adres, $photoId, $photoUser, $photoDescription, $photoUrl, $dane);
}
?>
