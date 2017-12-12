<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendmail($adresNadawcy, $nazwaNadawcy, $hasloNadawcy, $adresOdbiorcy, $nazwaOdbiorcy, $formatWiadomosci, $tematWiadomosci, $trescHTML, $trescNonHTML, $przeznaczenie = 'mail', $wystapienieZdjecia = false, $sciezkaZdjecia = null, $nazwaZdjecia = null, $plikZdjecia = null, $kodowanieZdjecia = 'base64', $typZdjecia = null, $pozycjaZdjecia = 'inline', $wystapienieZalacznika = false, $sciezkaZalacznika = null, $nazwaZalacznika = null)
{
	$stan = false;
	
	$mail = new PHPMailer(true);										//W³¹czenie obs³ugi wyj¹tków
	
	try {
		//Ustawienia mailer-a
		$mail->setLanguage('pl', 'mailer\language/');					//Za³adowanie polskiej wersji jêzykowej
		$mail->CharSet = 'UTF-8';										//Ustawienie kodowania znaków	//iso-8859-1	//UTF-8	//windows-1250
		//$mail->ContentType = 'text/plain';							//Ustawienie typu zawartoœci	//text/plain	//text/html
		//$mail->Encoding = '8bit';										//Ustawienie kodowania
		//$mail->XMailer = '';
		//$mail->WordWrap = 50;											//Ustawienie maksymalnej iloœci znaków w jednej linijce
		
		//Konfiguracja serwera poczty
		//$mail->SMTPDebug = 3;											//Ustawienie stopnia debugowania b³êdów
		$mail->isSMTP();												//Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com;smtp.gmail.com';					//Specify main and backup SMTP servers
		$mail->SMTPAuth = true;											//Enable SMTP authentication
		$mail->Username = $adresNadawcy;								//SMTP username
		$mail->Password = $hasloNadawcy;								//SMTP password
		$mail->SMTPSecure = 'tls';										//Enable TLS or SSL encryption	//tls	//ssl
		$mail->Port = 587;												//TCP port to connect to	//587	//465
		$mail->smtpConnect(												//Zezwolenie na w³asny certyfikat
			array(
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => true,
					"allow_self_signed" => true
				)
			)
		);
		
		//Ustawienie nadawcy i odbiorców
		$mail->setFrom($adresNadawcy, $nazwaNadawcy);					//Dodaj adres i nazwê nadawcy
		$mail->addAddress($adresOdbiorcy, $nazwaOdbiorcy);				//Dodaj adres i nazwê odbiorcy
		//$mail->addAddress($adresOdbiorcy);							//Dodaj adres odbiorcy
		//$mail->addReplyTo('info@example.com', 'Information');			//Dodaj adres dla odpowiedzi
		//$mail->addCC('cc@example.com');								//Dodaj adres kopii
		//$mail->addBCC('bcc@example.com');								//Dodaj adres kopii zapasowej
		
		//Dodanie zdjêcia
		if($wystapienieZdjecia == true) {
			$mail->addEmbeddedImage($sciezkaZdjecia, $nazwaZdjecia, $plikZdjecia, $kodowanieZdjecia, $typZdjecia, $pozycjaZdjecia);
		}
		
		//Dodanie za³¹czników
		if($wystapienieZalacznika == true && $nazwaZalacznika == null) {
			$mail->addAttachment($sciezkaZalacznika);					//Dodaj za³¹cznik
		}
		else if($wystapienieZdjecia == true && $nazwaZalacznika != null) {
			$mail->addAttachment($sciezkaZalacznika, $nazwaZalacznika);	//Dodaj za³¹cznik z nazw¹
		}
		
		//Treœæ wiadomoœci
		$mail->isHTML($formatWiadomosci);								//Ustaw format wiadomoœci e-mail na HTML
		$mail->Subject = $tematWiadomosci;								//Ustaw temat wiadomoœci
		if($przeznaczenie == 'sms') {
			$znakiPolskie = array('¥', 'Æ', 'Ê', '£', 'Ñ', 'Ó', 'Œ', '', '¯', '¹', 'æ', 'ê', '³', 'ñ', 'ó', 'œ', 'Ÿ', '¿');
			$znakiNormalne = array('A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z', 'a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z');
			$trescHTML = str_replace($znakiPolskie, $znakiNormalne, $trescHTML);
		}
		$mail->Body = $trescHTML;										//Treœæ wiadomoœci w formacie HTML
		if($przeznaczenie == 'mail') {
			$mail->AltBody = $trescNonHTML;								//Treœæ alternatywnej wiadomoœci w formacie tekstowym
		}
		
		//Wys³anie wiadomoœci
		$stan = $mail->send();
		
		//echo '<p class="success">Wiadomoœæ zosta³a poprawnie wys³ana na adres '.$adresOdbiorcy.'</p>';
	} catch (Exception $e) {
		//echo '<p class="error">Nie mo¿na by³o wys³aæ wiadomoœci.<br />';
		//echo 'B³¹d Mailer-a: '.$mail->ErrorInfo.'</p>';
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
		
		//Zasiêg stacji nadawczych
		$coverage = getcoverage($prefiks, $numerOdbiorcy, $apiTokenClickatellDev);
		
		$routable = $coverage[0];
		$minimumCharge = $coverage[1];
		
		//Sprawdzanie czy mo¿na wys³aæ wiadomoœæ
		if(($routable == true) && ($minimumCharge < $balance)) {
			//Wysy³anie wiadomoœci
			//$sending = sendmessage($prefiks, $numerOdbiorcy, $wiadomosc, $typ, $apiTokenClickatellDev);
			
			$accepted = $sending[0];
			$messageId = $sending[1];
			
			if($accepted == "true") {
				//Status wiadomoœci
				$status = getmessagestatus($messageId, $apiTokenClickatellDev);
				
				$messageStatus = $status[0];
				$messageDescription = $status[1];
				
				return $messageStatus.": ".$messageDescription;
			}
		}
	}
	
	if($zaPomocaMailera == true) {
		//Pobieranie kodu sieci komórkowej
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
			
			//Wys³anie maila (metoda podstawowa)
			$potwierdzenie = sendmail($adresNadawcySms, $nazwaNadawcySms, $hasloNadawcySms, '+'.$prefiks.$numerOdbiorcy.'@'.$brama, $nazwaOdbiorcy, false, $nazwaNadawcySms, $wiadomosc, $wiadomosc, 'sms');
			
			if($potwierdzenie != true) {
				$do = '+'.$prefiks.$numerOdbiorcy.'@'.$brama;
				$temat = '';
				$wiadomoscP = wordwrap($wiadomosc, 70, "\r\n");	//Podzia³ wiadomoœci na linie o d³ugoœci 70 znaków
				$nag³owki = 'MIME-Version: 1.0'."\r\n".'Content-type: text/html; charset=iso-8859-1'."\r\n".'From: '.$nazwaNadawcySms.' <'.$adresNadawcySms.'>'."\r\n".'X-Mailer: PHP/'.phpversion();
				
				//Wys³anie maila (metoda zapasowa)
				$potwierdzenie = mail($do, $temat, $wiadomoscP, $nag³owki);
			}
			
			if($potwierdzenie == "true") {
				return $nazwaOperatora;
			}
		}
	}
	
	return false;
}
?>
