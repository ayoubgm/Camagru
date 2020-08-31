<?php
   	if ( isset( $this->view_data['data'] ) ) {
		$data = $this->view_data['data'];
		$gallery = $data['gallery'];
		$userData = $data['userData'];
		$userGallery = $data['userGallery'];
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/usergallery.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.js"></script>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.min.js"></script>
	<title>User gallery</title>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="card w-100" id="main-model">
		<div class="card-body">
			<div class="card-title">
				<p class="text-dark" id="title">
					<?php echo ( $this->view_data['username'] == $userData['username'] ) ? "My gallery" : $this->view_data['username']." gallery"; ?>
				</p>
				<hr>
			</div>
			<div class="row col-lg-12">
				<?php if ( count($userGallery ) === 0 ) { ?>
					<p> <?php echo "No Edited images !"; ?> </p>
				<?php } else {
					foreach ( $userGallery as $image ) {
				?>
					<div class="card" id="image">
						<div class="card-body">
							<div class="card-title row">
								<div class="col-9">
									<?php if ( $image['gender'] === "female" ) { ?>
									<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-female.png"? id="user-img" onclick="showMenu()">
									<?php } else { ?>
									<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-male.png"? id="user-img" onclick="showMenu()">
									<?php } ?>
									By <?php print( $image['username'] ); ?>
								</div>
								<div class="col-3">
									<?php if ( $_SESSION['userid'] == $image['userid'] ) { ?>
										<img
											id="delete-img"
											src="<?php echo PUBLIC_FOLDER; ?>/images/delete-image.png"
											name="<?php echo SERVER."/gallery/delete/id/".$image['id']; ?>"
											onclick="activeModel()"
										/>
									<?php } ?>
								</div>
							</div> 
						</div>
						<img src="<?php print( $image['src'] ); ?>" class="card-img">
						<div class="card-footer w-100">
							<div class="w-100">
								<div id="likes">
									<img id="icone-like" src="<?php echo PUBLIC_FOLDER."/images/like-icone.png"; ?>"/>
								</div>
								<div id="comments">
									<img id="icone-comment" src="<?php echo PUBLIC_FOLDER."/images/comment-icone.png"; ?>"/>
								</div>
							</div>
						</div>
					</div>
				<?php } } ?>
			</div>
		</div>
	</div>
	<div class="model-bg" >
		<div class="model">
			<div class="row" id="model-header" >
				<div class="col-8" id="title">
					<h6>Are you sure you want to delete this picture ?</h6>
				</div>
				<div class="col-4" id="close">
					<img id="icon-cancel" src="<?php echo PUBLIC_FOLDER."/images/cancel.png"; ?>"/>
				</div>
			</div>
			<hr/>
			<div class="row px-5">
				<a name="btn-delete" id="btn-delete" class="btn btn-danger w-50">Delete</a>
				<input type="button" name="btn-cancel" id="btn-cancel" class="btn btn-dark w-50" value="Cancel" />
			</div>
		</div>
	</div>
	<!-- <?php require_once(VIEWS . "_footer.php");?> -->
</body>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script>
	const btn_profile = document.querySelector("nav .btn-auth #profile-img")
	const menu = document.querySelector("nav .btn-auth .dropdown");
	const modelBG = document.querySelector('.model-bg');
	const modelClose = document.querySelector('#icon-cancel');
	const btnDeleteImg = document.getElementById("delete-img");
	const btnDelete = document.getElementById("btn-delete");
	const btnCancel = document.getElementById("btn-cancel");

	const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	};

	const activeModel = () => {
		modelBG.classList.add('active-model');
		btnDelete.href = btnDeleteImg.name;
	};

	modelClose.addEventListener('click', () => { modelBG.classList.remove('active-model'); });
	btnCancel.addEventListener('click', () => { modelBG.classList.remove('active-model'); });
	
</script>
</html>