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

$linkId = 'listakont';
$idList = getuseridlist($path, $facebookAccountDbName);

//Sprawdzanie ile jest wszystkich kont
$ile = count($idList);

//Ustawianie ile kont ma być widocznych na jednej stronie
$naStronie = !isset($_REQUEST['n']) ? 100 : (int)$_REQUEST['n'];

//Sprawdzenie poprawności ilości kont widocznych na jednej stronie
if($naStronie == 10 || $naStronie == 20 || $naStronie == 50 || $naStronie == 100 || $naStronie == 1000) {}
else {
	$naStronie = 100;
}

//Obliczanie ilości stron
$ileStron = ceil($ile / $naStronie);

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
		<?php
		//Jeśli jest chociaż jedne konto
		if($ile > 0) {
			?>
			<h2>Lista kont na Facebook-u</h2>
			<hr />
			<table id="account-list-body"><tbody>
				<tr>
					<td width="150px"></td>
					<td>
						<center><table id="account-number"><tbody>
							<tr>
								<td>
								<?php
									echo '<a href="index.php?id=listakont&i=1&n='.$naStronie.'"><<</a>';
								echo '</td>';
								if($obecnaStrona-2 >= 1) {
									$strona = $obecnaStrona-2;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona-1 >= 1) {
									$strona = $obecnaStrona-1;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listakont&i='.$obecnaStrona.'&n='.$naStronie.'"><b>'.$obecnaStrona.'</b></a>';
								echo '</td>';
								if($obecnaStrona+1 <= $ileStron) {
									$strona = $obecnaStrona+1;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona+2 <= $ileStron) {
									$strona = $obecnaStrona+2;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listakont&i='.$ileStron.'&n='.$naStronie.'">>></a>';
								?>
								</td>
							</tr>
						</tbody></table></center>
					</td>
					<td width="150px">
						<table id="account-drop-down-list"><tbody>
							<tr>
								<td>
									<form method="post" action="accountslist.php" id="listakont">
										<select id="n" name="n" onChange="this.form.action='index.php?id=listakont&i=<?php echo $obecnaStrona; ?>'; this.form.submit()">
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
										<input type="hidden" name="id" value="listakont" />
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
				echo '<tr class="table-info"><th>No.</th><th>Imię i nazwisko</th><th>Telefon</th><th>Mail</th><th>Data urodzenia</th><th>Data rejestracji</th></tr>';
				
				$od = $naStronie*$obecnaStrona-$naStronie;
				$do = $naStronie*($obecnaStrona+1)-$naStronie;
				if($do > $ile)
					$do = $ile;
				
				for($i=$od; $i<$do; $i++) {
					$numer = $i+1;
					
					$l = strlen($idList[$i]);
					$k = substr($idList[$i], 5, $l);
					
					$accountData = getuserdata($path, $facebookAccountDbName, $idList[$i], array("tytul", "imie", "nazwisko", "data_urodzenia", "telefon", "mail", "rejestracja"));
					
					if($accountData[0] != null || $accountData[0] != '') {
						$nazwa = $accountData[0].' '.$accountData[1].' '.$accountData[2];
					}
					else {
						$nazwa = $accountData[1].' '.$accountData[2];
					}
					$rok = $accountData[3]['data_urodzenia_rok'];
					$miesiac = $accountData[3]['data_urodzenia_miesiac'];
					$dzien = $accountData[3]['data_urodzenia_dzien'];
					$dataUrodzenia = date('Y-m-d', strtotime($rok.'-'.$miesiac.'-'.$dzien));
					$rok = $accountData[6]['rejestracja_rok'];
					$miesiac = $accountData[6]['rejestracja_miesiac'];
					$dzien = $accountData[6]['rejestracja_dzien'];
					$godzina = $accountData[6]['rejestracja_godzina'];
					$minuta = $accountData[6]['rejestracja_minuta'];
					$sekunda = $accountData[6]['rejestracja_sekunda'];
					$rejestracja = date('Y-m-d H:i:s', strtotime($rok.'-'.$miesiac.'-'.$dzien.' '.$godzina.':'.$minuta.':'.$sekunda));
					
					if($i%2 == 1) {
						echo '<tr class="table-secondary">';
					}
					else {
						echo '<tr class="table-primary">';
					}
						echo '<td width="30px">'.$numer.'</td>';
						echo '<td width="*"><a href="index.php?id=kontozaawansowany&k='.$k.'">'.$nazwa.'</a></td>';
						echo '<td width="*">'.$accountData[4]['telefon_komorkowy'].'</td>';
						echo '<td width="*"><a href="'.$accountData[5]['email_url'].'" target="_blank">'.$accountData[5]['email'].'</a></td>';
						echo '<td width="160px">'.$dataUrodzenia.'</td>';
						echo '<td width="160px">'.$rejestracja.'</td>';
					echo '</tr>';
				}
			echo '</tbody></table>';
			
			?>
			<table id="account-list-body"><tbody>
				<tr>
					<td width="150px"></td>
					<td>
						<center><table id="account-number"><tbody>
							<tr>
								<td>
								<?php
									echo '<a href="index.php?id=listakont&i=1&n='.$naStronie.'"><<</a>';
								echo '</td>';
								if($obecnaStrona-2 >= 1) {
									$strona = $obecnaStrona-2;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona-1 >= 1) {
									$strona = $obecnaStrona-1;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listakont&i='.$obecnaStrona.'&n='.$naStronie.'"><b>'.$obecnaStrona.'</b></a>';
								echo '</td>';
								if($obecnaStrona+1 <= $ileStron) {
									$strona = $obecnaStrona+1;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona+2 <= $ileStron) {
									$strona = $obecnaStrona+2;
									echo '<td>';
										echo '<a href="index.php?id=listakont&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listakont&i='.$ileStron.'&n='.$naStronie.'">>></a>';
								?>
								</td>
							</tr>
						</tbody></table></center>
					</td>
					<td width="150px">
						<table id="account-drop-down-list"><tbody>
							<tr>
								<td>
									<form method="post" action="accountslist.php" id="listakont">
										<select id="n" name="n" onChange="this.form.action='index.php?id=listakont&i=<?php echo $obecnaStrona; ?>'; this.form.submit()">
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
										<input type="hidden" name="id" value="listakont" />
										<input type="hidden" name="i" value="<?php echo $obecnaStrona; ?>" />
									</form>
								</td>
							</tr>
						</tbody></table>
					</td>
				<tr>
			</tbody></table>
			<?php
		}
		//Jeśli nie znaleziono żadnego konta Facebook-a
		else {
			?>
			<table id="account-empty-list"><tbody>
				<tr>
					<td>Niestety nie znaleziono żadnych kont Facebook-a?!</td>
				</tr>
			</tbody></table>
			<?php
		}
		?>
	</div>
</div>
