<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camagru</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/index.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php"); ?>
	<div class="jumbotron">
		<div class="container col-lg-6">
			<h1 class="display-4">
				<?php echo ( isset($_SESSION['userid']) ) ? "Welcome" : "Camagru"; ?>
			</h1>
			<p class="lead">Make your pictures looks nice with our editor.</p>
			<hr class="my-4">
			<a class="btn btn-primary" href="#" role="button">Learn more</a>
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