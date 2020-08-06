<?php
	session_start();
	if ( !isset($_SESSION['userid']) ) {
		header("Location: /camagru_git/home");
	} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Camagru</title>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/profile.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
</head>
<body>
	<?php require_once(VIEWS . "_header.php"); ?>
	<div class="container">
		<div class="card offset-lg-2 col-lg-8">
			<div class="card-body">
				<p class="card-title">
					<span>Profile</span>
					<a href="<?php echo SERVER; ?>" class="btn btn-dark w-25 f-righ" id="btn-edit">Edit</a>
				</p>
				<div class="full-name text-center">
					<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-male.png"/></br>
					Ayoub guismi
				</div>
				<hr/>
				<div class="user-infos">
					<span id="field">Full name : </span>Ayoub guismi</br>
					<span id="field">Username : </span>aguismi</br>
					<span id="field">Email : </span>i.guismi@gmail.com</br>
					<span id="field">Gender : </span>male</br>
					<span id="field">Address : </span>lakjskljsnkjsd ksjdnsd </br>
					<span id="field">Created : </span>2020-03-03 </br>
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