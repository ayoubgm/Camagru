<?php
	$data = $this->view_data['data'];
	$userData = $data['userData'];
	$countUnreadNotifs = $data["countUnreadNotifs"];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Notifications preferences</title>
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/user/notification_preference.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php");?>
		<div class="container">
			<div class="card offset-lg-2 col-lg-8">
				<div class="card-body">
					<p class="card-title">
						<span>Notifications preferences</span>
					</p>
					<div id="icone" class="text-center">
						<img src="/public/images/notifications_preferences.png"/></br>
					</div>
					<hr/>
					<div class="p-4">
						<form method="POST" action="/user/notifications_preferences/notificationsemail/<?php echo ( $userData['notifEmail'] == '1' ) ? '0' : '1';?>">
							<div class="text-center">
								<div class="form-row edit">
									<input type="hidden" name="token" value="<?php echo $_SESSION["token"] ?>"/>
									<input
										type="submit"
										class="offset-2 col-8 btn btn-outline-primary w-50 mt-5"
										value="<?php echo ( $userData['notifEmail'] == "1" ) ? "I want to stop receiving emails notifications" : "I want to receive emails notifications"; ?>"
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
	<script type="text/javascript" src="/public/js/_header.js"></script>
</html>