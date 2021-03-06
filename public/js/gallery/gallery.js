const btn_profile = document.querySelector("nav .btn-auth #profile-img");
const btn_like = document.getElementById('btn-like');
const modelBG = document.querySelector('.model-bg');
const modelLikes = document.querySelector('#model-likes');
const btnDelete = document.getElementById("btn-delete");
const btnCancel = document.getElementById("btn-cancel");
const modelClose = document.querySelector('#icon-cancel');
const commentsImg = document.getElementById('comments-img');
const btnSendComment = document.getElementById('btn-send-comment');
const areaUsersLikeImg = document.querySelector("[id^=users-like-img]");
const url = window.location.href;
const imageidUrl = url.split('=')[1];
const pathWithourQS = url.split('?')[0];
const queryString = url.split('?')[1];

const			createUserWhoLike = ( data ) => {
	var divLike = document.createElement('div');
	var htmlString;

	divLike.id = "like-" + data.id;
	divLike.classList.add("row");
	divLike.style.cssText = "height: auto; margin: 5px 0px;";
	htmlString = "<div class='bg-success' style='width: 30px; height: 30px; display: inline-block; border-radius: 100%; text-align: center; color: white; font-size: 10pt; padding-top: 5px;'>";
	htmlString += data.firstname.charAt(0).toUpperCase() + data.lastname.charAt(0).toUpperCase() + "</div>";
	htmlString += "<a href='/user/profile/username/"+data.username+"' style='text-decoration: none; font-size: 10pt; color: rgb(78, 78, 78); padding: 5px;'>"+data.username+"</a></br>";
	divLike.innerHTML = htmlString;
	areaUsersLikeImg.appendChild( divLike );
}

const			createComment = ( data, connectedUserId ) => {
	var div = document.createElement('div');
	var Subdiv1 = document.createElement('div');
	var Subdiv2 = document.createElement('div');
	var htmlDiv2 = "";

	div.id = "comment-"+data.id;
	div.classList.add("row");
	div.style.cssText = "height: auto; margin-bottom: 5px;"
	Subdiv1.classList.add("col-1");
	Subdiv1.style.cssText = "vertical-align: middle;  display: table-cell;text-align: center;"
	Subdiv1.innerHTML = "<div class='bg-primary' style='width: 55px; height: 55px; display: inline-block; border-radius: 100%; text-align: center; color: white; font-size: 14pt; padding-top: 10px;'>" + data.firstname.charAt(0).toUpperCase() + data.lastname.charAt(0).toUpperCase() + "</div>";
	Subdiv2.classList.add("col-lg-11");
	if ( connectedUserId && connectedUserId.toString() == data.userid ) {
		htmlDiv2 += "<span id='btn-delete-com-" + data.id + "' style='float: right; color: red; cursor: pointer;' onclick='deleteComment( "+data.imgid+", "+data.id+", "+connectedUserId+" )'>x</span>";
	}
	htmlDiv2 += "<a href='/user/profile/username/"+data.username+"' style='text-decoration: none; font-weight: bold; font-size: 13pt; color: rgb(90, 90, 90)'>"+data.username+"</a></br>";
	htmlDiv2 += "<div>"+ data.content +"</div><span class='text-muted' style='float:right; font-size: 10pt;'>"+data.momments+" ago</span>";
	Subdiv2.innerHTML = htmlDiv2;
	div.appendChild(Subdiv1);
	div.appendChild(Subdiv2);
	commentsImg.scrollTop = commentsImg.scrollHeight - commentsImg.clientHeight;
	commentsImg.appendChild( div );
}

const			likeOrUnlike = ( id ) => {
	const xhr = new XMLHttpRequest();
	const imgid = id.split('-')[2];
	const srcBtnLike = document.getElementById( id ).src;
	const countLikes = parseInt( document.getElementById("countLikes-" + imgid).innerHTML );
	const url = "/like/add";
	const params = "id="+imgid+"&token="+localStorage.getItem( "token" );

	xhr.open( "POST", url, true );
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) {
				if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
				else { alertMessage( data.msg, "error" ); HideAlert(); }
			} else {
				if ( srcBtnLike.includes( "icone-like-inactive.png" ) ) {
					document.getElementById("countLikes-"+imgid).innerHTML = countLikes + 1,
					document.getElementById( id ).setAttribute("src", "/public/images/icone-like-active.png");
				} else {
					document.getElementById("countLikes-"+imgid).innerHTML = countLikes - 1,
					document.getElementById( id ).setAttribute("src", "/public/images/icone-like-inactive.png");
				}
			}
		} else {
			alertMessage( `An error has occurenced ${xhr.status}, ${xhr.statusText}`, "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const			getUsersWhoLikedImg = ( id ) => {
	const xhr = new XMLHttpRequest();
	var imgid = id.split('-')[2];
	const url = "/like/userswholikes";
	const params = "id="+imgid;
	var i = 0;

	xhr.open("GET", url+"?"+params, true);
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) { alertMessage( data.msg, "error" ); HideAlert(); }
			else {
				const users = data.users;
				if ( users.length == 0 ) { areaUsersLikeImg.innerHTML = "No likes yet !"; }
				else { for ( i = 0; i < users.length; i++ ) { createUserWhoLike ( users[i] ); } }
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send();
}

const			addComment = ( id, connectedUserid ) => {
	const xhr = new XMLHttpRequest();
	const imgid = id.split('-')[4];
	const commentContent = document.getElementById('commentInput');
	const countComment = parseInt( document.getElementById('countComments-'+imgid).innerHTML );
	const url = "/comment/add";
	const params = 'id='+imgid+'&comment='+commentContent.value+'&token='+localStorage.getItem( "token" );

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) {
				if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
				else { alertMessage( data.msg, "error" ); HideAlert(); }
			} else {
				document.getElementById('countComments-'+imgid).innerHTML = countComment + 1;
				getComments( "comments-img-" + imgid, connectedUserid );
				alertMessage( data.msg, "success" ); HideAlert();
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText}` , "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const			deleteComment = ( imgid, comid, connectedUserid ) => {
	const xhr = new XMLHttpRequest();
	const countComment = parseInt( document.getElementById('countComments-'+imgid).innerHTML );
	const url = "/comment/delete";
	const params = "id="+comid+"&token="+localStorage.getItem( "token" );

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) {
				if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
				else { alertMessage( data.msg, "error" ); HideAlert(); }
			} else {
				getComments( "comments-img-" + imgid, connectedUserid );
				document.getElementById('countComments-'+imgid).innerHTML = countComment - 1;
				alertMessage( data.msg, "success" ); HideAlert();
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const			deleteImage = ( id ) => {
	const xhr = new XMLHttpRequest();
	const imgid = id.split('-')[3];
	const url = "/gallery/delete";
	const params = "id=" + imgid + "&token=" + localStorage.getItem( "token" );

	xhr.open("POST", url, true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) {
				if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
				else { alertMessage( data.msg, "error" ); HideAlert(); }
			} else {
				alertMessage( data.msg, "success" );
				HideAlert();
				location.reload();
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const			getComments = ( id, connectedUserId ) => {
	var imgid = id.split('-')[2];
	const areaCountComments = document.getElementById("count-comments");
	const countLikes = document.getElementById("count-likes");
	const arealikes = document.querySelector("[id^=likes-img]");
	const xhr = new XMLHttpRequest();
	const url = "/comment/commentsImg";
	const params = "id="+imgid;
	var i = 0;

	modelBG.classList.add('active-model');
	getUsersWhoLikedImg("likes-img-" + imgid);
	btnSendComment.id = "btn-send-comment-img-" + imgid;
	arealikes.id = "likes-img-"+imgid;
	areaUsersLikeImg.id = "users-like-img-" + imgid;
	xhr.open("GET", url+"?"+params, true);
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) {
				if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
				else { alertMessage( data.msg, "error" ); HideAlert(); }
			} else {
				const comments = data.data;
				countLikes.innerHTML = data.countlikes;
				if ( comments.length != 0 ) {
					commentsImg.innerHTML = "";
					areaCountComments.innerHTML = comments.length;
					for ( i = 0; i < comments.length; i++ ) { createComment( comments[i], connectedUserId ); }
				} else {
					commentsImg.innerHTML = "No comments yet !"
					areaCountComments.innerHTML = 0;
				}
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send();
}

const			closeModel = () => {
	var btnSend = document.querySelector('[id^=btn-send-comment]');
	
	btnSend.id = "btn-send-comment";
	modelBG.classList.remove('active-model');
	if ( queryString ) window.location.href = pathWithourQS;
}

const			closeLikesModel = () => {
	modelLikes.classList.remove('active-model');
}

const			showDetailsImgMenu = ( burgerId ) => {
	var imgid = burgerId.split('-')[3];
	var menuDetailsImg = document.getElementById("details-img-"+imgid);
	var btnDetailsImg = document.getElementById( burgerId );
	
	if ( menuDetailsImg.style["display"] == "none" ) menuDetailsImg.style.display = "block";
	else menuDetailsImg.style.display = "none";
}

document.addEventListener("click", ( event ) => {
	const listMenusDetails = document.querySelectorAll('[id^="details-img-"]');
	[].forEach.call( listMenusDetails, ( node ) => {
		const imgid = node.id.split('-')[2];
		var btnBurgerDetails = document.getElementById("btn-details-img-" + imgid);
		var btnBurgerIsClicked = btnBurgerDetails.contains( event.target );
		var menuDetailsIsClicked = node.contains( event.target );
		if ( !btnBurgerIsClicked && !menuDetailsIsClicked && node.style.display == "block" ) {
			node.style.display = "none"
		}
	});
});

if ( ( typeof imageidUrl != 'undefined' && imageidUrl ) && ( typeof connectedUser != 'undefined' && connectedUser ) ) {
	getComments( "comments-img-" + imageidUrl, connectedUser );
}