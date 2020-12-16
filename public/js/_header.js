const userMenu = document.querySelector("nav .btn-auth .dropdown");
const btnProfilePic = document.getElementById("profile-img");
const menuNotifs = document.getElementById("notifications");
const btnMenuNotifs = document.getElementById("notif-img");

const       showOrHideMenu = ( menu, btnMenu, e ) => {
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

const navSlide = () => {
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
