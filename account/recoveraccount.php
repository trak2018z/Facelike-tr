<?php
$path = "./couchdb/";	//Ścieżka operacyjna

require 'includes/config.php';
?>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Odzyskaj konto</h2>
		<hr />
		<form method="post" action="recoveraccount.php" id="odzyskajkonto">
			<table>
				<tr>
					<td>
						<label for="email">Email:</label>
					</td>
					<td>
						<input type="email" name="email" id="email" class="i03" placeholder="example@domena.tld" size="25" maxlength="254" required pattern="^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]+\.(?:[a-zA-Z]{2}|com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum)$" title="example@domena.tld" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
						<input type="hidden" name="id" value="odzyskajkonto" />
						<br />
						<center>
							<input type="submit" class="btn btn-primary btn-lg" value="Odzyskaj konto &raquo" />
						</center>
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>
