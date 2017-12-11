<script>
//<![CDATA[
	jQuery(function(){
		jQuery('#camera_wrap_logo').camera({
			height: '300px',
			time: 10000,
			transPeriod: 1500,
			pagination: true,
			thumbnails: true
		});
	});
 //]]>
</script>

<div class="container body-content">
	<div class="jumbotron">
		<div class="row">
			<div class="col-md-12">
				<p class="name_1">Facelike</p>
				<p class="name_2">Like it&nbsp;&nbsp;.&nbsp;&nbsp;.&nbsp;&nbsp;.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="logo">
					<div class="camera_wrap camera_azure_skin" id="camera_wrap_logo">
						<div data-thumb="images/thumbs/logo1.jpg" data-src="images/logo1.jpg"></div>
						<div data-thumb="images/thumbs/logo2.jpg" data-src="images/logo2.jpg"></div>
						<div data-thumb="images/thumbs/logo3.jpg" data-src="images/logo3.jpg"></div>
						<div data-thumb="images/thumbs/logo4.jpg" data-src="images/logo4.jpg"></div>
						<div data-thumb="images/thumbs/logo5.jpg" data-src="images/logo5.jpg"></div>
						<div data-thumb="images/thumbs/logo6.jpg" data-src="images/logo6.jpg"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-lef">
					<a href="index.php?id=informacje" class="btn btn-primary btn-lg_logo_1">Dowiedz się więcej &raquo;</a>
				</div>
				<div class="col-cen">
					<?php
					$zalogowany = !checksession();
					if($zalogowany) {
						?>
						<a href="index.php?id=rejestracja" class="btn btn-primary btn-lg_logo_2">Zacznij już dziś &raquo;</a>
						<?php
					}
					else {
						$superUser = checkadmin($path, $dbName, getidfromsession(), $userData);
						if($superUser) {
							//Widok dla zalogowanego administratora
							?>
							<a href="index.php?id=listazdjec" class="btn btn-primary btn-lg_logo_4">Zdjęcia &raquo;</a>
							<?php
						}
						else {
							//Widok dla zalogowanego użytkownika
							?>
							<a href="index.php?id=listazdjec" class="btn btn-primary btn-lg_logo_4">Zdjęcia &raquo;</a>
							<?php
						}
					}
					?>
				</div>
				<div class="col-rig">
					<?php
					if($zalogowany) {
						?>
						<a href="index.php?id=logowanie" class="btn btn-primary btn-lg_logo_3">Polub zdjęcie &raquo;</a>
						<?php
					}
					else {
						if($superUser) {
							//Widok dla zalogowanego administratora
							?>
							<a href="index.php?id=dodajzdjecie" class="btn btn-primary btn-lg_logo_5">Polub zdjęcie &raquo;</a>
							<?php
						}
						else {
							//Widok dla zalogowanego użytkownika
							?>
							<a href="index.php?id=dodajzdjecie" class="btn btn-primary btn-lg_logo_5">Polub zdjęcie &raquo;</a>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>