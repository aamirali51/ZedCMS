<div class="penci-stock-progress-bar">
    <div class="stock-info">
        <div class="total-sold"><?php echo penci_woo_translate_text('penci_woo_trans_ordered'); ?>
            <span><?php esc_html_e( $args['total_sold'] ); ?></span>
        </div>
        <div class="current-stock"><?php echo penci_woo_translate_text('penci_woo_trans_items_avail'); ?>
            <span><?php esc_html_e( $args['current_stock'] ); ?></span>
        </div>
    </div>
    <div class="progress-area"
         title="<?php echo penci_woo_translate_text('penci_woo_trans_sold'); ?> <?php esc_html_e( $args['percentage'] ); ?>%">
        <div class="progress-bar" style="width:<?php echo esc_attr( $args['percentage'] ); ?>%;"></div>
    </div>
</div>
