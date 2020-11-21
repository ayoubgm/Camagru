<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /home");
	} else {
		if ( isset( $this->view_data['data'] ) ) {
			$data = $this->view_data['data'];
			$gallery = $data['gallery'];
			$userData = ( isset( $data['userData'] ) ) ? $data['userData'] : null;;
			$userGallery = ( isset( $data['userGallery'] ) ) ? $data['userGallery'] : null;;
		}
?>
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
						<input class="btn btn-danger w-100 float-left" type="button" value="Capture" name="btn-capture" id="btn-capture" disabled/>
						<input id="fileInput" accept="image/png,image/jpg,image/jpeg" class="btn btn-warning w-100 float-right text-white" type="file" value="Upload" disabled/>
						<div class="text-center mt-5 pt-2" id="area-msg" style="font-weight: bold;">
							<span id="msg" class="p-5
									<?php 
										if ( isset( $this->view_data['success'] ) && $this->view_data['success'] == "true" ) { echo "text-success"; }
										else { echo "text-danger"; }
									?>
								">
								<?php if ( isset($this->view_data['msg']) ) echo $this->view_data['msg'];?>
							</span>
						</div>
					</div>
					<div id="stickers-area" class="col-lg-4 form-group" name="option">
						<h4>Stickers</h4>
						<hr/>
						<div class="row">
							<select id="stickers-select" class="col-6 form-control" name="sticker" multiple onchange="viewOption(this)">
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
				<div class="area-images">
					<?php 
						if ( count( $data['userGallery'] ) === 0 ) {
							echo "No edited images yet !";
						} else {
							foreach ( $data['userGallery'] as $image ) {
								?>
									<img style="width: 400px; height: 300px; margin-bottom: 10px" src="<?php echo $image['src'] ?>"/>
								<?php
							}
						}
					?>
				</div>`
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
						<canvas id="canvas-webcam" width="640" height="480" name="image" hidden></canvas>
					</div>
					<div class="row d-flex justify-content-between px-5 py-1">
						<input type="submit" name="btn-save" id="btn-save" class="btn btn-dark w-25" value="Save" />
						<input type="button" name="btn-cancel" id="btn-cancel" class="btn btn-warning w-25" value="Cancel" />
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php require_once(VIEWS . "_footer.php"); ?>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script>
	const menu = document.querySelector("nav .btn-auth .dropdown");
	const video = document.querySelector("#videoElement");
	const fileInput = document.getElementById('fileInput');
	const canvas = document.getElementById('canvas');
	const canvasWebcam = document.getElementById('canvas-webcam');
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
	let contextWebcam = canvasWebcam.getContext('2d');
	let base_image = new Image();
	
	// display user menu
	const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	};
	
	// Web cam
	if ( navigator.mediaDevices.getUserMedia ) {
		navigator.mediaDevices.getUserMedia({ 'video': true })
		.then(( stream ) => {
			video.srcObject = stream;
			btn_capture.removeAttribute('disabled');
		})
		.catch(( error ) => {
			console.log("If your camera doesn't work you can upload an image !");
			btn_capture.setAttribute('disabled', 'on');
		});
	}

	const viewOption = ( option ) => {
		if ( option.value != "" ) {
			navigator.mediaDevices.getUserMedia({ 'video': true })
			.then(( stream ) => {
				video.srcObject = stream;
				fileInput.removeAttribute('disabled');
				btn_capture.removeAttribute('disabled');
			})
			.catch(( error ) => {
				console.log("If your camera doesn't work you can upload an image !");
				fileInput.removeAttribute('disabled');
			});
			base_image.src = option.value;
		} else {
			btn_capture.setAttribute('disabled', 'on');
		}
	}
	// click on btn capture will mix two images and display final image with options save or cancel
	btn_capture.addEventListener('click', () => {
		contextWebcam.drawImage(video, 0, 0, 640, 480);
		context.drawImage(video, 0, 0, 640, 480);
		context.drawImage(base_image, x.value, y.value, 150, 120);
		modelBG.classList.add('active-model');
	});
	// click on X and btn cancel with hide model
	modelClose.addEventListener('click', () => {
		modelBG.classList.remove('active-model');
		fileInput.value = "";
	});
	btn_cancel.addEventListener('click', () => {
		fileInput.files = "";
		modelBG.classList.remove('active-model');
	});

	// Event listener for image upload 
	fileInput.addEventListener('change', (e) => {
		if( e.target.files ) {
			let file = e.target.files[0];
			const fsize = e.target.files[0].size; 
			const sizefile = Math.round((fsize / 1024)); 
			
			// The size of the file. 
			if ( sizefile >= 4096 ) { 
				alert("File too Big, please select a file less than 4mb"); 
			} else {
				var reader  = new FileReader();
				reader.readAsDataURL( file );

				reader.onloadend = (e) => {
					var myImage = new Image(); // Creates image object
					myImage.src = e.target.result; // Assigns converted image to image object

					myImage.onload = (ev) => {
						if ( ev.width > 640 || ev.height > 480 ) {
							alert("Image must have 640X480"); 
						} else {
							contextWebcam.drawImage(myImage, 0, 0, 640, 480);
							context.drawImage(myImage, 0, 0, 640, 480);
							context.drawImage(base_image, x.value, y.value, 150, 120);
							modelBG.classList.add('active-model');
						}
					}
				}
			}
		}
	});

	form.addEventListener('submit', () => {
		let dataUrl = canvasWebcam.toDataURL();
		textarea.value = dataUrl;
		return true;
	});
	
</script>
</body>
</html>

<?php
	}
?>