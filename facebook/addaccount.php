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

//Upewnij się, że użytkownik to admin
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
	echo '<div class="error-box">Przykro nam, ale nie posiadasz wystarczających uprawnień.</div>';
	
	die;
}

require_once "datafun.php";

$generatedData = generateNewData();
$adres = $generatedData[0];
$newData = $generatedData[1];

if($newData == false) {
	$errors[] = "Zewnętrzny serwer o adresie ".$adres." nie odpowiada, lub jego Błąd!";
	$errors[] = "Czy wczytać dane zapasowe?";
	
	?>
	<div class="validation_error-box error-show"><?php foreach ($errors as $error) { echo $error."<br />"; } ?></div>
	<script type="text/javascript">
	//<![CDATA[
		swal( {
				title: 'Uwaga',
				text: '<?php foreach ($errors as $error) { echo $error; ?>\n<?php } ?>',
				type: 'error',
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: "Anuluj",
				cancelButtonText: "Tak",
				closeOnConfirm: false,
				closeOnCancel: true
			},
			function(isConfirm) {
				if(isConfirm) {
					history.back();
				}
				else {
					$(".error-show").hide();
				}
			}
		);
		//]]>
	</script>
	<?php
	
	$zapas = rand(1, 10);
	switch ($zapas) {
		case 1:
			$newData = '{"name":"Witold Dudek","address":"Pogodna 79A\/83, 96-881 Otwock","latitude":-7.327043,"longitude":-25.563006,"maiden_name":"Walczak","birth_data":"1997-12-02","phone_h":"(08) 532 88 69","phone_w":"231 086 317","email_u":"kalina.wroblews","email_d":"cross-law.ga","username":"kowalczykdaniel","password":"VKW$);n_~","domain":"szewczyk.com","useragent":"Mozilla\/5.0 (Windows NT 4.0; en-US; rv:1.9.1.20) Gecko\/20170710 Firefox\/35.0","ipv4":"160.164.143.183","macaddress":"2A:12:CC:D3:C4:35","plasticcard":"4532550152282","cardexpir":"08\/18","bonus":24,"company":"Szymczak S.A.","color":"blue","uuid":"5dd0feea-918c-306f-8fca-f65c6289c489","height":203,"weight":92,"blood":"A+","eye":"Blue","hair":"Wavy, Brown","pict":"6male","url":"https:\/\/api.namefake.com\/polish-poland\/male\/a25756313bb9054d683c87b528c43c1f","sport":"Rugby","ipv4_url":"\/\/myip-address.com\/ip-lookup\/160.164.143.183","email_url":"\/\/emailfake.com\/cross-law.ga\/kalina.wroblews","domain_url":"\/\/myip-address.com\/ip-lookup\/szewczyk.com"}';
			break;
		case 2:
			$newData = '{"name":"dr Ewelina Duda","address":"Lisia 58A, 54-224 Mielec","latitude":-46.19562,"longitude":-137.996409,"maiden_name":"Lewandowski","birth_data":"1975-10-18","phone_h":"970 999 199","phone_w":"0048 51 852 99 79","email_u":"nziolkowska","email_d":"ggg.pp.ua","username":"gwojciechowski","password":"w1;kUJtVg;`W~X","domain":"kowalczyk.pl","useragent":"Mozilla\/5.0 (Windows NT 5.2; en-US; rv:1.9.2.20) Gecko\/20110328 Firefox\/36.0","ipv4":"108.156.117.118","macaddress":"A1:10:3D:5C:78:26","plasticcard":"5322300475232603","cardexpir":"09\/18","bonus":35,"company":"Laskowska i syn","color":"silver","uuid":"22c129cb-f7c7-3650-a6c7-e07581f55699","height":157,"weight":105.7,"blood":"O\u2212","eye":"Amber","hair":"Straight, Blond","pict":"10female","url":"https:\/\/api.namefake.com\/polish-poland\/female\/7f12c49d26b0ccc0420a1555fc251173","sport":"Ice Hockey","ipv4_url":"\/\/myip-address.com\/ip-lookup\/108.156.117.118","email_url":"\/\/emailfake.com\/ggg.pp.ua\/nziolkowska","domain_url":"\/\/myip-address.com\/ip-lookup\/kowalczyk.pl"}';
			break;
		case 3:
			$newData = '{"name":"dr Natasza Duda","address":"Ko\u015bciuszki Tadeusza 99, 15-799 Jaros\u0142aw","latitude":17.3973,"longitude":-179.412632,"maiden_name":"Ko\u0142odziej","birth_data":"1968-07-19","phone_h":"+48 40 271 62 27","phone_w":"0048(48)9429497","email_u":"izabela.lis","email_d":"gwfh.tk","username":"wisniewskastani","password":"MHZj!NWA~vBh6","domain":"szulc.net","useragent":"Mozilla\/5.0 (Macintosh; U; PPC Mac OS X 10_8_6) AppleWebKit\/5361 (KHTML, like Gecko) Chrome\/39.0.899.0 Mobile Safari\/5361","ipv4":"88.14.41.5","macaddress":"01:73:4B:F9:33:62","plasticcard":"4916211459855167","cardexpir":"04\/20","bonus":25,"company":"Grupa Rutkowski","color":"silver","uuid":"09e0296b-5662-33da-b468-88dc355ba041","height":192,"weight":47.1,"blood":"O+","eye":"Amber","hair":"Straight, Auburn","pict":"2female","url":"https:\/\/api.namefake.com\/polish-poland\/female\/03389ea3dac7f5c13e7c5b60c2e1f3bb","sport":"Fencing","ipv4_url":"\/\/myip-address.com\/ip-lookup\/88.14.41.5","email_url":"\/\/emailfake.com\/gwfh.tk\/izabela.lis","domain_url":"\/\/myip-address.com\/ip-lookup\/szulc.net"}';
			break;
		case 4:
			$newData = '{"name":"Blanka Sadowska","address":"Nowa 45A\/23, 28-317 D\u0119bica","latitude":8.864669,"longitude":14.207518,"maiden_name":"Zakrzewski","birth_data":"1955-02-23","phone_h":"+48 005 466 462","phone_w":"0048(79)6803290","email_u":"ida03","email_d":"fucknloveme.top","username":"aleks43","password":"~p54ihN$JV),ot;","domain":"lewandowski.pl","useragent":"Mozilla\/5.0 (Windows NT 4.0) AppleWebKit\/5331 (KHTML, like Gecko) Chrome\/40.0.845.0 Mobile Safari\/5331","ipv4":"212.36.145.90","macaddress":"C8:59:86:A0:18:03","plasticcard":"5316957632671755","cardexpir":"12\/20","bonus":44,"company":"Kowalski sp. p.","color":"yellow","uuid":"14050b75-d393-312c-a9f5-563efb2ac4f8","height":197,"weight":85.5,"blood":"O+","eye":"Amber","hair":"Straight, Blond","pict":"8female","url":"https:\/\/api.namefake.com\/polish-poland\/female\/19b33dfde84145f99b70604df6be874d","sport":"Athletics","ipv4_url":"\/\/myip-address.com\/ip-lookup\/212.36.145.90","email_url":"\/\/emailfake.com\/fucknloveme.top\/ida03","domain_url":"\/\/myip-address.com\/ip-lookup\/lewandowski.pl"}';
			break;
		case 5:
			$newData = '{"name":"mgr Wojciech Wieczorek","address":"D\u0105br\u00f3wki 61\/83, 92-710 Busko-Zdr\u00f3j","latitude":-36.874086,"longitude":136.715584,"maiden_name":"B\u0142aszczyk","birth_data":"1993-05-28","phone_h":"(03) 065 87 86","phone_w":"+48 86 810 10 83","email_u":"iczarnecka","email_d":"gwfh.cf","username":"fryderyk70","password":"5T=%L-C#(2|Q,^.-5,y=","domain":"gorecka.pl","useragent":"Mozilla\/5.0 (Macintosh; U; Intel Mac OS X 10_5_0 rv:3.0) Gecko\/20100122 Firefox\/35.0","ipv4":"50.102.112.80","macaddress":"47:88:F2:F5:BE:59","plasticcard":"6011871946243225","cardexpir":"07\/20","bonus":32,"company":"Jasi\u0144ski","color":"yellow","uuid":"51b76bf4-82b8-364b-a565-b5448ddf13f9","height":163,"weight":75.3,"blood":"A+","eye":"Amber","hair":"Straight, Brown","pict":"5male","url":"https:\/\/api.namefake.com\/polish-poland\/male\/30b9bfe241df9c295d511b6253b05ea2","sport":"Swimming","ipv4_url":"\/\/myip-address.com\/ip-lookup\/50.102.112.80","email_url":"\/\/emailfake.com\/gwfh.cf\/iczarnecka","domain_url":"\/\/myip-address.com\/ip-lookup\/gorecka.pl"}';
			break;
		case 6:
			$newData = '{"name":"Tola Kwiatkowska","address":"Francuska 38A, 81-904 Brodnica","latitude":78.721205,"longitude":97.869388,"maiden_name":"Jankowski","birth_data":"1959-06-10","phone_h":"0048 52 150 56 82","phone_w":"+48(24)9605312","email_u":"szymon.baranows","email_d":"sdf.storeyee.com","username":"ada68","password":"0\\F`HGT`,%Vq1Cd1=Vn","domain":"kubiak.org","useragent":"Mozilla\/5.0 (iPad; CPU OS 8_1_1 like Mac OS X; en-US) AppleWebKit\/534.23.1 (KHTML, like Gecko) Version\/4.0.5 Mobile\/8B117 Safari\/6534.23.1","ipv4":"128.250.3.198","macaddress":"B7:A7:ED:40:60:2F","plasticcard":"6011848050098157","cardexpir":"05\/20","bonus":37,"company":"Zakrzewski P.P.O.F","color":"navy","uuid":"faa29a5f-3370-382f-9167-d38739bbbcd7","height":197,"weight":100.2,"blood":"O\u2212","eye":"Brown","hair":"Curly, Chestnut","pict":"7female","url":"https:\/\/api.namefake.com\/polish-poland\/female\/e2cc11a29ffbc837e796bed5c780febf","sport":"Cycling Road","ipv4_url":"\/\/myip-address.com\/ip-lookup\/128.250.3.198","email_url":"\/\/emailfake.com\/sdf.storeyee.com\/szymon.baranows","domain_url":"\/\/myip-address.com\/ip-lookup\/kubiak.org"}';
			break;
		case 7:
			$newData = '{"name":"dr Krystian Duda","address":"Go\u015bcinna 15A\/26, 03-542 Nowe Kramsko","latitude":-49.915977,"longitude":0.209013,"maiden_name":"Wi\u015bniewska","birth_data":"1977-08-02","phone_h":"925505555","phone_w":"0048(53)8669291","email_u":"sobczak.krystyn","email_d":"priceio.co","username":"anitalis","password":"HK9xflyGfFHzjBIE","domain":"mroz.com.pl","useragent":"Mozilla\/5.0 (Macintosh; U; Intel Mac OS X 10_5_3 rv:5.0; sl-SI) AppleWebKit\/532.8.1 (KHTML, like Gecko) Version\/4.0.2 Safari\/532.8.1","ipv4":"184.130.104.163","macaddress":"44:7A:67:C4:10:52","plasticcard":"4024007109949346","cardexpir":"03\/18","bonus":4,"company":"Przybylska S.K.A","color":"teal","uuid":"fc5894b1-b5b0-30c1-b1cb-550de366a73f","height":177,"weight":91,"blood":"A+","eye":"Amber","hair":"Straight, Chestnut","pict":"8male","url":"https:\/\/api.namefake.com\/polish-poland\/male\/09ce47869782bfe596e6965e4d379f39","sport":"Boxing","ipv4_url":"\/\/myip-address.com\/ip-lookup\/184.130.104.163","email_url":"\/\/emailfake.com\/priceio.co\/sobczak.krystyn","domain_url":"\/\/myip-address.com\/ip-lookup\/mroz.com.pl"}';
			break;
		case 8:
			$newData = '{"name":"Maja Lis","address":"Wierzbowa 73, 55-305 Pruszk\u00f3w","latitude":35.13701,"longitude":54.212646,"maiden_name":"Krajewski","birth_data":"1995-10-25","phone_h":"0048(97)5002428","phone_w":"0048 278 584 809","email_u":"jablonska.nikod","email_d":"opmmedia.ga","username":"krupamieszko","password":"e1qVE:`c8$h;=","domain":"sadowski.org","useragent":"Opera\/9.83 (Windows NT 6.0; en-US) Presto\/2.12.352 Version\/12.00","ipv4":"185.228.242.188","macaddress":"9F:19:E6:1E:E6:F0","plasticcard":"4904580433869","cardexpir":"09\/20","bonus":11,"company":"Wi\u015bniewski","color":"fuchsia","uuid":"cb4dba95-77c0-30bc-a412-8f995649becf","height":194,"weight":78.7,"blood":"A+","eye":"Amber","hair":"Straight, Auburn","pict":"3female","url":"https:\/\/api.namefake.com\/polish-poland\/female\/140a65a34d9f74b4ff47c6df57941593","sport":"Canoe Sprint","ipv4_url":"\/\/myip-address.com\/ip-lookup\/185.228.242.188","email_url":"\/\/emailfake.com\/opmmedia.ga\/jablonska.nikod","domain_url":"\/\/myip-address.com\/ip-lookup\/sadowski.org"}';
			break;
		case 9:
			$newData = '{"name":"dr Fabian Jakubowski","address":"Ok\u00f3lna 54\/55, 92-937 \u015awidwin","latitude":-82.510003,"longitude":45.674079,"maiden_name":"Laskowska","birth_data":"1970-03-31","phone_h":"0048(61)9372699","phone_w":"430495837","email_u":"ykozlowska","email_d":"rethmail.ga","username":"maciejewskawikt","password":"8{O^(ZKO7&\"?&]o8L\/","domain":"baran.pl","useragent":"Mozilla\/5.0 (Macintosh; Intel Mac OS X 10_5_5 rv:3.0; sl-SI) AppleWebKit\/533.49.6 (KHTML, like Gecko) Version\/4.1 Safari\/533.49.6","ipv4":"49.73.127.195","macaddress":"CF:3A:4C:1B:23:76","plasticcard":"343580980648163","cardexpir":"03\/20","bonus":35,"company":"W\u0142odarczyk sp. j.","color":"green","uuid":"9060baf3-0b4c-3add-ab1a-544fb1c07324","height":197,"weight":52.9,"blood":"O+","eye":"Amber","hair":"Straight, Brown","pict":"8male","url":"https:\/\/api.namefake.com\/polish-poland\/male\/5c20b8a2c21a421eddc8421b351a77d8","sport":"Boxing","ipv4_url":"\/\/myip-address.com\/ip-lookup\/49.73.127.195","email_url":"\/\/emailfake.com\/rethmail.ga\/ykozlowska","domain_url":"\/\/myip-address.com\/ip-lookup\/baran.pl"}';
			break;
		case 10:
			$newData = '{"name":"Dagmara Michalska","address":"Bieszczadzka 13A\/03, 20-340 Dzier\u017coni\u00f3w","latitude":0.680994,"longitude":78.482577,"maiden_name":"Krupa","birth_data":"1968-08-24","phone_h":"+48 00 548 73 99","phone_w":"+48(88)0314708","email_u":"rkucharski","email_d":"mail-temp.com","username":"kowalskikalina","password":"CZ]^]su#Ov+BvU=h","domain":"nowakowski.pl","useragent":"Opera\/8.91 (X11; Linux i686; sl-SI) Presto\/2.9.296 Version\/10.00","ipv4":"245.84.146.193","macaddress":"33:62:88:D7:58:77","plasticcard":"4485113629025","cardexpir":"06\/19","bonus":32,"company":"Wysocka","color":"yellow","uuid":"7d540fb5-99b1-3343-93da-a14dbb82f431","height":180,"weight":100.6,"blood":"B\u2212","eye":"Gray","hair":"Very curly, Black","pict":"11female","url":"https:\/\/api.namefake.com\/polish-poland\/female\/03b309e5c55569439a630c79c1806e4f","sport":"Swimming","ipv4_url":"\/\/myip-address.com\/ip-lookup\/245.84.146.193","email_url":"\/\/emailfake.com\/mail-temp.com\/rkucharski","domain_url":"\/\/myip-address.com\/ip-lookup\/nowakowski.pl"}';
			break;
	}
}
else {
	$zapas = 0;
}
//Test początek
$newData = '{"name":"dr Anita Nowak","address":"Łokietka Władysława 52A/49, 11-595 Słupsk","latitude":-49.01574,"longitude":-62.200857,"maiden_name":"Maciejewski","birth_data":"1979-05-29","phone_h":"0048 89 511 75 65","phone_w":"+48 91 511 49 49","email_u":"anita.nowakowsk","email_d":"tarma.ml","username":"dariakalinowski","password":"a4QOf6461b7D9fjc","domain":"sikorski.com.pl","useragent":"Mozilla\/5.0 (compatible; MSIE 9.0; Windows NT 5.1; Trident\/5.1)","ipv4":"110.237.81.37","macaddress":"ED:7C:A0:FE:A3:5F","plasticcard":"6011569278317704","cardexpir":"10\/20","bonus":11,"company":"Sp\u00f3\u0142dzielnia Krajewski","color":"Oliwa","uuid":"c418ff7c-0713-38e9-aa9f-d73cbdc54584","height":162,"weight":86.4,"blood":"O-","eye":"brązowy","hair":"Curly, Brown","pict":"2male","url":"//api.namefake.com/polish-poland/male/90681f93027ee3373e929ddd5458d2e9","sport":"Taekwondo","ipv4_url":"\/\/myip-address.com\/ip-lookup\/110.237.81.37","email_url":"//emailfake.com/tarma.ml/anita.nowakowsk","domain_url":"\/\/myip-address.com\/ip-lookup\/sikorski.com.pl"}';
//Test koniec

$newData = polishCharactersCorrection($newData);
$newData = json_decode($newData);

foreach($newData as $key => $value) {
	if($key == "color") {
		$accountData[$key] = translate($path, $value);
	}
	else if($key == "eye") {
		$accountData[$key] = translate($path, $value);
	}
	else if($key == "hair") {
		$accountData[$key] = translate($path, $value);
	}
	else if($key == "sport") {
		$accountData[$key] = translate($path, $value);
	}
	else if($key == "ipv4_url") {
		$accountData[$key] = "http:".$value;
	}
	else if($key == "email_url") {
		$accountData[$key] = "http:".$value;
	}
	else if($key == "domain_url") {
		$accountData[$key] = "http:".$value;
	}
	else {
		$accountData[$key] = $value;
	}
	
	if($key == "name") {
		$do = strrpos($accountData['name'], " ");
		$temp = substr($accountData['name'], 0, $do);
		if($od = strrpos($temp, " ")) {
			$accountData['tytul'] = substr($temp, 0, $od);
			$accountData['imie'] = substr($temp, $od+1);
		}
		else {
			$accountData['tytul'] = "";
			$accountData['imie'] = $temp;
		}
		$accountData['nazwisko'] = substr($accountData['name'], $do+1);
		$accountData['plec'] = getplec(setplec($accountData['imie']));
	}
	if($key == "address") {
		$accountData['address_country'] = "Polska";
		$do = strrpos($accountData['address'], ",");
		$temp = substr($accountData['address'], 0, $do);
		$od = strrpos($temp, " ");
		$accountData['address_street'] = substr($temp, 0, $od);
		$accountData['address_house_number'] = substr($temp, $od+1);
		$temp = substr($accountData['address'], $do);
		$od = strpos($temp, " ");
		$temp = substr($temp, $od+1);
		$do = strpos($temp, " ");
		$accountData['address_post'] = substr($temp, 0, $do);
		$accountData['address_city'] = substr($temp, $do+1);
	}
	if($key == "birth_data") {
		$od = strpos($accountData['birth_data'], "-");
		$do = strrpos($accountData['birth_data'], "-");
		$temp = substr($accountData['birth_data'], 0, $do);
		$accountData['birth_data_day'] = substr($accountData['birth_data'], $do+1);
		$accountData['birth_data_month'] = substr($temp, $od+1);
		$accountData['birth_data_year'] = substr($temp, 0, $od);
	}
}

$hasloDlugosc = strlen($accountData['password']);
$znakiSzukane = "!@#$%^&*()_+-=";
$znakiDlugos = strlen($znakiSzukane);
$znakiSpecjalne = false;
for($i=0; $i<$znakiDlugos; $i++) {
	if(stripos($accountData['password'], $znakiSzukane[$i]) == true) {
		$znakiSpecjalne = true;
	}
}
if(($hasloDlugosc < 8) || ($znakiSpecjalne == true)) {
	$accountData['password'] = randomString(12);
}
?>

<script language="javascript">
//<![CDATA[
function getPath() {
	var element = document.getElementById('zdjecie');
	var sciezka = element.value;
	
	document.forms[0].sciezka.value = sciezka;
}
 //]]>
</script>

<div class="container body-content">
	<div class="jumbotron">
		<h2>Generator kont na Facebook-u</h2>
		<hr />
		<form method="post" action="addaccount.php" id="dodajkonto" onload="formField(this);">
			<table>
				<tr>
					<td>
						<label for="tytul">Tytuł:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="tytul" id="tytul" class="i01" placeholder="tytuł" size="25" maxlength="20" value="<?php echo $accountData['tytul']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="imie">Imie:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="imie" id="imie" class="i01" placeholder="imie" size="25" maxlength="32" value="<?php echo $accountData['imie']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="nazwisko">Nazwisko:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="nazwisko" id="nazwisko" class="i01" placeholder="nazwisko" size="25" maxlength="32" value="<?php echo $accountData['nazwisko']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="nazwisko_panienskie">Nazwisko panieńskie matki:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="nazwisko_panienskie" id="nazwisko_panienskie" class="i01" placeholder="nazwisko panieńskie matki" size="25" maxlength="32" value="<?php echo $accountData['maiden_name']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="plec">Płec:</label>
					</td>
					<td>
						<input type="radio" name="plec" value="m" <?php if($accountData['plec'] == "mężczyzna") echo 'checked'; ?> required />&nbsp;mężczyzna&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="radio" name="plec" value="k" <?php if($accountData['plec'] == "kobieta") echo 'checked'; ?> required />&nbsp;kobieta<br />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="data_urodzenia">Data urodzenia:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="date" name="data_urodzenia" id="data_urodzenia" class="i01_date" value="<?php echo $accountData['birth_data']; ?>" required min="<?php echo date("Y")-100; ?>-01-01" max="<?php echo date("Y-m-d"); ?>" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="kraj">Kraj:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="kraj" id="kraj" class="i01" placeholder="kraj" size="25" maxlength="20" value="<?php echo $accountData['address_country']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="miasto">Miasto:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="miasto" id="miasto" class="i01" placeholder="miasto" size="25" maxlength="32" value="<?php echo $accountData['address_city']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="ulica">Ulica:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="ulica" id="ulica" class="i01" placeholder="ulica" size="25" maxlength="64" value="<?php echo $accountData['address_street']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="numer_domu">Numer domu:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="numer_domu" id="numer_domu" class="i01" placeholder="numer domu" size="25" maxlength="20" value="<?php echo $accountData['address_house_number']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="poczta">Poczta:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="poczta" id="poczta" class="i01" placeholder="kod pocztowy" size="25" maxlength="6" value="<?php echo $accountData['address_post']; ?>" required pattern="^[0-9]{2}-?[0-9]{3}$" title="▪ 12-345\n▪ 12345" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="firma">Firma:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="firma" id="firma" class="i01" placeholder="firma" size="25" maxlength="32" value="<?php echo $accountData['company']; ?>" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="wzrost">Wzrost:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="wzrost" id="wzrost" class="i01" placeholder="wzrost" size="25" maxlength="6" value="<?php echo $accountData['height']; ?>" required pattern="^[0-9]{2,3}.?[0-9]{0,2}$" title="▪ 174.65\n▪ 182" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="waga">Waga:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="waga" id="waga" class="i01" placeholder="waga" size="25" maxlength="6" value="<?php echo $accountData['weight']; ?>" required pattern="^[0-9]{2,3}.?[0-9]{0,2}$" title="▪ 56.48\n▪ 120" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="wlosy">Włosy:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="wlosy" id="wlosy" class="i01" placeholder="wlosy" size="25" maxlength="20" value="<?php echo $accountData['hair']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="oczy">Oczy:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="oczy" id="oczy" class="i01" placeholder="oczy" size="25" maxlength="20" value="<?php echo $accountData['eye']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="krew">Grupa krwi:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="krew" id="krew" class="i01" placeholder="grupa krwi" size="25" maxlength="6" value="<?php echo $accountData['blood']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="sport">Ulubiony sport:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="sport" id="sport" class="i01" placeholder="sport" size="25" maxlength="32" value="<?php echo $accountData['sport']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="kolor">Ulubiony kolor:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="kolor" id="kolor" class="i01" placeholder="kolor" size="25" maxlength="20" value="<?php echo $accountData['color']; ?>" required />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="szerokosc_geograficzna">Szerokość geograficzna:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="szerokosc_geograficzna" id="szerokosc_geograficzna" class="i01" placeholder="szerokość geograficzna" size="25" maxlength="32" value="<?php echo $accountData['latitude']; ?>" required pattern="^-?[0-9]{1,2}.?[0-9]{0,16}$" title="▪ może zawierać tylko cyfry" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="dlugosc_geograficzna">Długość geograficzna:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="dlugosc_geograficzna" id="dlugosc_geograficzna" class="i01" placeholder="długość geograficzna" size="25" maxlength="32" value="<?php echo $accountData['longitude']; ?>" required pattern="^-?[0-9]{1,3}.?[0-9]{0,16}$" title="▪ może zawierać tylko cyfry" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="telefon_komorkowy">Telefon komórkowy:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="tel" name="telefon_komorkowy" id="telefon_komorkowy" class="i01" placeholder="numer telefonu" size="25" maxlength="15" value="<?php echo $accountData['phone_h']; ?>" required pattern="^+?[0-9]{1}[0-9 ]{5,13}[0-9]{1}$" title="▪ minimum 7 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko + i cyfry" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="telefon_stacjonarny">Telefon stacjonarny:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="tel" name="telefon_stacjonarny" id="telefon_stacjonarny" class="i01" placeholder="numer telefonu" size="25" maxlength="17" value="<?php echo $accountData['phone_w']; ?>" pattern="^+?[0-9]{1}[0-9 ]{5,15}[0-9]{1}$" title="▪ minimum 7 znaków\n▪ maximum 17 znaków\n▪ może zawierać tylko + i cyfry" />
					</td>
				</tr>
				<tr>
					<td>
						<label for="email">Email:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="email" name="email" id="email" class="i01" placeholder="example@domena.tld" size="25" maxlength="254" value="<?php echo $accountData['email_u']."@".$accountData['email_d']; ?>" required pattern="^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]+\.(?:[a-zA-Z]{2}|com|org|net|gov|mil|biz|win|top|men|fun|info|mobi|name|aero|jobs|club|site|zone|space|museum)$" title="example@domena.tld" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="login">Login:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="text" name="login" id="login" class="i01" placeholder="nazwa użytkownika" size="25" maxlength="32" value="<?php echo $accountData['username']; ?>" required pattern="^[a-zA-Z]{1}[a-zA-Z0-9]{4,}$" title="▪ minimum 5 znaków\n▪ maximum 32 znaki\n▪ może zawierać tylko litery i cyfry\n▪ musi zaczynać się od litery" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="password">Hasło:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="password" name="password" id="password" class="i01" placeholder="hasło" size="25" value="<?php echo $accountData['password']; ?>" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ minimum 8 znaków\n▪ musi zawierać co najmniej jedną małą literę\n▪ musi zawierać co najmniej jedną dużą literę\n▪ musi zawierać co najmniej jedną cyfrę\n▪ musi zawierać co najmniej jeden znak z grupy !@#$%^&*()_+-=" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="password_v">Ponów hasło:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="password" name="password_v" id="password_v" class="i01" placeholder="powtórzone hasło" size="25" value="<?php echo $accountData['password']; ?>" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\!\@\#\$\%\^\&\*\(\)\_\+\-\=])(?!.*\s).{8,}$" title="▪ musi być takie same jak powyżej" />
					</td>
					<td>
						<span class="form-required">* </span>
					</td>
				</tr>
				<tr>
					<td>
						<label for="zdjecie">Zdjęcie:</label>
					</td>
					<td>
						<input class="form-add_account_field" type="file" name="zdjecie" id="zdjecie" placeholder="zdjęcie" size="22" accept="image/jpeg,image/png" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
						<input type="hidden" name="sciezka" value="" />
						<input type="hidden" name="uuid" value="<?php echo $accountData['uuid']; ?>" />
						<input type="hidden" name="data_url" value="<?php echo $accountData['url']; ?>" />
						<input type="hidden" name="email_url" value="<?php echo $accountData['email_url']; ?>" />
						<input type="hidden" name="rok" value="<?php echo date("Y"); ?>" />
						<input type="hidden" name="miesiac" value="<?php echo date("n"); ?>" />
						<input type="hidden" name="dzien" value="<?php echo date("j"); ?>" />
						<input type="hidden" name="godzina" value="<?php echo date("G"); ?>" />
						<input type="hidden" name="minuta" value="<?php echo date("i"); ?>" />
						<input type="hidden" name="sekunda" value="<?php echo date("s"); ?>" />
						<input type="hidden" name="id" value="dodajkonto" />
						<br />
						<center>
							<input type="submit" class="btn btn-primary btn-lg" value="Utwórz konto &raquo;" onclick="getPath()" />
						</center>
						<br />
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
		var fields = ["poczta", "wzrost", "waga", "szerokosc_geograficzna", "dlugosc_geograficzna", "telefon_komorkowy", "telefon_stacjonarny", "email", "login", "password", "password_v"];
		var tekst = "";
		
		for(var i = 0; i < fields.length; i++) {
			switch (fields[i]) {
				case "poczta":
					tekst = "▪ 12-345\n▪ 12345";
					break;
				case "wzrost":
					tekst = "▪ 174.65\n▪ 182";
					break;
				case "waga":
					tekst = "▪ 56.48\n▪ 120";
					break;
				case "szerokosc_geograficzna":
					tekst = "▪ może zawierać tylko cyfry";
					break;
				case "dlugosc_geograficzna":
					tekst = "▪ może zawierać tylko cyfry";
					break;
				case "telefon_komorkowy":
					tekst = "▪ minimum 7 znaków\n▪ maximum 15 znaków\n▪ może zawierać tylko + i cyfry";
					break;
				case "telefon_stacjonarny":
					tekst = "▪ minimum 7 znaków\n▪ maximum 17 znaków\n▪ może zawierać tylko + i cyfry";
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
