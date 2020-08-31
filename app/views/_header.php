<nav>
	<div class="logo">
		<img class="logo" src="<?php echo PUBLIC_FOLDER; ?>/images/logo.png" alt="logo-camagru"/>
	</div>
	<ul class="nav-links">
		<li><a class="active-link" href="<?php echo SERVER . '/home'; ?>">Home</a></li>
		<li><a href="<?php echo SERVER . '/user/editing'; ?>">Editing</a></li>
		<?php if ( isset($data['gallery']) ) {
				if ( count( $data['gallery'] ) !== 0 ) {
		?>
			<li><a href="<?php echo SERVER . '/gallery/index'; ?>">Gallery</a></li>
		<?php } } ?>
		<li><a href="<?php echo SERVER . '/help'; ?>">Help</a></li>
		<li><a href="<?php echo SERVER . '/aboutus'; ?>">About us</a></li>
		<li><a href="<?php echo SERVER . '/signin'; ?>" id="btn-signin">Signin</a></li>
		<li><a href="<?php echo SERVER . '/signup'; ?>" id="btn-signup">Signup</a></li>
	</ul>
	<div class="btn-auth">
		<?php if ( !isset($_SESSION['userid']) ) { ?>
			<a href="<?php echo SERVER; ?>/signin" id="btn-signin">Signin</a>
			<a href="<?php echo SERVER; ?>/signup" id="btn-signup">Signup</a>
		<?php } else { ?>
			<img src="<?php echo PUBLIC_FOLDER; ?>/images/notification.png"? id="notif-img">
			<?php if ( $userData['gender'] === "female" ) { ?>
				<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-female.png"? id="profile-img" onclick="showMenu()">
			<?php } else { ?>
				<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-male.png"? id="profile-img" onclick="showMenu()">
			<?php } ?>
			<div class="dropdown">
				<ul>
					<?php if ( isset( $userGallery ) && !empty( $userGallery ) ) { ?>
						<li>
							<a href="<?php echo SERVER; ?>/gallery/user/username/<?php echo $userData['username'] ?>">
								<img src="<?php echo PUBLIC_FOLDER; ?>/images/gallery-icone.png" id="gallery"/> My gallery
							</a>
						</li>
					<?php } ?>
					<li>
						<a href="<?php echo SERVER; ?>/user/profile">
							<?php if ( $userData['gender'] ==="female" ) { ?>
								<img src="<?php echo PUBLIC_FOLDER; ?>/images/profile-female.png" id="profile"/>
							<?php } else { ?>
								<img src="<?php echo PUBLIC_FOLDER; ?>/images/profile-male.png" id="profile"/>
							<?php } ?>
							Profile
						</a>
					</li>
					<li>
						<a href="<?php echo SERVER; ?>/user/settings">
							<img src="<?php echo PUBLIC_FOLDER; ?>/images/settings.png" id="settings"/>
							Settings
						</a>
					</li>
					<li>
						<a href="<?php echo SERVER; ?>/user/logout">
							<img src="<?php echo PUBLIC_FOLDER; ?>/images/logout.png" id="logout"/>
							Logout
						</a>
					</li>
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