<?php
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /camagru_git/home");
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
	<title>Notifications preferences</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/notification_preference.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Notifications preferences</span>
				</p>
				<div id="icone" class="text-center">
					<img src="<?php echo PUBLIC_FOLDER; ?>/images/notifications_preferences.png"/></br>
				</div>
				<hr/>
				<div class="p-4">
					<form method="POST" action="<?php echo SERVER; ?>/user/notifications_preferences/notificationsemail/<?php echo ( $userData['notifEmail'] == '1' ) ? '0' : '1';?>">
						<div class="text-center">
							<div class="form-row edit">
								<input
									type="submit"
									class="offset-2 col-8 btn btn-outline-primary w-50 mt-5"
									<?php if ( $userData['notifEmail'] == "1" ) { ?>
										value="I want to stop receiving emails notifications"
									<?php } else { ?>
										value="I want to receive emails notifications"
									<?php } ?>
									name="btn-change-preference"
								/>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
</body>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script>
	const msg = document.getElementById("msg");
	const menu = document.querySelector("nav .btn-auth .dropdown");

	const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	}
	
</script>
</html>
<?php
	}
?>