<nav>
	<div class="logo-area">
		<a href="/">
			<img class="logo" src="/public/images/logo.png" alt="logo-camagru"/>
		</a>
	</div>
	<ul class="nav-links">
		<li class="active-link"><a href="/">Home</a></li>
		<li><a href="/user/editing">Editing</a></li>
		<?php if ( isset($data['gallery']) && count( $data['gallery'] ) !== 0 ) { ?>
			<li><a href="/gallery">Gallery</a></li>
		<?php } ?>
		<li><a href="/help">Help</a></li>
		<li><a href="/aboutus">About us</a></li>
		<li><a href="/signin" id="btn-signin">Signin</a></li>
		<li><a href="/signup" id="btn-signup">Signup</a></li>
	</ul>
	<div class="btn-auth">
		<?php if ( !isset($_SESSION['userid']) ) { ?>
			<a href="/signin" id="btn-signin">Signin</a>
			<a href="/signup" id="btn-signup">Signup</a>
		<?php } else { ?>
			<img src="/public/images/notification.png"? id="notif-img">
			<?php if ( $userData['gender'] === "female" ) { ?>
				<img src="/public/images/user-female.png"? id="profile-img" onclick="showMenu()">
			<?php } else { ?>
				<img src="/public/images/user-male.png"? id="profile-img" onclick="showMenu()">
			<?php } ?>
			<div class="dropdown">
				<ul>
					<?php if ( isset( $userGallery ) && !empty( $userGallery ) ) { ?>
						<li>
							<a href="/gallery/user/username/<?php echo $userData['username'] ?>">
								<img src="/public/images/gallery-icone.png" id="gallery"/> My gallery
							</a>
						</li>
					<?php } ?>
					<li>
						<a href="/user/profile">
							<?php if ( $userData['gender'] ==="female" ) { ?>
								<img src="/public/images/profile-female.png" id="profile"/>
							<?php } else { ?>
								<img src="/public/images/profile-male.png" id="profile"/>
							<?php } ?>
							Profile
						</a>
					</li>
					<li>
						<a href="/user/settings">
							<img src="/public/images/settings.png" id="settings"/>
							Settings
						</a>
					</li>
					<li>
						<a href="/user/logout">
							<img src="/public/images/logout.png" id="logout"/>
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