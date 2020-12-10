const menu = document.querySelector("nav .btn-auth .dropdown");
const btnProfilePic = document.getElementById("profile-img");

document.addEventListener('click', (event) => {
	if ( menu && btnProfilePic ) {
		var isClickedInsideMenu = menu.contains(event.target);
		var isClickedInsideProfilePic = btnProfilePic.contains(event.target);

		if ( isClickedInsideProfilePic && menu.style.display == "none" ) {
			menu.style.display = "block";
		} else if ( !isClickedInsideMenu ) {
			menu.style.display = "none";
		}
	}	
});