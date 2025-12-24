<?php
/**
 * Template Name: Page Full Width
 * Description: Display content for the homepage.
 *
 */

get_header(); ?>
	<div class="container-single-page container-default-page">
		<div id="main" class="penci-main-single-page-default">
			<?php
			while ( have_posts() ) : the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div><!-- ooo -->
<?php get_footer(); ?>