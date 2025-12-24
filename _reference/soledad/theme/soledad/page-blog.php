<?php
get_header();
?>
    <div class="container-single-page container-default-page">
        <div id="main" class="penci-main-single-page-default">
			<?php
			$page_id = get_option( 'page_for_posts' );
			echo penci_get_elementor_content( $page_id );
			?>
        </div>
    </div>
<?php
get_footer();