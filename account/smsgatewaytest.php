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
$maxDlugosc = 500;
$path = 'smsgate/';
$nazwaOdbiorcy = $userData['imie'].' '.$userData['nazwisko'];
$krajOdbiorcy = $userData['adres']['kraj'];
$numerOdbiorcy = $userData['telefon'];
$wiadomosc = "Test systemu\nąćęłńóśźż\nĄĆĘŁŃÓŚŹŻ";
$typ = 'sms';	//sms, flash
$zaPomocaClickatell = 0;
$zaPomocaMailera = 1;

//Pobieranie dodatkowych informacji
if(isset($_REQUEST['r'])) {
	$response = $_REQUEST['r'];
	
	if(isset($_REQUEST['n'])) {
		$nazwa = $_REQUEST['n'];
		$nazwaOdbiorcy = $nazwa;
	}
	if(isset($_REQUEST['t'])) {
		$numer = $_REQUEST['t'];
		$numerOdbiorcy = $numer;
	}
	if(isset($_REQUEST['w'])) {
		$tekst = $_REQUEST['w'];
		$wiadomosc = str_replace("<br />", "\n", $tekst);
	}
	$dataZdarzenia = !isset($_REQUEST['d']) ? '??-??-??' : $_REQUEST['d'];
	$czasZdarzenia = !isset($_REQUEST['c']) ? '??:??:??' : $_REQUEST['c'];
}
else {
	$response = null;
}
?>

<script type="text/javascript">
//<![CDATA[
	var stan = true;
	
	function mb_strlen(str) {
		var len = 0;
		
		for(var i = 0; i < str.length; i++) {
			if(str.charCodeAt(i) == 10)
				len += 3;
			else if(str.charCodeAt(i) == 211 || str.charCodeAt(i) == 243)
				len += 2;
			else
				len += (str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255) ? 2 : 1;
		}
		return len;
	}
	
	function getValue(id) {
		var response = document.getElementById(id);
		var result = response.value;
		var resultMaxLength = response.maxlength;
		var prompt = response.placeholder;
		var name = response.name;
		
		var resultLength = mb_strlen(result);
		
		if(resultMaxLength == undefined || resultMaxLength == 'NaN' || resultMaxLength == null || resultMaxLength == '') {
			resultMaxLength = '<?php echo $maxDlugosc; ?>';
		}
		resultMaxLength = Number(resultMaxLength);
		
		//Sprawdzanie stanu
		if(resultLength <= resultMaxLength) {
			stan = true;
		}
		else {
			stan = false;
		}
		
		//Korekta
		if(stan == false) {
			do {
				var length = result.length;
				var lengthDifference = resultLength - length;
				var koniec = resultMaxLength - lengthDifference;
				
				var temp = result.substring(0, koniec);
				document.getElementById(id).value = temp;
				
				response = document.getElementById(id);
				result = response.value;
				resultLength = mb_strlen(result);
			}
			while(resultLength > resultMaxLength);
			
			stan = true;
		}
		
		//Wyświetlanie ilości znaków
		$("#znaki-wiadomosc").text(resultLength + "/" + resultMaxLength);
	}
//]]>
</script>

<div class="container body-content">
	<div class="jumbotron">
		<br /><br />
		<h2>Test bramki SMS</h2>
		<hr />
		<h3>Wiadomość SMS</h3>
		
		<form method="post" action="index.php" id="wyslijwiadomoscsms" onload="formField(this);">
			<table>
				<tr>
					<td>
						Odbiorca:
					</td>
					<td>
						<input type="text" name="nazwa" id="nazwa" value="<?php echo $nazwaOdbiorcy; ?>" size="20" maxlength="20" placeholder="nazwa odbiorcy" title="Nazwa odbiorcy wiadomości SMS" />
					</td>
				</tr>
				<tr>
					<td>
						Kraj:
					</td>
					<td>
						<input list="kraj" name="kraj" value="<?php echo $krajOdbiorcy; ?>" size="42" placeholder="kraj odbiorcy" title="Państwo, w którym zarejestrowany jest numer telefonu odbiorcy wiadomości SMS" />
						<datalist id="kraj">
							<option value="<?php echo $krajOdbiorcy; ?>">
							<?php
							$kraje = getCountriesName();
							$ile = count($kraje);
							
							for($i=0; $i<$ile; $i++) {
								if($kraje[$i] != $krajOdbiorcy) {
									?>
									<option value="<?php echo $kraje[$i]; ?>">
									<?php
								}
							}
							?>
						</datalist>
					</td>
				</tr>
				<tr>
					<td>
						Telefon:
					</td>
					<td>
						<input type="tel" name="telefon" id="telefon" value="<?php echo $numerOdbiorcy; ?>" size="20" maxlength="15" pattern="^[0-9]{1}[0-9 ]{7,13}[0-9]{1}$" placeholder="numer telefonu" title="Numer telefonu odbiorcy wiadomości SMS\n▪ minimum 9 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko cyfry" />
					</td>
				</tr>
				<tr>
					<td>
						Wiadomość:
					</td>
					<td>
						<div id="znaki-wiadomosc"></div>
					</td>
				</tr>
			</table>
			<table>
				<th>
					<textarea class="form-send_message_field" name="wiadomosc" id="wiadomosc" value="<?php echo $wiadomosc; ?>" onKeyUp="getValue('wiadomosc')" onKeyDown="getValue('wiadomosc')" rows="6" cols="60" maxlength="<?php echo $maxDlugosc; ?>" placeholder="treść wiadomości" title="Treść wiadomości SMS"><?php echo $wiadomosc; ?></textarea>
				</th>
				<tr>
					<td>
						<input type="hidden" name="id" value="wyslijwiadomoscsms" />
						<input type="hidden" name="max" value="<?php echo $maxDlugosc; ?>" />
						<input type="hidden" name="path" value="<?php echo $path; ?>" />
						<input type="hidden" name="typ" value="<?php echo $typ; ?>" />
						<input type="hidden" name="c" value="<?php echo $zaPomocaClickatell; ?>" />
						<input type="hidden" name="m" value="<?php echo $zaPomocaMailera; ?>" />
						<br />
						<center>
							<input class="submit-send_message_button" type="submit" value="Wyślij wiadomość" />
						</center>
					</td>
				</tr>
			</table>
		</form>
		
		<script type="text/javascript">
		//<![CDATA[
			var element = document.getElementById('wiadomosc');
			var tekst = element.value;
			
			var poczatkowaDlugosc = mb_strlen(tekst);
			var maksymalnaDlugosc = '<?php echo $maxDlugosc; ?>';
			
			//Wyświetlanie początkowej ilości znaków
			document.getElementById("znaki-wiadomosc").innerHTML = poczatkowaDlugosc + "/" + maksymalnaDlugosc;
		//]]>
		</script>
		
		<script type="text/javascript">
		//<![CDATA[
			formField();
			
			function formField(field) {
				var fields = ["nazwa", "kraj", "telefon", "wiadomosc"];
				var tekst = "";
				
				for(var i = 0; i < fields.length; i++) {
					switch (fields[i]) {
						case "nazwa":
							tekst = "Nazwa odbiorcy wiadomości SMS";
							break;
						case "kraj":
							tekst = "Państwo, w którym zarejestrowany jest numer telefonu odbiorcy wiadomości SMS";
							break;
						case "telefon":
							tekst = "▪ minimum 9 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko cyfry";
							break;
						case "wiadomosc":
							tekst = "Treść wiadomości SMS";
							break;
					}
					
					//field.title = tekst;
					document.getElementById(fields[i]).title = tekst;
				}
			}
			//]]>
		</script>
		
		<?php
		//Informacje zwrotne
		if($response != null && $response != '' && $response != false) {
			?>
			<br />
			<h3>Informacja zwrotna</h3>
			<p>Wiadomość SMS do <?php echo $nazwa; ?> na numer <?php echo $numer; ?></p>
			<table class="received-table_message">
				<tr>
					<td>
						Treść:
					</td>
					<td>
						<?php echo $tekst; ?>
					</td>
				</tr>
				<tr>
					<td>
						Data:
					</td>
					<td>
						<?php echo $dataZdarzenia.' '.$czasZdarzenia; ?>
					</td>
				</tr>
				<tr>
					<td>
						Status:
					</td>
					<td>
						<?php echo $response; ?>
					</td>
				</tr>
			</table>
			<?php
		}
		?>
	</div>
</div>
