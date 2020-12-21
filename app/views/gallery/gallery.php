<?php
	$data = $this->view_data["data"];
	$userData = ( isset( $data['userData'] ) ) ? $data['userData'] : null;
	$countUnreadNotifs = $data["countUnreadNotifs"];
	$totalImages = $data['totalImages'];
	$imagePerPage = 5;
	$currentPage = $data['page'];
	$totalPages = ceil( $totalImages / $imagePerPage );
	$gallery = $data['gallery'];

	function		searchForMyLike ( $users, $userid ) {
		foreach ( $users as $key => $value ) {
			if ( $value["id"] == $userid ) { return true; }
		}
		return false;
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="/public/images/logo.png">
		<link rel="stylesheet" href="/public/css/bootstrap.min.css"/>
		<link rel="stylesheet" href="/public/css/_header.css"/>
		<link rel="stylesheet" href="/public/css/gallery/gallery.css"/>
		<link rel="stylesheet" href="/public/css/_footer.css"/>
		<title>Gallery</title>
		<script> const connectedUser = <?php if ( isset( $userData ) ) { echo $userData["id"]; } ?>;</script>
	</head>
	<body onload="getNotifications();">
		<?php require_once(VIEWS . "_header.php");?>
		<div class="row col-12" id="gallery">
			<?php if ( count( $gallery ) === 0 ) { ?>
				<p> <?php echo "No Edited images !"; ?> </p>
			<?php } else { foreach ( $gallery as $image ) { ?>
			<div class="card col-md-4 col-lg-5 col-xl-3" id="image">
				<div class="card-body">
					<div class="card-title">
						<div class="float-right w-100">
							<div id="area-user" class="float-left">
								<img id="user-img" src="<?php echo ( $image['gender'] == "female" ) ? "/public/images/user-female.png" : "/public/images/user-male.png" ?>"/>
								<a id="user-link" href="/user/profile/username/<?php echo $image['username']; ?>"><?php print( $image['username'] ); ?></a>
							</div>
							<div id="area-img-menu" class="float-right">
								<?php if ( $image["userid"] == $_SESSION["userid"] ) { ?>
									<div
										id="btn-details-img-<?php echo $image["id"]; ?>"
										class="burger-img"
										onclick="showDetailsImgMenu ( this.id );"
									>
										<div class="line1"></div>
										<div class="line2"></div>
										<div class="line3"></div>
									</div>
									<div id="details-img-<?php echo $image["id"]; ?>" style="display: none;" >
										<ul id="details-imgList">
											<li id="btn-delete-img-<?php echo $image["id"]; ?>" onclick="deleteImage( this.id );">Delete</li>
											<hr style="margin: 0;"/>
											<li>
												<a
													href="http://www.facebook.com/sharer.php?u=<?php echo $image['src']; ?>"
													target="_blank"
												>
													Share on facebook
												</a>
											</li>
											<li>
												<a
													href="http://twitter.com/share?text=<?php echo $image['description']; ?>&amp;url=<?php echo $image['src']; ?>"
													target="_blank"
												>
													Share on twitter
												</a>
											</li>
											<hr style="margin: 0;"/>
											<li>
												<a href="<?php echo $image['src']; ?>" download> Download </a>
											</li>
										</ul>
									</div>
								<?php } ?>
							</div>
						</div>
						<div id="area-description" class="float-left"><?php print( $image['description'] ); ?></div>
					</div>
				</div>
				<img id="img-<?php echo $image['id']; ?>" class="card-img p-xlg-3" src="<?php print( $image['src'] ); ?>">
				<div class="card-footer w-100">
					<div class="footer1">
						<div class="footer-side1">
							<div id="likes">
								<img id="icone-like" src="/public/images/like-icone.png"/>
								<span id="countLikes-<?php echo $image['id']; ?>"><?php echo $image['countlikes']; ?></span>
							</div>
							<div id="comments" >
								<img id="icone-comment" src="/public/images/comment-icone.png"/>
								<span id="countComments-<?php echo $image['id']; ?>"><?php echo $image['countcomments']; ?></span>
							</div>
						</div>
						<div class="footer-side2"><span><?php echo $image["moments"]; ?></span></div>
					</div>
					<div class="footer2">
						<div class="like">
							<img
								id="like-img-<?php echo $image['id']; ?>"
								src="<?php
									echo ( searchForMyLike ( $image["usersWhoLike"], $_SESSION['userid'] ) )
									? "/public/images/icone-like-active.png"
									: "/public/images/icone-like-inactive.png";
								?>"
								onclick="likeOrUnlike( this.id )"
							/>
						</div>
						<div class="comment">
							<img
								id="comments-img-<?php echo $image['id']; ?>"
								src="/public/images/comment-icone.png"
								onclick="getComments( this.id, <?php echo $_SESSION['userid'] ?> )"
							/>
						</div>
					</div>
				</div>
			</div>
			<?php } } ?>
		</div>
		<div class="model-bg" >
			<div class="model">
				<div class="row" id="model-header" >
					<div class="col-8" id="title">
						<h5>Comments (<span id="count-comments"></span>)</h5>
						<div id="likes-img" onclick="getUsersWhoLikedImg( this.id );">
							<img src="/public/images/icone-like-active.png" /> Likes (<span id="count-likes"></span>)
						</div>
					</div>
					<div class="col-4" id="close">
						<img id="icon-cancel" src="/public/images/cancel.png" onclick="closeModel()"/>
					</div>
				</div>
				<div class="area-comments">
					<div id="comments-img" class="comments w-100 mt-4 p-3"></div>
					<div class="row area-write-coment w-100 mx-0 my-4 p-1" style="height: 10%;">
						<div class="col-10">
							<input
								id="commentInput"
								class="w-100 form-control"
								name="comment"
								placeholder="Write a comment..."
								autocomplete="off"
							/>
						</div>
						<div class="col-1">
							<input
								id="btn-send-comment"
								class="btn btn-primary"
								type="submit"
								name="btn-comment"
								value="Send"
								onclick="addComment( <?php echo $_SESSION['userid'] ?> )"
							>
						</div>
					</div>
					<div id="area-error-msg"></div>
				</div>
			</div>
		</div>
		<div class="model" id="model-likes">
			
		</div>
		<nav id="nav-pagination" class="bg-dark">
			<ul class="pagination pagination-sm justify-content-end">
				<?php if ( $currentPage - 1 != 0 ) { ?>
					<li class="page-item"><a class="page-link" href="/gallery/index/page/<?php echo $currentPage - 1; ?>">Previous</a></li>
					<?php } else { ?>
					<li class="page-item disabled"><a class="page-link disabled" href="">Previous</a></li>
				<?php } ?>
				<?php for ( $i = 1; $i <= $totalPages; $i++ ) { ?>
					<li class="page-item"><a class="page-link" href="/gallery/index/page/<?php echo $i ?>"><?php echo $i ?></a></li>	
				<?php } ?>
				<?php if ( $currentPage != $totalPages ) { ?>
					<li class="page-item"><a class="page-link" href="/gallery/index/page/<?php echo $currentPage + 1; ?>">Next</a></li>
				<?php } else { ?>
					<li class="page-item disabled"><a class="page-link disabled" href="">Next</a></li>
				<?php } ?>
			</ul>
		</nav>
		<?php require_once(VIEWS . "_footer.php");?>
	</body>
	<script type="text/javascript" src="/public/js/_header.js"></script>
	<script type="text/javascript" src="/public/js/gallery/gallery.js"></script>
</html>