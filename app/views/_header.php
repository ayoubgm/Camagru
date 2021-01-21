<header>
	<div id="alert-msg" class="alert"></div>
	<nav>
		<div class="logo-area">
			<a href="/"><img class="logo" src="/public/images/logo-app.png" alt="logo-camagru"/></a>
		</div>
		<ul class="nav-links">
			<li class="active-link"><a href="/">Home</a></li>
			<li><a href="/user/editing">Editing</a></li>
			<li><a href="/gallery">Gallery</a></li>
			<?php if ( !isset($_SESSION['userid']) ) { ?>
				<li><a href="/signin" id="btn-signin">Signin</a></li>
				<li><a href="/signup" id="btn-signup">Signup</a></li>
			<?php } else { ?>
				<li><a href="/user/profile" id="profile">Profile</a></li>
				<li><a href="/user/settings" id="settings">Settings</a></li>
				<li><a href="#" id="logout" onclick="logout();">Logout</a></li>
			<?php } ?>
		</ul>
		<div class="btn-auth m-0">
			<?php if ( !isset($_SESSION['userid']) ) { ?>
				<a href="/signin" id="btn-signin">Signin</a>
				<a href="/signup" id="btn-signup">Signup</a>
			<?php } else { ?>
				<div id="area-notifications">
			 		<img src="/public/images/notification.png"? id="notif-img">
				 	<span id="countNotifs"><?php echo $countUnreadNotifs; ?></span>
					<div id="notifications" style="display: none;">
						<div id="title"><span id="title">Notifications</span></div>
						<hr>
						<div id="manage-notifications">
							<?php if ( $countUnreadNotifs != 0 ) { ?>
								<span id="see-all" class="float-left" onclick="readAllUserNotifs();">read all</span>
							<?php } ?>
							<span id="delete-all" class="float-right" onclick="deleteAllUserNotifs();">delete all</span>
						</div>
						<hr>
						<div id="notifications-user"></div>
					</div>
				</div>
				<div id="area-user">
					<img src="<?php echo ( $userData['gender'] == "female" ) ? "/public/images/user-female.png" : "/public/images/user-male.png"; ?> " id="profile-img"/>
					<div class="dropdown" style="display: none;">
						<ul>
							<li><a href="/user/profile"><img src="<?php echo ( $userData['gender'] == "female" ) ? "/public/images/profile-female.png" : "/public/images/profile-male.png"; ?>" id="profile"/>Profile</a></li>
							<li><a href="/user/settings"> <img src="/public/images/settings.png" id="settings"/>Settings</a></li>
							<li><a href="#" onclick="logout();"> <img src="/public/images/logout.png" id="logout"/>Logout</a></li>
						</ul>
					</div>
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