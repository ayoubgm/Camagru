<?php
	if ( isset( $this->view_data['data'] ) && !empty( $this->view_data['data'] ) ) {
		$data = $this->view_data['data'];
		$userData = ( isset( $data['userData'] ) ) ? $data['userData'] : null;
		$userGallery = ( isset( $data['userGallery'] ) ) ? $data['userGallery'] : null;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camagru</title>
	<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="/public/css/index.css"/>
	<link rel="stylesheet" href="/public/css/_header.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php"); ?>
	<div class="jumbotron">
		<div class="container col-lg-8">
			<h1 class="display-3">
				<?php echo ( isset($_SESSION['userid']) ) ? "Welcome" : ""; ?>
			</h1>
			<p class="lead">Make your pictures looks nice with our editor.</p>
			<hr class="my-4">
			<a class="btn btn-primary" href="#" role="button">Learn more</a>
		</div>
	</div>
	<?php require_once(VIEWS . "_footer.php"); ?>
	<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
	<script>
		const menu = document.querySelector("nav .btn-auth .dropdown");
		
		const showMenu = () => {
			if ( menu.style.display == "none" ) { menu.style.display = "block"; }
			else { menu.style.display = "none"; }
		};
	</script>
</body>
</html>