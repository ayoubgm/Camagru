<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /camagru_git/home");
	} else {
		$userData = $this->view_data['data'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Profile</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/profile.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Profile</span>
					<a href="<?php echo SERVER; ?>/user/edit" class="btn btn-dark w-25" id="btn-edit">Edit</a>
				</p>
				<div class="full-name text-center">
					<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-male.png"/></br>
					</span><?php print($userData['firstname']); echo " "; print($userData['lastname']); ?>
				</div>
				<hr/>
				<div class="user-infos">
					<span id="field">Full name : </span><?php print($userData['firstname']); echo " "; print($userData['lastname']); ?></br>
					<span id="field">Username : </span><?php print($userData['username']) ?></br>
					<span id="field">Email : </span><?php print($userData['email']) ?></br>
					<span id="field">Gender : </span>male</br>
					<?php if ( $userData['address'] ) { ?>
						<span id="field">Address : </span><?php print($userData['address']); ?></br>
					<?php } ?>
					<span id="field">Created : </span><?php print($userData['createdat']); ?></br>
				</div>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
	<script>
		const btn_profile = document.querySelector("nav .btn-auth #profile-img")
		const menu = document.querySelector("nav .btn-auth .dropdown");

		const showMenu = () => {
			if ( menu.style.display == "none" ) { menu.style.display = "block"; }
			else { menu.style.display = "none"; }
		};
		
	</script>
</body>
</html>
<?php
	}
?>