<?php
$autoLogoutTime = getOneCookie('autoLogoutTime');

$error = "Upłynęło ".$autoLogoutTime." minut bezczynności systemu<br />Użytkownik został wylogowany";

?>
<div class="auto_logout_page" id="auto_logout-div"></div>

<script type="text/javascript">
//<![CDATA[
	var documentHeight = document.documentElement.clientHeight;
	documentHeight = documentHeight - 130;
	
	var div = document.getElementById('auto_logout-div');
	with (div.style) {
		height = documentHeight+'px';
	}
	//]]>
</script>

<script type="text/javascript">
//<![CDATA[
	swal( {
			title: 'Bezczynność systemu',
			text: '<?php echo $error; ?>\n',
			type: 'error',
			timer: 5000,
			showConfirmButton: false,
			html: true
		},
		function() {
			window.location.href = 'index.php';
		}
	);
	//]]>
</script>
