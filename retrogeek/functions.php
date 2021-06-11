<?php
/**
 * Theme retrogeek functions.php file
 *
 * @package retrogeek
 * @since 22.02.2021
 */

if ( ! isset( $content_width ) ) {
	$content_width = 960;
}


// include ascii art needed functions and classes.
require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
require_once 'inc/class-retrogeek-text-figlet.php';

/**
 *  UTF-8 convert to FIGlet Unicode
 *
 *  @param string $str String in UTF8 to convert to ASCII-Art.
 */
function retrogeek_utf8tofiglet( $str ) {
	// escape %u.
	$str = str_replace( '%u', sprintf( '%%%%u%04X', ord( 'u' ) ), $str );

	if ( function_exists( 'iconv' ) ) {
		$str = iconv( 'utf-8', 'ucs-2be', $str );
		$out = '';

		for ( $i = 0, $len = strlen( $str ); $i < $len; $i++ ) {
			$code = ord( $str[ $i++ ] ) * 256 + ord( $str[ $i ] );

			$out .= $code < 128 ? $str[ $i ] : sprintf( '%%u%04X', $code );
		}

		return $out;
	}

	return $str;
}

// load customizer stuff.
require_once 'inc/class-retrogeek-customize.php';

// Setup the Theme Customizer settings and controls...if on admin page.
add_action( 'customize_register', array( 'RetroGeek_Customize', 'register' ) );

/**
 * Add all the theme supported features.
 */
function retrogeek_theme_support() {
	// add theme support features.
	add_theme_support( 'title-tag' );

	// add support for background color.
	$retrogeek_args = array(
		'default-color' => '#232323',
	);
	add_theme_support( 'custom-background', $retrogeek_args );

	// add support for feed links.
	add_theme_support( 'automatic-feed-links' );

	// add support for post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// add support for custom-header.
	$retrogeek_args1 = array(
		'default-text-color' => '#00ff00',
		'width'              => 900,
		'height'             => 150,
		'flex-width'         => true,
		'flex-height'        => true,
	);
	add_theme_support( 'custom-header', $retrogeek_args1 );

	$retrogeek_args2 = array(
		'width'                => 170,
		'height'               => 150,
		'flex-width'           => true,
		'flex-height'          => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true,
	);
	add_theme_support( 'custom-logo', $retrogeek_args2 );
}
add_action( 'after_setup_theme', 'retrogeek_theme_support' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function retrogeek_editor_styles_setup() {
	$m1 = esc_attr( get_theme_mod( 'textcolor', '#00ff00' ) );
	$m2 = esc_attr( get_theme_mod( 'link_textcolor', '#74f774' ) );
	$m3 = esc_attr( get_theme_mod( 'background_color', '#232323' ) );

	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => 'retrogeek background color',
				'slug'  => 'retrogeek-background',
				'color' => $m3,
			),
			array(
				'name'  => 'retrogeek foreground color',
				'slug'  => 'retrogeek-foreground',
				'color' => $m1,
			),
			array(
				'name'  => 'retrogeek link color',
				'slug'  => 'retrogeek-link',
				'color' => $m2,
			),
		)
	);

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style( 'assets/css/editor-styles.css' );
}
add_action( 'after_setup_theme', 'retrogeek_editor_styles_setup' );

/**
 *  This functions echos the CSS vars used from customizer for the WordPress editor.
 **/
function retrogeek_css_admin_vars() {
	$css_str = RetroGeek_Customize::header_output();
	echo '<style>' . esc_attr( $css_str ) . '</style>';
}
add_action( 'admin_head', 'retrogeek_css_admin_vars' );


/**
 *  Enqueue style sheets.
 */
function retrogeek_add_theme_scripts() {
	wp_enqueue_script( 'retrogeek-javascript', get_template_directory_uri() . '/assets/js/retrogeek_func.js', false, 42, true );
	wp_enqueue_style( 'retrogeek-normalize', get_template_directory_uri() . '/assets/css/normalize.css', false, '42', 'all' );
	wp_enqueue_style( 'retrogeek-skeleton', get_template_directory_uri() . '/assets/css/skeleton.css', 'retrogeek-normalize', '42', 'all' );
	wp_enqueue_style( 'retrogeek-style', get_template_directory_uri() . '/style.css', 'retrogeek-skeleton', '42', 'all' );

	// Output custom CSS to site.
	$css_str = RetroGeek_Customize::header_output();
	wp_add_inline_style( 'retrogeek-skeleton', $css_str, 'after' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'retrogeek_add_theme_scripts' );

/**
 *  Remove invalid html from WordPress.
 *
 * @param string $content HTML string to sanitize.
 */
function retrogeek_sanitize_pagination( $content ) {
	// Remove role attribute.
	$content = str_replace( 'role="navigation"', '', $content );

	// Remove h2 tag.
	$content = preg_replace( '#<h2.*?>(.*?)<\/h2>#si', '', $content );

	return $content;
}
add_action( 'navigation_markup_template', 'retrogeek_sanitize_pagination' );


/**
 * Register main menu.
 */
function retrogeek_register_main_menu() {
	register_nav_menu( 'primary', __( 'Mainmenu', 'retrogeek' ) );
}
add_action( 'init', 'retrogeek_register_main_menu' );


// include widget classes.
require_once 'inc/class-retrogeek-ticker-widget.php';

/**
 * Register our footer widget area and widgets.
 */
function retrogeek_widgets_init() {

	register_sidebar(
		array(
			'name'          => __( 'Footer left', 'retrogeek' ),
			'id'            => 'rg_footer_left',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="rounded">',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer middle', 'retrogeek' ),
			'id'            => 'rg_footer_middle',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="rounded">',
			'after_title'   => '</h5>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer right', 'retrogeek' ),
			'id'            => 'rg_footer_right',
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="rounded">',
			'after_title'   => '</h5>',
		)
	);

	// register widgets.
	register_widget( 'retrogeek_ticker_widget' );
}
add_action( 'widgets_init', 'retrogeek_widgets_init' );

/**
 * Add search form to the right of the menubar
 *
 * @param array $items array of Menuiitems.
 * @param array $args special arguments for menu.
 */
function retrogeek_add_search_form_to_menu( $items, $args ) {
	$rg_show_searchform = get_theme_mod( 'rg_homepage_searchform', true );

	// If this isn't the main navbar menu or searchform is disabled, do nothing.
	if ( ! ( 'primary' === $args->theme_location ) || ! $rg_show_searchform ) { // with Customizr Pro 1.2+ and Cusomizr 3.4+ you can chose to display the saerch box to the secondary menu, just replacing 'main' with 'secondary'.
		return $items;
	} else {
		// On main menu: put styling around search and append it to the menu items.
		return $items . '<li class="my-nav-menu-search">' . get_search_form( false ) . '</li>';
	}
}

// As of 3.1.10, Customizr doesn't output an html5 form.
add_theme_support( 'html5', array( 'search-form' ) );

add_filter( 'wp_nav_menu_items', 'retrogeek_add_search_form_to_menu', 10, 2 );

/**
 * Return the ASCII-Logo string depending on the customizer setting.
 */
function retrogeek_get_asciilogo() {
	$asciilogo = get_theme_mod( 'rg_logo_radio', 'snail' );
	if ( 'snail' === $asciilogo ) {
		$asciilogo_string = <<<ALS
     .----.   @   @
   / .-"-.`.  \\v/
   | | '\ \ \_/ )
 ,-\ `-.' /.'  /
'---`----'----'
ALS;
	}
	if ( 'kilroy' === $asciilogo ) {
		$asciilogo_string = <<<ALS
        \\//
      -(@ @)-
--oOO-- (_)-- OOo--
Kilroy was here
ALS;
	}
	if ( 'tux' === $asciilogo ) {
		$asciilogo_string = <<<ALS
    .--.
   |o_o |
   |:_/ |
  //   \ \
 (|     | )
/'\_   _/`\
\___)=(___/
ALS;
	}
	if ( 'none' === $asciilogo ) {
		$asciilogo_string = '&nbsp;';
	}
	if ( 'user' === $asciilogo ) {
		$asciilogo_string = get_theme_mod( 'rg_logo_userdefined', '' );
	}

	return $asciilogo_string;
}
