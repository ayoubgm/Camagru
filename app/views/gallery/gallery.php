<?php
   	if ( isset( $this->view_data['data'] ) ) {
		$data = $this->view_data['data'];
		$totalImages = $data['totalImages'];
		$imagePerPage = 5;
		$currentPage = $data['page'];
		$totalPages = ceil( $totalImages / $imagePerPage );
		$gallery = $data['gallery'];
		$userData = ( isset( $data['userData'] ) ) ? $data['userData'] : null;
		$userGallery = ( isset ( $data['userGallery'] ) ) ? $data['userGallery'] : null;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_header.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/gallery.css"/>
	<link rel="stylesheet" href="<?php echo PUBLIC_FOLDER; ?>/css/_footer.css"/>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.js"></script>
	<script src="<?php echo PUBLIC_FOLDER?>/js/jquery-3.5.1.min.js"></script>
	<title>Gallery</title>
</head>
<body>
	<?php require_once(VIEWS . "_header.php");?>
	<div class="row col-lg-12" id="gallery">
		<?php if ( count($gallery ) === 0 ) { ?>
			<p> <?php echo "No Edited images !"; ?> </p>
		<?php } else {
			foreach ( $gallery as $image ) {
		?>
			<div class="card" id="image">
				<div class="card-body">
					<div class="card-title bg-dark">
						<?php if ( $image['gender'] === "female" ) { ?>
							<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-female.png"? id="user-img" onclick="showMenu()">
						<?php } else { ?>
							<img src="<?php echo PUBLIC_FOLDER; ?>/images/user-male.png"? id="user-img" onclick="showMenu()">
						<?php } ?>
						<a id="user-link" href="<?php echo SERVER."/user/profile/username/".$image['username']; ?>">By <?php print( $image['username'] ); ?></a>
					</div>
				</div>
				<img src="<?php print( $image['src'] ); ?>" class="card-img">
				<div class="card-footer w-100">
					<div class="w-100">
						<div id="likes">
							<img id="icone-like" src="<?php echo PUBLIC_FOLDER; ?>/images/like-icone.png"/>
							<?php echo ( $image['countlikes'] == 0 ) ? "No Likes" : $image['countlikes'] ?>
						</div>
						<div id="comments">
							<img id="icone-comment" src="<?php echo PUBLIC_FOLDER; ?>/images/comment-icone.png"/>
							<?php echo $image['countcomments'] ?>
						</div>
					</div>
				</div>
			</div>
		<?php }
		} ?>
	</div>
	<!-- <?php require_once(VIEWS . "_footer.php");?> -->
</body>
<script src="<?php echo PUBLIC_FOLDER; ?>/js/_menu.js"></script>
<script>
	const btn_profile = document.querySelector("nav .btn-auth #profile-img")
	const menu = document.querySelector("nav .btn-auth .dropdown");
	const showMenu = () => {
		if ( menu.style.display == "none" ) { menu.style.display = "block"; }
		else { menu.style.display = "none"; }
	};
	
</script>
</html>