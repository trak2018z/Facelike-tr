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
		<h2>Edytuj profil <?php echo $nazwa.' '.$userData['imie'].' '.$userData['nazwisko']; ?></h2>
		<hr />
		<form class="form-change_profil_container" method="post" action="index.php" id="zmienprofil" onload="formField(this);">
			<table>
				<tr>
					<td>Imię: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['imie']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="imie" id="imie" placeholder="imię" size="25" maxlength="32" />
					</td>
				</tr>
				<tr>
					<td>Nazwisko: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['nazwisko']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="nazwisko" id="nazwisko" placeholder="nazwisko" size="25" maxlength="32" />
					</td>
				</tr>
				<tr>
					<td>Płeć: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo getplec($userData['plec']); ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input type="hidden" name="plec" value="" />
						<input type="radio" name="plec" value="m" />&nbsp;mężczyzna&nbsp;&nbsp;&nbsp;
						<input type="radio" name="plec" value="k" />&nbsp;kobieta
					</td>
				</tr>
				<tr>
					<td>Kraj: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['kraj']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="kraj" id="kraj" placeholder="kraj" size="25" maxlength="20" />
					</td>
				</tr>
				<tr>
					<td>Województwo: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['wojewodztwo']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="wojewodztwo" id="wojewodztwo" placeholder="województwo" size="25" maxlength="20" />
					</td>
				</tr>
				<tr>
					<td>Miasto: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['miasto']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="miasto" id="miasto" placeholder="miasto" size="25" maxlength="32" />
					</td>
				</tr>
				<tr>
					<td>Ulica: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['ulica']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="ulica" id="ulica" placeholder="ulica i numer domu" size="25" maxlength="64" />
					</td>
				</tr>
				<tr>
					<td>Poczta: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['adres']['poczta']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="text" name="poczta" id="poczta" placeholder="kod pocztowy" size="25" maxlength="6" pattern="^[0-9]{2}-?[0-9]{3}$" title="▪ 12-345\n▪ 12345" />
					</td>
				</tr>
				<tr>
					<td>Telefon: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['telefon']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="tel" name="telefon" id="telefon" placeholder="numer telefonu" size="25" maxlength="15" pattern="^[0-9]{1}[0-9 ]{5,13}[0-9]{1}$" title="▪ minimum 7 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko cyfry" />
					</td>
				</tr>
				<tr>
					<td>Email: </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo $userData['mail']; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="email" name="email" id="email" placeholder="example@domena.tld" size="25" maxlength="254" pattern="^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]+\.(?:[a-zA-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)$" title="example@domena.tld" />
					</td>
				</tr>
				<tr>
					<td>Obecne&nbsp;hasło&nbsp;* </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo ""; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="password" name="password_o" id="password_o" placeholder="hasło" size="25" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" />
					</td>
				</tr>
				<tr>
					<td>Nowe&nbsp;hasło&nbsp;* </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo ""; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="password" name="password" id="password" placeholder="hasło" size="25" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" />
					</td>
				</tr>
				<tr>
					<td>Powtórz&nbsp;nowe&nbsp;hasło&nbsp;* </td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td><font color="black"><?php echo ""; ?></font></td>
					<td><?php echo "&nbsp;&nbsp;&nbsp;"; ?></td>
					<td>
						<input class="form-change_profil_field" type="password" name="password_v" id="password_v" placeholder="powtórzone hasło" size="25" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ musi być takie same jak powyżej" />
					</td>
				</tr>
			</table>
			<?php
			echo "* - uzupełnij tylko jeśli chcesz zmienić hasło<br />";
			?>
			<input type="hidden" name="id" value="zmienprofil" />
			<br />
			<center>
				<input type="submit" class="btn btn-primary btn-lg" value="Zapisz zmiany &raquo;" />
			</center>
		</form>
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
