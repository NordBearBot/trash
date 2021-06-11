<?php
/**
 * The main template file
 *
 * Description. (use period)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/URL
 *
 * @package retrogeek
 * @since 22.02.2021
 */

get_header(); ?>

<main id="main" class="site-main">
	<div class="row">
	<div class="two columns">&nbsp;</div>
	<div id="primary" class="eight columns">



	<article class="content-area">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
		<div id="post1-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
				<div id="thumbnail-post-<?php the_ID(); ?>" class="retrogeek-thumbnail">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail( 'medium' ); ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="entry-right">
				<header class="entry-header">
					<h5 class="entry-title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h5>
				</header>

				<?php if ( ! is_singular() ) : ?>
				<div class="entry">
					<?php the_excerpt(); ?>
					<?php wp_link_pages(); ?>
				</div>
				<?php endif; ?>

				<div class="metadata">
					<?php if ( ! is_singular() ) : ?>
						<span class="u-pull-right" ><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_date(); ?></a></span>
					<?php else : ?>

						<?php
						the_content( __( 'Continue reading', 'retrogeek' ) );


						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}

						if ( is_singular( 'attachment' ) ) {
							// Parent post navigation.
							the_post_navigation(
								array(
									'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'retrogeek' ),
								)
							);
						}

						wp_link_pages(
							array(
								'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'retrogeek' ) . '">',
								'after'    => '</nav>',
								/* translators: %: page number. */
								'pagelink' => esc_html__( 'Page %', 'retrogeek' ),
							)
						);
						?>
						<div class="clearfix"></div>
						<?php if ( get_theme_mod( 'rg_homepage_metadata', true ) ) : ?>
							<hr>
							<?php if ( is_single() ) : ?>
							<p><span class="linkcat"><?php esc_html_e( 'Filed under: ', 'retrogeek' ); ?> <?php the_category( ',' ); ?> </span> -  @ <?php the_date(); ?> <?php the_time(); ?> <?php edit_post_link( __( 'Edit This', 'retrogeek' ) ); ?></p>
							<p class="taglink"><?php the_tags(); ?></p>
							<?php else : ?>
							<p><?php esc_html_e( 'Published', 'retrogeek' ); ?> @ <?php the_date(); ?> <?php the_time(); ?> <?php edit_post_link( __( 'Edit This', 'retrogeek' ) ); ?></p>
							<?php endif; ?>
						<?php endif; ?>

					<?php endif; ?>
				</div>
			</div> <!-- entry-right -->

			<div class="u-cf"></div>

		</div> <!-- post1 -->
				<?php
				endwhile;
			else :
				?>
				<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>
		<div class="article-footer"><hr></div>
	</article>
	</div><!-- eight columns -->
	</div><!-- row -->

	<div class="row">
	<div class="two columns">&nbsp;</div>
	<div id="pagenav" class="eight columns">
	<?php
		// Previous/next page navigation.
		the_posts_pagination(
			array(
				'before_page_number'  => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'retrogeek' ) . ' </span>',
				'ascreen_reader_text' => '&nbsp;',
			)
		);
		?>
	</div><!-- eight columns -->
	</div><!-- row -->

</main><!-- .site-main -->
<?php get_footer(); ?>
