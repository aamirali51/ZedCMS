<div class="header-builder-sticky header-builder-sticky-<?php echo esc_html( $row ); ?> flex-row header-builder-row"
     data-row="<?php echo esc_html( $row ); ?>">
    <div class="header-builder-row-option" data-section="penci_header_desktop_sticky_<?php echo esc_html( $row ); ?>">
        <i class="fa fa-cog"></i> <?php echo esc_html__( 'Sticky Bar - ' . $row, 'soledad' ); ?>
    </div>
	<?php
	$columns = array( 'left', 'center', 'right' );
	foreach ( $columns as $column ) {
		$template->render( 'header-sticky-column', array(
			'row'      => $row,
			'column'   => $column,
			'template' => $template
		), true );
	}
	?>
</div>
