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
		<title>Profile</title>
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/user/profile.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php");?>
		<div class="container">
			<div class="card offset-lg-2 col-lg-8">
				<div class="card-body">
					<p class="card-title">
						<span>Profile</span>
						<?php if ( $_SESSION['userid'] == $userData['id'] ) { ?>
							<a
								id="btn-edit"
								class="btn btn-outline-dark w-25 float-right"
								href="/user/edit"
							>
								<img id="icon-edit" src="/public/images/icone-edit.png"/>Edit
							</a>
						<?php } ?>
					</p>
					<div class="full-name text-center mt-5">
						<img src="/public/images/user-profile.png"/></br>
						</span><?php print($userData['firstname']); echo " "; print($userData['lastname']); ?>
						<hr/>
					</div>
					<div class="user-infos">
						<span id="field">Full name : </span><?php print($userData['firstname']); echo " "; print($userData['lastname']); ?></br>
						<span id="field">Username : </span><?php print($userData['username']); ?></br>
						<span id="field">Email : </span><?php print($userData['email']); ?></br>
						<span id="field">Gender : </span><?php print($userData['gender']); ?></br>
						<?php if ( $userData['address'] ) { ?>
							<span id="field">Address : </span><?php print($userData['address']); ?></br>
						<?php } ?>
						<span id="field">Created at : </span><?php print($userData['createdat']); ?></br>
					</div>
				</div>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
	<script type="text/javascript" src="/public/js/_header.js"></script>
</html>