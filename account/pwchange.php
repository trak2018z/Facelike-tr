<?php
$path = "./couchdb/";	//Ścieżka operacyjna

require 'includes/config.php';

//Pobranie id użytkownika do zmiany hasła
$pwChangeUserId = null;
if(isset($_GET['userid'])) {
	$pwChangeUserId = $_GET['userid'];
	$pwChangeUserId = base64_decode($pwChangeUserId);
}

//Pobranie adresu mail do zmiany hasła
$pwChangeEmail = null;
if(isset($_GET['email'])) {
	$pwChangeEmail = $_GET['email'];
	$pwChangeEmail = base64_decode($pwChangeEmail);
}

//Pobranie klucza do zmiany hasła
$pwChangeKey = null;
if(isset($_GET['key'])) {
	$pwChangeKey = $_GET['key'];
}

//Podstawowa walidacja formularza
if(empty($pwChangeUserId) || empty($pwChangeEmail) || empty($pwChangeKey)) {
	$errors[] = 'Błędny link';
}
else {
	//Sprawdzanie danych
	$istniejeId = checkid($path, $userDataDbName, $pwChangeUserId);
	
	if($istniejeId == false) {
		$errors[] = 'Błąd przy sprawdzaniu użytkownika';
	}
	else {
		$type = "";
		$type = getusertype($path, $userSecurityDbName, $pwChangeUserId);
		if($type == "niepotwierdzony") {
			$errors[] = 'Błąd przy sprawdzaniu statusu konta użytkownika';
		}
		else {
			$istniejeEmail = checkuserdata($path, $userDataDbName, $pwChangeUserId, 'mail', $pwChangeEmail);
			$istniejeKey = checkuserdata($path, $userSecurityDbName, $pwChangeUserId, 'rkey', $pwChangeKey);
			
			if($istniejeEmail == false || $istniejeKey == false) {
				$errors[] = 'Błąd przy sprawdzaniu danych użytkownika';
			}
			else {
				//Pobieranie daty i czasu utworzenia linku ratunkowego
				$recovery = getuserdata($path, $userSecurityDbName, $pwChangeUserId, 'recovery');
				
				//Wyznaczanie znacznika czasowego maksymalnej dopuszczalnej daty i czasu
				$rok = $recovery['rok'];
				$miesiac = $recovery['miesiac'];
				$dzien = $recovery['dzien'];
				$godzina = $recovery['godzina'];
				$minuta = $recovery['minuta'];
				$sekunda = $recovery['sekunda'];
				
				$maksymalnyZnacznikCzasu = mktime($godzina, $minuta, $sekunda, $miesiac, ($dzien+1), $rok);
				
				//Wyznaczanie znacznika czasowego aktualnej daty i czasu
				$aktualnyZnacznikCzasu = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
				
				if($maksymalnyZnacznikCzasu < $aktualnyZnacznikCzasu) {
					$errors[] = 'Błąd, link wygasł';
					
					//Utworzenie pustej tablicy ratunkowej
					$recovery = NULL;
					
					//Tworzenie dokumentu
					$document = array(
						'recovery' => $recovery
					);
					
					//Zapisanie pustej tablicy ratunkowej do bazy danych
					updatedocument($path, $userSecurityDbName, $pwChangeUserId, $document);
				}
				else {
					$login = getuserdata($path, $userSecurityDbName, $pwChangeUserId, 'login');
					
					if($login == false) {
						$errors[] = 'Błąd przy pobieraniu loginu użytkownika';
					}
				}
			}
		}
	}
}

if(empty($errors)) {
	//Jeżeli nie ma błędów to przechodzimy dalej
	?>
	<div class="container body-content">
		<div class="jumbotron">
			<h2>Zmiana hasła</h2>
			<hr />
			<form method="post" action="pwchange.php" id="zmianahasla">
				<table>
					<tr>
						<td>
							<label for="login">Login:</label>
						</td>
						<td>
							<?php echo $login; ?>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td>
							<br />
						</td>
					</tr>
					<tr>
						<td>
							<label for="password">Hasło:</label>
						</td>
						<td>
							<input type="password" name="password" id="password" placeholder="hasło" size="25" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" />
						</td>
					</tr>
					<tr>
						<td>
							<label for="password_v">Ponów hasło:</label>
						</td>
						<td>
							<input type="password" name="password_v" id="password_v" placeholder="powtórzone hasło" size="25" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ musi być takie same jak powyżej" />
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td>
							<input type="hidden" name="userid" value="<?php echo $pwChangeUserId; ?>" />
							<input type="hidden" name="email" value="<?php echo $pwChangeEmail; ?>" />
							<input type="hidden" name="key" value="<?php echo $pwChangeKey; ?>" />
							<input type="hidden" name="id" value="zmianahasla" />
							<br />
							<center>
								<input type="submit" class="btn btn-primary btn-lg" value="Zmień hasło &raquo;" />
							</center>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
	<?php
}
else {
	//Jeśli wystąpiły jakieś błędy, to je pokaż
	?>
	<div class="validation_error-box"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
	<script type="text/javascript">
	//<![CDATA[
		swal( {
				title: 'Uwaga',
				text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
				type: 'error',
				confirmButtonColor: '#DD6B55',
				closeOnConfirm: false
			},
			function() {
				window.location.href = 'index.php';
			}
		);
		//]]>
	</script>
	<?php
}
?>

<script type="text/javascript">
//<![CDATA[
	formField();
	
	function formField(field) {
		var fields = ["password", "password_v"];
		var tekst = "";
		
		for(var i = 0; i < fields.length; i++) {
			switch (fields[i]) {
				case "password":
					tekst = "▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=";
					break;
				case "password_v":
					tekst = "▪ musi być takie same jak powyżej";
					break;
			}
			
			//field.title = tekst;
			document.getElementById(fields[i]).title = tekst;
		}
	}
	//]]>
</script>
