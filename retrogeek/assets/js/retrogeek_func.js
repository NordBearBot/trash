/**
 * Some small javascript functions for the RetroGeek Theme
 *
 * @package retrogeek
 */

/**
 * Function to process the tagline as a terminal like ticker
 **/
function rg_terminal(cont, notes, cl) {
	var container = document.getElementById( cont );
	var div       = document.createElement( "div" );
	div.className = cl;
	container.appendChild( div );
	var txt = notes.toUpperCase().split( "" );
	var i   = 0;
	(function display() {
		if (i < txt.length) {
			div.innerHTML += txt[i].replace( "\n", "<br />" );
			++i;
			setTimeout( display, 135 );
		}
	})();
}

/*
 *  Javascript function to toggle the burger menu on mobile devices
 **/
function retrogeek_toggle_mobile_menu() {
	x = document.getElementById( 'mobile-menu' );
	d = document.getElementById( "mobile-menu" ).style.display;
	if ( d === "none" || d === "") {
		x.style.display = 'inline-block';
	} else {
		x.style.display = 'none';
	}
}
