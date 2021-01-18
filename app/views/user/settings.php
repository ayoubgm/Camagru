<?php
	if ( isset( $this->view_data['data'] ) ) {
		$gallery = ( isset( $this->view_data['data']['gallery'] ) ) ? $this->view_data['data']['gallery'] : null;
		$userData = ( isset( $this->view_data['data']['userData'] ) ) ? $this->view_data['data']['userData'] : null;
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
		<link rel="stylesheet" href="/public/css/user/settings.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Settings</title>
		<noscript>
			<p class="text-white">We're sorry but the application doesn't work properly without JavaScript enabled. Please enable it to continue.</p>
			<style>
				header { display: none; }
				div { display:none; }
				footer { display: none; }
			</style>
		</noscript>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php");?>
		<div class="container">
			<div class="card text-center">
				<div class="card-body"> 
					<div class="row">
						<p class="card-title"><span>Settings</span></p>
					</div>
					<div class="row">
						<div class="col-lg-6 bg-white m-2" id="choice">
							<img src="/public/images/change-password.png"/></br>
							<a href="<?php echo SERVER; ?>/user/change_password">Change password</a>
						</div>
						<div class="col-lg-5 bg-white m-2" id="choice">
							<img src="/public/images/notif-email.png"/></br>
							<a href="<?php echo SERVER; ?>/user/notifications_preferences">Notification email preference</a> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
	<script type="text/javascript" src="/public/js/_header.js"></script>
</html>