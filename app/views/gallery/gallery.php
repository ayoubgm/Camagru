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
							<div id="comments" >
								<img id="icone-comment" src="/public/images/comment-icone.png"/>
								<span id="countComments-<?php echo $image['id']; ?>">
									<?php echo $image['countcomments']; ?>
								</span>
							</div>
						</div>
						<div class="footer-side2">
							<span> <?php echo $image["moments"]; ?></span>
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
		const commentsImg = document.getElementById('comments-img');
		const btnSendComment = document.getElementById('btn-send-comment');
		const msgErrorComment = document.getElementById('area-error-msg');

		const			alertMessage = ( text, type ) => {
			switch ( type ) {
				case "success":
					alert.classList.add("alert-success");
					alert.innerHTML = text;
					
				break;
				case "error":
					alert.classList.add("alert-danger");
					alert.innerHTML = text;
				break;
			}
			alert.style.display = "block";
		}
		
		const			HideAlert = () => {
			setTimeout(() => {
				alert.classList.remove("alert-success");
				alert.classList.remove("alert-danger");
				alert.style.display = "none";
				alert.innerHTML = "";
			}, 3000);
		}

		const		closeModel = () => {
			let btnSend = document.querySelector('[id^=btn-send-comment]');

			btnSend.id = "btn-send-comment";
			modelBG.classList.remove('active-model');
		}

		const		showDetailsImgMenu = ( burgerId ) => {
			let imgid = burgerId.split('-')[3];
			let menuDetailsImg = document.getElementById("details-img-"+imgid);
			let btnDetailsImg = document.getElementById( burgerId );

			if ( menuDetailsImg.style["display"] == "none" ) menuDetailsImg.style.display = "block";
			else menuDetailsImg.style.display = "none";
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

		const			like = ( id ) => {
			const xhr = new XMLHttpRequest();
			const imgid = id.split('-')[2];
			const srcBtnLike = document.getElementById( id ).src;
			const countLikes = parseInt( document.getElementById("countLikes-" + imgid).innerHTML );
		
			xhr.open( "POST", "/like/add/id/"+imgid, true );
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onloadend = () => {
				if ( xhr.readyState === 4 && xhr.status === 200 ) {
					const result = JSON.parse( xhr.response );
					
					if ( result.success == "false" ) {
						if ( result.msg == "You need to login first !" ) { location.href = "/signin"; }
						else { alertMessage( result.msg, "error" ); }
					} else {
						if ( srcBtnLike.includes( "icone-like-inactive.png" ) ) {
							document.getElementById("countLikes-"+imgid).innerHTML = countLikes + 1,
							document.getElementById( id ).setAttribute("src", "/public/images/icone-like-active.png");
							alertMessage( "Liked successfully !", "success" );
						} else {
							document.getElementById("countLikes-"+imgid).innerHTML = countLikes - 1,
							document.getElementById( id ).setAttribute("src", "/public/images/icone-like-inactive.png");
							alertMessage( "Disliked successfully !", "success" );
						}
					}
				} else {
					alertMessage( `An error has occurenced ${xhr.status}, ${xhr.statusText}`, "error" );
				} 
				HideAlert();
			}
			xhr.send();
		}

		const createComment = ( data, connectedUserId ) => {
			let div = document.createElement('div');
			let Subdiv1 = document.createElement('div');
			let Subdiv2 = document.createElement('div');
			let htmlDiv2 = "";

			div.id = "comment-"+data.id;
			div.classList.add("row");
			div.style.cssText = "height: auto; margin-bottom: 5px;"
			Subdiv1.classList.add("col-lg-1");
			Subdiv1.style.cssText = "vertical-align: middle;  display: table-cell;text-align: center;"
			Subdiv1.innerHTML = "<div class='bg-primary' style='width: 55px; height: 55px; display: inline-block; border-radius: 100%; text-align: center; color: white; font-size: 14pt; padding-top: 10px;'>" + data.firstname.charAt(0).toUpperCase() + data.lastname.charAt(0).toUpperCase() + "</div>";
			Subdiv2.classList.add("col-lg-11");
			htmlDiv2 += "<div>";
			if ( connectedUserId.toString() == data.userid ) {
				htmlDiv2 += "<span id='btn-delete-com-" + data.id + "' style='float: right; color: red; cursor: pointer;' onclick='deleteComment( "+data.imgid+", "+data.id+", "+connectedUserId+" )'>x</span>";
			}
			htmlDiv2 += "<span style='font-weight: bold; font-size: 13pt; color: rgb(78, 78, 78)'>"+data.username+"</span></br>";
			htmlDiv2 += "<div>"+ data.content +"</div><span class='text-muted' style='float:right; font-size: 10pt;'>"+data.momments+" ago</span>";
			htmlDiv2 += "</div>";
			Subdiv2.innerHTML = htmlDiv2;
			div.append(Subdiv1);
			div.append(Subdiv2);
			commentsImg.scrollTop = commentsImg.scrollHeight - commentsImg.clientHeight;
			commentsImg.appendChild( div );
		}

		const addComment = ( connectedUserid ) => {
			const xhr = new XMLHttpRequest();
			const btnSend = document.querySelector('[id^="btn-send-comment-img-"]');
			const imgid = btnSend.id.split('-')[4];
			const commentContent = document.getElementById('commentInput');
			const countComment = parseInt( document.getElementById('countComments-'+imgid).innerHTML );
			const url = "/comment/add/id/" + imgid;
			const params = 'comment=' + commentContent.value;

			xhr.open("POST", url);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onloadend = () => {
				if ( xhr.readyState === 4 && xhr.status === 200 ) {
					const result = JSON.parse( xhr.response );
					
					if ( result.success == "false" ) {
						if ( result.msg == "You need to login first !" ) { location.href = "/signin"; }
						else { alertMessage( result.msg, "error" ); }
					} else {
						getComments( "comments-img-" + imgid, connectedUserid );
						document.getElementById('countComments-'+imgid).innerHTML = countComment + 1;
						alertMessage( result.msg, "success" );
					}
				} else {
					alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText}` , "error" );
				}
			}
			xhr.send( params );
		}

		const getComments = ( id, connectedUserId ) => {
			const xhr = new XMLHttpRequest();
			const areaCountComments = document.getElementById("count-comments");
			let imgid = id.split('-')[2];
			
			modelBG.classList.add('active-model');
			btnSendComment.id = "btn-send-comment-img-" + imgid;
			xhr.open("GET", "/comment/commentsImg/id/" + imgid, true);
			xhr.onloadend = () => {
				if ( xhr.readyState === 4 && xhr.status === 200 ) {
					const data = JSON.parse( xhr.response );

					if ( data.success == "false" ) {
						if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
						else { alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText}`, "error" ); }
					} else {
						const comments = data.data;

						if ( comments.length != 0 ) {
							commentsImg.innerHTML = "";
							areaCountComments.innerHTML = comments.length;
							for ( let i = 0; i < comments.length; i++ ) { createComment( comments[i], connectedUserId ); }
						} else {
							commentsImg.innerHTML = "No comments yet !"
							areaCountComments.innerHTML = 0;
						}
					}
				} else {
					alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})` )
				}
				HideAlert();
			}
			xhr.send();
		}

		const deleteComment = ( imgid, comid, connectedUserid ) => {
			const xhr = new XMLHttpRequest();
			const countComment = parseInt( document.getElementById('countComments-'+imgid).innerHTML );

			xhr.open("POST", "/comment/delete/id/" + comid, true);
			xhr.onloadend = () => {
				if ( xhr.readyState === 4 && xhr.status === 200 ) {
					const data = JSON.parse( xhr.response );

					if ( data.success == "false" ) {
						if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
						else { alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText}`, "error" ); }
					} else {
						getComments( "comments-img-" + imgid, connectedUserid );
						document.getElementById('countComments-'+imgid).innerHTML = countComment - 1;
						alertMessage( data.msg, "success" );
					}
				} else {
					alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})` )
				}
				HideAlert();
			}
			xhr.send();
		}

	</script>
</html>