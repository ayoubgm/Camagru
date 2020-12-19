const btn_profile = document.querySelector("nav .btn-auth #profile-img");
const btn_like = document.getElementById('btn-like');
const modelBG = document.querySelector('.model-bg');
const btnDelete = document.getElementById("btn-delete");
const btnCancel = document.getElementById("btn-cancel");
const modelClose = document.querySelector('#icon-cancel');
const commentsImg = document.getElementById('comments-img');
const btnSendComment = document.getElementById('btn-send-comment');

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

const			likeOrUnlike = ( id ) => {
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
	if ( connectedUserId.toString() == data.userid ) {
		htmlDiv2 += "<span id='btn-delete-com-" + data.id + "' style='float: right; color: red; cursor: pointer;' onclick='deleteComment( "+data.imgid+", "+data.id+", "+connectedUserId+" )'>x</span>";
	}
	htmlDiv2 += "<span style='font-weight: bold; font-size: 13pt; color: rgb(78, 78, 78)'>"+data.username+"</span></br>";
	htmlDiv2 += "<div>"+ data.content +"</div><span class='text-muted' style='float:right; font-size: 10pt;'>"+data.momments+" ago</span>";
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
	xhr.open("POST", url, true);
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
				else { alertMessage( data.msg, "error" ); }
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
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" )
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
				else { alertMessage( data.msg, "error" ); }
			} else {
				getComments( "comments-img-" + imgid, connectedUserid );
				document.getElementById('countComments-'+imgid).innerHTML = countComment - 1;
				alertMessage( data.msg, "success" );
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" )
		}
		HideAlert();
	}
	xhr.send();
}
const deleteImage = ( id ) => {
	const xhr = new XMLHttpRequest();
	const imgid = id.split('-')[3];
	
	xhr.open("POST", "/gallery/delete/id/" + imgid, true);
	xhr.onloadend = () => {
		if ( xhr.readyState === 4 && xhr.status === 200 ) {
			const data = JSON.parse( xhr.response );

			if ( data.success == "false" ) {
				if ( data.msg == "You need to login first !" ) { location.href = "/signin"; }
				else { alertMessage( data.msg, "error" ); }
			} else {
				alertMessage( data.msg, "success" );
				location.reload();
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" );
		}
		HideAlert();
	}
	xhr.send();
}