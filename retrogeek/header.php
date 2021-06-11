<?php
/**
 * The template for displaying the header.
 *
 * Description: The area of the page that contains the ASCII-Art header and navbar.
 *
 * @package retrogeek
 * @since 22.02.2021
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
		<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" >

	<!-- columns should be the immediate child of a .row -->
	<div class="row">
		<div class="two columns">&nbsp;</div>
		<div class="eight columns">
		<a class="screen-reader-text skip-link" href="#content"><?php echo esc_html__( 'Skip to content', 'retrogeek' ); ?></a>
		<!-- Mobile Top-Menubar -->
		<div class="toggle-topnav-div"><a class="toggle-topnav" href="#" onclick="retrogeek_toggle_mobile_menu();">&#9776;</a></div>
		<nav class="topnav">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'menu_class'      => 'top-nav',
					'menu_id'         => 'mobile-menu',
					'container_class' => 'top-nav',
				)
			);
		}
		?>
		</nav>

			<div class="site-branding" 
			<?php
			if ( ! display_header_text() ) {
				echo ' style="display:none" ';
			}
			?>
			>

				<div id="asciiartlogo" class="u-pull-left three columns" style="padding-right: 20px;">
					<a style="text-decoration:none !important;" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php
				if ( has_custom_logo() ) {
					$rg_custom_logo_id = get_theme_mod( 'custom_logo' );
					$rg_image          = wp_get_attachment_image_src( $rg_custom_logo_id, 'full' );
					echo '<img src=' . esc_url( $rg_image[0] ) . ' class="custom-logo" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
				} else {
					echo '<div><pre>';
					$rg_asciilogo_string = retrogeek_get_asciilogo();
					echo esc_attr( $rg_asciilogo_string );
					echo '</pre></div>';
				} // end of the_header_logo else
				?>
					</a>
				</div>

				<div class="u-pull-left">
					<!-- mobile site title -->
					<h1 class="site-title-mob">
						<a style="text-decoration:none !important;" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>
						</a>
					</h1>

				<?php
				// custom header image.
				if ( get_header_image() ) :
					?>
					<div class="header-image">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img src="<?php header_image(); ?>" 
								srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id ) ); ?>" 
								width="<?php echo esc_attr( get_custom_header()->width ); ?>" 
								height="<?php echo esc_attr( get_custom_header()->height ); ?>" 
								alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"
							>
						</a>
					</div><!-- .header-image -->
				<?php endif; // end header image check. ?>

					<?php
					if ( ! get_header_image() ) {
						// ASCII site title generation.
						$rg_header_ascii = get_theme_mod( 'rg_header_ascii', false );
						if ( $rg_header_ascii ) {
							$rg_figlet = new Retrogeek_Text_Figlet();
							$rg_figlet->load_font( get_template_directory() . '/inc/slant.flf' );
							$rg_banner = $rg_figlet->line_echo( retrogeek_utf8tofiglet( get_bloginfo( 'name' ) ) );
						} else {
							$rg_banner = get_bloginfo( 'name' );
						}

						echo '<!-- Site title as ASCII or Text -->';
						echo '<div class="site-title">';
						echo '<a style="text-decoration:none !important;" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
						if ( $rg_header_ascii ) {
							echo '<pre>' . esc_html( $rg_banner ) . '</pre>';
						} else {
							echo '<h1>' . esc_html( $rg_banner ) . '</h1>';
						}
						echo '</a></div>';
					}
					?>
				</div>
				</div> <!-- site-branding -->
				<div class="u-cf"></div>

				<?php
				$rg_header_ticker = get_theme_mod( 'rg_header_ticker', true );
				// modem receiving text like ticker to show the blog description.
				$rg_description = get_bloginfo( 'description', 'display' );
				if ( $rg_description && ! $rg_header_ticker ) :
					?>
					<div id="rgcontainer"  
					<?php
					if ( ! display_header_text() ) {
						echo ' style="display:none" ';
					}
					?>
					>
					<?php echo esc_html( $rg_description ); ?>
					</div><br />
				<?php endif; ?>
				<?php
				// Handle the ticker parameters and add the ticker script and execution.
				if ( $rg_description && $rg_header_ticker ) {
					echo '<div id="rgcontainer"';
					if ( ! display_header_text() ) {
						echo ' style="display:none" ';
					}
					echo '></div>';

					// add the ticker javascript and execution.
					wp_add_inline_script(
						'retrogeek-javascript',
						'rg_terminal("rgcontainer", "' . esc_attr( $rg_description ) . '", "site-description" );',
						'after'
					);
				}
				?>

				<!-- Menubar -->
				<nav class="nav">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'menu_class'      => 'main-nav',
							'container_class' => 'main-nav',
						)
					);
				}
				?>
				</nav>

		</div><!-- eight columns -->
	</div> <!-- row -->
</header><!-- .site-header -->

	<div id="content" class="site-content">
