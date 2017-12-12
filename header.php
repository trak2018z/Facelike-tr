<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
	<div class="container">
		<div class="navbar-header">
			<a href="./" class="navbar-brand">Facelike</a>
		</div>
		<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
        </button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav mr-auto">
				<?php
				$path = "couchdb/";	//Ścieżka operacyjna
				
				require 'account/includes/config.php';
				
				$signed = checksession(true);
				
				if($signed) {
					$signed = checksessionrefresh($path, $userSecurityDbName, getidfromsession());
				}
				
				if($signed) {
					//Pobierz dane o użytkowniku i zapisz je do zmiennej $userData
					$userData = data($path, $userDataDbName);
					//echo "userData=".json_decode($userData, true)."<br />";	//test
					
					if(checkadmin($path, $userSecurityDbName, getidfromsession(), $userData)) {
						//Widok dla zalogowanego administratora
						?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="paneladministracyjny" aria-expanded="false">Panel administracyjny <span class="caret"></span></a>
							<div class="dropdown-menu" aria-labelledby="paneladministracyjny">
								<a href="index.php?id=listauzytkownikow" class="dropdown-item">Użytkownicy</a>
								<a href="index.php?id=listazalogowanychuzytkownikow" class="dropdown-item">Zalogowani użytkownicy</a>
								<div class="dropdown-divider"></div>
								<a href="index.php?id=listakontfacebook" class="dropdown-item">Konta na Facebook-u</a>
								<a href="index.php?id=generatorkontfacebook" class="dropdown-item">Generator kont na Facebook-u</a>
								<div class="dropdown-divider"></div>
								<a href="index.php?id=listazdjec" class="dropdown-item">Zdjęcia</a>
								<a href="index.php?id=dodajzdjecie" class="dropdown-item">Dodaj zdjęcie</a>
								<a href="index.php?id=statystykizdjec" class="dropdown-item">Statystyki zdjęć</a>
								<div class="dropdown-divider"></div>
								<a href="index.php?id=informacjesystemowe" class="dropdown-item">System</a>
								<a href="index.php?id=ustawieniaadministratora" class="dropdown-item">Zaawansowane</a>
								<a href="index.php?id=testbramkisms" class="dropdown-item">Test bramki SMS</a>
								<a href="index.php?id=zaawansowanaedycjabazydanych" class="dropdown-item">Zaawansowana edycja bazy danych</a>
								<a href="index.php?id=ustawieniaadministratora" class="dropdown-item">Ustawienia administratora</a>
							</div>
						</li>
						<?php
					}
					else {
						//Widok dla zalogowanego użytkownika
						?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="paneluzytkownika" aria-expanded="false">Panel użytkownika <span class="caret"></span></a>
							<div class="dropdown-menu" aria-labelledby="menuuzytkownika">
								<a href="index.php?id=listazdjec" class="dropdown-item">Zdjęcia</a>
								<a href="index.php?id=dodajzdjecie" class="dropdown-item">Dodaj zdjęcie</a>
							</div>
						</li>
						<?php
					}
				}
				?>
				<li class="nav-item">
					<a href="index.php?id=informacje" class="nav-link">Informacje</a>
				</li>
				<li class="nav-item">
					<a href="index.php?id=kontakt" class="nav-link">Kontakt</a>
				</li>
			</ul>
			<?php
			if($signed) {
				//Widok dla zalogowanego użytkownika
				echo '<div class="navbar-nav-welcome">Witaj</div>';
			}
			?>
			<ul class="navbar-nav navbar-right">
				<?php
				if($signed) {
					//Widok dla zalogowanego użytkownika
					echo '<li class="nav-item"><a href="index.php?id=profil" class="nav-link">'.$userData['imie'].' '.$userData['nazwisko'].'</a></li>';
					echo '<li class="nav-item"><a href="index.php?id=wylogowanie" class="nav-link">Wyloguj się</a></li>';
				}
				else {
					//Widok dla niezalogowanego użytkownika
					echo '<li class="nav-item"><a href="index.php?id=rejestracja" class="nav-link">Zarejestruj się</a></li>';
					echo '<li class="nav-item"><a href="index.php?id=logowanie" class="nav-link">Zaloguj</a></li>';
				}
				?>
			</ul>
		</div>
	</div>
</nav>