<?php
	if ( isset( $this->view_data['data'] ) ) {
		$gallery = ( isset( $this->view_data['data']['gallery'] ) ) ? $this->view_data['data']['gallery'] : null;
		$userData = ( isset( $this->view_data['data']['userData'] ) ) ? $this->view_data['data']['userData'] : null;
		$userGallery = ( isset( $this->view_data['data']['userGallery'] ) ) ? $this->view_data['data']['userGallery'] : null;
		$countUnreadNotifs = ( isset( $this->view_data['data']["countUnreadNotifs"] ) ) ? $this->view_data['data']["countUnreadNotifs"] : 0 ;
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/user/editing.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Editing</title>
		<noscript>
			<p class="text-white">We're sorry but the application doesn't work properly without JavaScript enabled. Please enable it to continue.</p>
			<style>
				header { display: none; }
				div { display:none; }
				footer { display: none; }
			</style>
		</noscript>
	</head>
	<body>
		<?php require_once(VIEWS . "_header.php");?>
		<form id="form-save" action="/user/editing" method="post">
			<div id="main" class="row d-flex justify-content-around">
				<!-- Main section -->
				<div id="main-section" class="col-lg-8 row d-flex justify-content-start">
						<div id="video-area" class="col-lg-8">
							<video autoplay="true" id="videoElement"></video>
							<input class="btn btn-danger w-50 float-left" type="button" value="Capture" name="btn-capture" id="btn-capture" disabled/>
							<input id="fileInput" accept="image/png,image/jpg,image/jpeg" class="btn btn-warning w-50 float-right text-white" type="file" value="Upload" disabled/>
							<div class="text-center mt-5 pt-2" id="area-msg" style="font-weight: bold;">
								<span id="msg" class="p-5 <?php echo ( isset( $this->view_data["success"] ) && $this->view_data["success"] == "true" ) ? "text-success" : "text-danger"; ?>">
									<?php if ( isset($this->view_data["msg"]) ) echo $this->view_data["msg"];?>
								</span>
							</div>
						</div>
						<div id="stickers-area" class="col-lg-4 form-group" name="option">
							<h4>Stickers</h4>
							<hr/>
							<div class="row">
								<select id="stickers-select" class="col-6 form-control" name="sticker" multiple onchange="viewOption(this)">
									<option style="background-image: url(/public/images/stickers/sticker01.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker01.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker02.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker02.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker03.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker03.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker04.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker04.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker05.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker05.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker06.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker06.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker07.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker07.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker08.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker08.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker09.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker09.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker10.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker10.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker11.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker11.png"></option>
									<option style="background-image: url(/public/images/stickers/sticker12.png);" value="<?php echo PUBLIC_FOLDER; ?>/images/stickers/sticker12.png"></option>
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
							if ( count( $userGallery ) == 0 ) {
								echo "No edited images yet !";
							} else {
								foreach ( $userGallery as $image ) {
									?>
										<img style="width: 400px; height: 300px; margin-bottom: 10px" src="<?php echo $image['src'] ?>"/>
									<?php
								}
							}
						?>
					</div>
				</div>
			</div>
			<div class="model-bg">
				<div class="model row col-md-10 col-lg-8">
					<div id="canvas-area">
						<div class="row" id="model-header" >
							<h4>Save it to the gallery</h4>
							<img id="icon-cancel" src="/public/images/cancel.png" onclick="closeModel()"/>
						</div>
						<hr/>
						<div id="row" class="p-2">
							<canvas id="canvas" width="640" height="480" name="image" class="img-fluid"></canvas>	
							<canvas id="canvas-webcam" width="640" height="480" name="image" hidden></canvas>
						</div>
						<div id="area-description" class="row">
							<label for="description" class="col-lg-2 font-weight-bold">Description :</label>
							<input id="description" name="description" class="col-lg-10 w-100" autocomplete="off" required />
						</div>
						<div class="row d-flex justify-content-between px-5 py-1">
							<input type="hidden" name="token" value="<?php echo $_SESSION["token"] ?>"/>
							<input type="submit" name="btn-save" id="btn-save" class="btn btn-dark w-25" value="Save" />
							<input type="button" name="btn-cancel" id="btn-cancel" class="btn btn-warning w-25" value="Cancel" onclick="closeModel()"/>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
	<script type="text/javascript" src="/public/js/_header.js"></script>
	<script type="text/javascript" src="/public/js/user/editing.js"></script>
</html>