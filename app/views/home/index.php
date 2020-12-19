<?php
	if ( isset( $this->view_data['data'] ) && !empty( $this->view_data['data'] ) ) {
		$data = $this->view_data['data'];
		$userData = ( isset( $data['userData'] ) ) ? $data['userData'] : null;
		$userGallery = ( isset( $data['userGallery'] ) ) ? $data['userGallery'] : null;
		$countUnreadNotifs = $data["countUnreadNotifs"];
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Camagru</title>
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/home/index.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php"); ?>
		<div class="jumbotron">
			<div class="container col-lg-10">
				<h1 class="display-4">
					<?php echo ( isset($_SESSION['userid']) ) ? "Welcome ".$userData["username"] : "Welcome"; ?>
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