<?php
/**
 * Class and functions for theme customization screen
 *
 * @link http://codex.wordpress.org/Theme_Customization_API
 *
 * @package retrogeek
 * @since 22.02.2021
 */

/**
 * Class for customizer parameters.
 */
class RetroGeek_Customize {
	/**
	 * This hooks into 'customize_register' (available as of WP 3.4) and
	 * adds fields and controlsallows
	 *
	 * @param WP_Customize_Manager $wp_customize a customizer object.
	 * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
	 */
	public static function register( $wp_customize ) {
		// Register new settings to the WP database.
		$wp_customize->add_setting(
			'textcolor',
			array(
				'default'           => '#00ff00', // Default setting/value to save.
				'type'              => 'theme_mod', // Is this an 'option' or a 'theme_mod'?
				'capability'        => 'edit_theme_options', // Optional. Special permissions for accessing this setting.
				'transport'         => 'refresh', // What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				'sanitize_callback' => 'sanitize_hex_color', // callback to sanitize input values.
			)
		);

		// Define the control itself (which links a setting to a section and renders the HTML controls).
		$wp_customize->add_control(
			new WP_Customize_Color_Control( // Instantiate the color control class.
				$wp_customize, // Pass the $wp_customize object (required).
				'retrogeek_textcolor', // Set a unique ID for the control.
				array(
					'label'    => __( 'Text Color', 'retrogeek' ), // Admin-visible name of the control.
					'settings' => 'textcolor', // Which setting to load and manipulate (serialized is okay).
					'priority' => 10, // Determines the order this control appears in for the specified section.
					'section'  => 'colors', // ID of the section this control should render in (can be one of yours, or a WordPress default section).
				)
			)
		);

		$wp_customize->add_setting(
			'link_textcolor',
			array(
				'default'           => '#74f774',
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		// Define the control itself (which links a setting to a section and renders the HTML controls).
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'retrogeek_link_textcolor',
				array(
					'label'             => __( 'Link Color', 'retrogeek' ),
					'settings'          => 'link_textcolor',
					'priority'          => 11,
					'section'           => 'colors',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			)
		);

		$wp_customize->add_setting(
			'rg_header_ascii',
			array(
				'default'           => false,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => array( 'RetroGeek_Customize', 'sanitize_checkbox' ),
			)
		);

		// Define the control itself (which links a setting to a section and renders the HTML controls).
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'retrogeek_header_ascii',
				array(
					'label'       => __( 'Title as ASCII-Art?', 'retrogeek' ),
					'settings'    => 'rg_header_ascii',
					'priority'    => 10,
					'section'     => 'title_tagline',
					'type'        => 'checkbox',
					'description' => __( 'If activated converts the blog title to a ASCII-Art representation', 'retrogeek' ),
				)
			)
		);

		$wp_customize->add_setting(
			'rg_header_ticker',
			array(
				'default'           => true,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => array( 'RetroGeek_Customize', 'sanitize_checkbox' ),
			)
		);

		// Define the control itself (which links a setting to a section and renders the HTML controls).
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'rg_header_ticker',
				array(
					'label'       => __( 'Retro-Ticker for Subtitle?', 'retrogeek' ),
					'settings'    => 'rg_header_ticker',
					'priority'    => 14,
					'section'     => 'title_tagline',
					'type'        => 'checkbox',
					'description' => __( 'If activated, show the tagline in a letter by letter way, like on a terminal in the 80s. Geeky.', 'retrogeek' ),
				)
			)
		);

		$wp_customize->add_setting(
			'rg_logo_radio',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => 'snail',
				'transport'         => 'refresh',
				'sanitize_callback' => array( 'RetroGeek_Customize', 'sanitize_logo_radio' ),
			)
		);

		$wp_customize->add_control(
			'rg_logo_radio',
			array(
				'type'        => 'radio',
				'section'     => 'title_tagline',
				'label'       => __( 'ASCII-Art Logo', 'retrogeek' ),
				'description' => __( 'Select your ASCII-Art logo.', 'retrogeek' ),
				'priority'    => 55,
				'choices'     => array(
					'snail'  => __( 'Snail', 'retrogeek' ),
					'kilroy' => __( 'Kilroy', 'retrogeek' ),
					'tux'    => __( 'Tux', 'retrogeek' ),
					'user'   => __( 'Userdefined', 'retrogeek' ),
					'none'   => __( 'None', 'retrogeek' ),
				),
			)
		);

		$wp_customize->add_setting(
			'rg_logo_userdefined',
			array(
				'capability'        => 'edit_theme_options',
				'default'           => '',
				'sanitize_callback' => 'sanitize_textarea_field',
			)
		);

		$wp_customize->add_control(
			'rg_logo_userdefined',
			array(
				'type'        => 'textarea',
				'section'     => 'title_tagline',
				'label'       => __( 'Custom ASCII-Art Logo', 'retrogeek' ),
				'description' => __( 'This is to have your own ASCII-Art Logog in the header of the site.', 'retrogeek' ),
				'priority'    => 56,
			)
		);

		$wp_customize->add_setting(
			'rg_homepage_searchform',
			array(
				'default'           => true,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => array( 'RetroGeek_Customize', 'sanitize_checkbox' ),
			)
		);

		// Define the control itself (which links a setting to a section and renders the HTML controls).
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'rg_homepage_searchform',
				array(
					'label'       => __( 'Toggle searchform', 'retrogeek' ),
					'settings'    => 'rg_homepage_searchform',
					'priority'    => 10,
					'section'     => 'static_front_page',
					'type'        => 'checkbox',
					'description' => __( 'If activated shows a searchform in the menu bar', 'retrogeek' ),
				)
			)
		);

		$wp_customize->add_setting(
			'rg_homepage_metadata',
			array(
				'default'           => true,
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => array( 'RetroGeek_Customize', 'sanitize_checkbox' ),
			)
		);

		// Define the control itself (which links a setting to a section and renders the HTML controls).
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'retrogeek_homepgae_metadata',
				array(
					'label'       => __( 'Toggle metadata', 'retrogeek' ),
					'settings'    => 'rg_homepage_metadata',
					'priority'    => 10,
					'section'     => 'static_front_page',
					'type'        => 'checkbox',
					'description' => __( 'If activated shows metadata about posts', 'retrogeek' ),
				)
			)
		);
	}


	/**
	 * This will output the custom WordPress settings fro CSS to the WP head.
	 *
	 * Used by hook: 'wp_enqueue_script'
	 */
	public static function header_output() {

		$m1 = get_theme_mod( 'textcolor', '#00ff00' );
		$m2 = get_theme_mod( 'link_textcolor', '#74f774' );
		$m3 = get_theme_mod( 'background_color', '#232323' );
		$m4 = get_theme_mod( 'header_textcolor', '#00ff00' );

		// Work around some WordPress special effects.
		if ( false === strpos( $m1, '#' ) ) {
			$m1 = '#' . $m1;
		}

		if ( false === strpos( $m2, '#' ) ) {
			$m2 = '#' . $m2;
		}

		if ( false === strpos( $m3, '#' ) ) {
			$m3 = '#' . $m3;
		}

		if ( false === strpos( $m4, '#' ) ) {
			$m4 = '#' . $m4;
		}

		$css_str  = "/* Customizer CSS */\n";
		$css_str .= ":root {\n";
		$css_str .= esc_attr( "--rg-text-color: $m1;\n" );
		$css_str .= esc_attr( "--rg-link-text-color: $m2;\n" );
		$css_str .= esc_attr( "--rg-background-color: $m3;\n" );
		$css_str .= esc_attr( "--rg-header-text-color: $m4;\n" );

		$css_str .= "}\n";
		$css_str .= "/* /Customizer CSS */\n";

		return $css_str;
	}


	/**
	 * Sanitize radio button input
	 *
	 * @param string $input string to sanitize.
	 * @param object $setting radio object.
	 **/
	public static function sanitize_logo_radio( $input, $setting ) {
		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize check box
	 *
	 * @param string $input String to sanitize.
	 **/
	public static function sanitize_checkbox( $input ) {
		// returns true if checkbox is checked.
		return ( ( isset( $input ) && true === $input ) ? true : false );
	}
}
