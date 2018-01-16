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

//Ustawianie ilu użytkowników ma być widocznych na jednej stronie
$naStronie = !isset($_REQUEST['n']) ? 100 : (int)$_REQUEST['n'];

//Sprawdzenie poprawności ilości użytkowników widocznych na jednej stronie
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
		//Jeśli jest chociaż jeden użytkownik
		if($ile > 0) {
			?>
			<h2>Lista użytkowników</h2>
			<hr />
			<table id="user-list-body"><tbody>
				<tr>
					<td width="150px"></td>
					<td>
						<center><table id="user-number"><tbody>
							<tr>
								<td>
								<?php
									echo '<a href="index.php?id=listauzytkownikow&i=1&n='.$naStronie.'"><<</a>';
								echo '</td>';
								if($obecnaStrona-2 >= 1) {
									$strona = $obecnaStrona-2;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona-1 >= 1) {
									$strona = $obecnaStrona-1;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listauzytkownikow&i='.$obecnaStrona.'&n='.$naStronie.'"><b>'.$obecnaStrona.'</b></a>';
								echo '</td>';
								if($obecnaStrona+1 <= $ileStron) {
									$strona = $obecnaStrona+1;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona+2 <= $ileStron) {
									$strona = $obecnaStrona+2;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listauzytkownikow&i='.$ileStron.'&n='.$naStronie.'">>></a>';
								?>
								</td>
							</tr>
						</tbody></table></center>
					</td>
					<td width="150px">
						<table id="user-drop-down-list"><tbody>
							<tr>
								<td>
									<form method="post" action="userslist.php" id="listauzytkownikow">
										<select id="n" name="n" onChange="this.form.action='index.php?id=listauzytkownikow&i=<?php echo $obecnaStrona; ?>'; this.form.submit()">
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
										<input type="hidden" name="id" value="listauzytkownikow" />
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
				echo '<tr class="table-info"><th>No.</th><th>Imię i nazwisko</th><th>Telefon</th><th>Mail</th><th>Data rejestracji</th><th>Uprawnienia</th></tr>';
				
				$od = $naStronie*$obecnaStrona-$naStronie;
				$do = $naStronie*($obecnaStrona+1)-$naStronie;
				if($do > $ile)
					$do = $ile;
				
				for($i=$od; $i<$do; $i++) {
					$numer = $i+1;
					
					$l = strlen($idList[$i]);
					$u = substr($idList[$i], 5, $l);
					
					$dane1 = getuserdata($path, $userDataDbName, $idList[$i], array("imie", "nazwisko", "telefon", "mail", "rejestracja"));
					$dane2 = getuserdata($path, $userSecurityDbName, $idList[$i], "typ");
					
					$nazwa = $dane1[0].' '.$dane1[1];
					$rok = $dane1[4]['rejestracja_rok'];
					$miesiac = $dane1[4]['rejestracja_miesiac'];
					$dzien = $dane1[4]['rejestracja_dzien'];
					$godzina = $dane1[4]['rejestracja_godzina'];
					$minuta = $dane1[4]['rejestracja_minuta'];
					$sekunda = $dane1[4]['rejestracja_sekunda'];
					$rejestracja = date('Y-m-d H:i:s', strtotime($rok.'-'.$miesiac.'-'.$dzien.' '.$godzina.':'.$minuta.':'.$sekunda));
					if($dane2 != null || $dane2 != '') {
						$uprawnienia = $dane2;
					}
					else {
						$uprawnienia = 'nieprzydzielone';
					}
					
					if($i%2 == 1) {
						echo '<tr class="table-secondary">';
					}
					else {
						echo '<tr class="table-primary">';
					}
						echo '<td width="30px">'.$numer.'</td>';
						echo '<td width="*"><a href="index.php?id=profilzaawansowany&u='.$u.'">'.$nazwa.'</a></td>';
						echo '<td width="*">'.$dane1[2].'</td>';
						echo '<td width="*">'.$dane1[3].'</td>';
						echo '<td width="160px">'.$rejestracja.'</td>';
						echo '<td width="160px">'.$uprawnienia.'</td>';
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
									echo '<a href="index.php?id=listauzytkownikow&i=1&n='.$naStronie.'"><<</a>';
								echo '</td>';
								if($obecnaStrona-2 >= 1) {
									$strona = $obecnaStrona-2;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona-1 >= 1) {
									$strona = $obecnaStrona-1;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listauzytkownikow&i='.$obecnaStrona.'&n='.$naStronie.'"><b>'.$obecnaStrona.'</b></a>';
								echo '</td>';
								if($obecnaStrona+1 <= $ileStron) {
									$strona = $obecnaStrona+1;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								if($obecnaStrona+2 <= $ileStron) {
									$strona = $obecnaStrona+2;
									echo '<td>';
										echo '<a href="index.php?id=listauzytkownikow&i='.$strona.'&n='.$naStronie.'">'.$strona.'</a>';
									echo '</td>';
								}
								echo '<td>';
									echo '<a href="index.php?id=listauzytkownikow&i='.$ileStron.'&n='.$naStronie.'">>></a>';
								?>
								</td>
							</tr>
						</tbody></table></center>
					</td>
					<td width="150px">
						<table id="user-drop-down-list"><tbody>
							<tr>
								<td>
									<form method="post" action="userslist.php" id="listauzytkownikow">
										<select id="n" name="n" onChange="this.form.action='index.php?id=listauzytkownikow&i=<?php echo $obecnaStrona; ?>'; this.form.submit()">
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
										<input type="hidden" name="id" value="listauzytkownikow" />
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
		//Jeśli nie znaleziono żadnego użytkownika
		else {
			?>
			<table id="user-empty-list"><tbody>
				<tr>
					<td>Niestety nie znaleziono żadnych użytkowników?!</td>
				</tr>
			</tbody></table>
			<?php
		}
		?>
	</div>
</div>
