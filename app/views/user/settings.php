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
	<title>Settings</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/settings.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="container">
        <div class="card mt-5 text-center">
            <div class="card-body"> 
				<div class="row">
					<p class="card-title"><span>Settings</span></p>
				</div>
                <div class="row">
                    <div class="col-lg-6 bg-white m-2" id="choice">
                        <img src="<?php echo PUBLIC_FOLDER; ?>/images/change-password.png"/></br>
                        <a href="<?php echo SERVER; ?>/user/change_password">Change password</a>
                    </div>
                    <div class="col-lg-5 bg-white m-2" id="choice">
                        <img src="<?php echo PUBLIC_FOLDER; ?>/images/notif-email.png"/></br>
                        <a href="<?php echo SERVER; ?>/user/notifications_preferences">Notification email preference</a> 
                    </div>
                </div>
            </div>
        </div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
</body>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_userMenu.js"></script>
</html>
<?php
	}
?>