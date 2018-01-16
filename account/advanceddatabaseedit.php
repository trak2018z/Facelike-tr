<?php
$path = "./couchdb/";	//Ścieżka operacyjna

//Upewnij się że użytkownik jest zalogowany
if(!checksession()) {
	if(headers_sent()) {
		?>
		<script type="text/javascript">
		//<![CDATA[
			location.replace('index.php');
		 //]]>
		</script>
		<?php
	}
	else{
		exit(header('Location: index.php'));
	}
	echo '<div class="error-box">Przykro nam, ale ta strona jest dostępna tylko dla zalogowanych użytkowników.</div>';
	
	die;
}

$userId = getidfromsession();

//Upewnij się, że użytkownik istnieje
if(!checkid($path, $userDataDbName, $userId)) {
	if(headers_sent()) {
		?>
		<script type="text/javascript">
		//<![CDATA[
			location.replace('index.php');
		 //]]>
		</script>
		<?php
	}
	else{
		exit(header('Location: index.php'));
	}
	echo '<div class="error-box">Przykro nam, ale użytkownik o podanym identyfikatorze nie istnieje.</div>';
	
	die;
}

//Sprawdzenie czy użytkownik to admin
if(!checkadmin($path, $userSecurityDbName, $userId, $userData)) {
	if(headers_sent()) {
		?>
		<script type="text/javascript">
		//<![CDATA[
			location.replace('index.php');
		 //]]>
		</script>
		<?php
	}
	else{
		exit(header('Location: index.php'));
	}
	echo '<div class="error-box">Przykro nam, ale administrator o podanym identyfikatorze nie istnieje.</div>';
	
	die;
}

//Pobieranie standardowych informacji
$haslo = "jabłko jest dobre 123";

//Pobieranie dodatkowych informacji
if(isset($_REQUEST['r'])) {
	$response = $_REQUEST['r'];
	
	if(isset($_REQUEST['h'])) {
		$tekst = $_REQUEST['h'];
		$haslo = $tekst;
	}
}
else {
	$response = null;
}
?>

<div class="container body-content" style="max-width:1460px;">
	<div class="jumbotron">
		<h2>Zaawansowana edycja bazy danych</h2>
		<hr />
		<h3>Generator haseł</h3>
		
		<form method="post" action="index.php" id="zaawansowanaedycjabazydanych">
			<table>
				<tr>
					<td>
						<input type="text" name="haslo" id="haslo" value="<?php echo $haslo; ?>" size="40" placeholder="hasło" title="Hasło do wygenerowania" />
					</td>
					<td>
						<input type="hidden" name="id" value="zaawansowanaedycjabazydanych" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input class="submit-generate_password_button" type="submit" value="Generuj hasło &raquo;" />
					</td>
					<td>
					</td>
				</tr>
			</table>
		</form>
		<br />
		
		<?php
		//Informacje zwrotne
		if($response != null && $response != '' && $response != false) {
			?>
			<table class="received-table_password">
				<tr>
					<th>
						Metoda
					</th>
					<th>
						Wynik
					</th>
					<th>
						Stan
					</th>
				</tr>
				<tr>
					<td>
						haslo
					</td>
					<td>
						<?php echo $tekst; ?>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						hash(md5,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("md5", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(sha512,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("sha512", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(ripemd320,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("ripemd320", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(whirlpool,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("whirlpool", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(tiger192,4,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("tiger192,4", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(snefru256,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("snefru256", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(gost-crypto,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("gost-crypto", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(adler32,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("adler32", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(crc32b,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("crc32b", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(fnv1a64,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("fnv1a64", $tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						hash(joaat,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("joaat", $tekst); ?>
					</td>
					<td>
						Wolne
					</td>
				</tr>
				<tr>
					<td>
						hash(haval256,5,&nbsp;haslo)
					</td>
					<td>
						<?php echo hash("haval256,5", $tekst); ?>
					</td>
					<td>
						Wolne
					</td>
				</tr>
				<tr>
					<td>
						hash_hmac(whirlpool,&nbsp;haslo,&nbsp;key)
					</td>
					<td>
						<?php echo hash_hmac("whirlpool", $tekst, 'key'); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						password_hash(haslo,&nbsp;algorytm)
					</td>
					<td>
						<?php echo password_hash($tekst, PASSWORD_DEFAULT); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
				<tr>
					<td>
						base64_encode(haslo)
					</td>
					<td>
						<?php echo base64_encode($tekst); ?>
					</td>
					<td>
						Użyte
					</td>
				</tr>
			</table>
			<br />
			<?php
		}
		?>
		
		<h3>Edycja bazy danych</h3>
		
		<?php
		require_once "couchdb/couchdb.php";
		require_once "couchdb/datafun.php";
		
		//Dane
		$path = "couchdb/";
		$dbName = 'test';
		$dbNameTarget = 'test_backup';
		$documentId = 'dokument_b';
		$documentIdTarget = 'dokument_c';
		$document = array(
			'_id' => $documentId,
			'imie' => 'Jan',
			'nazwisko' => 'Kowalski',
			'plec' => 'm',
			'wiek' => 25,
			'wzrost' => 1.82,
			'stan1' => true,
			'stan2' => false,
			'stan3' => true,
			'stan4' => false,
			'tel' => 123456789,
			'email' => 'jan.kowalski@gmail.com',
			'pass' => md5('abcd1234'),
		);
		$repository = 'D:/Download/';
		$attachment = 'tekst.txt';	//tekst.txt	//wallpaper.jpg
		
		//Wybór opcji
		$couch = 1;
		
		switch ($couch) {
			case 1:
				$description = 'Test działania bazy danych';
				$response = checkcouchdb($path);
				break;
			case 2:
				$description = 'Pobranie identyfikatora UUID';
				$response = checkuuid($path);
				break;
			case 3:
				$description = 'Pobranie listy baz danych';
				$response = listofdb($path);
				break;
			case 4:
				$description = 'Pobranie listy dokumentów w bazie danych';
				$response = listofdocument($path, $dbName);
				break;
			case 5:
				$description = 'Pobranie listy wersji dokumentu';
				$response = listofdocumentrev($path, $dbName, $documentId);
				break;
			case 6:
				$description = 'Pobranie szczegółowej listy wersji dokumentu';
				$response = detailedlistofdocumentrev($path, $dbName, $documentId);
				break;
			case 7:
				$description = 'Pobranie informacji o bazie danych';
				$response = getdb($path, $dbName);
				break;
			case 8:
				$description = 'Pobranie informacji o dokumencie';
				$response = getdocument($path, $dbName, $documentId);
				break;
			case 9:
				$description = 'Pobranie wersji dokumentu';
				$response = getdocumentrev($path, $dbName, $documentId);
				break;
			case 10:
				$description = 'Utworzenie bazy danych';
				$response = createdb($path, $dbName);
				break;
			case 11:
				$description = 'Utworzenie dokumentu';
				$response = createdocument($path, $dbName, $document);
				break;
			case 12:
				$description = 'Utworzenie dokumentu z załącznikiem';
				$response = createdocumentwithattachment($path, $dbName, $documentId, $repository, $attachment);
				break;
			case 13:
				$description = 'Aktualizacja dokumentu';
				$response = updatedocument($path, $dbName, $documentId, $document);
				break;
			case 14:
				$description = 'Skasowanie bazy danych';
				$response = deletedb($path, $dbName);
				break;
			case 15:
				$description = 'Skasowanie dokumentu';
				$response = deletedocument($path, $dbName, $documentId);
				break;
			case 16:
				$description = 'Kopiowanie bazy danych';
				$response = copydb($path, $dbName, $dbNameTarget);
				break;
			case 17:
				$description = 'Kopiowanie dokumentu';
				$response = copydocument($path, $dbName, $documentId, $documentIdTarget);
				break;
		}
		
		//Podgląd rezultatów
		?>
		<table>
			<tr>
				<td>
					<?php echo 'Zapytanie: '; ?>
				</td>
				<td>
					<?php echo $couch.' => '.$description; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo 'Odpowiedź: '; ?>
				</td>
				<td>
					<?php view_response($response); ?>
				</td>
			</tr>
		</table>
	</div>
</div>
