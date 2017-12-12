<?php
$path = "./couchdb/";	//Ścieżka operacyjna

//Upewnij się że użytkownik jest zalogowany
if(!checksession()) {
	header('Location: index.php');
	echo '<p class="error">Przykro nam, ale ta strona jest dostępna tylko dla zalogowanych użytkowników.</p>';
	
	die;
}

$userId = getidfromsession();

//Upewnij się, że użytkownik istnieje
if(!checkid($path, $userDataDbName, $userId)) {
	header('Location: index.php');
	echo '<p class="error">Przykro nam, ale użytkownik o podanym identyfikatorze nie istnieje.</p>';
	
	die;
}

//Sprawdzenie czy użytkownik to admin
if(checkadmin($path, $userSecurityDbName, $userId, $userData)) {
	$superUser = true;
	$nazwa = "administratora";
}
else {
	$superUser = false;
	$nazwa = "użytkownika";
}
?>

<div class="container body-content">
	<div class="jumbotron">
		<form></form>
		<center>
			<form class="form-change_profil_container" method="post" action="index.php" id="zmienprofil" onload="formField(this);">
				<div class="form-change_profil_title">
					<h2>Edytuj profil <?php echo $nazwa; ?></h2>
				</div>
				
				<label class="form-change_profil_name" style="font-style:normal; for="imie"><b>Imię:</b><font color="black"> <?php echo $userData['imie'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="imie" id="imie" placeholder="imię" size="25" maxlength="32" /><br />
				
				<label class="form-change_profil_name" for="nazwisko"><b>Nazwisko:</b><font color="black"> <?php echo $userData['nazwisko'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="nazwisko" id="nazwisko" placeholder="nazwisko" size="25" maxlength="32" /><br />
				
				<label class="form-change_profil_name" for="plec"><b>Plec:</b><font color="black"> <?php echo getplec($userData['plec']) ?></font></label><br />
				<input type="hidden" name="plec" value="" />
				<input type="radio" name="plec" value="m" />&nbsp;mężczyzna&nbsp;&nbsp;&nbsp;
				<input type="radio" name="plec" value="k" />&nbsp;kobieta<br /><br />
				
				<label class="form-change_profil_name" for="kraj"><b>Kraj:</b><font color="black"> <?php echo $userData['adres']['kraj'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="kraj" id="kraj" placeholder="kraj" size="25" maxlength="20" /><br />
				
				<label class="form-change_profil_name" for="wojewodztwo"><b>Województwo:</b><font color="black"> <?php echo $userData['adres']['wojewodztwo'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="wojewodztwo" id="wojewodztwo" placeholder="województwo" size="25" maxlength="20" /><br />
				
				<label class="form-change_profil_name" for="miasto"><b>Miasto:</b><font color="black"> <?php echo $userData['adres']['miasto'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="miasto" id="miasto" placeholder="miasto" size="25" maxlength="32" /><br />
				
				<label class="form-change_profil_name" for="ulica"><b>Ulica:</b><font color="black"> <?php echo $userData['adres']['ulica'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="ulica" id="ulica" placeholder="ulica i numer domu" size="25" maxlength="64" /><br />
				
				<label class="form-change_profil_name" for="poczta"><b>Poczta:</b><font color="black"> <?php echo $userData['adres']['poczta'] ?></font></label><br />
				<input class="form-change_profil_field" type="text" name="poczta" id="poczta" placeholder="kod pocztowy" size="25" maxlength="6" pattern="^[0-9]{2}-?[0-9]{3}$" title="▪ 12-345\n▪ 12345" /><br />
				
				<label class="form-change_profil_name" for="telefon"><b>Telefon:</b><font color="black"> <?php echo $userData['telefon'] ?></font></label><br />
				<input class="form-change_profil_field" type="tel" name="telefon" id="telefon" placeholder="numer telefonu" size="25" maxlength="15" pattern="^[0-9]{1}[0-9 ]{5,13}[0-9]{1}$" title="▪ minimum 7 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko cyfry" /><br />
				
				<label class="form-change_profil_name" for="email"><b>Email:</b><font color="black"> <?php echo $userData['mail'] ?></font></label><br />
				<input class="form-change_profil_field" type="email" name="email" id="email" placeholder="example@domena.tld" size="25" maxlength="254" pattern="^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]+\.(?:[a-zA-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)$" title="example@domena.tld" /><br />
				
				<label class="form-change_profil_name" for="password_o"><b>Obecne hasło *</b></label><br />
				<input class="form-change_profil_field" type="password" name="password_o" id="password_o" placeholder="hasło" size="25" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" /><br />
				
				<label class="form-change_profil_name" for="password"><b>Nowe hasło *</b></label><br />
				<input class="form-change_profil_field" type="password" name="password" id="password" placeholder="hasło" size="25" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" /><br />
				
				<label class="form-change_profil_name" for="password_v"><b>Powtórz nowe hasło *</b></label><br />
				<input class="form-change_profil_field" type="password" name="password_v" id="password_v" placeholder="powtórzone hasło" size="25" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ musi być takie same jak powyżej" /><br />
				
				<?php
				echo "* - uzupełnij tylko jeśli chcesz zmienić hasło<br />";
				?>
				
				<input type="hidden" name="id" value="zmienprofil" />
				<div class="submit-save_profil_container">
					<input class="submit-save_profil_button" type="submit" value="Zapisz zmiany" />
				</div>
			</form>
		</center>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	formField();
	
	function formField(field) {
		var fields = ["poczta", "telefon", "email", "login", "password", "password_v"];
		var tekst = "";
		
		for(var i = 0; i < fields.length; i++) {
			switch (fields[i]) {
				case "poczta":
					tekst = "▪ 12-345\n▪ 12345";
					break;
				case "telefon":
					tekst = "▪ minimum 7 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko cyfry";
					break;
				case "email":
					tekst = "example@domena.tld";
					break;
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
