<?php
//Zapisanie nowego statusu i czasu ostatniej aktywności użytkownika do bazy danych
changeloginstatus($path, $userSecurityDbName, getidfromsession(), 5, false);

//Wyloguj
session_destroy();

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
//echo '<p class="success">Zostałeś wylogowany! Możesz przejść na <a href="index.php">stronę główną</a></p>';
?>
