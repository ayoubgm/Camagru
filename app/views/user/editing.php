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
	<form id="form-save" action="<?php echo SERVER; ?>/user/editing" method="post">
		<div id="main" class="row d-flex justify-content-around">
			<!-- Main section -->
			<div id="main-section" class="col-lg-8 row d-flex justify-content-start">
					<div id="video-area" class="col-lg-8">
						<video autoplay="true" id="videoElement"></video>
						<input class="btn btn-danger w-50 float-left" type="button" value="Capture" name="btn-capture" id="btn-capture" disabled/>
						<input class="btn btn-warning w-50 float-right" type="button" value="Upload"/>
					</div>
					<div id="stickers-area" class="col-lg-4 form-group" name="option">
						<h4>Predifined images </h4>
						<hr/>
						<div class="row">
							<select id="stickers-select" class="col-6 form-control" multiple onchange="viewOption(this)">
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker01.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker01.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker02.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker02.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker03.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker03.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker04.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker04.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker05.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker05.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker06.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker06.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker07.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker07.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker08.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker08.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker09.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker09.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker10.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker10.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker11.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker11.png"></option>
								<option style="background-image: url(<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker12.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker12.png"></option>
							</select>
							<div class="col-6">
								<div class="form-row">
									<label class="w-25" style="font-size: 12pt; font-weight: bold;">X : </label>
									<input type="number" class="form-control w-75" name="x" id="x-axis" min="0" max="640"/>
								</div>
								<div class="form-row">
									<label class="w-25" style="font-size: 12pt; font-weight: bold;">Y : </label>
									<input type="number" class="form-control w-75" name="y" id="y-axis" min="0" max="480"/>
								</div>
							</div>
						</div>
					</div>
			</div>
			<!-- Side section -->
			<div id="side-section" class="col-lg-4 bg-dark">
				<h4>Edited images</h4>
				<hr/>
				<textarea type="text" id="textarea" name="dataimage" readonly hidden></textarea>
			</div>
		</div>
		<div class="model-bg">
			<div class="model row">
				<div id="canvas-area">
					<div class="row" id="model-header" >
						<h4>Save it to the gallery</h4>
						<img id="icon-cancel" src="<?php echo PUBLIC_FOLDER; ?>/images/cancel.png"/>
					</div>
					<hr/>
					<div id="row" class="p-2">
						<canvas id="canvas" width="640" height="480" name="image"></canvas>	
					</div>
					<div class="row d-flex justify-content-between px-5 py-1">
						<input type="submit" name="btn-save" id="btn-save" class="btn btn-dark w-25" value="Save" disabled />
						<input type="button" name="btn-cancel" id="btn-cancel" class="btn btn-warning w-25" value="Cancel" />
					</div>
				</div>
			</div>
		</div>
	</form>

	<?php require_once(VIEWS . "_footer.php"); ?>
	<script>
		const menu = document.querySelector("nav .btn-auth .dropdown");
		const video = document.querySelector("#videoElement");
		const canvas = document.getElementById('canvas');
		const option = document.getElementById('stickers-select');
		const x = document.getElementById('x-axis');
		const y = document.getElementById('y-axis');
		const btn_capture = document.getElementById('btn-capture');
		const btn_save = document.getElementById('btn-save');
		const btn_cancel = document.getElementById('btn-cancel');
		const form = document.getElementById('form-save');
		const modelBG = document.querySelector('.model-bg');
		const modelClose = document.querySelector('#icon-cancel');
		const textarea = document.getElementById('textarea');
		let context = canvas.getContext('2d');
		let base_image = new Image();

		const showMenu = () => {
			if ( menu.style.display == "none" ) { menu.style.display = "block"; }
			else { menu.style.display = "none"; }
		};

		if ( navigator.mediaDevices.getUserMedia ) {
			navigator.mediaDevices.getUserMedia({ 'video': true })
			.then(( stream ) => { video.srcObject = stream; })
			.catch(( error ) => { console.log("Something went wrong!"); });
		}

		const viewOption = ( option ) => {
			if ( option.value != "" ) {
				btn_capture.removeAttribute('disabled');
				base_image.src = option.value;
			} else {
				btn_capture.setAttribute('disabled', 'on');
			}
		}

		btn_capture.addEventListener('click', () => {
			context.drawImage(video, 0, 0, 640, 480);
			context.drawImage(base_image, x.value, y.value, 150, 120);
			modelBG.classList.add('active-model');
		});

		modelClose.addEventListener('click', () => { modelBG.classList.remove('active-model'); });
		btn_cancel.addEventListener('click', () => { modelBG.classList.remove('active-model'); });

		form.addEventListener('submit', () => {
			let dataUrl = canvas.toDataURL();

			textarea.value = dataUrl;
			return true;
		});
		
	</script>
</body>
</html>