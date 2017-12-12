<?php
function checksession($refresh = false)
{
	if(isset($_SESSION['user_id'])) {
		if($refresh == true) {
			if(isset($_SESSION['last_refresh_time'])) {
				return true;
			}
		}
		else {
			return true;
		}
	}
	
	return false;
}

function checksessionrefresh($path, $userSecurityDbName, $userId)
{
	$autoLogoutTime = $_SESSION['auto_logout_time'];
	
	$waitingTime = $autoLogoutTime*60;
	
	if(($_SESSION['last_refresh_time']+$waitingTime) < time()) {
		setOneCookie('autoLogoutTime', $autoLogoutTime, 0, 1);
		
		//Zapisanie nowego statusu i czasu ostatniej aktywności użytkownika do bazy danych
		changeloginstatus($path, $userSecurityDbName, $userId, 5, false);
		
		//Wyloguj
		session_destroy();
		
		header('Location: index.php?id=automatycznewylogowanie');
	}
	else {
		updatesession($path, $userSecurityDbName, $userId);
		
		return true;
	}
	
	return false;
}

function getidfromsession()
{
	if(isset($_SESSION['user_id'])) {
		return $_SESSION['user_id'];
	}
	
	return false;
}

function createsession($userId, $path)
{
	$autoLogoutTime = getspecialdata($path, "facelike", "config", "auto_logout_time");
	if(($autoLogoutTime < 5) || ($autoLogoutTime > 60)) {
		$autoLogoutTime = 15;
	}
	
	$_SESSION['user_id'] = $userId;	//Zapisujemy ID użytkownika do sesji i oznaczamy go jako zalogowanego
	$_SESSION['auto_logout_time'] = $autoLogoutTime;
	$_SESSION['last_refresh_time'] = time();
}

function updatesession($path, $userSecurityDbName, $userId)
{	
	//Zapisanie nowego statusu i czasu ostatniej aktywności użytkownika do bazy danych
	changeloginstatus($path, $userSecurityDbName, $userId, 4, false);
	
	$_SESSION['last_refresh_time'] = time();
}
?>
