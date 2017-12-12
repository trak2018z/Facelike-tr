<?php
$path = "./couchdb/";	//Ścieżka operacyjna

//Upewnij się że użytkownik jest zalogowany
if(!checksession()) {
	exit(header('Location: index.php'));
	echo '<p class="error">Przykro nam, ale ta strona jest dostępna tylko dla zalogowanych użytkowników.</p>';
	
	die;
}

$userId = getidfromsession();

//Upewnij się, że użytkownik istnieje
if(!checkid($path, $userDataDbName, $userId)) {
	exit(header('Location: index.php'));
	echo '<p class="error">Przykro nam, ale użytkownik o podanym identyfikatorze nie istnieje.</p>';
	
	die;
}

//Sprawdzenie czy użytkownik to admin
if(!checkadmin($path, $userSecurityDbName, $userId, $userData)) {
	exit(header('Location: index.php'));
	echo '<p class="error">Przykro nam, ale administrator o podanym identyfikatorze nie istnieje.</p>';
	
	die;
}

//Informacje o systemie
$wersjaPHP = phpversion();
$clientData = getClientData();
?>
<div class="container body-content">
	<div class="jumbotron">
		<br /><br />
		<h2>Informacje systemowe</h2>
		<hr />
		<h3>System</h3>

		<p><b>Serwer PHP w wersji:</b> <?php echo $wersjaPHP; ?></p><br />
		<p><b>System operacyjny:</b> <?php echo $clientData['system']; ?></p><br />
		<p><b>Przeglądarka:</b> <?php echo $clientData['przegladarka']; ?></p><br />
		<p><b>Aplikacja kliencka:</b> <?php echo $clientData['aplikacja_kliencka']; ?></p><br />
		<p><b>Port połączenia:</b> <?php echo $clientData['port']; ?></p><br />
		<p><b>Rozdzielczość ekranu:</b> <?php echo $clientData['rozdzielczosc']; ?>px</p><br />
		<br />

		<h3>Adres IP</h3>
		<?php
		//Informacje o adresie IP
		$details = getIpDetails();
		$ipDetails = checkIpDetails($details);
		$latitude =  number_format($ipDetails['szerokosc_geograficzna'], 2);
		$longitude = number_format($ipDetails['dlugosc_geograficzna'], 2);
		?>
		<p><b>IP:</b> <?php echo $ipDetails['ip']; ?></p><br />
		<p><b>Nazwa hosta:</b> <?php echo $ipDetails['hostname']; ?></p><br />
		<p><b>Organizacja:</b> <?php echo $ipDetails['organizacja']; ?></p><br />
		<p><b>Szerokość geograficzna:</b> <?php echo $latitude; ?><sup>o</sup></p><br />
		<p><b>Długość geograficzna:</b> <?php echo $longitude; ?><sup>o</sup></p><br />
		<p><b>Kraj:</b> <?php echo $ipDetails['kraj']; ?></p><br />
		<p><b>Region:</b> <?php echo $ipDetails['region']; ?></p><br />
		<p><b>Miasto:</b> <?php echo $ipDetails['miasto']; ?></p><br />
		<p><b>Kod pocztowy:</b> <?php echo $ipDetails['kod_pocztowy']; ?></p><br />
	</div>
</div>
