<?php
	$data = $this->view_data["data"];
	$totalImages = $data['totalImages'];
	$imagePerPage = 5;
	$currentPage = $data['page'];
	$totalPages = ceil( $totalImages / $imagePerPage );
	$gallery = $data['gallery'];
	$userData = ( isset( $data['userData'] ) ) ? $data['userData'] : null;
	$userGallery = ( isset ( $data['userGallery'] ) ) ? $data['userGallery'] : null;
	
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
	<link rel="stylesheet" href="/public/css/gallery.css"/>
	<link rel="stylesheet" href="/public/css/_footer.css"/>
	<title>Gallery</title>
</head>
	<body>
		<?php require_once(VIEWS . "_header.php");?>
		<div id="alert-msg" >
			<!-- <span class="alert alert-danger">HELLO</span> -->
		</div>
		<div class="row col-12" id="gallery">
			<?php if ( count($gallery ) === 0 ) { ?>
				<p> <?php echo "No Edited images !"; ?> </p>
			<?php } else { foreach ( $gallery as $image ) { ?>
			<div class="card col-md-4 col-lg-5 col-xl-3" id="image">
				<div class="card-body">
					<div class="card-title">
						<div class="float-right w-100">
							<div id="area-user" class="float-left">
								<img id="user-img" src="<?php echo ( $image['gender'] == "female" ) ? "/public/images/user-female.png" : "/public/images/user-male.png" ?>"/>
								<a id="user-link" href="/user/profile/username/<?php echo $image['username']; ?>">By <?php print( $image['username'] ); ?></a>
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
									<div
										id="details-img-<?php echo $image["id"]; ?>"
										style="display: none;"
									>
										<ul id="details-imgList">
											<li>Edit</li>
											<li>Delete</li>
											<li>Share</li>
										</ul>
									</div>
								<?php } ?>
							</div>
						</div>
						<div id="area-description" class="float-left"><?php print( $image['description'] ); ?></div>
					</div>
				</div>
				<img id="img-<?php echo $image['id']; ?>" class="card-img" src="<?php print( $image['src'] ); ?>">
				<div class="card-footer w-100">
					<div class="footer1">
						<div class="footer-side1">
							<div id="likes">
								<img id="icone-like" src="/public/images/like-icone.png"/>
								<span id="countLikes-<?php echo $image['id']; ?>" >
									<?php echo $image['countlikes']; ?>
								</span>
							</div>
							<div id="comments">
								<img id="icone-comment" src="/public/images/comment-icone.png"/>
								<span id="countComments">
									<?php echo $image['countcomments']; ?>
								</span>
							</div>
						</div>
						<div class="footer-side2">
							<span> <?php echo $image["moments"]; ?> </span>
						</div>
					</div>
					<div class="footer2">
						<div class="like">
							<img
								id="like-img-<?php echo $image['id']; ?>"
								src="<?php echo ( searchForMyLike ( $image["usersWhoLike"], $_SESSION['userid'] ) ) ? "/public/images/icone-like-active.png" : "/public/images/icone-like-inactive.png"; ?>"
								onclick="like( this.id )"
							/>
						</div>
						<div class="comment">
							<img
								id="comments-img-<?php echo $image['id']; ?>"
								src="/public/images/comment-icone.png"
								onclick="getComments( this.id )"
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
						<h5>Comments</h5>
					</div>
					<div class="col-4" id="close">
						<img id="icon-cancel" src="/public/images/cancel.png" onclick="closeModel()"/>
					</div>
				</div>
				<div class="area-comments">
					<div class="comments w-100 mt-4 p-3">
						<?php if( empty( $data['comments'][$image['id']] ) ) { echo "No comments yet"; } ?>
					</div>
					<form method="POST" action="/gallery/comment/id/<?php echo $image['id']; ?>">
						<div class="row area-write-coment w-100 mx-0 my-2 p-1" style="height: 20%; border-radius: 8px;">
							<div class="col-10">
								<textarea id="commentInput" name="comment" class="w-100 form-control" placeholder="Write a comment..."></textarea>
							</div>
							<div class="col-1">
								<input id="btn-send" class="btn btn-primary" type="submit" name="btn-comment" value="Send">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php require_once(VIEWS . "_footer.php");?>
	</body>
	<script src="/public/js/_menu.js"></script>
	<script src="/public/js/_userMenu.js"></script>
	<script>
		const btn_profile = document.querySelector("nav .btn-auth #profile-img");
		const btn_like = document.getElementById('btn-like');
		const alert = document.getElementById('alert-msg');
		const modelBG = document.querySelector('.model-bg');
		const btnDelete = document.getElementById("btn-delete");
		const btnCancel = document.getElementById("btn-cancel");
		const modelClose = document.querySelector('#icon-cancel');

		const closeModel = () => {
			modelBG.classList.remove('active-model');
		}

		const showDetailsImgMenu = ( burgerId ) => {
			let imgid = burgerId.split('-')[3];
			let menuDetailsImg = document.getElementById("details-img-"+imgid);
			let btnDetailsImg = document.getElementById( burgerId );

			if ( menuDetailsImg.style["display"] == "none" ) menuDetailsImg.style.display = "block";
			else menuDetailsImg.style.display = "none";
		}
		
		const like = ( id ) => {
			const imgid = id.split('-')[2];
			const xhr = new XMLHttpRequest();
		
			xhr.open( "POST", "/like/add/id/"+imgid, true );
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send();
			xhr.onload = () => {
				if ( xhr.status != 200 ) {
					console.log( `Error: ${xhr.status}, ${xhr.statusText}` )
				} else {
					const result = JSON.parse( xhr.response );
					
					if ( result.success == "false" ) {
						if ( result.msg == "You need to login first !" ) { location.href = "/signin"; }
						else {
							alert( result.msg );
						}
					} else {
						const srcBtnLike = document.getElementById( id ).src;
						const countLikes = parseInt( document.getElementById("countLikes-"+imgid).innerHTML );

						if ( srcBtnLike.includes( "icone-like-inactive.png" ) ) {
							document.getElementById("countLikes-"+imgid).innerHTML = countLikes + 1,
							document.getElementById( id ).setAttribute("src", "/public/images/icone-like-active.png");
						} else {
							document.getElementById("countLikes-"+imgid).innerHTML = countLikes - 1,
							document.getElementById( id ).setAttribute("src", "/public/images/icone-like-inactive.png");
						}
					}
				}
			}
		}

		const getComments = ( imgid ) => {
			modelBG.classList.add('active-model');
			const imgid = id.split('-')[2];
			const xhr = new XMLHttpRequest();

			// Get all comments of an image
			
		}

		document.addEventListener("click", ( event ) => {
				const listMenusDetails = document.querySelectorAll('[id^="details-img-"]');

				[].forEach.call( listMenusDetails, ( node ) => {
					const imgid = node.id.split('-')[2];
					let btnBurgerDetails = document.getElementById("btn-details-img-" + imgid);
					let btnBurgerIsClicked = btnBurgerDetails.contains( event.target );
					let menuDetailsIsClicked = node.contains( event.target );

					if ( !btnBurgerIsClicked && !menuDetailsIsClicked && node.style.display == "block" ) {
						node.style.display = "none"
					}
				});
			});

		setTimeout(() => { if ( alert ) alert.remove() }, 2500);


	</script>
</html>