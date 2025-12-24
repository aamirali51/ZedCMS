<div class="header-builder-<?php echo esc_html( $row ); ?> flex-row header-builder-row"
     data-row="<?php echo esc_html( $row ); ?>">
    <div class="header-builder-row-option"
         data-section="penci_header_<?php echo esc_html( $row ) ?>bar_setting">
        <i class="fa fa-cog"></i> <?php echo esc_html( ucfirst( $row ) ) . ' ' . esc_html__( 'Bar', 'soledad' ); ?>
    </div>
    <div class="header-builder-row-drag-handle"><i class="fa fa-arrows"></i></div>
	<?php
	$columns = array( 'left', 'center', 'right' );
	if ( 'topblock' === $row || 'bottomblock' === $row ) {
		$columns = [ 'center' ];
	}
	foreach ( $columns as $column ) {
		$template->render( 'header-column', array(
			'row'      => $row,
			'column'   => $column,
			'template' => $template
		), true );
	}
	?>
</div>
