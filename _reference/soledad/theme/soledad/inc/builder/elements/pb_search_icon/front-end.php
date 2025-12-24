<?php
$header_data = $args['header_data'];
$style = penci_builder_validate_mod( $header_data, 'penci_header_search_style' );
?>
<div id="top-search"
     class="pc-builder-element penci-top-search pcheader-icon top-search-classes <?php echo penci_builder_validate_mod( $header_data, 'penci_header_builder_search_icon_css_class' ); ?>">
    <a href="#" aria-label="Search" class="search-click pc-button-define-<?php echo penci_builder_validate_mod( $header_data, 'penci_header_search_icon_btn_style', 'customize' ); ?>">
        <i class="penciicon-magnifiying-glass"></i>
    </a>
    <div class="show-search pcbds-<?php echo $style; ?>">
        <?php if ( 'popup' == $style ) : ?>
            <h3 class="pc-search-top-title">
                <?php echo penci_get_setting( 'penci_trans_search_title' ); ?>
            </h3>
        <?php endif; ?>
		<?php penci_search_form( [ 'innerclass' => true, 'innerclass_css' => 'pc-searchform-inner pc-eajxsearch' ] ); ?>
        <?php if ( 'popup' == $style ) : ?>
            <div class="pc-search-suggest-term post-tags">
                <?php wp_list_categories( array(
						'title_li'   => '',
						'style'      => '',
						'separator'  => '',
						'orderby'    => 'name',
						'show_count' => false,
						'taxonomy'   => 'category',
						'number'     => 10,
						'depth'      => 1,
				) ); ?>
            </div>
			<?php
			$recent_posts = get_posts( array(
				'numberposts' => 4,
				'post_status' => 'publish',
			) );
			if ( $recent_posts ) : ?>
                <div class="pc-search-recent-posts">
                    <h3 class="pc-search-recent-posts-title">
                        <?php echo penci_get_setting( 'penci_trans_recent' ); ?>
                    </h3>
                    <div class="penci-smalllist pcsl-wrapper pwsl-id-default">
                        <div class="pcsl-inner penci-clearfix pcsl-grid pencipw-hd-text pcsl-imgpos-top pcsl-col-4 pcsl-tabcol-2 pcsl-mobcol-1">
							<?php foreach ( $recent_posts as $post ) : setup_postdata( $post ); ?>
                                <div class="pcsl-item">
                                    <div class="pcsl-itemin">
                                        <div class="pcsl-iteminer">

                                            <div class="pcsl-thumb">

                                                <a <?php echo penci_layout_bg(penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ), false );?> href="<?php the_permalink(); ?>"
                                                                title="<?php echo wp_strip_all_tags( get_the_title() ); ?>"
                                                                class="<?php echo penci_layout_bg_class(false);?> penci-image-holder">
                                                                    <?php echo penci_layout_img(penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ),get_the_title(),false);?>
                                                                </a>

                                            </div>
                                            <div class="pcsl-content">

                                                <div class="pcsl-title">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title();?></a>
                                                </div>

                                                <div class="grid-post-box-meta pcsl-meta">
                                                    <span class="sl-date"><?php penci_soledad_time_link(); ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
							<?php endforeach;
							wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
			<?php
			endif;
			wp_reset_postdata();
		endif;
		?>
        <a href="#" aria-label="Close" class="search-click close-search"><i class="penciicon-close-button"></i></a>
    </div>
</div>
<?php if ( 'popup' == $style ) : ?>
    <div class="pc-search-popup-overlay"></div>
<?php endif; ?>