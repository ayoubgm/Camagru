const menu = document.querySelector("nav .btn-auth .dropdown");
const btnProfilePic = document.getElementById("profile-img");
const menuNotifs = document.getElementById("notifications");
const btnMenuNotifs = document.getElementById("notif-img");


document.addEventListener('click', (event) => {
	if ( menu && btnProfilePic ) {
		let isClickedInsideMenu = menu.contains(event.target);
		let isClickedInsideProfilePic = btnProfilePic.contains(event.target);

		if ( isClickedInsideProfilePic && menu.style.display == "none" ) {
			menu.style.display = "block";
		} else if ( !isClickedInsideMenu ) {
			menu.style.display = "none";
		}
	}
	if ( menuNotifs && btnMenuNotifs ) {
		let isClickedInsideMenuNotifs = menuNotifs.contains( event.target );
		let isClickedInsideBtnNotifs = btnMenuNotifs.contains( event.target );

		if ( isClickedInsideBtnNotifs && menuNotifs.style.display == "none" ) {
			menuNotifs.style.display = "block";
		} else if ( !isClickedInsideMenuNotifs ) {
			menuNotifs.style.display = "none";
		}
	}
});