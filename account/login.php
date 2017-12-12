<?php
$path = "./couchdb/";	//Ścieżka operacyjna

require 'includes/config.php';
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Logowanie</h2>
		<hr />
		<form method="post" action="index.php" id="logowanie">
			<table>
				<tr>
					<td>
						<label for="login">Login:</label>
					</td>
					<td>
						<input type="text" name="login" id="login" class="i02" maxlength="32" required />
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">Hasło:</label>
					</td>
					<td>
						<input type="password" name="password" id="password" class="i02" required />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
						<input type="hidden" name="id" value="logowanie" />
						<br />
						<center>
							<input type="submit" class="btn btn-primary btn-lg" value="Zaloguj &raquo;" />
						</center>
						<br />
					</td>
				</tr>
			</table>
			<table>
				<tr>
					<td>
						<p>
							<a href="index.php?id=rejestracja">Zarejestruj się jako nowy użytkownik</a>
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<p>
							<a href="index.php?id=odzyskajkonto">Nie pamiętasz hasła?</a>
						</p>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
