const navSlide = () => {
    const burger = document.querySelector('.burger');
	const nav = document.querySelector('.nav-links');
	const navLinks = document.querySelectorAll('.nav-links li');

    burger.addEventListener('click', () => {
		// Toggle navbar
        nav.classList.toggle('nav-active');
		
		// Animate nav links
		var i = 0;
		for ( i = 0; i < navLinks.length; i++ ) {
			if ( navLinks[i].style.animation ) { navLinks[i].style.animation = ""; }
			else { navLinks[i].style.animation = `navLinkFade 0.5s ease forwards ${index / 4 + 0.5}s`; }
		}

		// Burger animation
		burger.classList.toggle('toggle');
	});
}

navSlide();
