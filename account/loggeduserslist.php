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

$idList = getuseridlist($path, $userSecurityDbName);

//Sprawdzanie ilu jest wszystkich użytkowników
$ile = count($idList);

$ileZalogowanych = 0;
$idListZalogowani = array();

for($i=0; $i<$ile; $i++) {
	$dane = getuserdata($path, $userStatisticsDbName, $idList[$i], "loginstatus");
	
	if($dane["status"] == "logowanie" || $dane["status"] == "auto logowanie" || $dane["status"] == "zalogowany") {
		$rok = $dane["date"]["rok"];
		$miesiac = $dane["date"]["miesiac"];
		$dzien = $dane["date"]["dzien"];
		$godzina = $dane["date"]["godzina"];
		$minuta = $dane["date"]["minuta"];
		$sekunda = $dane["date"]["sekunda"];
		$czasLetni = $dane["date"]["czas_letni"];
		
		//$loginTime = mktime($godzina, $minuta, $sekunda, $miesiac, $dzien, $rok, (int)$czasLetni);
		$loginTime = mktime($godzina, $minuta, $sekunda, $miesiac, $dzien, $rok);
		
		$autoLogoutTime = getuserdata($path, "facelike", "config", "auto_logout_time");
		if(($autoLogoutTime < 5) || ($autoLogoutTime > 60)) {
			$autoLogoutTime = 15;
		}
		
		$waitingTime = $autoLogoutTime*60;
		
		$cutoffTime = $loginTime+$waitingTime;
		
		if($cutoffTime >= time()) {
			$idListZalogowani[$ileZalogowanych] = $idList[$i];
			
			$ileZalogowanych++;
		}
	}
}

//Ustawianie ilu użytkowników ma być widocznych na jednej stronie
$naStronie = !isset($_REQUEST['n']) ? 10 : (int)$_REQUEST['n'];

//Sprawdzenie poprawności ilości użytkowników widocznych na jednej stronie
if($naStronie == 10 || $naStronie == 20 || $naStronie == 50 || $naStronie == 100 || $naStronie == 1000) {}
else {
	$naStronie = 10;
}

//Obliczanie ilości stron
$ileStron = ceil($ileZalogowanych / $naStronie);

//Pobranie numeru aktualnej strony
$obecnaStrona = !isset($_REQUEST['i']) ? 1 : (int)$_REQUEST['i'];

//Jeśli ktoś poda stronę mniejszą niż 1
if($obecnaStrona < 1) {
	$obecnaStrona = 1;
}
//Jeśli ktoś poda stronę większą niż ilość stron
if($obecnaStrona > $ileStron) {
	$obecnaStrona = $ileStron;
}

?>
<div class="container body-content">
	<div class="jumbotron">
		<h2>Lista zalogowanych użytkowników</h2>
		<hr />
		<table id="user-list-body"><tbody>
			<tr>
				<td width="150px"></td>
				<td>
					<center><table id="user-number"><tbody>
						<tr>
							<td>
							<?php
								echo '<a href="index.php?id=listazalogowanychuzytkownikow&i=1&n='.$naStronie.'"><<</a>';
							echo '</td>';
							if($obecnaStrona-2 >= 1) {
								$strona = $obecnaStrona-2;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							if($obecnaStrona-1 >= 1) {
								$strona = $obecnaStrona-1;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							echo '<td>';
								echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$obecnaStrona.'&n='.$naStronie.'"><b>'.$obecnaStrona.'</b></a>';
							echo '</td>';
							if($obecnaStrona+1 <= $ileStron) {
								$strona = $obecnaStrona+1;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							if($obecnaStrona+2 <= $ileStron) {
								$strona = $obecnaStrona+2;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							echo '<td>';
								echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$ileStron.'&n='.$naStronie.'">>></a>';
							?>
							</td>
						</tr>
					</tbody></table></center>
				</td>
				<td width="150px">
					<table id="user-drop-down-list"><tbody>
						<tr>
							<td>
								<form method="post" action="loggeduserslist.php" id="listazalogowanychuzytkownikow">
									<select id="n" name="n" onChange="this.form.action='index.php?id=listazalogowanychuzytkownikow&i=<?php echo $obecnaStrona; ?>'; this.form.submit()">
									<?php
										if($naStronie==10) {
											echo '<option value="10" selected>10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==20) {
											echo '<option value="10">10</option>';
											echo '<option value="20" selected>20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==50) {
											echo '<option value="10">10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50" selected>50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==100) {
											echo '<option value="10">10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100" selected>100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==1000) {
											echo '<option value="10">10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000" selected>1000</option>';
										}
									?>
									</select>
									<input type="hidden" name="id" value="listazalogowanychuzytkownikow" />
									<input type="hidden" name="i" value="<?php echo $obecnaStrona; ?>" />
								</form>
							</td>
						</tr>
					</tbody></table>
				</td>
			<tr>
		</tbody></table>
		<?php
		
		echo '<table class="table table-hover"><tbody>';
			echo '<tr class="table-info"><th>No.</th><th>Imię i nazwisko</th><th>Ostatnia aktywność</th></tr>';
			
			$od = $naStronie*$obecnaStrona-$naStronie;
			$do = $naStronie*($obecnaStrona+1)-$naStronie;
			if($do > $ileZalogowanych)
				$do = $ileZalogowanych;
			
			for($i=$od; $i<$do; $i++) {
				$numer = $i+1;
				
				$l = strlen($idListZalogowani[$i]);
				$u = substr($idListZalogowani[$i], 5, $l);
				
				$dane1 = getuserdata($path, $userDataDbName, $idListZalogowani[$i], array("imie", "nazwisko"));
				$dane2 = getuserdata($path, $userStatisticsDbName, $idListZalogowani[$i], "loginstatus");
				
				$nazwa = $dane1[0].' '.$dane1[1];
				if($dane2 != null || $dane2 != '') {
					$aktywnoscDate = $dane2['date']['rok']."-".$dane2['date']['miesiac']."-".$dane2['date']['dzien'];
					$aktywnoscTime = $dane2['date']['godzina'].":".$dane2['date']['minuta'].":".$dane2['date']['sekunda'].".".$dane2['date']['mikrosekunda'];
					$aktywnoscSummerTime = $dane2['date']['czas_letni'] ? "Czas letni" : "Czas zimowy";
					$aktywnoscData = $dane2['date']['strefa_czasowa']." ".$aktywnoscSummerTime;
					$aktywnosc = $aktywnoscDate." ".$aktywnoscTime." ".$aktywnoscData;
				}
				else {
					$aktywnosc = 'Brak danych';
				}
				
				if($i%2 == 1) {
					echo '<tr class="table-secondary">';
				}
				else {
					echo '<tr class="table-primary">';
				}
					echo '<td width="30px">'.$numer.'</td>';
					echo '<td width="*"><a href="index.php?id=profilzaawansowany&u='.$u.'">'.$nazwa.'</a></td>';
					echo '<td width="350px">'.$aktywnosc.'</td>';
				echo '</tr>';
			}
		echo '</tbody></table>';
		
		?>
		<table id="user-list-body"><tbody>
			<tr>
				<td width="150px"></td>
				<td>
					<center><table id="user-number"><tbody>
						<tr>
							<td>
							<?php
								echo '<a href="index.php?id=listazalogowanychuzytkownikow&i=1&n='.$naStronie.'"><<</a>';
							echo '</td>';
							if($obecnaStrona-2 >= 1) {
								$strona = $obecnaStrona-2;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							if($obecnaStrona-1 >= 1) {
								$strona = $obecnaStrona-1;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							echo '<td>';
								echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$obecnaStrona.'&n='.$naStronie.'"><b>'.$obecnaStrona.'</b></a>';
							echo '</td>';
							if($obecnaStrona+1 <= $ileStron) {
								$strona = $obecnaStrona+1;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							if($obecnaStrona+2 <= $ileStron) {
								$strona = $obecnaStrona+2;
								echo '<td>';
									echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
								echo '</td>';
							}
							echo '<td>';
								echo '<a href="index.php?id=listazalogowanychuzytkownikow&i='.$ileStron.'&n='.$naStronie.'">>></a>';
							?>
							</td>
						</tr>
					</tbody></table></center>
				</td>
				<td width="150px">
					<table id="user-drop-down-list"><tbody>
						<tr>
							<td>
								<form method="post" action="loggeduserslist.php" id="listazalogowanychuzytkownikow">
									<select id="n" name="n" onChange="this.form.action='index.php?id=listazalogowanychuzytkownikow&i=<?php echo $obecnaStrona; ?>'; this.form.submit()">
									<?php
										if($naStronie==10) {
											echo '<option value="10" selected>10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==20) {
											echo '<option value="10">10</option>';
											echo '<option value="20" selected>20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==50) {
											echo '<option value="10">10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50" selected>50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==100) {
											echo '<option value="10">10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100" selected>100</option>';
											echo '<option value="1000">1000</option>';
										}
										if($naStronie==1000) {
											echo '<option value="10">10</option>';
											echo '<option value="20">20</option>';
											echo '<option value="50">50</option>';
											echo '<option value="100">100</option>';
											echo '<option value="1000" selected>1000</option>';
										}
									?>
									</select>
									<input type="hidden" name="id" value="listazalogowanychuzytkownikow" />
									<input type="hidden" name="i" value="<?php echo $obecnaStrona; ?>" />
								</form>
							</td>
						</tr>
					</tbody></table>
				</td>
			<tr>
		</tbody></table>
	</div>
</div>
