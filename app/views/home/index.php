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
    <nav>
        <div class="logo">
            <img class="logo" src="<?php echo PUBLIC_FOLDER; ?>/images/logo.png" alt="logo-camagru"/>
        </div>
        <ul class="nav-links">
            <li><a href="<?php echo SERVER . '/home'; ?>">Home</a></li>
            <li><a href="#">Editing</a></li>
            <li><a href="<?php echo SERVER . '/help'; ?>">Help</a></li>
			<li><a href="<?php echo SERVER . '/about-us'; ?>">About us</a></li>
			<li><a href="<?php echo SERVER . '/signin'; ?>" id="btn-signin">Signin</a></li>
            <li><a href="<?php echo SERVER . '/signup'; ?>" id="btn-signup">Signup</a></li>
        </ul>
        <div class="btn-auth">
            <a href="<?php echo SERVER; ?>/signin" id="btn-signin">Signin</a>
            <a href="<?php echo SERVER; ?>/signup" id="btn-signup">Signup</a>
        </div>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
	</nav>
	<div class="jumbotron">
		<div class="container col-lg-6">
			<h1 class="display-4">Camagru</h1>
			<p class="lead">Make your pictures looks nice with our editor.</p>
			<hr class="my-4">
			<a class="btn btn-primary" href="#" role="button">Learn more</a>
		</div>
	</div>
	<footer>
		<p> gayoub © Camagru 2020 </p>
	</footer>
	<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
</body>
</html>