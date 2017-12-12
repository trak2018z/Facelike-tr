<?php  
header('Content-Type: text/html;charset=UTF-8'); 
?><!DOCTYPE html>

<html lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	<meta name="author" content="Łukasz Gotówko" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Facelike</title>
	<link href="styles/style.css" rel="stylesheet" type="text/css" />
	<!----><link href="styles/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="styles/camera.css" rel="stylesheet" type="text/css" id="camera-css" media="all" /> 
	<link href="styles/sweetalert.css" rel="stylesheet" type="text/css" />
	<link href="favicon_64.ico" rel="shortcut icon" type="image/x-icon" />
	<script src="scripts/jquery-1.12.0.js" type="text/javascript"></script>
	<script src="scripts/jquery.easing-1.3.js" type="text/javascript"></script>
	<script src="scripts/divante.cookies.min.js" type="text/javascript"></script>
	<script>
	//<![CDATA[
		window.jQuery.cookie || document.write('<script src="scripts/jquery.cookie.min.js"><\/script>')
	 //]]>
	</script>
	<script src="scripts/camera.js" type="text/javascript"></script>
	<script src="alert/dist/sweetalert.min.js" type="text/javascript"></script>
	<script src="scripts/modernizr-2.6.2.js" type="text/javascript"></script>
</head>
<body>
    <form method="post" action="./" id="ctl01">
		<!--<div class="aspNetHidden">
			<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
			<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
			<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="wVwkWWw9cSYxwvro3jvzCVgOD2+MEulv+E1nXpvMJbs22sjTMXxbD/MPQX6UYBNFTEtAhFQGIJnSHRWUYS6yUjBZMYei19JjQEau9S5UAICqq/C9VccX3o+ARwHmT3weQUWUSO4wJ9//bHi8cktQ2kEKK7WXXciM3tSZ7HbQEt5vngVf1uPERFChJYJaUNEFBfCd7tigbuwHpuQ+o5I4Hg==" />
		</div>-->
		
		<script type="text/javascript">
		//<![CDATA[
			var theForm = document.forms['ctl01'];
			if(!theForm) {
				theForm = document.ctl01;
			}
			function __doPostBack(eventTarget, eventArgument) {
				if(!theForm.onsubmit || (theForm.onsubmit() != false)) {
					theForm.__EVENTTARGET.value = eventTarget;
					theForm.__EVENTARGUMENT.value = eventArgument;
					theForm.submit();
				}
			}
		 //]]>
		</script>
		<!--<script type="text/javascript">
		//<![CDATA[
		 if (typeof(Sys) === 'undefined')
		  throw new Error('Ładowanie struktury strony klienta ASP.NET Ajax nie powiodło się.');
		 //]]>
		</script>-->
		<script src="scripts/popper.min.js" type="text/javascript"></script>
		<script src="scripts/bootstrap.js" type="text/javascript"></script>
		<!--<script src="scripts/respond.js" type="text/javascript"></script>
		<script src="/bundles/WebFormsJs?v=AAyiAYwMfvmwjNSBfIMrBAqfU5exDukMVhrRuZ-PDU01" type="text/javascript"></script>-->
		<script type="text/javascript">
		//<![CDATA[
			Sys.WebForms.PageRequestManager._initialize('ctl00$ctl09', 'ctl01', [], [], [], 90, 'ctl00');
		 //]]>
		</script>
		
		<?php
		include("header.php");
		?>
		
		<div id="middle">
			<?php
			
			//Zapisanie rozdzielczości ekranu do ciasteczka
			if((!isset($_COOKIE['w'])) || (!isset($_COOKIE['h']))) {
				setScreenResolution();
			}
			
			//Pobranie id strony
			$pageId = null;
			if(isset($_GET['id'])) {
				$pageId = $_GET['id'];
			}
			//echo "pageId=".$pageId."<br />";	//test
			
			//Pobranie id formularza
			$postId = null;
			if(isset($_REQUEST['id'])) {
				$postId = $_REQUEST['id'];
			}
			//echo "postId=".$postId."<br />";	//test
			echo "<br /><br />";	//test
			
			switch($postId)
			{
				case "rejestracja":
					postRegister($path, $userSecurityDbName, $userDataDbName);
					break;
				case "logowanie":
					postLogin($path, $userSecurityDbName);
					break;
				case "odzyskajkonto":
					postRecoverAccount($path, $userSecurityDbName, $userDataDbName);
					break;
				case "zmianahasla":
					postChangePassword($path, $userSecurityDbName, $userDataDbName);
					break;
				case "zmienprofil":
					postEditProfile($path, $userSecurityDbName, $userDataDbName, $userData);
					break;
				
				case "wyslijwiadomoscsms":
					postSendTextMessage();
					break;
				case "zaawansowanaedycjabazydanych":
					postAdvancedDatabaseEdit($path);
					break;
				case "zmienustawieniaadministratora":
					postEditAdminSettings($path);
					break;
			}
			
			switch($pageId)
			{
				case "paneladministracyjny":
					include("account/adminpanel.php");
					break;
				case "listauzytkownikow":
					include("account/adminpanel.php");
					break;
				case "listazalogowanychuzytkownikow":
					include("account/adminpanel.php");
					break;
				case "salazaawansowany":
					include("account/adminpanel.php");
					break;
				case "listasalzaawansowany":
					include("account/adminpanel.php");
					break;
				case "listazarezerwowanychsalzaawansowany":
					include("account/adminpanel.php");
					break;
				case "dodajsale":
					include("account/adminpanel.php");
					break;
				case "edytujsale":
					include("account/adminpanel.php");
					break;
				case "usunsale":
					include("account/adminpanel.php");
					break;
				case "rezerwacjazaawansowany":
					include("account/adminpanel.php");
					break;
				case "listarezerwacjizaawansowany":
					include("account/adminpanel.php");
					break;
				case "dodajrezerwacjezaawansowany":
					include("account/adminpanel.php");
					break;
				case "potwierdzrezerwacje":
					include("account/adminpanel.php");
					break;
				case "anulujrezerwacje":
					include("account/adminpanel.php");
					break;
				case "uslugazaawansowany":
					include("account/adminpanel.php");
					break;
				case "listauslugzaawansowany":
					include("account/adminpanel.php");
					break;
				case "informacjesystemowe":
					include("account/systeminfo.php");
					break;
				case "testbramkisms":
					include("account/smsgatewaytest.php");
					break;
				case "zaawansowanaedycjabazydanych":
					include("account/adminpanel.php");
					break;
				case "ustawieniaadministratora":
					include("account/adminpanel.php");
					break;
				case "profilzaawansowany":
					include("account/adminpanel.php");
					break;
				case "paneluzytkownika":
					include("account/userpanel.php");
					break;
				case "sala":
					include("account/userpanel.php");
					break;
				case "listasal":
					include("account/userpanel.php");
					break;
				case "listazarezerwowanychsal":
					include("account/userpanel.php");
					break;
				case "rezerwacja":
					include("account/userpanel.php");
					break;
				case "listarezerwacji":
					include("account/userpanel.php");
					break;
				case "dodajrezerwacje":
					include("account/userpanel.php");
					break;
				case "usluga":
					include("account/userpanel.php");
					break;
				case "listauslug":
					include("account/userpanel.php");
					break;
				case "profil":
					include("account/profile.php");
					break;
				case "informacje":
					include("about.php");
					break;
				case "kontakt":
					include("contact.php");
					break;
				case "ciasteczka":
					include("cookies.php");
					break;
				case "rejestracja":
					include("account/register.php");
					break;
				case "aktywacja":
					include("account/activation.php");
					break;
				case "logowanie":
					include("account/login.php");
					break;
				case "edytujprofil":
					include("account/editprofile.php");
					break;
				case "wylogowanie":
					include("account/logout.php");
					break;
				case "automatycznewylogowanie":
					include("account/autologout.php");
					break;
				case "odzyskajkonto":
					include("account/recoveraccount.php");
					break;
				case "zmianahasla":
					include("account/pwchange.php");
					break;
				default:
					include("main.php");
					break;
			}
			
			?>
		</div>
		
		<?php
		include("footer.php");
		?>
	</form>
	
	<script type="text/javascript">
	//<![CDATA[
		jQuery.divanteCookies.render({
			privacyPolicy: true,
			cookiesPageURL: 'index.php?id=ciasteczka'
		});
	 //]]>
	</script>
</body>
</html>