<?php
function getprefiks($krajOdbiorcy)
{
	//Wyznaczenie prefiks-u
	switch ($krajOdbiorcy) {
		//Priorytet
		case 'Polska':
			$prefiks = '48';
			break;
		
		//Reszta
		case 'Afganistan':
			$prefiks = '93';
			break;
		case 'Albania':
			$prefiks = '355';
			break;
		case 'Algieria':
			$prefiks = '213';
			break;
		case 'Andora':
			$prefiks = '376';
			break;
		case 'Angola':
			$prefiks = '244';
			break;
		case 'Anguilla':
			$prefiks = '1264';
			break;
		case 'Antigua i Barbuda':
			$prefiks = '1268';
			break;
		case 'Antyle Holenderskie':
			$prefiks = '599';
			break;
		case 'Arabia Saudyjska':
			$prefiks = '966';
			break;
		case 'Argentyna':
			$prefiks = '54';
			break;
		case 'Armenia':
			$prefiks = '374';
			break;
		case 'Aruba':
			$prefiks = '297';
			break;
		case 'Australia':
			$prefiks = '61';
			break;
		case 'Austria':
			$prefiks = '43';
			break;
		case 'Azerbejdżan':
			$prefiks = '994';
			break;
		case 'Bahama':
			$prefiks = '1242';
			break;
		case 'Bahrajn':
			$prefiks = '973';
			break;
		case 'Bangladesz':
			$prefiks = '880';
			break;
		case 'Barbados':
			$prefiks = '1246';
			break;
		case 'Belau':
			$prefiks = '680';
			break;
		case 'Belgia':
			$prefiks = '32';
			break;
		case 'Belize':
			$prefiks = '501';
			break;
		case 'Benin':
			$prefiks = '229';
			break;
		case 'Bermudy':
			$prefiks = '1441';
			break;
		case 'Bhutan':
			$prefiks = '975';
			break;
		case 'Białoruś':
			$prefiks = '375';
			break;
		case 'Birma':
		case 'Mianmar':
			$prefiks = '95';
			break;
		case 'Boliwia':
			$prefiks = '591';
			break;
		case 'Bośnia i Hercegowina':
			$prefiks = '387';
			break;
		case 'Botswana':
			$prefiks = '267';
			break;
		case 'Brazylia':
			$prefiks = '55';
			break;
		case 'Brunei':
			$prefiks = '673';
			break;
		case 'Bułgaria':
			$prefiks = '359';
			break;
		case 'Burkina Faso':
			$prefiks = '226';
			break;
		case 'Burundi':
			$prefiks = '257';
			break;
		case 'Chile':
			$prefiks = '56';
			break;
		case 'Chiny':
			$prefiks = '86';
			break;
		case 'Chorwacja':
			$prefiks = '385';
			break;
		case 'Cypr':
			$prefiks = '357';
			break;
		case 'Czad':
			$prefiks = '235';
			break;
		case 'Czechy':
			$prefiks = '420';
			break;
		case 'Dania':
			$prefiks = '45';
			break;
		case 'Demokratyczna Republika Konga':
		case 'Zair':
			$prefiks = '243';
			break;
		case 'Diego Garcia':
			$prefiks = '246';
			break;
		case 'Dominika':
			$prefiks = '1767';
			break;
		case 'Dominikana':
			$prefiks = '1809';
			break;
		case 'Dziewicze Wyspy Brytyjskie':
			$prefiks = '1284';
			break;
		case 'Dziewicze Wyspy Stanów Zjednoczonych':
			$prefiks = '1340';
			break;
		case 'Dżibuti':
			$prefiks = '253';
			break;
		case 'Egipt':
			$prefiks = '20';
			break;
		case 'Ekwador':
			$prefiks = '593';
			break;
		case 'Erytrea':
			$prefiks = '291';
			break;
		case 'Estonia':
			$prefiks = '372';
			break;
		case 'Etiopia':
			$prefiks = '251';
			break;
		case 'Falklandy':
			$prefiks = '500';
			break;
		case 'Fidżi':
			$prefiks = '679';
			break;
		case 'Filipiny':
			$prefiks = '63';
			break;
		case 'Finlandia':
			$prefiks = '358';
			break;
		case 'Francja':
			$prefiks = '33';
			break;
		case 'Gabon':
			$prefiks = '241';
			break;
		case 'Gambia':
			$prefiks = '220';
			break;
		case 'Ghana':
			$prefiks = '233';
			break;
		case 'Gibraltar':
			$prefiks = '350';
			break;
		case 'Grecja':
			$prefiks = '30';
			break;
		case 'Grenlandia':
			$prefiks = '299';
			break;
		case 'Gruzja':
			$prefiks = '995';
			break;
		case 'Guam':
			$prefiks = '1671';
			break;
		case 'Gujana':
			$prefiks = '592';
			break;
		case 'Gujana Francuska':
			$prefiks = '594';
			break;
		case 'Gwadelupa':
			$prefiks = '590';
			break;
		case 'Gwatemala':
			$prefiks = '502';
			break;
		case 'Gwinea':
			$prefiks = '224';
			break;
		case 'Gwinea Bissau':
			$prefiks = '245';
			break;
		case 'Gwinea Równikowa':
			$prefiks = '240';
			break;
		case 'Haiti':
			$prefiks = '509';
			break;
		case 'Hiszpania':
			$prefiks = '34';
			break;
		case 'Holandia':
			$prefiks = '31';
			break;
		case 'Honduras':
			$prefiks = '504';
			break;
		case 'Hongkong':
			$prefiks = '852';
			break;
		case 'Indie':
			$prefiks = '91';
			break;
		case 'Indonezja':
			$prefiks = '62';
			break;
		case 'Irak':
			$prefiks = '964';
			break;
		case 'Iran':
			$prefiks = '98';
			break;
		case 'Irlandia':
			$prefiks = '353';
			break;
		case 'Islandia':
			$prefiks = '354';
			break;
		case 'Izrael':
			$prefiks = '972';
			break;
		case 'Jamusukro':
			$prefiks = '225';
			break;
		case 'Jamajka':
			$prefiks = '1876';
			break;
		case 'Japonia':
			$prefiks = '81';
			break;
		case 'Jemen':
			$prefiks = '967';
			break;
		case 'Jordania':
			$prefiks = '962';
			break;
		case 'Kajmany':
			$prefiks = '1345';
			break;
		case 'Kambodża':
			$prefiks = '855';
			break;
		case 'Kamerun':
			$prefiks = '237';
			break;
		case 'Kanada':
			$prefiks = '1';
			break;
		case 'Kanaryjskie Wyspy':
			$prefiks = '34';
			break;
		case 'Katar':
			$prefiks = '974';
			break;
		case 'Kazachstan':
			$prefiks = '7';
			break;
		case 'Kenia':
			$prefiks = '254';
			break;
		case 'Kirgistan':
			$prefiks = '996';
			break;
		case 'Kiribati':
			$prefiks = '686';
			break;
		case 'Kolumbia':
			$prefiks = '57';
			break;
		case 'Komory':
			$prefiks = '269';
			break;
		case 'Kongo':
			$prefiks = '242';
			break;
		case 'Korea Południowa':
			$prefiks = '82';
			break;
		case 'Korea Północna':
			$prefiks = '850';
			break;
		case 'Kostaryka':
			$prefiks = '506';
			break;
		case 'Kuba':
			$prefiks = '53';
			break;
		case 'Kuwejt':
			$prefiks = '965';
			break;
		case 'Laos':
			$prefiks = '856';
			break;
		case 'Lesotho':
			$prefiks = '266';
			break;
		case 'Liban':
			$prefiks = '961';
			break;
		case 'Liberia':
			$prefiks = '231';
			break;
		case 'Libia':
			$prefiks = '218';
			break;
		case 'Liechtenstein':
			$prefiks = '4175';
			break;
		case 'Litwa':
			$prefiks = '370';
			break;
		case 'Luksemburg':
			$prefiks = '352';
			break;
		case 'Łotwa':
			$prefiks = '371';
			break;
		case 'Macedonia':
			$prefiks = '389';
			break;
		case 'Madagaskar':
			$prefiks = '261';
			break;
		case 'Makau':
			$prefiks = '853';
			break;
		case 'Malawi':
			$prefiks = '265';
			break;
		case 'Malediwy':
			$prefiks = '960';
			break;
		case 'Malezja':
			$prefiks = '60';
			break;
		case 'Mali':
			$prefiks = '223';
			break;
		case 'Malta':
			$prefiks = '356';
			break;
		case 'Mariany Północne':
		case 'Saipan':
			$prefiks = '1670';
			break;
		case 'Maroko':
			$prefiks = '212';
			break;
		case 'Martynika':
			$prefiks = '596';
			break;
		case 'Mauretania':
			$prefiks = '222';
			break;
		case 'Mauritius':
			$prefiks = '230';
			break;
		case 'Meksyk':
			$prefiks = '52';
			break;
		case 'Mikronezja':
			$prefiks = '691';
			break;
		case 'Mołdawia':
			$prefiks = '373';
			break;
		case 'Monako':
			$prefiks = '377';
			break;
		case 'Mongolia':
			$prefiks = '976';
			break;
		case 'Montserrat':
			$prefiks = '1664';
			break;
		case 'Mozambik':
			$prefiks = '258';
			break;
		case 'Namibia':
			$prefiks = '264';
			break;
		case 'Nauru':
			$prefiks = '674';
			break;
		case 'Nepal':
			$prefiks = '977';
			break;
		case 'Niemcy':
			$prefiks = '49';
			break;
		case 'Niger':
			$prefiks = '227';
			break;
		case 'Nigeria':
			$prefiks = '234';
			break;
		case 'Nikaragua':
			$prefiks = '505';
			break;
		case 'Niue':
			$prefiks = '683';
			break;
		case 'Norfolk':
			$prefiks = '672';
			break;
		case 'Norwegia':
			$prefiks = '47';
			break;
		case 'Nowa Kaledonia':
			$prefiks = '687';
			break;
		case 'Nowa Zelandia':
			$prefiks = '64';
			break;
		case 'Oman':
			$prefiks = '968';
			break;
		case 'Pakistan':
			$prefiks = '92';
			break;
		case 'Panama':
			$prefiks = '507';
			break;
		case 'Papua Nowa Gwinea':
			$prefiks = '675';
			break;
		case 'Paragwaj':
			$prefiks = '595';
			break;
		case 'Peru':
			$prefiks = '51';
			break;
		case 'Polinezja Francuska':
			$prefiks = '689';
			break;
		case 'Portugalia':
			$prefiks = '351';
			break;
		case 'Portoryko':
			$prefiks = '1787';
			break;
		case 'Republika Południowej Afryki':
			$prefiks = '27';
			break;
		case 'Republika Środkowoafrykańska':
			$prefiks = '236';
			break;
		case 'Reunion':
			$prefiks = '262';
			break;
		case 'Rosja':
			$prefiks = '7';
			break;
		case 'Rumunia':
			$prefiks = '40';
			break;
		case 'Rwanda':
			$prefiks = '250';
			break;
		case 'Saint Kitts i Nevis':
			$prefiks = '1869';
			break;
		case 'Saint Lucia':
			$prefiks = '1758';
			break;
		case 'Saint Vincent i Grenadyny':
			$prefiks = '1809';
			break;
		case 'Salwador':
			$prefiks = '503';
			break;
		case 'Samoa':
			$prefiks = '684';
			break;
		case 'Samoa Amerykańskie':
			$prefiks = '685';
			break;
		case 'San Marino':
			$prefiks = '378';
			break;
		case 'Senegal':
			$prefiks = '221';
			break;
		case 'Seszele':
			$prefiks = '248';
			break;
		case 'Sierra Leone':
			$prefiks = '232';
			break;
		case 'Singapur':
			$prefiks = '65';
			break;
		case 'Słowacja':
			$prefiks = '421';
			break;
		case 'Słowenia':
			$prefiks = '386';
			break;
		case 'Somalia':
			$prefiks = '252';
			break;
		case 'Sri Lanka':
			$prefiks = '94';
			break;
		case 'Stany Zjednoczone Ameryki':
			$prefiks = '1';
			break;
		case 'Suazi':
			$prefiks = '268';
			break;
		case 'Sudan':
			$prefiks = '249';
			break;
		case 'Surinam':
			$prefiks = '597';
			break;
		case 'Syria':
			$prefiks = '963';
			break;
		case 'Szwajcaria':
			$prefiks = '41';
			break;
		case 'Szwecja':
			$prefiks = '46';
			break;
		case 'Tadżykistan':
			$prefiks = '7';
			break;
		case 'Tajlandia':
			$prefiks = '66';
			break;
		case 'Tajwan':
			$prefiks = '886';
			break;
		case 'Tanzania':
			$prefiks = '255';
			break;
		case 'Togo':
			$prefiks = '228';
			break;
		case 'Tokelau':
			$prefiks = '690';
			break;
		case 'Tonga':
			$prefiks = '616';
			break;
		case 'Trynidad i Tobago':
			$prefiks = '1868';
			break;
		case 'Tunezja':
			$prefiks = '216';
			break;
		case 'Turcja':
			$prefiks = '90';
			break;
		case 'Turkmenistan':
			$prefiks = '993';
			break;
		case 'Tuvalu':
			$prefiks = '688';
			break;
		case 'Uganda':
			$prefiks = '256';
			break;
		case 'Ukraina':
			$prefiks = '380';
			break;
		case 'Urugwaj':
			$prefiks = '598';
			break;
		case 'Uzbekistan':
			$prefiks = '7';
			break;
		case 'Vanuatu':
			$prefiks = '678';
			break;
		case 'Walia':
			$prefiks = '681';
			break;
		case 'Watykan':
			$prefiks = '396';
			break;
		case 'Wenezuela':
			$prefiks = '58';
			break;
		case 'Węgry':
			$prefiks = '36';
			break;
		case 'Wielka Brytania':
			$prefiks = '44';
			break;
		case 'Wietnam':
			$prefiks = '84';
			break;
		case 'Włochy':
			$prefiks = '39';
			break;
		case 'Wybrzeże Kości Słoniowej':
			$prefiks = '225';
			break;
		case 'Wyspa Świętej Heleny':
			$prefiks = '290';
			break;
		case 'Wyspy Cooka':
			$prefiks = '682';
			break;
		case 'Wyspy Marshalla':
			$prefiks = '692';
			break;
		case 'Wyspy Owcze':
			$prefiks = '298';
			break;
		case 'Wyspy Salomona':
			$prefiks = '677';
			break;
		case 'Wyspy Świętego Piotra i Mikeleona':
			$prefiks = '508';
			break;
		case 'Wyspy Świętego Tomasza i Książęca':
			$prefiks = '239';
			break;
		case 'Wyspy Turks i Caicos':
			$prefiks = '1649';
			break;
		case 'Wyspy Wniebowstąpienia':
			$prefiks = '247';
			break;
		case 'Zambia':
			$prefiks = '260';
			break;
		case 'Zanzibar':
			$prefiks = '259';
			break;
		case 'Zimbabwe':
			$prefiks = '263';
			break;
		case 'Zjednoczone Emiraty Arabskie':
			$prefiks = '971';
			break;
		
		//Standardowy wybór
		default:
			$prefiks = '48';
			break;
	}
	
	return $prefiks;
}

function getnetworkcode($prefiks, $numerOdbiorcy)
{
	$MCC = null;
	$MNC  = null;
	
	//Pobieranie kodu sieci komórkowej
	switch ($prefiks) {
		//Priorytet
		case '48':
			//Pobieranie informacji
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, 'http://download.t-mobile.pl/updir/updir.cgi?msisdn='.$prefiks.$numerOdbiorcy);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'X-Version: 1',
				'Content-Type: application/json',
				'Accept: */*'
			));
			
			$response = curl_exec($ch);
			
			curl_close($ch);
			
			//Sprawdzenie informacji
			$tekst = strip_tags($response);
			$temp = stristr($tekst, 'Kod sieci');
			
			$kod = preg_replace('/[^0-9]+[^0-9]+/', '', $temp);
			
			$do = strpos($kod, ' ');
			$od = $do + 1;
			
			$MCC = substr($kod, 0, $do);
			$MNC = substr($kod, $od);
			break;
		
		//Reszta
		case '49':
			//Pobieranie informacji
			$smsarc_API_key = '';	//leave this blank - we may never implement an authentication key
			
			$smsarc_number = $numerOdbiorcy;	//enter the user's 10 digit cell phone number, example format: $smsarc_to = '5556667777';
			
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, 'http://www.smsarc.com/api-carrier-lookup.php?sa_number='.$smsarc_number);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'X-Version: 1',
				'Content-Type: application/json',
				'Accept: */*'
			));
			
			$error = curl_error($ch);
			$AskApache_result = curl_exec($ch);
			
			curl_close($ch);
			
			$smsarc_message_status =  $AskApache_result;
			echo "<br />";	//test
			echo "error=".$error."<br />";	//test
			echo "AskApache_result=".$AskApache_result."<br />";	//test
			if(isset($smsarc_carrier)) {
				echo "smsarc_carrier=".$smsarc_carrier."<br />";	//test
			}
			break;
	}
	
	return array($MCC, $MNC);
}

function getmobilenetwork($MCC, $MNC)
{
	$krajOperatora = null;
	$nazwaOperatora = null;
	
	//Wyznaczenie operatora
	switch ($MCC) {
		//Priorytet
		case '260':
			$krajOperatora = 'Polska';
			switch ($MNC) {
				case '01':
					$nazwaOperatora = 'Plus';
					break;
				case '02':
					$nazwaOperatora = 'T-Mobile';
					break;
				case '03':
				case '05':
				case '34':
					$nazwaOperatora = 'Orange';
					break;
				case '04':
					$nazwaOperatora = 'CenterNet';
					break;
				case '06':
				case '98':
					$nazwaOperatora = 'Play';
					break;
				case '07':
					$nazwaOperatora = 'Netia';
					break;
				case '08':
					$nazwaOperatora = 'E-Telko';
					break;
				case '09':
					$nazwaOperatora = 'Lycamobile';
					break;
				case '10':
				case '13':
				case '14':
					$nazwaOperatora = 'Sferia';
					break;
				case '11':
					$nazwaOperatora = 'Nordisk Polska';
					break;
				case '12':
					$nazwaOperatora = 'Cyfrowy Polsat';
					break;
				case '15':
					$nazwaOperatora = 'CenterNet';
					break;
				case '16':
					$nazwaOperatora = 'Mobyland';
					break;
				case '17':
					$nazwaOperatora = 'Aero 2';
					break;
				case '18':
					$nazwaOperatora = 'AMD Telecom';
					break;
				case '20':
					$nazwaOperatora = 'Mobile.Net';
					break;
				case '21':
					$nazwaOperatora = 'Exteri';
					break;
				case '2101':
					$nazwaOperatora = 'MSWiA';
					break;
				case '2102':
					$nazwaOperatora = 'Miejskie Przedsiębiorstwo Komunikacyjne Sp. z o.o. (Wrocław)';
					break;
				case '2103':
					$nazwaOperatora = 'Przedsiębiorstwo Państwowe PORTY LOTNICZE';
					break;
				case '2104':
					$nazwaOperatora = 'Gdynia Container Terminal S.A.';
					break;
				case '2105':
					$nazwaOperatora = 'DCT Gdańsk S.A.';
					break;
				case '2106':
					$nazwaOperatora = 'GTL LOT Usługi Lotniskowe Sp. z o.o.';
					break;
				case '2107':
					$nazwaOperatora = 'Miejskie Przedsiębiorstwo Komunikacyjne S. A. (Kraków)';
					break;
				case '2108':
					$nazwaOperatora = 'MPWiK Sp. z o.o. (Wrocław)';
					break;
				case '2109':
					$nazwaOperatora = 'Politechnika Gdańska';
					break;
				case '2110':
					$nazwaOperatora = 'BCT';
					break;
				case '2111':
					$nazwaOperatora = 'PGE';
					break;
				case '2112':
					$nazwaOperatora = 'Port Lotniczy Rzeszów-Jasionka Sp. z o.o.';
					break;
				case '22':
					$nazwaOperatora = 'Arcomm';
					break;
				case '23':
					$nazwaOperatora = 'Amicomm';
					break;
				case '24':
					$nazwaOperatora = 'WideNet';
					break;
				case '25':
					$nazwaOperatora = 'Best Solutions & Technology Sp. z o.o.';
					break;
				case '26':
					$nazwaOperatora = 'ATE-Advanced Technology & Experience Sp. z o.o.';
					break;
				case '27':
					$nazwaOperatora = 'Intertelcom';
					break;
				case '28':
					$nazwaOperatora = 'PhoneNet';
					break;
				case '29':
					$nazwaOperatora = 'Interfonica';
					break;
				case '30':
					$nazwaOperatora = 'GrandTel';
					break;
				case '31':
					$nazwaOperatora = 'Phone IT';
					break;
				case '32':
					$nazwaOperatora = 'Compatel Limited';
					break;
				case '33':
					$nazwaOperatora = 'Truphone Poland Sp. z.o.o.';
					break;
			}
			break;
		
		//Reszta
		case '412':
			$krajOperatora = 'Afganistan';
			break;
		case '276':
			$krajOperatora = 'Albania';
			break;
		case '603':
			$krajOperatora = 'Algieria';
			break;
		case '213':
			$krajOperatora = 'Andora';
			break;
		case '631':
			$krajOperatora = 'Angola';
			break;
		case '365':
			$krajOperatora = 'Anguilla';
			break;
		case '344':
			$krajOperatora = 'Antigua i Barbuda';
			break;
		case '362':
			$krajOperatora = 'Antyle Holenderskie';
			break;
		case '420':
			$krajOperatora = 'Arabia Saudyjska';
			break;
		case '722':
			$krajOperatora = 'Argentyna';
			break;
		case '283':
			$krajOperatora = 'Armenia';
			break;
		case '363':
			$krajOperatora = 'Aruba';
			break;
		case '505':
			$krajOperatora = 'Australia';
			break;
		case '232':
			$krajOperatora = 'Austria';
			break;
		case '400':
			$krajOperatora = 'Azerbejdżan';
			break;
		case '364':
			$krajOperatora = 'Bahama';
			break;
		case '426':
			$krajOperatora = 'Bahrajn';
			break;
		case '470':
			$krajOperatora = 'Bangladesz';
			break;
		case '342':
			$krajOperatora = 'Barbados';
			break;
		case '206':
			$krajOperatora = 'Belgia';
			break;
		case '702':
			$krajOperatora = 'Belize';
			break;
		case '616':
			$krajOperatora = 'Benin';
			break;
		case '350':
			$krajOperatora = 'Bermudy';
			break;
		case '402':
			$krajOperatora = 'Bhutan';
			break;
		case '257':
			$krajOperatora = 'Białoruś';
			break;
		case '414':
			$krajOperatora = 'Birma';
			break;
		case '736':
			$krajOperatora = 'Boliwia';
			break;
		case '218':
			$krajOperatora = 'Bośnia i Hercegowina';
			break;
		case '652':
			$krajOperatora = 'Botswana';
			break;
		case '724':
			$krajOperatora = 'Brazylia';
			break;
		case '528':
			$krajOperatora = 'Brunei';
			break;
		case '284':
			$krajOperatora = 'Bułgaria';
			break;
		case '613':
			$krajOperatora = 'Burkina Faso';
			break;
		case '642':
			$krajOperatora = 'Burundi';
			break;
		case '622':
			$krajOperatora = 'Czad';
			break;
		case '297':
			$krajOperatora = 'Czarnogóra';
			break;
		case '730':
			$krajOperatora = 'Chile';
			break;
		case '460':
		case '461':
			$krajOperatora = 'Chiny';
			break;
		case '219':
			$krajOperatora = 'Chorwacja';
			break;
		case '280':
			$krajOperatora = 'Cypr';
			break;
		case '230':
			$krajOperatora = 'Czechy';
			break;
		case '238':
			$krajOperatora = 'Dania';
			break;
		case '630':
			$krajOperatora = 'Demokratyczna Republika Konga';
			break;
		case '366':
			$krajOperatora = 'Dominika';
			break;
		case '370':
			$krajOperatora = 'Dominikana';
			break;
		case '348':
			$krajOperatora = 'Dziewicze Wyspy Brytyjskie';
			break;
		case '332':
			$krajOperatora = 'Dziewicze Wyspy Stanów Zjednoczonych';
			break;
		case '638':
			$krajOperatora = 'Dżibuti';
			break;
		case '602':
			$krajOperatora = 'Egipt';
			break;
		case '740':
			$krajOperatora = 'Ekwador';
			break;
		case '657':
			$krajOperatora = 'Erytrea';
			break;
		case '248':
			$krajOperatora = 'Estonia';
			break;
		case '636':
			$krajOperatora = 'Etiopia';
			break;
		case '542':
			$krajOperatora = 'Fidżi';
			break;
		case '515':
			$krajOperatora = 'Filipiny';
			break;
		case '244':
			$krajOperatora = 'Finlandia';
			break;
		case '208':
			$krajOperatora = 'Francja';
			break;
		case '628':
			$krajOperatora = 'Gabon';
			break;
		case '607':
			$krajOperatora = 'Gambia';
			break;
		case '620':
			$krajOperatora = 'Ghana';
			break;
		case '266':
			$krajOperatora = 'Gibraltar';
			break;
		case '202':
			$krajOperatora = 'Grecja';
			break;
		case '352':
			$krajOperatora = 'Grenada';
			break;
		case '290':
			$krajOperatora = 'Grenlandia';
			break;
		case '282':
			$krajOperatora = 'Gruzja';
			break;
		case '535':
			$krajOperatora = 'Guam';
			break;
		case '738':
			$krajOperatora = 'Gujana';
			break;
		case '742':
			$krajOperatora = 'Gujana Francuska';
			break;
		case '340':
			$krajOperatora = 'Gwadelupa';
			break;
		case '704':
			$krajOperatora = 'Gwatemala';
			break;
		case '611':
			$krajOperatora = 'Gwinea';
			break;
		case '632':
			$krajOperatora = 'Gwinea Bissau';
			break;
		case '627':
			$krajOperatora = 'Gwinea Równikowa';
			break;
		case '372':
			$krajOperatora = 'Haiti';
			break;
		case '214':
			$krajOperatora = 'Hiszpania';
			break;
		case '204':
			$krajOperatora = 'Holandia';
			break;
		case '708':
			$krajOperatora = 'Honduras';
			break;
		case '454':
			$krajOperatora = 'Hongkong';
			break;
		case '404':
			$krajOperatora = 'Indie';
			break;
		case '510':
			$krajOperatora = 'Indonezja';
			break;
		case '418':
			$krajOperatora = 'Irak';
			break;
		case '432':
			$krajOperatora = 'Iran';
			break;
		case '272':
			$krajOperatora = 'Irlandia';
			break;
		case '274':
			$krajOperatora = 'Islandia';
			break;
		case '425':
			$krajOperatora = 'Izrael';
			break;
		case '338':
			$krajOperatora = 'Jamajka';
			break;
		case '440':
		case '441':
			$krajOperatora = 'Japonia';
			break;
		case '421':
			$krajOperatora = 'Jemen';
			break;
		case '416':
			$krajOperatora = 'Jordania';
			break;
		case '346':
			$krajOperatora = 'Kajmany';
			break;
		case '456':
			$krajOperatora = 'Kambodża';
			break;
		case '624':
			$krajOperatora = 'Kamerun';
			break;
		case '302':
			$krajOperatora = 'Kanada';
			break;
		case '427':
			$krajOperatora = 'Katar';
			break;
		case '401':
			$krajOperatora = 'Kazachstan';
			break;
		case '639':
			$krajOperatora = 'Kenia';
			break;
		case '437':
			$krajOperatora = 'Kirgistan';
			break;
		case '545':
			$krajOperatora = 'Kiribati';
			break;
		case '732':
			$krajOperatora = 'Kolumbia';
			break;
		case '654':
			$krajOperatora = 'Komory';
			break;
		case '629':
			$krajOperatora = 'Kongo';
			break;
		case '450':
			$krajOperatora = 'Korea Południowa';
			break;
		case '467':
			$krajOperatora = 'Korea Północna';
			break;
		case '712':
			$krajOperatora = 'Kostaryka';
			break;
		case '368':
			$krajOperatora = 'Kuba';
			break;
		case '419':
			$krajOperatora = 'Kuwejt';
			break;
		case '457':
			$krajOperatora = 'Laos';
			break;
		case '651':
			$krajOperatora = 'Lesotho';
			break;
		case '415':
			$krajOperatora = 'Liban';
			break;
		case '618':
			$krajOperatora = 'Liberia';
			break;
		case '606':
			$krajOperatora = 'Libia';
			break;
		case '295':
			$krajOperatora = 'Liechtenstein';
			break;
		case '246':
			$krajOperatora = 'Litwa';
			break;
		case '270':
			$krajOperatora = 'Luksemburg';
			break;
		case '247':
			$krajOperatora = 'Łotwa';
			break;
		case '294':
			$krajOperatora = 'Macedonia';
			break;
		case '646':
			$krajOperatora = 'Madagaskar';
			break;
		case '455':
			$krajOperatora = 'Makau';
			break;
		case '650':
			$krajOperatora = 'Malawi';
			break;
		case '472':
			$krajOperatora = 'Malediwy';
			break;
		case '502':
			$krajOperatora = 'Malezja';
			break;
		case '610':
			$krajOperatora = 'Mali';
			break;
		case '278':
			$krajOperatora = 'Malta';
			break;
		case '534':
			$krajOperatora = 'Mariany Północne';
			break;
		case '604':
			$krajOperatora = 'Maroko';
			break;
		case '340':
			$krajOperatora = 'Martynika';
			break;
		case '609':
			$krajOperatora = 'Mauretania';
			break;
		case '617':
			$krajOperatora = 'Mauritius';
			break;
		case '334':
			$krajOperatora = 'Meksyk';
			break;
		case '550':
			$krajOperatora = 'Mikronezja';
			break;
		case '259':
			$krajOperatora = 'Mołdawia';
			break;
		case '212':
			$krajOperatora = 'Monako';
			break;
		case '428':
			$krajOperatora = 'Mongolia';
			break;
		case '354':
			$krajOperatora = 'Montserrat';
			break;
		case '643':
			$krajOperatora = 'Mozambik';
			break;
		case '649':
			$krajOperatora = 'Namibia';
			break;
		case '536':
			$krajOperatora = 'Nauru';
			break;
		case '429':
			$krajOperatora = 'Nepal';
			break;
		case '262':
			$krajOperatora = 'Niemcy';
			switch ($MNC) {
				case '1':
				case '6':
					$nazwaOperatora = 'T-Mobile';
					break;
				case '2':
				case '4':
				case '9':
					$nazwaOperatora = 'Vodafone';
					break;
				case '3':
				case '5':
				case '77':
					$nazwaOperatora = 'E-plus';
					break;
				case '7':
				case '8':
				case '11':
					$nazwaOperatora = 'O2';
					break;
				case '10':
					$nazwaOperatora = 'Arcor';
					break;
				case '12':
					$nazwaOperatora = 'Dolphin Telecom';
					break;
				case '13':
					$nazwaOperatora = 'Mobilcom Multimedia';
					break;
				case '14':
					$nazwaOperatora = 'Group 3G UMTS';
					break;
				case '15':
					$nazwaOperatora = 'Airdata';
					break;
				case '16':
					$nazwaOperatora = 'Vistream';
					break;
				case '17':
					$nazwaOperatora = 'Ring Mobilfunk';
					break;
				case '20':
					$nazwaOperatora = 'OnePhone';
					break;
				case '43':
					$nazwaOperatora = 'Lyca';
					break;
				case '60':
					$nazwaOperatora = 'DB Telematik';
					break;
				case '76':
					$nazwaOperatora = 'Siemens';
					break;
				case '901':
					$nazwaOperatora = 'Debitel';
					break;
			}
			break;
		case '614':
			$krajOperatora = 'Niger';
			break;
		case '621':
			$krajOperatora = 'Nigeria';
			break;
		case '710':
			$krajOperatora = 'Nikaragua';
			break;
		case '242':
			$krajOperatora = 'Norwegia';
			break;
		case '546':
			$krajOperatora = 'Nowa Kaledonia';
			break;
		case '530':
			$krajOperatora = 'Nowa Zelandia';
			break;
		case '422':
			$krajOperatora = 'Oman';
			break;
		case '410':
			$krajOperatora = 'Pakistan';
			break;
		case '552':
			$krajOperatora = 'Palau';
			break;
		case '714':
			$krajOperatora = 'Panama';
			break;
		case '537':
			$krajOperatora = 'Papua Nowa Gwinea';
			break;
		case '744':
			$krajOperatora = 'Paragwaj';
			break;
		case '716':
			$krajOperatora = 'Peru';
			break;
		case '547':
			$krajOperatora = 'Polinezja Francuska';
			break;
		case '268':
			$krajOperatora = 'Portugalia';
			break;
		case '330':
			$krajOperatora = 'Portoryko';
			break;
		case '655':
			$krajOperatora = 'Republika Południowej Afryki';
			break;
		case '623':
			$krajOperatora = 'Republika Środkowoafrykańska';
			break;
		case '625':
			$krajOperatora = 'Republika Zielonego Przylądka';
			break;
		case '647':
			$krajOperatora = 'Reunion';
			break;
		case '250':
			$krajOperatora = 'Rosja';
			break;
		case '226':
			$krajOperatora = 'Rumunia';
			break;
		case '635':
			$krajOperatora = 'Rwanda';
			break;
		case '356':
			$krajOperatora = 'Saint Kitts i Nevis';
			break;
		case '358':
			$krajOperatora = 'Saint Lucia';
			break;
		case '308':
			$krajOperatora = 'Saint Pierre i Miquelon';
			break;
		case '360':
			$krajOperatora = 'Saint Vincent i Grenadyny';
			break;
		case '706':
			$krajOperatora = 'Salwador';
			break;
		case '549':
			$krajOperatora = 'Samoa';
			break;
		case '544':
			$krajOperatora = 'Samoa Amerykańskie';
			break;
		case '292':
			$krajOperatora = 'San Marino';
			break;
		case '608':
			$krajOperatora = 'Senegal';
			break;
		case '220':
			$krajOperatora = 'Serbia';
			break;
		case '633':
			$krajOperatora = 'Seszele';
			break;
		case '619':
			$krajOperatora = 'Sierra Leone';
			break;
		case '525':
			$krajOperatora = 'Singapur';
			break;
		case '231':
			$krajOperatora = 'Słowacja';
			break;
		case '293':
			$krajOperatora = 'Słowenia';
			break;
		case '637':
			$krajOperatora = 'Somalia';
			break;
		case '413':
			$krajOperatora = 'Sri Lanka';
			break;
		case '310':
		case '311':
		case '312':
		case '313':
		case '314':
		case '315':
		case '316':
			$krajOperatora = 'Stany Zjednoczone Ameryki';
			break;
		case '653':
			$krajOperatora = 'Suazi';
			break;
		case '634':
			$krajOperatora = 'Sudan';
			break;
		case '746':
			$krajOperatora = 'Surinam';
			break;
		case '417':
			$krajOperatora = 'Syria';
			break;
		case '228':
			$krajOperatora = 'Szwajcaria';
			break;
		case '240':
			$krajOperatora = 'Szwecja';
			break;
		case '436':
			$krajOperatora = 'Tadżykistan';
			break;
		case '520':
			$krajOperatora = 'Tajlandia';
			break;
		case '466':
			$krajOperatora = 'Tajwan';
			break;
		case '640':
			$krajOperatora = 'Tanzania';
			break;
		case '514':
			$krajOperatora = 'Timor Wschodni';
			break;
		case '615':
			$krajOperatora = 'Togo';
			break;
		case '539':
			$krajOperatora = 'Tonga';
			break;
		case '374':
			$krajOperatora = 'Trynidad i Tobago';
			break;
		case '605':
			$krajOperatora = 'Tunezja';
			break;
		case '286':
			$krajOperatora = 'Turcja';
			break;
		case '438':
			$krajOperatora = 'Turkmenistan';
			break;
		case '641':
			$krajOperatora = 'Uganda';
			break;
		case '255':
			$krajOperatora = 'Ukraina';
			break;
		case '748':
			$krajOperatora = 'Urugwaj';
			break;
		case '434':
			$krajOperatora = 'Uzbekistan';
			break;
		case '541':
			$krajOperatora = 'Vanuatu';
			break;
		case '225':
			$krajOperatora = 'Watykan';
			break;
		case '734':
			$krajOperatora = 'Wenezuela';
			break;
		case '216':
			$krajOperatora = 'Węgry';
			break;
		case '234':
		case '235':
			$krajOperatora = 'Wielka Brytania';
			break;
		case '452':
			$krajOperatora = 'Wietnam';
			break;
		case '222':
			$krajOperatora = 'Włochy';
			break;
		case '612':
			$krajOperatora = 'Wybrzeże Kości Słoniowej';
			break;
		case '548':
			$krajOperatora = 'Wyspy Cooka';
			break;
		case '551':
			$krajOperatora = 'Wyspy Marshalla';
			break;
		case '288':
			$krajOperatora = 'Wyspy Owcze';
			break;
		case '540':
			$krajOperatora = 'Wyspy Salomona';
			break;
		case '626':
			$krajOperatora = 'Wyspy Świętego Tomasza i Książęca';
			break;
		case '376':
			$krajOperatora = 'Wyspy Turks i Caicos';
			break;
		case '543':
			$krajOperatora = 'Wyspy Wallis i Futuna';
			break;
		case '645':
			$krajOperatora = 'Zambia';
			break;
		case '648':
			$krajOperatora = 'Zimbabwe';
			break;
		case '424':
		case '430':
		case '431':
			$krajOperatora = 'Zjednoczone Emiraty Arabskie';
			break;
		
		//Test sieci GSM
		case '001':
			$krajOperatora = 'TEST';
			switch ($MNC) {
				case '01':
					$nazwaOperatora = 'Test Network';
					break;
			}
			break;
	}
	
	return array($krajOperatora, $nazwaOperatora);
}

function getgateway($krajOperatora, $nazwaOperatora)
{
	$gateway = null;
	
	//Wyznaczenie bramki
	switch ($krajOperatora) {
		//Priorytet
		case 'Polska':
			switch ($nazwaOperatora) {
				case 'Orange':
					$gateway = 'orange.pl';
					break;
				case 'Plus':
					$gateway = 'text.plusgsm.pl';
					break;
				
				//Standardowy wybór
				default:
					$gateway = 'text.plusgsm.pl';
					break;
			}
			break;
		
		//Reszta
		case 'Argentyna':
			switch ($nazwaOperatora) {
				case 'CTI':
					$gateway = 'sms.ctimovil.com.ar';
					break;
				case 'Movicom':
					$gateway = 'sms.movistar.net.ar';
					break;
				case 'Nextel':
					$gateway = 'nextel.net.ar';
					break;
				case 'Personal':
					$gateway = 'alertas.personal.com.ar';
					break;
			}
			break;
		case 'Aruba':
			switch ($nazwaOperatora) {
				case 'Setar Mobile':
					$gateway = 'mas.aw';
					break;
			}
			break;
		case 'Australia':
			switch ($nazwaOperatora) {
				case 'Blue Sky Frog':
					$gateway = 'blueskyfrog.com';
					break;
				case 'Optus Mobile':
					$gateway = 'optusmobile.com.au';
					break;
				case 'Powertel':
					$gateway = 'voicestream.net';
					break;
				case 'SL Interactive':
					$gateway = 'slinteractive.com.au';
					break;
			}
			break;
		case 'Austria':
			switch ($nazwaOperatora) {
				case 'MaxMobil':
					$gateway = 'max.mail.at';
					break;
				case 'One Connect':
					$gateway = 'onemail.at';
					break;
				case 'T-Mobile':
					$gateway = 'sms.t-mobile.at';
					break;
			}
			break;
		case 'Belgia':
			switch ($nazwaOperatora) {
				case 'Mobistar':
					$gateway = 'mobistar.be';
					break;
			}
			break;
		case 'Bermudy':
			switch ($nazwaOperatora) {
				case 'Mobility':
					$gateway = 'ml.bm';
					break;
			}
			break;
		case 'Brazylia':
			switch ($nazwaOperatora) {
				case 'Nextel':
					$gateway = 'nextel.com.br';
					break;
				case 'Claro':
					$gateway = 'clarotorpedo.com.br';
					break;
			}
			break;
		case 'Bułgaria':
			switch ($nazwaOperatora) {
				case 'Globul':
					$gateway = 'sms.globul.bg';
					break;
				case 'Mtel':
					$gateway = 'sms.mtel.net';
					break;
			}
			break;
		case 'Chile':
			switch ($nazwaOperatora) {
				case 'Bell South':
					$gateway = 'bellsouth.cl';
					break;
			}
			break;
		case 'Czarnogóra':
		case 'Serbia':
			switch ($nazwaOperatora) {
				case 'Mobtel Srbija':
					$gateway = 'mobtel.co.yu';
					break;
			}
			break;
		case 'Czechy':
			switch ($nazwaOperatora) {
				case 'Eurotel':
					$gateway = 'sms.eurotel.cz';
					break;
				case 'Oskar':
					$gateway = 'mujoskar.cz';
					break;
			}
			break;
		case 'Dania':
			switch ($nazwaOperatora) {
				case 'Sonofon':
					$gateway = 'note.sonofon.dk';
					break;
				case 'Tele Danmark Mobil':
					$gateway = 'sms.tdk.dk';
					break;
				case 'Telia Denmark':
					$gateway = 'gsm1800.telia.dk';
					break;
			}
			break;
		case 'Estonia':
			switch ($nazwaOperatora) {
				case 'EMT':
					$gateway = 'sms.emt.ee';
					break;
			}
			break;
		case 'Francja':
			switch ($nazwaOperatora) {
				case 'SFR':
					$gateway = 'sfr.fr';
					break;
			}
			break;
		case 'Hiszpania':
			switch ($nazwaOperatora) {
				case 'Movistar':
					$gateway = 'correo.movistar.net';
					break;
				case 'Telefonica Movistar':
					$gateway = 'movistar.net';
					break;
				case 'Vodafone':
					$gateway = 'vodafone.es';
					break;
			}
			break;
		case 'Holandia':
			switch ($nazwaOperatora) {
				case 'Dutchtone':
					$gateway = 'sms.orange.nl';
					break;
				case 'Orange-NL':
					$gateway = 'sms.orange.nl';
					break;
				case 'T-Mobile':
					$gateway = 'gin.nl';
					break;
			}
			break;
		case 'Indie':
			switch ($nazwaOperatora) {
				case 'Andhra Pradesh Airtel':
					$gateway = 'airtelap.com';
					break;
				case 'BPL mobile':
					$gateway = 'bplmobile.com';
					break;
				case 'Chennai RPG Cellular':
					$gateway = 'rpgmail.net';
					break;
				case 'Chennai Skycell / Airtel':
					$gateway = 'airtelchennai.com';
					break;
				case 'Delhi Aritel':
					$gateway = 'airtelmail.com';
					break;
				case 'Delhi Hutch':
					$gateway = 'delhi.hutch.co.in';
					break;
				case 'Escotel':
					$gateway = 'escotelmobile.com';
					break;
				case 'Gujarat Celforce':
					$gateway = 'celforce.com';
					break;
				case 'Idea Cellular':
					$gateway = 'ideacellular.net';
					break;
				case 'Karnataka Airtel':
					$gateway = 'airtelkk.com';
					break;
				case 'Kerala Airtel':
					$gateway = 'airtelkerala.com';
					break;
				case 'Kolkata Airtel':
					$gateway = 'airtelkol.com';
					break;
				case 'Orange':
					$gateway = 'orangemail.co.in';
					break;
				case 'Tamil Nadu Aircel':
					$gateway = 'airsms.com';
					break;
			}
			break;
		case 'Irlandia':
			switch ($nazwaOperatora) {
				case 'Meteor':
					$gateway = 'mymeteor.ie';
					break;
				case 'Meteor':
					$gateway = 'sms.mymeteor.ie';
					break;
				case 'Meteor MMS':
					$gateway = 'mms.mymeteor.ie';
					break;
			}
			break;
		case 'Islandia':
			switch ($nazwaOperatora) {
				case 'OgVodafone':
					$gateway = 'sms.is';
					break;
				case 'Siminn':
					$gateway = 'box.is';
					break;
			}
			break;
		case 'Japonia':
			switch ($nazwaOperatora) {
				case 'au by KDDI':
					$gateway = 'ezweb.ne.jp';
					break;
				case 'NTT Docomo':
					$gateway = 'docomo.ne.jp';
					break;
				case 'Vodafone Chuugoku/Western':
					$gateway = 'n.vodafone.ne.jp';
					break;
				case 'Vodafone Hokkaido':
					$gateway = 'd.vodafone.ne.jp';
					break;
				case 'Vodafone Hokuriko/Central North':
					$gateway = 'r.vodafone.ne.jp';
					break;
				case 'Vodafone Kansai/West (including Osaka)':
					$gateway = 'k.vodafone.ne.jp';
					break;
				case 'Vodafone Kyuushu/Okinawa':
					$gateway = 'q.vodafone.ne.jp';
					break;
				case 'Vodafone Shikoku':
					$gateway = 's.vodafone.ne.jp';
					break;
				case 'Vodafone Japan':
					$gateway = 'c.vodafone.ne.jp';
					break;
				case 'Vodafone Japan':
					$gateway = 'h.vodafone.ne.jp';
					break;
				case 'Vodafone Japan':
					$gateway = 't.vodafone.ne.jp';
					break;
				case 'Willcom':
					$gateway = 'pdx.ne.jp';
					break;
				case 'Willcom di':
					$gateway = 'di.pdx.ne.jp';
					break;
				case 'Willcom dk':
					$gateway = 'dk.pdx.ne.jp';
					break;
			}
			break;
		case 'Kanada':
			switch ($nazwaOperatora) {
				case 'Bell Canada':
					$gateway = 'txt.bell.ca';
					break;
				case 'Bell Mobility':
					$gateway = 'txt.bellmobility.ca';
					break;
				case 'Bell Mobility':
					$gateway = 'bellmobility.ca';
					break;
				case 'Clearnet':
					$gateway = 'msg.clearnet.com';
					break;
				case 'Fido':
					$gateway = 'fido.ca';
					break;
				case 'Koodo Mobile':
					$gateway = 'msg.koodomobile.com';
					break;
				case 'Microcell':
					$gateway = 'fido.ca';
					break;
				case 'Manitoba Telecom Systems':
					$gateway = 'text.mtsmobility.com';
					break;
				case 'NBTel':
					$gateway = 'wirefree.informe.ca';
					break;
				case 'PageMart':
					$gateway = 'pmcl.net';
					break;
				case 'PageNet':
					$gateway = 'pagegate.pagenet.ca';
					break;
				case 'PageNet Canada':
					$gateway = 'e.pagenet.ca';
					break;
				case 'President’s Choice':
					$gateway = 'mobiletxt.ca';
					break;
				case 'Rogers':
					$gateway = 'pcs.rogers.com';
					break;
				case 'Sasktel Mobility':
					$gateway = 'pcs.sasktelmobility.com';
					break;
				case 'Telus':
					$gateway = 'msg.telus.com';
					break;
				case 'Virgin Mobile Canada':
					$gateway = 'vmobile.ca';
					break;
			}
			break;
		case 'Kolumbia':
			switch ($nazwaOperatora) {
				case 'Comcel':
					$gateway = 'comcel.com.co';
					break;
				case 'Movistar':
					$gateway = 'movistar.com.co';
					break;
			}
			break;
		case 'Liban':
			switch ($nazwaOperatora) {
				case 'Cellis':
					$gateway = 'ens.jinny.com.lb';
					break;
				case 'LibanCell':
					$gateway = 'ens.jinny.com.lb';
					break;
			}
			break;
		case 'Luksemburg':
			switch ($nazwaOperatora) {
				case 'P&T Luxembourg':
					$gateway = 'sms.luxgsm.lu';
					break;
				case 'Tigo':
					$gateway = 'sms.tigo.com.co';
					break;
			}
			break;
		case 'Łotwa':
			switch ($nazwaOperatora) {
				case 'Kyivstar':
					$gateway = 'smsmail.lmt.lv';
					break;
				case 'LMT':
					$gateway = 'smsmail.lmt.lv';
					break;
				case 'Tele2':
					$gateway = 'sms.tele2.lv';
					break;
			}
			break;
		case 'Malezja':
			switch ($nazwaOperatora) {
				case 'Celcom':
					$gateway = 'sms.celcom.com.my';
					break;
			}
			break;
		case 'Mauritius':
			switch ($nazwaOperatora) {
				case 'Emtel':
					$gateway = 'emtelworld.net';
					break;
			}
			break;
		case 'Meksyk':
			switch ($nazwaOperatora) {
				case 'Iusacell':
					$gateway = 'rek2.com.mx';
					break;
			}
			break;
		case 'Nepal':
			switch ($nazwaOperatora) {
				case 'Mero Mobile':
					$gateway = 'sms.spicenepal.com';
					break;
			}
			break;
		case 'Niemcy':
			switch ($nazwaOperatora) {
				case 'E-Plus':
					$gateway = 'eplus.de';
					break;
				case 'Mannesmann Mobilefunk':
					$gateway = 'd2-message.de';
					break;
				case 'O2':
					$gateway = 'o2online.de';
					break;
				case 'T-Mobile':
					$gateway = 't-d1-sms.de';
					break;
				case 'T-Mobile':
					$gateway = 't-mobile-sms.de';
					break;
				case 'Vodafone':
					$gateway = 'vodafone-sms.de';
					break;
				case 'VoiceStream':
					$gateway = 'voicestream.net';
					break;
			}
			break;
		case 'Nikaragua':
			switch ($nazwaOperatora) {
				case 'Claro':
					$gateway = 'ideasclaro-ca.com';
					break;
			}
			break;
		case 'Norwegia':
			switch ($nazwaOperatora) {
				case 'Netcom':
					$gateway = 'sms.netcom.no';
					break;
				case 'Telenor':
					$gateway = 'mobilpost.no';
					break;
			}
			break;
		case 'Panama':
			switch ($nazwaOperatora) {
				case 'Cable and Wireless':
					$gateway = 'cwmovil.com';
					break;
			}
			break;
		case 'Portugalia':
			switch ($nazwaOperatora) {
				case 'Telcel':
					$gateway = 'sms.telecel.pt';
					break;
				case 'Optimus':
					$gateway = 'sms.optimus.pt';
					break;
				case 'TMN':
					$gateway = 'mail.tmn.pt';
					break;
			}
			break;
		case 'Republika Południowej Afryki':
			switch ($nazwaOperatora) {
				case 'MTN':
					$gateway = 'sms.co.za';
					break;
				case 'Vodacom':
					$gateway = 'voda.co.za';
					break;
			}
			break;
		case 'Rosja':
			switch ($nazwaOperatora) {
				case 'BeeLine GSM':
					$gateway = 'sms.beemail.ru';
					break;
				case 'MTS':
					$gateway = 'sms.mts.ru';
					break;
				case 'Personal Communication':
					$gateway = 'pcom.ru';
					break;
				case 'Primtel':
					$gateway = 'sms.primtel.ru';
					break;
				case 'SCS-900':
					$gateway = 'scs-900.ru';
					break;
				case 'Uraltel':
					$gateway = 'sms.uraltel.ru';
					break;
				case 'Vessotel':
					$gateway = 'pager.irkutsk.ru';
					break;
				case 'YCC':
					$gateway = 'sms.ycc.ru';
					break;
			}
			break;
		case 'Singapur':
			switch ($nazwaOperatora) {
				case 'MiWorld':
					$gateway = 'm1.com.sg';
					break;
				case 'Mobileone':
					$gateway = 'm1.com.sg';
					break;
			}
			break;
		case 'Słowenia':
			switch ($nazwaOperatora) {
				case 'Mobitel':
					$gateway = 'linux.mobitel.si';
					break;
				case 'Si Mobil':
					$gateway = 'simobil.net';
					break;
			}
			break;
		case 'Sri Lanka':
			switch ($nazwaOperatora) {
				case 'Mobitel':
					$gateway = 'sms.mobitel.lk';
					break;
			}
			break;
		case 'Stany Zjednoczone Ameryki':
			switch ($nazwaOperatora) {
				case '3 River Wireless':
					$gateway = 'sms.3rivers.net';
					break;
				case 'ACS Wireless':
					$gateway = 'paging.acswireless.com';
					break;
				case 'Advantage Communications':
					$gateway = 'advantagepaging.com';
					break;
				case 'Airtel Wireless (Montana)':
					$gateway = 'sms.airtelmontana.com';
					break;
				case 'AirVoice':
					$gateway = 'mmode.com';
					break;
				case 'Airtouch Pagers':
					$gateway = 'airtouch.net';
					break;
				case 'Airtouch Pagers':
					$gateway = 'airtouchpaging.com';
					break;
				case 'Airtouch Pagers':
					$gateway = 'alphapage.airtouch.com';
					break;
				case 'Airtouch Pagers':
					$gateway = 'myairmail.com';
					break;
				case 'Alaska Communications Systems':
					$gateway = 'msg.acsalaska.com';
					break;
				case 'AllTel':
					$gateway = 'message.alltel.com';
					break;
				case 'Alltel PCS':
					$gateway = 'message.alltel.com';
					break;
				case 'Alltel':
					$gateway = 'alltelmessage.com';
					break;
				case 'AlphNow':
					$gateway = 'alphanow.net';
					break;
				case 'American Messaging (SBC, Ameritech)':
					$gateway = 'page.americanmessaging.net';
					break;
				case 'Ameritech Clearpath':
					$gateway = 'clearpath.acswireless.com';
					break;
				case 'Ameritech Paging':
					$gateway = 'pageapi.com';
					break;
				case 'Aql':
					$gateway = 'text.aql.com';
					break;
				case 'Arch Pagers (PageNet)':
					$gateway = 'archwireless.net';
					break;
				case 'Arch Pagers (PageNet)':
					$gateway = 'epage.arch.com';
					break;
				case 'AT&T':
					$gateway = 'txt.att.net';
					break;
				case 'AT&T Free2Go':
					$gateway = 'mmode.com';
					break;
				case 'AT&T PCS':
					$gateway = 'mobile.att.net';
					break;
				case 'AT&T Pocketnet PCS':
					$gateway = 'dpcs.mobile.att.net';
					break;
				case 'Beepwear':
					$gateway = 'beepwear.net';
					break;
				case 'Bell Atlantic':
					$gateway = 'message.bam.com';
					break;
				case 'Bell South (Blackberry)':
					$gateway = 'bellsouthtips.com';
					break;
				case 'Bell South Mobility':
					$gateway = 'blsdcs.net';
					break;
				case 'Bell South':
					$gateway = 'blsdcs.net';
					break;
				case 'Bell South':
					$gateway = 'sms.bellsouth.com';
					break;
				case 'Bell South':
					$gateway = 'wireless.bellsouth.com';
					break;
				case 'Bluegrass Cellular':
					$gateway = 'sms.bluecell.com';
					break;
				case 'Boost Mobile':
					$gateway = 'myboostmobile.com';
					break;
				case 'Boost':
					$gateway = 'myboostmobile.com';
					break;
				case 'CallPlus':
					$gateway = 'mmode.com';
					break;
				case 'Carolina Mobile Communications':
					$gateway = 'cmcpaging.com';
					break;
				case 'Carolina West Wireless':
					$gateway = 'cwwsms.com';
					break;
				case 'Cellular One MMS':
					$gateway = 'mms.uscc.net';
					break;
				case 'Cellular One East Coast':
					$gateway = 'phone.cellone.net';
					break;
				case 'Cellular One PCS':
					$gateway = 'paging.cellone-sf.com';
					break;
				case 'Cellular One South West':
					$gateway = 'swmsg.com';
					break;
				case 'Cellular One West':
					$gateway = 'mycellone.com';
					break;
				case 'Cellular One':
					$gateway = 'cellularone.txtmsg.com';
					break;
				case 'Cellular One':
					$gateway = 'cellularone.textmsg.com';
					break;
				case 'Cellular One':
					$gateway = 'cell1.textmsg.com';
					break;
				case 'Cellular One':
					$gateway = 'message.cellone-sf.com';
					break;
				case 'Cellular One':
					$gateway = 'mobile.celloneusa.com';
					break;
				case 'Cellular One':
					$gateway = 'sbcemail.com';
					break;
				case 'Cellular South':
					$gateway = 'csouth1.com';
					break;
				case 'Centennial Wireless':
					$gateway = 'cwemail.com';
					break;
				case 'Central Vermont Communications':
					$gateway = 'cvcpaging.com';
					break;
				case 'CenturyTel':
					$gateway = 'messaging.centurytel.net';
					break;
				case 'Cincinnati Bell Wireless':
					$gateway = 'gocbw.com';
					break;
				case 'Cingular':
					$gateway = 'mycingular.com';
					break;
				case 'Cingular':
					$gateway = 'mycingular.net';
					break;
				case 'Cingular':
					$gateway = 'mms.cingularme.com';
					break;
				case 'Cingular':
					$gateway = 'page.cingular.com';
					break;
				case 'Cingular':
					$gateway = 'cingularme.com';
					break;
				case 'Cingular (GSM)':
					$gateway = 'cingularme.com';
					break;
				case 'Cingular (TDMA)':
					$gateway = 'mmode.com';
					break;
				case 'Cingular Wireless':
					$gateway = 'mobile.mycingular.net';
					break;
				case 'Cingular Wireless':
					$gateway = 'mycingular.textmsg.com';
					break;
				case 'Cingular Wireless':
					$gateway = 'mobile.mycingular.com';
					break;
				case 'Comcast':
					$gateway = 'comcastpcs.textmsg.com';
					break;
				case 'Communication Specialists':
					$gateway = 'pager.comspeco.com';
					break;
				case 'Communication Specialists':
					$gateway = 'pageme.comspeco.net';
					break;
				case 'Cook Paging':
					$gateway = 'cookmail.com';
					break;
				case 'Corr Wireless Communications':
					$gateway = 'corrwireless.net';
					break;
				case 'Cricket':
					$gateway = 'sms.mycricket.com';
					break;
				case 'Digi-Page':
					$gateway = 'page.hit.net';
					break;
				case 'Page Kansas':
					$gateway = 'page.hit.net';
					break;
				case 'Dobson Communications Corporation':
					$gateway = 'mobile.dobson.net';
					break;
				case 'Dobson-Alex Wireless':
					$gateway = 'mobile.cellularone.com';
					break;
				case 'Dobson-Cellular One':
					$gateway = 'mobile.cellularone.com';
					break;
				case 'Edge Wireless':
					$gateway = 'sms.edgewireless.com';
					break;
				case 'Gabriel Wireless':
					$gateway = 'epage.gabrielwireless.com';
					break;
				case 'Galaxy Corporation':
					$gateway = 'sendabeep.net';
					break;
				case 'GCS Paging':
					$gateway = 'webpager.us';
					break;
				case 'General Communications Inc. (Alaska)':
					$gateway = 'msg.gci.net';
					break;
				case 'Globalstar (Satellite)':
					$gateway = 'msg.globalstarusa.com';
					break;
				case 'GrayLink':
					$gateway = 'epage.porta-phone.com';
					break;
				case 'Porta-Phone':
					$gateway = 'epage.porta-phone.com';
					break;
				case 'GTE':
					$gateway = 'gte.pagegate.net';
					break;
				case 'GTE':
					$gateway = 'messagealert.com';
					break;
				case 'GTE':
					$gateway = 'airmessage.net';
					break;
				case 'Helio':
					$gateway = 'messaging.sprintpcs.com';
					break;
				case 'Houston Cellular':
					$gateway = 'text.houstoncellular.net';
					break;
				case 'Illinois Valley Cellular':
					$gateway = 'ivctext.com';
					break;
				case 'Infopage Systems':
					$gateway = 'page.infopagesystems.com';
					break;
				case 'Inland Cellular Telephone':
					$gateway = 'inlandlink.com';
					break;
				case 'Iridium (Satellite)':
					$gateway = 'msg.iridium.com';
					break;
				case 'i Wireless':
					$gateway = 'iwspcs.net';
					break;
				case 'JSM Tele-Page':
					$gateway = 'jsmtel.com';
					break;
				case 'Lauttamus Communication':
					$gateway = 'e-page.net';
					break;
				case 'MCI Phone':
					$gateway = 'mci.com';
					break;
				case 'MCI':
					$gateway = 'pagemci.com';
					break;
				case 'Metro PCS':
					$gateway = 'metropcs.sms.us';
					break;
				case 'Metro PCS':
					$gateway = 'mymetropcs.com';
					break;
				case 'MetroPCS':
					$gateway = 'mymetropcs.com';
					break;
				case 'Metrocall (2-way)':
					$gateway = 'my2way.com';
					break;
				case 'Metrocall':
					$gateway = 'page.metrocall.com';
					break;
				case 'Midwest Wireless':
					$gateway = 'clearlydigital.com';
					break;
				case 'Mobilecom PA':
					$gateway = 'page.mobilcom.net';
					break;
				case 'Mobilfone':
					$gateway = 'page.mobilfone.com';
					break;
				case 'MobiPCS (Hawaii)':
					$gateway = 'mobipcs.net';
					break;
				case 'Motient':
					$gateway = 'isp.com';
					break;
				case 'Morris Wireless':
					$gateway = 'beepone.net';
					break;
				case 'Northeast Paging':
					$gateway = 'pager.ucom.com';
					break;
				case 'Nextel':
					$gateway = 'messaging.nextel.com';
					break;
				case 'Nextel':
					$gateway = 'page.nextel.com';
					break;
				case 'NPI Wireless':
					$gateway = 'npiwireless.com';
					break;
				case 'Ntelos':
					$gateway = 'pcs.ntelos.com';
					break;
				case 'O2':
					$gateway = 'mobile.celloneusa.com';
					break;
				case 'Omnipoint':
					$gateway = 'omnipoint.com';
					break;
				case 'Omnipoint':
					$gateway = 'omnipointpcs.com';
					break;
				case 'OnlineBeep':
					$gateway = 'onlinebeep.net';
					break;
				case 'PCS One':
					$gateway = 'pcsone.net';
					break;
				case 'Pacific Bell':
					$gateway = 'pacbellpcs.net';
					break;
				case 'PageMart Advanced /2way':
					$gateway = 'airmessage.net';
					break;
				case 'PageMart':
					$gateway = 'pagemart.net';
					break;
				case 'PageOne NorthWest':
					$gateway = 'page1nw.com';
					break;
				case 'Pioneer':
					$gateway = 'msg.pioneerenidcellular.com';
					break;
				case 'Pioneer':
					$gateway = 'msg.pioneerenidcellular.com';
					break;
				case 'Price Communications':
					$gateway = 'mobilecell1se.com';
					break;
				case 'Primeco':
					$gateway = 'email.uscc.net';
					break;
				case 'ProPage':
					$gateway = 'page.propage.net';
					break;
				case 'Public Service Cellular':
					$gateway = 'sms.pscel.com';
					break;
				case 'Qualcomm':
					$gateway = 'pager.qualcomm.com';
					break;
				case 'Qwest':
					$gateway = 'qwestmp.com';
					break;
				case 'RAM Page':
					$gateway = 'ram-page.com';
					break;
				case 'SBC Ameritech Paging':
					$gateway = 'paging.acswireless.com';
					break;
				case 'ST Paging':
					$gateway = 'page.stpaging.com';
					break;
				case 'Safaricom':
					$gateway = 'safaricomsms.com';
					break;
				case 'Satelindo GSM':
					$gateway = 'satelindogsm.com';
					break;
				case 'Satellink':
					$gateway = 'satellink.net';
					break;
				case 'Simple Freedom':
					$gateway = 'text.simplefreedom.net';
					break;
				case 'Skytel Pagers':
					$gateway = 'email.skytel.com';
					break;
				case 'Skytel Pagers':
					$gateway = 'skytel.com';
					break;
				case 'Smart Telecom':
					$gateway = 'mysmart.mymobile.ph';
					break;
				case 'Southern LINC':
					$gateway = 'page.southernlinc.com';
					break;
				case 'Southwestern Bell':
					$gateway = 'email.swbw.com';
					break;
				case 'Sprint PCS':
					$gateway = 'messaging.sprintpcs.com';
					break;
				case 'Sprint':
					$gateway = 'sprintpaging.com';
					break;
				case 'SunCom':
					$gateway = 'tms.suncom.com';
					break;
				case 'SunCom':
					$gateway = 'suncom1.com';
					break;
				case 'Surewest Communications':
					$gateway = 'mobile.surewest.com';
					break;
				case 'T-Mobile':
					$gateway = 'tmomail.net';
					break;
				case 'T-Mobile':
					$gateway = 'voicestream.net';
					break;
				case 'TIM':
					$gateway = 'timnet.com';
					break;
				case 'TSR Wireless':
					$gateway = 'alphame.com';
					break;
				case 'TSR Wireless':
					$gateway = 'beep.com';
					break;
				case 'Teleflip':
					$gateway = 'teleflip.com';
					break;
				case 'Teletouch':
					$gateway = 'pageme.teletouch.com';
					break;
				case 'Telus':
					$gateway = 'msg.telus.com';
					break;
				case 'The Indiana Paging Co':
					$gateway = 'pager.tdspager.com';
					break;
				case 'Thumb Cellular':
					$gateway = 'sms.thumbcellular.com';
					break;
				case 'Tracfone':
					$gateway = 'txt.att.net';
					break;
				case 'Triton':
					$gateway = 'tms.suncom.com';
					break;
				case 'UCOM':
					$gateway = 'pager.ucom.com';
					break;
				case 'US Cellular':
					$gateway = 'smtp.uscc.net';
					break;
				case 'US Cellular':
					$gateway = 'uscc.textmsg.com';
					break;
				case 'US West':
					$gateway = 'uswestdatamail.com';
					break;
				case 'US Cellular':
					$gateway = 'email.uscc.net';
					break;
				case 'USA Mobility':
					$gateway = 'mobilecomm.net';
					break;
				case 'Unicel':
					$gateway = 'utext.com';
					break;
				case 'Verizon PCS':
					$gateway = 'myvzw.com';
					break;
				case 'Verizon Pagers':
					$gateway = 'myairmail.com';
					break;
				case 'Verizon':
					$gateway = 'vtext.com';
					break;
				case 'Virgin Mobile':
					$gateway = 'vmobl.com';
					break;
				case 'Virgin Mobile':
					$gateway = 'vxtras.com';
					break;
				case 'WebLink Wiereless':
					$gateway = 'airmessage.net';
					break;
				case 'WebLink Wireless':
					$gateway = 'pagemart.net';
					break;
				case 'West Central Wireless':
					$gateway = 'sms.wcc.net';
					break;
				case 'Western Wireless':
					$gateway = 'cellularonewest.com';
					break;
				case 'Wyndtell':
					$gateway = 'wyndtell.com';
					break;
			}
			break;
		case 'Szwajcaria':
			switch ($nazwaOperatora) {
				case 'Sunrise Mobile':
					$gateway = 'freesurf.ch';
					break;
				case 'Sunrise Mobile':
					$gateway = 'mysunrise.ch';
					break;
				case 'Swisscom':
					$gateway = 'bluewin.ch';
					break;
			}
			break;
		case 'Szwecja':
			switch ($nazwaOperatora) {
				case 'Comviq GSM':
					$gateway = 'sms.comviq.se';
					break;
				case 'Europolitan':
					$gateway = 'europolitan.se';
					break;
				case 'Tele2':
					$gateway = 'sms.tele2.se';
					break;
			}
			break;
		case 'Tanzania':
			switch ($nazwaOperatora) {
				case 'Mobitel':
					$gateway = 'sms.co.tz';
					break;
			}
			break;
		case 'Ukraina':
			switch ($nazwaOperatora) {
				case 'Golden Telecom':
					$gateway = 'sms.goldentele.com';
					break;
				case 'Kyivstar':
					$gateway = '2sms.kyivstar.net';
					break;
				case 'UMC':
					$gateway = 'sms.umc.com.ua';
					break;
			}
			break;
		case 'Węgry':
			switch ($nazwaOperatora) {
				case 'PGSM':
					$gateway = 'sms.pgsm.hu';
					break;
			}
			break;
		case 'Wielka Brytania':
			switch ($nazwaOperatora) {
				case 'BigRedGiant Mobile Solutions':
					$gateway = 'tachyonsms.co.uk';
					break;
				case 'Orange':
					$gateway = 'orange.net';
					break;
				case 'O2':
					$gateway = 'o2imail.co.uk';
					break;
				case 'T-Mobile UK':
					$gateway = 't-mobile.uk.net';
					break;
				case 'Vodafone UK':
					$gateway = 'vodafone.net';
					break;
			}
			break;
		case 'Włochy':
			switch ($nazwaOperatora) {
				case 'TIM':
					$gateway = 'timnet.com';
					break;
				case 'Telecom Italia Mobile':
					$gateway = 'posta.tim.it';
					break;
				case 'Vodafone Omnitel':
					$gateway = 'vizzavi.it';
					break;
				case 'Vodafone':
					$gateway = 'sms.vodafone.it';
					break;
			}
			break;
	}
	
	return $gateway;
}
?>
