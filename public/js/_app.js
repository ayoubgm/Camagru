const alert = document.getElementById('alert-msg');
const userMenu = document.querySelector("nav .btn-auth .dropdown");
const btnProfilePic = document.getElementById("profile-img");
const menuNotifs = document.getElementById("notifications");
const btnMenuNotifs = document.getElementById("notif-img");
const notificationsArea = document.getElementById("notifications-user");

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

const					createNotification = ( data ) => {
	let div = document.createElement('div');
	htmldiv = "";

	div.id = "notif-" + data.id;
	div.classList.add("w-100");
	div.style.cssText = "height: auto; padding: 5px;";
	htmldiv += "<span class='float-left' style='color: #00a3cc;'>"+ data.content +"</span>";
	htmldiv += "<span class='float-right' style='font-size: 10pt'>"+ data.moments +"</span>"
	div.innerHTML = htmldiv;
	notificationsArea.appendChild( div );
}

// Display all user notifications
const					getNotifications = () => {
	const xhr = new XMLHttpRequest();
	
	xhr.open("GET", "/notification/user", true);
	xhr.onloadend = () => {
		if ( xhr.readyState === 4 && xhr.status ) {
			const data = JSON.parse( xhr.response );

			if ( data.success == "false" ) {
				if ( data.msg != "You need to login first !" ) { alertMessage( data.msg, "error" ); }
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
			alertMessage( `An error has occurenced: ${xhr.status}, ${xhr.statusText})`, "error" )
		}
		HideAlert();
	}
	xhr.send();
}

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
			else { link.style.animation = `navLinkFade 0.5s ease forwards ${index / 4 + 0.5}s`; }
		});

		// Burger animation
		burger.classList.toggle('toggle');
	});
}

navSlide();
