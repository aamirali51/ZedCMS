<?php
/**
 * Archive Sorter
 *
 * @package Soledad
 */
global $wp;
$show_sorter = get_theme_mod( 'penci_general_show_post_order' );
$show_month  = get_theme_mod( 'penci_archive_show_date_filter' );
if ( $show_sorter || $show_month ): ?>
    <div class="pc-title-bar-sorter">
        <form action="<?php echo home_url( $wp->request ); ?>" method="get">
			<?php if ( $show_sorter ) :
				$current_sort = get_query_var( 'pc_archive_sort', 'desc' );
				?>
                <select class="penci-arfilter-item" name="pc_archive_sort" id="pc_archive_sort">
                    <option <?php selected( 'desc', $current_sort ); ?>
                            value="desc"><?php echo penci_get_setting( 'penci_trans_newest' ); ?></option>
                    <option <?php selected( 'asc', $current_sort ); ?>
                            value="asc"><?php echo penci_get_setting( 'penci_trans_oldest' ); ?></option>
                    <option <?php selected( 'view', $current_sort ); ?>
                            value="view"><?php echo penci_get_setting( 'penci_trans_mostviewed' ); ?></option>
                    <option <?php selected( 'comment', $current_sort ); ?>
                            value="comment"><?php echo penci_get_setting( 'penci_trans_mostcommented' ); ?></option>
                </select>
			<?php endif;
            if ( $show_month ) {
				penci_show_archive_month_select();
			} ?>
        </form>
    </div>
<?php endif; ?>