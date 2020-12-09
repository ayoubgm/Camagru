<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /home");
	} else {
		$data = $this->view_data['data'];
		$userData = $data['userData'];
		$userGallery = $data['userGallery'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/profile.css"/>
	<link rel="stylesheet" href="/public/css/_header.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Profile</span>
					<?php if ( $_SESSION['userid'] == $userData['id'] ) { ?>
						<a href="<?php echo SERVER; ?>/user/edit" class="btn btn-dark w-25" id="btn-edit">
							<img id="icon-edit" src="/public/images/icone-edit.png"/>Edit
						</a>
					<?php } ?>
				</p>
				<div class="full-name text-center mt-5">
					<img src="/public/images/user-profile.png"/></br>
					</span><?php print($userData['firstname']); echo " "; print($userData['lastname']); ?>
				</div>
				<hr/>
				<a href="<?php echo SERVER."/gallery/user/username/".$userData['username']; ?>" id="link-gallery" class="btn btn-dark float-right">
					<img src="<?php echo PUBLIC_FOLDER ?>/images/gallery.png">
					Link to gallery
				</a>
				<div class="user-infos">
					<span id="field">Full name : </span><?php print($userData['firstname']); echo " "; print($userData['lastname']); ?></br>
					<span id="field">Username : </span><?php print($userData['username']); ?></br>
					<span id="field">Email : </span><?php print($userData['email']); ?></br>
					<span id="field">Gender : </span><?php print($userData['gender']); ?></br>
					<?php if ( $userData['address'] ) { ?>
						<span id="field">Address : </span><?php print($userData['address']); ?></br>
					<?php } ?>
					<span id="field">Created : </span><?php print($userData['createdat']); ?></br>
				</div>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
</body>
<script src="/public/js/_menu.js"></script>
<script src="/public/js/_userMenu.js"></script>
</html>
<?php
	}
?>