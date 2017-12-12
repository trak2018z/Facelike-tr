<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendmail($adresNadawcy, $nazwaNadawcy, $hasloNadawcy, $adresOdbiorcy, $nazwaOdbiorcy, $formatWiadomosci, $tematWiadomosci, $trescHTML, $trescNonHTML, $przeznaczenie = 'mail', $wystapienieZdjecia = false, $sciezkaZdjecia = null, $nazwaZdjecia = null, $plikZdjecia = null, $kodowanieZdjecia = 'base64', $typZdjecia = null, $pozycjaZdjecia = 'inline', $wystapienieZalacznika = false, $sciezkaZalacznika = null, $nazwaZalacznika = null)
{
	$stan = false;
	
	$mail = new PHPMailer(true);										//W��czenie obs�ugi wyj�tk�w
	
	try {
		//Ustawienia mailer-a
		$mail->setLanguage('pl', 'mailer\language/');					//Za�adowanie polskiej wersji j�zykowej
		$mail->CharSet = 'UTF-8';										//Ustawienie kodowania znak�w	//iso-8859-1	//UTF-8	//windows-1250
		//$mail->ContentType = 'text/plain';							//Ustawienie typu zawarto�ci	//text/plain	//text/html
		//$mail->Encoding = '8bit';										//Ustawienie kodowania
		//$mail->XMailer = '';
		//$mail->WordWrap = 50;											//Ustawienie maksymalnej ilo�ci znak�w w jednej linijce
		
		//Konfiguracja serwera poczty
		//$mail->SMTPDebug = 3;											//Ustawienie stopnia debugowania b��d�w
		$mail->isSMTP();												//Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com;smtp.gmail.com';					//Specify main and backup SMTP servers
		$mail->SMTPAuth = true;											//Enable SMTP authentication
		$mail->Username = $adresNadawcy;								//SMTP username
		$mail->Password = $hasloNadawcy;								//SMTP password
		$mail->SMTPSecure = 'tls';										//Enable TLS or SSL encryption	//tls	//ssl
		$mail->Port = 587;												//TCP port to connect to	//587	//465
		$mail->smtpConnect(												//Zezwolenie na w�asny certyfikat
			array(
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => true,
					"allow_self_signed" => true
				)
			)
		);
		
		//Ustawienie nadawcy i odbiorc�w
		$mail->setFrom($adresNadawcy, $nazwaNadawcy);					//Dodaj adres i nazw� nadawcy
		$mail->addAddress($adresOdbiorcy, $nazwaOdbiorcy);				//Dodaj adres i nazw� odbiorcy
		//$mail->addAddress($adresOdbiorcy);							//Dodaj adres odbiorcy
		//$mail->addReplyTo('info@example.com', 'Information');			//Dodaj adres dla odpowiedzi
		//$mail->addCC('cc@example.com');								//Dodaj adres kopii
		//$mail->addBCC('bcc@example.com');								//Dodaj adres kopii zapasowej
		
		//Dodanie zdj�cia
		if($wystapienieZdjecia == true) {
			$mail->addEmbeddedImage($sciezkaZdjecia, $nazwaZdjecia, $plikZdjecia, $kodowanieZdjecia, $typZdjecia, $pozycjaZdjecia);
		}
		
		//Dodanie za��cznik�w
		if($wystapienieZalacznika == true && $nazwaZalacznika == null) {
			$mail->addAttachment($sciezkaZalacznika);					//Dodaj za��cznik
		}
		else if($wystapienieZdjecia == true && $nazwaZalacznika != null) {
			$mail->addAttachment($sciezkaZalacznika, $nazwaZalacznika);	//Dodaj za��cznik z nazw�
		}
		
		//Tre�� wiadomo�ci
		$mail->isHTML($formatWiadomosci);								//Ustaw format wiadomo�ci e-mail na HTML
		$mail->Subject = $tematWiadomosci;								//Ustaw temat wiadomo�ci
		if($przeznaczenie == 'sms') {
			$znakiPolskie = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
			$znakiNormalne = array('A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z', 'a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z');
			$trescHTML = str_replace($znakiPolskie, $znakiNormalne, $trescHTML);
		}
		$mail->Body = $trescHTML;										//Tre�� wiadomo�ci w formacie HTML
		if($przeznaczenie == 'mail') {
			$mail->AltBody = $trescNonHTML;								//Tre�� alternatywnej wiadomo�ci w formacie tekstowym
		}
		
		//Wys�anie wiadomo�ci
		$stan = $mail->send();
		
		//echo '<p class="success">Wiadomo�� zosta�a poprawnie wys�ana na adres '.$adresOdbiorcy.'</p>';
	} catch (Exception $e) {
		//echo '<p class="error">Nie mo�na by�o wys�a� wiadomo�ci.<br />';
		//echo 'B��d Mailer-a: '.$mail->ErrorInfo.'</p>';
	}
	
	return $stan;
}

function sendsms($path, $adresNadawcySms, $nazwaNadawcySms, $hasloNadawcySms, $numerOdbiorcy, $nazwaOdbiorcy, $krajOdbiorcy, $wiadomosc, $typ = 'sms', $zaPomocaClickatell = true, $zaPomocaMailera = true)
{
	require $path.'datafun.php';
	
	//Wyznaczenie prefiks-u
	$prefiks = getprefiks($krajOdbiorcy);
	
	if($zaPomocaClickatell == true) {
		//Inicjalizacja bramki sms
		require $path.'config.php';
		
		//Stan konta
		$balance = getaccountbalance($apiTokenClickatellDev);
		
		//Zasi�g stacji nadawczych
		$coverage = getcoverage($prefiks, $numerOdbiorcy, $apiTokenClickatellDev);
		
		$routable = $coverage[0];
		$minimumCharge = $coverage[1];
		
		//Sprawdzanie czy mo�na wys�a� wiadomo��
		if(($routable == true) && ($minimumCharge < $balance)) {
			//Wysy�anie wiadomo�ci
			//$sending = sendmessage($prefiks, $numerOdbiorcy, $wiadomosc, $typ, $apiTokenClickatellDev);
			
			$accepted = $sending[0];
			$messageId = $sending[1];
			
			if($accepted == "true") {
				//Status wiadomo�ci
				$status = getmessagestatus($messageId, $apiTokenClickatellDev);
				
				$messageStatus = $status[0];
				$messageDescription = $status[1];
				
				return $messageStatus.": ".$messageDescription;
			}
		}
	}
	
	if($zaPomocaMailera == true) {
		//Pobieranie kodu sieci kom�rkowej
		$kodSieci = getnetworkcode($prefiks, $numerOdbiorcy);
		
		$MCC = $kodSieci[0];
		$MNC  = $kodSieci[1];
		
		//Wyznaczenie operatora
		$operator = getmobilenetwork($MCC, $MNC);
		
		$krajOperatora = $operator[0];
		$nazwaOperatora = $operator[1];
		
		//Wyznaczenie bramki
		$brama = getgateway($krajOperatora, $nazwaOperatora);
		
		if((!empty($brama)) && ($brama != null) && ($brama != '0')) {
			$potwierdzenie = false;
			
			//Wys�anie maila (metoda podstawowa)
			$potwierdzenie = sendmail($adresNadawcySms, $nazwaNadawcySms, $hasloNadawcySms, '+'.$prefiks.$numerOdbiorcy.'@'.$brama, $nazwaOdbiorcy, false, $nazwaNadawcySms, $wiadomosc, $wiadomosc, 'sms');
			
			if($potwierdzenie != true) {
				$do = '+'.$prefiks.$numerOdbiorcy.'@'.$brama;
				$temat = '';
				$wiadomoscP = wordwrap($wiadomosc, 70, "\r\n");	//Podzia� wiadomo�ci na linie o d�ugo�ci 70 znak�w
				$nag�owki = 'MIME-Version: 1.0'."\r\n".'Content-type: text/html; charset=iso-8859-1'."\r\n".'From: '.$nazwaNadawcySms.' <'.$adresNadawcySms.'>'."\r\n".'X-Mailer: PHP/'.phpversion();
				
				//Wys�anie maila (metoda zapasowa)
				$potwierdzenie = mail($do, $temat, $wiadomoscP, $nag�owki);
			}
			
			if($potwierdzenie == "true") {
				return $nazwaOperatora;
			}
		}
	}
	
	return false;
}
?>
