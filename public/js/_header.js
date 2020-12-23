if ( typeof logged !== 'undefined' ) { localStorage.setItem(`token`, userToken); }
const alert = document.getElementById('alert-msg');
const userMenu = document.querySelector("nav .btn-auth .dropdown");
const btnProfilePic = document.getElementById("profile-img");
const menuNotifs = document.getElementById("notifications");
const btnMenuNotifs = document.getElementById("notif-img");
const notificationsArea = document.getElementById("notifications-user");

const					createNotification = ( data ) => {
	let div = document.createElement('div');
	let hr = document.createElement('hr');
	let htmldiv = "";
	
	div.id = "notif-" + data.id;
	div.classList.add("w-100");
	div.style.cssText = "height: 32px; padding: 5px; cursor: pointer;";
	if ( data.seen == "0" ) { div.style.cssText += "background-color: #e2f9ff"; }
	htmldiv += "<span class='float-left' style='color: #00a3cc;'>"+ data.content +"</span>";
	htmldiv += "<span class='float-right' style='font-size: 10pt'>"+ data.moments +"</span>"
	div.addEventListener('click', () => {
		readANotification( data.userid, data.id )
		window.location.href = "/gallery?image=" + data.imgid;
	});
	div.innerHTML = htmldiv;
	notificationsArea.appendChild( div );
	notificationsArea.appendChild( hr );
}

// Display all user notifications
const					getNotifications = () => {
	const xhr = new XMLHttpRequest();
	
	xhr.open("GET", "/notification/user", true);
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );

			if ( data.success == "false" ) {
				if ( data.msg != "You need to login first !" ) {
					alertMessage( data.msg, "error" );
					HideAlert();
				}
			} else {
				const notifs = data.data;

				if ( notifs.length != 0 ) {
					notificationsArea.innerHTML = "";
					for ( let i = 0; i < notifs.length; i++ ) { createNotification( notifs[i] ); }
				} else {
					notificationsArea.innerHTML = "<span class='p-1'>No notifications yet !</span>";
				}
			}
		} else {
			alertMessage( `An error has occurenced : ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send();
}

const					readANotification = ( userid, id ) => {
	const xhr = new XMLHttpRequest();
	const url = "/notification/readnotifuser";
	const params = "userid="+userid+"&notifid="+id+"&token="+localStorage.getItem( "token" );
	
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const data = JSON.parse( xhr.response );
			if ( data.success == "false" ) {
				if ( data.msg != "You need to login first !" ) {
					alertMessage( data.msg, "error" ); HideAlert();
				}
			} else {
				getNotifications();
			}
		} else {
			alertMessage( `An error has occurenced : ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const					readAllUserNotifs = () => {
	const xhr = new XMLHttpRequest();
	const url = "/notification/readallnotifsuser";
	const params = "token="+localStorage.getItem( "token" );

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const result = JSON.parse( xhr.response );

			if ( result.success == "false" ) {
				if ( result.msg != "You need to login first !" ) {
					alertMessage( result.msg, "error" ); HideAlert();
				}
			} else {
				document.getElementById('countNotifs').innerHTML = 0;
				getNotifications();
				alertMessage( result.msg, "success" );
				HideAlert();
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const					deleteAllUserNotifs = () => {
	const xhr = new XMLHttpRequest();
	const url = "/notification/deleteallnotifsuser";
	const params = "token="+localStorage.getItem( "token" );

	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const result = JSON.parse( xhr.response );

			if ( result.success == "false" ) {
				if ( result.msg == "You need to login first !" ) {
					location.href = "/signin";
				} else {
					alertMessage( result.msg, "error" ); HideAlert();
				}
			} else {
				document.getElementById('countNotifs').innerHTML = 0;
				getNotifications();
				alertMessage( result.msg, "success" );
				HideAlert();
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send( params );
}

const					logout = () => {
	const xhr = new XMLHttpRequest();

	xhr.open("GET", "/user/logout", true);
	xhr.onloadend = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			const result = JSON.parse( xhr.response );

			if ( result.success == "false" ) { alertMessage( result.msg, "error" ); HideAlert(); }
			else {
				localStorage.removeItem( "token" );
				location.href = "/home";
			}
		} else {
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" ); HideAlert();
		}
	}
	xhr.send();
}

const					alertMessage = ( text, type ) => {
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

const					HideAlert = () => {
	setTimeout(() => {
		alert.classList.remove("alert-success");
		alert.classList.remove("alert-danger");
		alert.style.display = "none";
		alert.innerHTML = "";
	}, 3000);
}

const					showOrHideMenu = ( menu, btnMenu, e ) => {
	if ( menu && btnMenu ) {
		var isClickedInsideMenu = menu.contains( e.target );
		var isClickedInsidBtnMenu = btnMenu.contains( e.target );

		if ( isClickedInsidBtnMenu && menu.style.display == "none" ) { menu.style.display = "block"; }
		else if ( !isClickedInsideMenu ) { menu.style.display = "none"; }
	}
}

document.addEventListener('click', (event) => {
	showOrHideMenu( userMenu, btnProfilePic, event );
	showOrHideMenu( menuNotifs, btnMenuNotifs, event );
});

const					navSlide = () => {
	const burger = document.querySelector('.burger');
	const nav = document.querySelector('.nav-links');
	const navLinks = document.querySelectorAll('.nav-links li')

	burger.addEventListener('click', () => {
		// Toggle navbar
		nav.classList.toggle('nav-active');
		
		// Animate nav links
		navLinks.forEach((link, index) => {
			if ( link.style.animation ) { link.style.animation = ""; }
			else { link.style.animation = `navLinkFade 0.5s ease forwards ${index / 6 + 0.4}s`; }
		});

		// Burger animation
		burger.classList.toggle('toggle');
	});
}

navSlide();