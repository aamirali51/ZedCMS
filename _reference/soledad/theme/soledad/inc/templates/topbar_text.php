<?php if( penci_get_setting( 'penci_tb_date_text' ) ){
$custom_text = penci_get_setting( 'penci_tb_date_text' );
?>
<div class="pctopbar-item penci-topbar-ctext">
	<?php 
	$tex = do_shortcode( $custom_text ); 
	$tex = str_replace('</span> ', '</span>&nbsp;', $tex);
	echo $tex;
	?>
</div>
<?php } ?>