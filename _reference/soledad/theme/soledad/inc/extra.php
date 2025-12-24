<?php
add_action( 'admin_init', 'penci_get_abobe_kits' );
function penci_get_abobe_kits() {

    if ( current_user_can( 'manage_options' ) && isset( $_REQUEST['clear_pencitypekit_css'] ) && $_REQUEST['clear_pencitypekit_css'] ) {
        delete_option( 'penci-typekit-data' );
    }

    $api_key = get_theme_mod( 'penci_api_adobe_font' );

    // If data exists we don't need to query the API.
    if ( get_option( 'penci-typekit-data', false ) ) {
        return;
    }

    if ( ! $api_key ) {
        return;
    }

    $url       = 'https://typekit.com/api/v1/json/kits/';
    $curl_args = array();
    $response  = wp_remote_request( $url . '?token=' . esc_attr( $api_key ), $curl_args );

    if ( wp_remote_retrieve_response_code( $response ) != '200' ) {
        return;
    }

    $response_body = json_decode( wp_remote_retrieve_body( $response ) );
    $kits          = array();

    if ( is_array( $response_body->kits ) ) {
        // loop through the kits object.
        foreach ( $response_body->kits as $kit ) {
            // perform an API request for the individual kit.
            $data = penci_get_kit_from_api( $kit->id, $api_key );

            if ( $data ) {
                // Enable kits by default.
                $kits[ $kit->id ]['enabled'] = true;
                // loop through the kit and standardize the data.
                foreach ( $data->families as $family ) {
                    $kits[ $kit->id ]['families'][] = array(
                        'label'    => $family->name,
                        'id'       => $family->slug,
                        'variants' => array_map( 'penci_standardize_variant_names', $family->variations ),
                        'stack'    => $family->css_stack,
                    );
                }
            }
        }
    }
    // Save the results so we don't need to query the API again.
    update_option( 'penci-typekit-data', $kits );
}

function penci_get_kit_font_family() {
    $typekit_data = get_option( 'penci-typekit-data' );
    $family = [];
    if ( ! empty ( $typekit_data ) ) {
        foreach ( $typekit_data as $id => $values ) {
            
            foreach ( $values['families'] as $font_id => $font_data ) {
                $label = $font_data['label'];
                $id = $font_data['id'];
                $family[ $id ] = $label;
            }
        }
    }
    return $family;
}
function penci_get_kit_font_id() {
    $typekit_data = get_option( 'penci-typekit-data' );
    $family = [];
    if ( ! empty ( $typekit_data ) ) {
        foreach ( $typekit_data as $id => $values ) {
            
            foreach ( $values['families'] as $font_id => $font_data ) {
                $label = $font_data['id'];
                $family[ $label ] = $label;
            }
        }
    }
    return $family;
}
add_filter( 'penci_font_list', function( $font_list ) {
    if ( !empty ( penci_get_kit_font_family() ) ) {
        $font_list = array_merge( penci_get_kit_font_family(), $font_list ); 
    }
    return $font_list;
} );

add_filter( 'penci_exlude_fonts_css', function( $font_list ) {
    if ( !empty ( penci_get_kit_font_id() ) ) {
        $font_list = array_merge( penci_get_kit_font_id(), $font_list ); 
    }
    return $font_list;
} );

function penci_get_kit_from_api( $kit_id, $api ) {
    $url       = 'https://typekit.com/api/v1/json/kits/' . esc_attr( $kit_id ) . '?token=' . esc_attr( $api );
    $curl_args = array();
    $response  = wp_remote_request( $url, $curl_args );

    if ( wp_remote_retrieve_response_code( $response ) === 200 ) {
        $response_body = json_decode( wp_remote_retrieve_body( $response ) );
        return $response_body->kit;
    }

    return false;
}

function penci_standardize_variant_names( $variant ) {
    $variants_key = array(
        'n1' => '100',
        'n2' => '200',
        'n3' => '300',
        'n4' => '400',
        'n5' => '500',
        'n6' => '600',
        'n7' => '700',
        'n8' => '800',
        'n9' => '900',
        'i1' => '100i',
        'i2' => '200i',
        'i3' => '300i',
        'i4' => '400i',
        'i5' => '500i',
        'i6' => '600i',
        'i7' => '700i',
        'i8' => '800i',
        'i9' => '900i',
    );

    if ( array_key_exists( $variant, $variants_key ) ) {
        return $variants_key[ $variant ];
    } else {
        return $variant;
    }
}

add_action( 'wp_enqueue_scripts', 'penci_typekit_enqueue_css' );
function penci_typekit_enqueue_css() {
    $typekit_data = get_option( 'penci-typekit-data', array() );

    if ( is_array( $typekit_data ) ) {
        foreach ( $typekit_data as $id => $values ) {

            // skip if the kit is disabled.
            if ( $values['enabled'] === false ) {
                continue;
            }

            $clean_id = sanitize_text_field( $id );

            wp_enqueue_style( 'typekit-' . $clean_id, 'https://use.typekit.com/' . $clean_id . '.css', array(), PENCI_SOLEDAD_VERSION );
        }
    }
}

if ( ! function_exists( 'penci_clear_typekit_toolbar_link' ) ) {
	function penci_clear_typekit_toolbar_link( $wp_admin_bar ) {

		if ( current_user_can( 'manage_options' ) &&  get_option( 'penci-typekit-data' ) ) {
			$btn_title = 'Clear Tyepkit Fonts Cache';
			$args = array(
				'id'    => 'pencitypekit-clearcache',
				'title' => $btn_title,
				'href'  => '?clear_pencitypekit_css=true',
				'meta'  => array(
					'class' => 'pencitypekit-clear-button',
					'title' => $btn_title,
				)
			);
			$wp_admin_bar->add_node( $args );
		}
	}

	add_action( 'admin_bar_menu', 'penci_clear_typekit_toolbar_link', 999 );
}