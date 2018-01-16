<?php
$path = "./couchdb/";	//Ścieżka operacyjna

require 'includes/config.php';
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Rejestracja</h2>
		<hr />
		<form method="post" action="register.php" id="rejestracja" onload="formField(this);">
			<table>
				<tr>
					<td>
						<label for="imie">Imię:</label>
					</td>
					<td>
						<input type="text" name="imie" id="imie" class="i01" placeholder="imię" size="25" maxlength="32" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="nazwisko">Nazwisko:</label>
					</td>
					<td>
						<input type="text" name="nazwisko" id="nazwisko" class="i01" placeholder="nazwisko" size="25" maxlength="32" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="plec">Płec:</label>
					</td>
					<td>
						<input type="radio" name="plec" value="m" required />&nbsp;mężczyzna&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="plec" value="k" required />&nbsp;kobieta<br />
					</td>
				</tr>
				<tr>
					<td>
						<label for="data_urodzenia">Data urodzenia:</label>
					</td>
					<td>
						<input type="date" name="data_urodzenia" id="data_urodzenia" class="i01_date" required min="<?php echo date("Y")-100; ?>-01-01" max="<?php echo date("Y-m-d"); ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="kraj">Kraj:</label>
					</td>
					<td>
						<input type="text" name="kraj" id="kraj" class="i01" placeholder="kraj" size="25" maxlength="20" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="wojewodztwo">Województwo:</label>
					</td>
					<td>
						<input type="text" name="wojewodztwo" id="wojewodztwo" class="i01" placeholder="wojewodztwo" size="25" maxlength="20" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="miasto">Miasto:</label>
					</td>
					<td>
						<input type="text" name="miasto" id="miasto" class="i01" placeholder="miasto" size="25" maxlength="32" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="ulica">Ulica:</label>
					</td>
					<td>
						<input type="text" name="ulica" id="ulica" class="i01" placeholder="ulica i numer domu" size="25" maxlength="64" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="poczta">Poczta:</label>
					</td>
					<td>
						<input type="text" name="poczta" id="poczta" class="i01" placeholder="kod pocztowy" size="25" maxlength="6" required pattern="^[0-9]{2}-?[0-9]{3}$" title="▪ 12-345\n▪ 12345" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="telefon">Telefon:</label>
					</td>
					<td>
						<input type="tel" name="telefon" id="telefon" class="i01" placeholder="numer telefonu" size="25" maxlength="15" required pattern="^[0-9]{1}[0-9 ]{5,13}[0-9]{1}$" title="▪ minimum 7 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko cyfry" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="email">Email:</label>
					</td>
					<td>
						<input type="email" name="email" id="email" class="i01" placeholder="example@domena.tld" size="25" maxlength="254" required pattern="^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]+\.(?:[a-zA-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)$" title="example@domena.tld" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="login">Login:</label>
					</td>
					<td>
						<input type="text" name="login" id="login" class="i01" placeholder="nazwa użytkownika" size="25" maxlength="32" required pattern="^[a-zA-Z]{1}[a-zA-Z0-9]{4,}$" title="▪ minimum 5 znaków\n▪ maximum 32 znaki\n▪ może zawierać tylko litery i cyfry\n▪ musi zaczynać się od litery" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">Hasło:</label>
					</td>
					<td>
						<input type="password" name="password" id="password" class="i01" placeholder="hasło" size="25" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password_v">Ponów hasło:</label>
					</td>
					<td>
						<input type="password" name="password_v" id="password_v" class="i01" placeholder="powtórzone hasło" size="25" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ musi być takie same jak powyżej" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
						<input type="hidden" name="rejestracja_rok" value="<?php echo date("Y"); ?>" />
						<input type="hidden" name="rejestracja_miesiac" value="<?php echo date("n"); ?>" />
						<input type="hidden" name="rejestracja_dzien" value="<?php echo date("j"); ?>" />
						<input type="hidden" name="rejestracja_godzina" value="<?php echo date("G"); ?>" />
						<input type="hidden" name="rejestracja_minuta" value="<?php echo date("i"); ?>" />
						<input type="hidden" name="rejestracja_sekunda" value="<?php echo date("s"); ?>" />
						<input type="hidden" name="id" value="rejestracja" />
						<br />
						<center>
							<input type="submit" class="btn btn-primary btn-lg" value="Zarejestruj &raquo;" />
						</center>
					</td>
				</tr>
			</table>
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
				case "login":
					tekst = "▪ minimum 5 znaków\n▪ maximum 32 znaki\n▪ może zawierać tylko litery i cyfry\n▪ musi zaczynać się od litery";
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
