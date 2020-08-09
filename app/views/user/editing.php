<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Editing</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/editing.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div id="main" class="row d-flex justify-content-start">
		<!-- Main section -->
		<div id="main-section" class="col-lg-8 row d-flex justify-content-between">
			<div id="video-area" class="col-lg-8">
				<video autoplay="true" id="videoElement"></video>
				<input class="btn btn-danger w-50 float-left" type="button" value="Capture"/>
				<input class="btn btn-warning w-50 float-right" type="button" value="Upload"/>
			</div>
			<div id="stickers-area" class="col-lg-4">
				<h4>Predifined images </h4>
				<hr/>
				<select class=custom-select multiple>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker01.png);" value="01"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker02.png);" value="02"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker03.png);" value="03"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker04.png);" value="04"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker05.png);" value="05"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker06.png);" value="06"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker07.png);" value="07"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker08.png);" value="08"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker09.png);" value="09"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker10.png);" value="10"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker11.png);" value="11"></option>
					<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker12.png);" value="12"></option>
				</select>
			</div>
		</div>
		<!-- Side section -->
		<div id="side-section" class="col-lg-4 bg-dark">
			<h4>Edited images</h4>
			<hr/>
		</div>
	</div>
	<!-- <?php //require_once(VIEWS . "_footer.php"); ?> -->
	<script>
		const btn_profile = document.querySelector("nav .btn-auth #profile-img")
		const menu = document.querySelector("nav .btn-auth .dropdown");
		const video = document.querySelector("#videoElement");

		const showMenu = () => {
			if ( menu.style.display == "none" ) { menu.style.display = "block"; }
			else { menu.style.display = "none"; }
		};

		if (navigator.mediaDevices.getUserMedia) {
			navigator.mediaDevices.getUserMedia({ video: true })
			.then(function (stream) {
				video.srcObject = stream;
			})
			.catch(function (err0r) {
				console.log("Something went wrong!");
			});
		}
		
	</script>
</body>
</html>