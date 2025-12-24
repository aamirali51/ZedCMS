<?php

$postid = get_the_ID();
$page_title = get_post_meta( get_the_ID(), 'penci_pmeta_page_title', true );

$pheader_hideline      = get_theme_mod( 'penci_pheader_hideline' );
$pheader_hidebead      = get_theme_mod( 'penci_pheader_hidebead' );
$pheader_align         = get_theme_mod( 'penci_pheader_align' );

$page_title_df = array(
	'pheader_hideline'      => '',
	'pheader_hidebead'      => '',
	'pheader_align'         => ''
);

$page_title = wp_parse_args( $page_title, $page_title_df );

if( 'hide' == $page_title['pheader_hidebead'] ){
	$pheader_hidebead = true;
}elseif( 'show' == $page_title['pheader_hidebead'] ){
	$pheader_hidebead = false;
}

if( 'hide' == $page_title['pheader_hideline'] ){
	$pheader_hideline = true;
}elseif( 'show' == $page_title['pheader_hideline'] ){
	$pheader_hideline = false;
}


if( $page_title['pheader_align'] ){
	$pheader_align = $page_title['pheader_align'];
}

$class_page_header = 'penci-page-header-wrap';
$class_page_header .= ' penci-pheader-'.  esc_attr( $pheader_align ? $pheader_align : 'center' );
$class_page_header .= $pheader_hidebead  ? ' penci-phhide-bread' : '';
$class_page_header .= $pheader_hideline  ? ' penci-phhide-line' : '';
?>
<div class="<?php echo esc_attr( $class_page_header ); ?>">
	<div class="penci-page-header-inner container">
		<h1 class="penci-page-header-title"> <?php echo get_the_title( $postid ); ?> </h1>

		<?php if ( ! $pheader_hidebead ) : ?>
			<?php
			$yoast_breadcrumb = '';
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				$yoast_breadcrumb = yoast_breadcrumb( '<div class="container container-single-page penci-breadcrumb">', '</div>', false );
			}

			if( $yoast_breadcrumb ){
				echo $yoast_breadcrumb;
			}else{ ?>
				<div class="container container-single-page penci-breadcrumb">
					<span><a class="crumb" href="<?php echo esc_url( home_url('/') ); ?>"><?php echo penci_get_setting( 'penci_trans_home' ); ?></a></span><?php penci_fawesome_icon('fas fa-angle-right'); ?>
					<?php
					$page_parent = get_post_ancestors( get_the_ID() );
					if( ! empty( $page_parent ) ):
						$page_parent = array_reverse($page_parent);
						foreach( $page_parent as $pages ){
							?>
							<span><a class="crumb" href="<?php echo get_permalink( $pages ); ?>"><?php echo get_the_title( $pages ); ?></a></span><?php penci_fawesome_icon('fas fa-angle-right'); ?>
						<?php }
					endif; ?>
					<span><?php the_title(); ?></span>
				</div>
			<?php } ?>
		<?php endif; ?>
	</div>
</div>
