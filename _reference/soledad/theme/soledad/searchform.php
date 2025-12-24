<form role="search" method="get" class="pc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="pc-searchform-inner">
        <input type="text" class="search-input"
               placeholder="<?php echo penci_get_setting( 'penci_trans_type_and_hit' ); ?>" name="s"/>
        <i class="penciicon-magnifiying-glass"></i>
        <input type="submit" class="searchsubmit penci-ele-btn" value="<?php echo penci_get_setting( 'penci_trans_search' ); ?>"/>
    </div>
</form>
