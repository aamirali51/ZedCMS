<?php
/**
 * The Template for displaying block template
 *
 * @package Wordpress
 * @since   1.0
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
	the_content();
endwhile; endif;
get_footer();
