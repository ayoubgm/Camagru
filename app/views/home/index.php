<?php
	if ( isset( $this->view_data['data'] ) ) {
		$gallery = ( isset( $this->view_data['data']['gallery'] ) ) ? $this->view_data['data']['gallery'] : null;
		$userData = ( isset( $this->view_data['data']['userData'] ) ) ? $this->view_data['data']['userData'] : null;
		$countUnreadNotifs = ( isset( $this->view_data['data']["countUnreadNotifs"] ) ) ? $this->view_data['data']["countUnreadNotifs"] : 0 ;
	}
	$token = ( isset( $_SESSION["token"] ) && !empty( $_SESSION["token"] ) ) ? $_SESSION["token"] : null;
	$logged = ( isset( $userData ) ) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/home/index.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Camagru</title>
		<noscript>
			<p class="text-white">We're sorry but the application doesn't work properly without JavaScript enabled. Please enable it to continue.</p>
			<style>
				header { display: none; }
				div { display:none; }
				footer { display: none; }
			</style>
		</noscript>
		<script>
			const userToken = "<?php echo $token; ?>";
			const logged = <?php echo ( $logged ) ? "true" : "false"; ?>;
		</script>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php"); ?>
		<div class="jumbotron">
			<div class="container col-lg-10">
				<?php if ( isset( $this->view_data["success"] ) && $this->view_data["success"] == "false" ) { ?>
					<div class="alert alert-danger">
						<?php echo $this->view_data["msg"]; ?>
					</div>
				<?php } ?>
				<h1 class="display-4">
					<?php echo ( isset( $_SESSION['userid'] ) ) ? "Welcome " . $userData["username"] : "Welcome"; ?>
				</h1>
				<p class="lead">Make your pictures looks nice with our editor.</p>
				<hr class="my-4">
				<a class="btn btn-primary" href="#" role="button">Learn more</a>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php"); ?>
	</body>
	<script type="text/javascript" src="/public/js/_header.js"></script>
</html>