<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /home");
	} else {
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
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/deletepic.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.js"></script>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.min.js"></script>
	<title>Delete picture</title>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="row offset-lg-3 col-md-6" id="gallery">
		<div class="card" id="image">
			<div class="card-body">
				<div class="card-title row">
                    Delete an edited picture
                </div>
                <div class="main">
					<p id="msg" class="
						<?php
							if ( isset( $this->view_data['success'] ) ) {
								echo ( $this->view_data['success'] == "true" ) ? "text-success" : "text-danger";
							}
						?>
						"
					>
					<?php if ( isset( $this->view_data['msg'] ) ) {
							echo $this->view_data['msg'];
						}
					?>
					</p>
                </div>
			</div>
			<div class="card-footer">
				<a href="<?php echo SERVER."/gallery/user/username/".$userData['username']; ?>">Back</a>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php");?>
</body>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script>
	const btn_profile = document.querySelector("nav .btn-auth #profile-img")
	const menu = document.querySelector("nav .btn-auth .dropdown");

	const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	};

</script>
</html>

<?php
	}
?>