<?php
wp_enqueue_script( 'pcwp-heading-animate', PENCI_SOLEDAD_URL . '/js/heading-animates-wb.js', [ 'jquery' ], PENCI_SOLEDAD_VERSION, true );
$settings            = vc_map_get_attributes( $this->getShortcode(), $atts );
$tag                 = esc_attr( $settings['text_html_tag'] );
$normal_color_style  = 'color';
$dynamic_color_style = 'color';
$style               = $settings['text_style'];
$link                = '';

$text_link = [];

if ( isset( $settings['text_link']['url'] ) && ! empty( $settings['text_link']['url'] ) ) {
	$text_link[] = 'href="' . esc_url( $settings['text_link']['url'] ) . '"';
	$text_link[] = 'class="pc-textlink"';
	if ( isset( $settings['text_link']['is_external'] ) && ! empty( $settings['text_link']['is_external'] ) ) {
		$text_link[] = 'target="_blank"';
	}
	if ( isset( $settings['text_link']['nofollow'] ) && $settings['text_link']['nofollow'] ) {
		$text_link[] = 'rel="nofollow"';
	}
	$link = '<a ' . implode( ' ', $text_link ) . '></a>';
}

if ( 'rotating' === $style ) {
	$text   = array();
	$lists  = explode( ',', $settings['text_animated_list'] );
	$rotate = $settings['text_rotating'];
	$delay  = $settings['text_delay_change'] ? $settings['text_delay_change'] : 2500;

	foreach ( $lists as $list ) {
		array_push( $text, $list );
	}

	$text    = implode( ',', $text );
	$options = array(
		'style'  => esc_attr( $style ),
		'text'   => esc_attr( $text ),
		'rotate' => esc_attr( $rotate ),
		'delay'  => esc_attr( $delay ),
	);

	if ( in_array( $rotate, array( 'typing', 'swirl', 'blinds', 'wave' ), true ) ) {
		$options['letter-speed'] = esc_attr( $settings['text_letter_speed'] ? $settings['text_letter_speed'] : 100 );
	}

	if ( 'clip' === $rotate ) {
		$options['clip-duration'] = esc_attr( $settings['text_clip_duration'] ? $settings['text_clip_duration'] : 2000 );
	}

	if ( 'typing' === $rotate ) {
		$options['delay-delete'] = esc_attr( $settings['text_delay_delete'] ? $settings['text_delay_delete'] : 500 );
	}
} elseif ( 'highlighted' === $style ) {
	$options = array(
		'style' => esc_attr( $style ),
		'text'  => esc_attr( $settings['text_animated'] ),
		'shape' => esc_attr( $settings['text_shape'] ),
	);
} else {
	$options = array( 'style' => esc_attr( $style ) );
}

$headline = [];

foreach ( $options as $option => $value ) {
	$headline[] = 'data-' . $option . '="' . $value . '"';
}

$id = 'wppcel-' . rand();

$headline[] = 'class="penci-animated-headline"';

$text = '<' . $tag . ' class="penci-animated-text">';

$inner_text = '<span class="normal-text style-' . $normal_color_style . '">' . esc_attr( $settings['text_before'] ) . '</span>';

if ( 'rotating' === $style ) {

	$lists        = explode( ',', $settings['text_animated_list'] );
	$rotate_style = $settings['text_rotating'];

	$inner_text = $inner_text . '<span class="dynamic-wrapper style-' . $dynamic_color_style . '">';
	$text_list  = '';
	if ( in_array( $rotate_style, array( 'typing', 'swirl', 'blinds', 'wave' ), true ) ) {
		foreach ( $lists as $text_string ) {
			$text_length = mb_strlen( $text_string, 'UTF-8' );
			$text_list   = $text_list . '<span class="dynamic-text">';

			for ( $i = 0; $i < $text_length; $i ++ ) {
				$text_list = $text_list . '<span class="dynamic-text-letter">' . mb_substr( $text_string, $i, 1, 'UTF-8' ) . '</span>';
			}

			$text_list = $text_list . '</span>';
		}
	} else {
		foreach ( $lists as $text_string ) {
			$text_list = $text_list . '<span class="dynamic-text">' . $text_string . '</span>';
		}
	}

	$inner_text .= $text_list . '</span>';


} elseif ( 'highlighted' === $style ) {
	$inner_text = $inner_text . ' <span class="dynamic-wrapper style-' . $dynamic_color_style . '"><span class="dynamic-text">' . esc_attr( $settings['text_animated'] ) . '</span> ' . penci_animated_heading_stroke( $settings['text_shape'], 'color' ) . '</span>';
} else {
	$inner_text = $inner_text . ' <span class="dynamic-wrapper style-' . $dynamic_color_style . '"><span class="dynamic-text">' . esc_attr( $settings['text_animated'] ) . '</span></span> ';
}

$inner_text = $inner_text . ' <span class="normal-text style-' . $normal_color_style . '">' . esc_attr( $settings['text_after'] ) . '</span>';

$text .= $inner_text;

$text .= '</' . $tag . '>';

$css = [
	'text_alignment'               => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text' => 'text-align:{{VALUE}}' ],
	'text_normal_typography'       => '{{WRAPPER}} .penci-animated-headline .penci-animated-text .normal-text',
	'text_normal_color'            => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text .normal-text' => 'color:{{VALUE}}' ],
	'text_normal_acolor'           => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .normal-text' => 'color:{{VALUE}}' ],
	'text_ani_typography'          => '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-text',
	'text_ani_color'               => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text .dynamic-wrapper.style-color .dynamic-text' => 'color:{{VALUE}}' ],
	'text_ani_acolor'              => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text:hover .dynamic-wrapper.style-color .dynamic-text' => 'color:{{VALUE}}' ],
	'highlight_width'              => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => 'stroke-width:{{SIZE}}px' ],
	'highlight_animation_duration' => [ '{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => '-moz-animation-duration: {{SIZE}}s; -webkit-animation-duration: {{SIZE}}s; -o-animation-duration: {{SIZE}}s; -ms-animation-duration: {{SIZE}}s; animation-duration: {{SIZE}}s;' ],
	'highlight_animation_delay'    => [
		'{{WRAPPER}} .penci-animated-headline .penci-animated-text svg path' => '-moz-animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s; -o-animation-delay: {{SIZE}}s; -ms-animation-delay: {{SIZE}}s; animation-delay: {{SIZE}}s;'
	],
	'cursor_color'                 => [ '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=typing] .penci-animated-text .dynamic-wrapper:after' => 'background-color:{{VALUE}}' ],
	'typing_delete_color'          => [ '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=typing] .penci-animated-text .dynamic-wrapper.typing-delete .dynamic-text .dynamic-text-letter' => 'color:{{VALUE}}' ],
	'typing_background'            => [ '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=typing] .penci-animated-text .dynamic-wrapper.typing-delete' => 'background-color:{{VALUE}}' ],
	'clip_width'                   => array(
		'{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=clip] .penci-animated-text .dynamic-wrapper:after' => 'width: {{SIZE}}px;',
	),
	'clip_background'              => '{{WRAPPER}} .penci-animated-headline[data-style=rotating][data-rotate=clip] .penci-animated-text .dynamic-wrapper:after',

];

$css_out = '';

foreach ( $css as $mod => $selectors ) {
	$mod_val = isset( $settings[ $mod ] ) && $settings[ $mod ] ? $settings[ $mod ] : '';
	if ( $mod_val ) {
		if ( is_array( $selectors ) ) {
			foreach ( $selectors as $selector => $prop ) {
				$selector = str_replace( '{{WRAPPER}}', '#' . $id, $selector );
				$css_out  .= $selector . '{' . str_replace( '{{VALUE}}', $mod_val, $prop ) . '}';
			}
		} else {
			$selector = str_replace( '{{WRAPPER}}', '#' . $id, $selectors );
			$css_out  .= $selector . '{' . penci_soledad_vc_extract_font_prop( $mod_val ) . '}';
		}
	}
}

echo '<div id="' . esc_attr( $id ) . '"><div ' . implode( ' ', $headline ) . '>' . $text . $link . '</div></div>';

if ( $css_out ) {
	echo '<style>' . $css_out . '</style>';
}