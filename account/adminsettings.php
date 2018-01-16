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

//Pobieranie danych
$dataType = array('auto_logout_time');
$ustawienia = getspecialdata($path, 'facelike', 'config', $dataType);

//Sprawdzanie ilu jest wszystkich danych
$error = null;
$ile1 = count($dataType);
$ile2 = count($ustawienia);
if($ile1 == $ile2) {
	$ile = $ile1;
}
else {
	$ile = $ile2;
	$error = 'Błąd przy pobieraniu wszystkich danych - różna suma kontrolna (ilość)!';
}

$zmienna = null;

?>
<script type="text/javascript">
//<![CDATA[
	var i;
	var ile = <?php echo $ile; ?>;
	var warunek = new Array();
	var sumaKontrolna = 0;
	for(i=0; i<ile; i++) {
		warunek[i] = true;
	}
	
	function checksum() {
		i = 0;
		sumaKontrolna = 0;
		for(i=0; i<ile; i++) {
			if(warunek[i] == true) {
				sumaKontrolna++;
			}
		}
		
		if(sumaKontrolna == i) {
			$("#przycisk").show("slow");
		}
		else {
			$("#przycisk").hide("slow");
		}
	}
	
	function getValue(id) {
		var response = document.getElementById(id);
		var result = response.value;
		var resultMin = response.min;
		var resultMax = response.max;
		var step = response.step;
		var prompt = response.placeholder;
		var name = response.name;
		
		var l = name.length;
		var number = name.substring(2, l);
		
		result = Number(result);
		resultMin = Number(resultMin);
		resultMax = Number(resultMax);
		step = Number(step);
		number = Number(number);
		
		number--;
		
		jQuery(document).ready(function() {
			$.ajax({
				type: "POST",
				url: "account/adminsettingscheck.php",
				data: {"zmienna": id, "wartosc": result, "wartoscMin": resultMin, "wartoscMax": resultMax},
				success: function(msg) {
					//Ten fragment wykona się po POMYŚLNYM zakończeniu połączenia
					//"msg" zawiera dane zwrócone z serwera
					if(result >= resultMin && result <= resultMax) {
						if(number % 2 == 0) {
							$("#status" + number)
								.css("background-color", "#2db300")
								.css("color", "black");
							$("td.status#status" + number)
								.css("background-color", "#2db300")
								.css("color", "black");
						}
						else {
							$("#status" + number)
								.css("background-color", "#36d900")
								.css("color", "black");
							$("td.status#status" + number)
								.css("background-color", "#36d900")
								.css("color", "black");
						}
						warunek[number] = true;
					}
					else {
						if(number % 2 == 0) {
							$("#status" + number)
								.css("background-color", "#ff2b00")
								.css("color", "black");
							$(".status#status" + number)
								.css("background-color", "#ff2b00")
								.css("color", "black");
						}
						else {
							$("#status" + number)
								.css("background-color", "ff5c26")
								.css("color", "black");
							$(".status#status" + number)
								.css("background-color", "#ff5c26")
								.css("color", "black");
						}
						warunek[number] = false;
					}
					$("#status" + number).show("slow");
					$("#status" + number).text(msg);
					$(".status#status" + number).text(msg);
				},
				complete: function(r) {
					//Ten fragment wykona się po ZAKONCZENIU połączenia
					//"r" to przykładowa nazwa zmiennej, która zawiera dane zwrócone z serwera
					$('#loading').hide("slow");
					checksum();
				},
				error: function(error) {
					//Ten fragment wykona się w przypadku BŁĘDU
					$("#przycisk").hide("slow");
					console.log(error);
					alert("Błąd systemu:" + error);
				}
			});
		});
	}
//]]>
</script>

<!--< ?php
for($i=0; $i<$ile; $i++) {
	? >
	<div id="< ?php echo "status".$i; ? >" class="status"></div>
	< ?php
}
? >
<br />-->

<div class="container body-content">
	<div class="jumbotron">
		<h2>Ustawienia administratora</h2>
		<hr />
		<form method="post" action="index.php" id="zmienustawieniaadministratora">
			<table width="100%"><tbody>
				<tr><th>Nazwa</th><th>Obecne ustawienie</th><th>Nowe ustawienie</th><th></th></tr>
				<?php
				
				$szerokosc = 98% - 600;
				if($szerokosc < 250) {
					$szerokosc = 250;
				}
				
				for($i=0; $i<$ile; $i++) {
					$numer = $i+1;
					
					switch ($dataType[$i]) {
						case '_rev':
							$name = 'Wersja';
							$min = 0;
							$max = 1000;
							$step = 1;
							$content = "wersja";
							$prompt = "Numer wersji dokumentu";
							break;
						case 'auto_logout_time':
							$name = 'Czas automatycznego wylogowania';
							$min = 5;
							$max = 60;
							$step = 1;
							$content = "minuty";
							$prompt = "Czas automatycznego wylogowania\n▪ minimum 5 minut\n▪ maximum 60 minut";
							break;
						case 'opcja_3':
							$name = 'Opcja 3';
							$min = 0;
							$max = 100;
							$step = 1;
							$content = "coś tam";
							$prompt = "coś więcej";
							break;
					}
					
					?>
					<tr>
						<td width="250px"><?php echo $name; ?></td>
						<td width="200px"><?php echo $ustawienia[$i]; ?></td>
						<td width="150px">
							<input type="hidden" name="vn<?php echo $numer; ?>" value="<?php echo $dataType[$i]; ?>" />
							<input id="<?php echo $dataType[$i]; ?>" name="vc<?php echo $numer; ?>" type="number" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" value="<?php echo $ustawienia[$i]; ?>" onChange="getValue('<?php echo $dataType[$i]; ?>')" placeholder="<?php echo $content; ?>" title="<?php echo $prompt; ?>" />
						</td>
						<td width="<?php echo $szerokosc."px"; ?>" id="<?php echo "status".$i; ?>" class="status">
						</td>
					</tr>
					<?php
				}
				?>
				<input type="hidden" name="id" value="zmienustawieniaadministratora" />
				<input type="hidden" name="ile" value="<?php echo $ile; ?>" />
			</tbody></table>
			<br />
			
			<?php
			//Jeśli nie wykryto żadnego błędu
			if($error == null || $error == '') {
				?>
				<center>
					<div id="przycisk" style="display: none;">
						<input class="submit-save_admin_settings_button" type="submit" value="Zapisz zmiany &raquo;" />
					</div>
				</center>
				<?php
			}
			?>
		</form>
	</div>
</div>
