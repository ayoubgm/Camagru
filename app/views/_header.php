<header>
	<nav>
		<div class="logo-area">
			<a href="/"><img class="logo" src="/public/images/logo.png" alt="logo-camagru"/></a>
		</div>
		<ul class="nav-links">
			<li class="active-link"><a href="/">Home</a></li>
			<li><a href="/user/editing">Editing</a></li>
			<?php if ( $data['gallery'] ) { ?><li><a href="/gallery">Gallery</a></li><?php } ?>
			<li><a href="/help">Help</a></li>
			<li><a href="/aboutus">About us</a></li>
			<?php if ( !isset($_SESSION['userid']) ) { ?>
				<li><a href="/signin" id="btn-signin">Signin</a></li>
				<li><a href="/signup" id="btn-signup">Signup</a></li>
			<?php } else { ?>
				<li><a href="/user/profile" id="profile">Profile</a></li>
				<li><a href="/user/settings" id="settings">Settings</a></li>
				<li><a href="/user/logout" id="logout">Logout</a></li>
			<?php } ?>
		</ul>
		<div class="btn-auth">
			<?php if ( !isset($_SESSION['userid']) ) { ?>
				<a href="/signin" id="btn-signin">Signin</a>
				<a href="/signup" id="btn-signup">Signup</a>
			<?php } else { ?>
				<img src="/public/images/notification.png"? id="notif-img">
				<img src="<?php echo ( $userData['gender'] === "female" ) ? "/public/images/user-female.png" : "/public/images/user-male.png"; ?> " id="profile-img"/>
				<div class="dropdown">
					<ul>
						<li><a href="/user/profile"><img src="<?php echo ( $userData['gender'] == "female" ) ? "/public/images/profile-female.png" : "/public/images/profile-male.png"; ?>" id="profile"/>Profile</a></li>
						<li><a href="/user/settings"> <img src="/public/images/settings.png" id="settings"/>Settings</a></li>
						<li><a href="/user/logout"> <img src="/public/images/logout.png" id="logout"/>Logout</a></li>
					</ul>
				</div>
			<?php } ?>
		</div>
		<div class="burger">
			<div class="line1"></div>
			<div class="line2"></div>
			<div class="line3"></div>
		</div>
	</nav>
</header>