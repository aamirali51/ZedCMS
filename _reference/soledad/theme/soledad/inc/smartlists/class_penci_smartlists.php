<?php

abstract class Penci_SmartLists {

	private $counting_order_asc = false;
	private $counting_start = 1;
	protected $use_pagination = false;
	private $list_items;

	abstract protected function render_list_item( $item_array, $current_item_id, $current_item_number, $total_items_number );

	function get_content( $smart_list_settings ) {

		$this->counting_order_asc = $smart_list_settings['counting_order_asc'];


		$penci_tokenizer                   = new Penci_Splitter();
		$penci_tokenizer->list_title_start = $smart_list_settings['penci_smart_list_h'];
		$penci_tokenizer->list_title_end   = $smart_list_settings['penci_smart_list_h'];


		$list_items = $penci_tokenizer->split_to_list_items( array(
				'content'             => $smart_list_settings['post_content'],
				'extract_first_image' => $smart_list_settings['extract_first_image']
			)
		);

		if ( empty( $list_items['list_items'] ) ) {
			return $smart_list_settings['post_content'];
		}

		$list_items = $this->add_numbers_to_list_items( $list_items );

		if ( $this->use_pagination === true ) {

			$current_page = $this->get_current_page( $list_items );

			return $this->render( $list_items, $current_page );
		} else {
			return $this->render( $list_items );
		}

	}


	private function add_numbers_to_list_items( $list_items ) {

		$total_items_number = count( $list_items['list_items'] ) - 1 + $this->counting_start; // fix for 0 base counting (0 of 3 - to -  3 of 3)

		foreach ( $list_items['list_items'] as $list_item_key => &$list_item ) {

			if ( $this->counting_order_asc === true ) {
				$current_item_index = $list_item_key + $this->counting_start;
			} else {
				$current_item_index = $total_items_number - ( $list_item_key );
			}

			$list_item['current_item_number'] = $current_item_index;
			$list_item['total_items_number']  = $total_items_number;
		}

		return $list_items;
	}

	private function render( $list_items, $current_page = false ) {


		$this->list_items = $list_items;

		$content = '';

		if ( ! empty( $list_items['before_list'] ) ) {
			$content .= implode( '', $list_items['before_list'] );
		}

		$content .= $this->render_before_list_wrap();  //from child class

		if ( $current_page === false ) {
			foreach ( $list_items['list_items'] as $list_item_key => $list_item ) {
				$content .= $this->render_list_item( $list_item, $list_item_key + 1, $list_item['current_item_number'], $list_item['total_items_number'] );
			}
		} else {

			$array_id_from_paged = $current_page - 1;
			$content             .= $this->render_list_item(
				$list_items['list_items'][ $array_id_from_paged ],
				$array_id_from_paged,
				$list_items['list_items'][ $array_id_from_paged ]['current_item_number'],
				$list_items['list_items'][ $array_id_from_paged ]['total_items_number']
			);
		}

		$content .= $this->render_after_list_wrap();

		if ( ! empty( $list_items['after_list'] ) ) {
			$content .= implode( '', $list_items['after_list'] );
		}

		return $content;
	}

	protected function callback_render_pagination() {


		$content = '';

		$current_page = $this->get_current_page( $this->list_items );
		$total_pages  = count( $this->list_items['list_items'] );

		if ( $total_pages == 1 ) {
			return '';
		}

		if ( $current_page == 1 ) {
			$content .= '<div class="pcsml-pagination">';
			$content .= '<span class="pcsml-button pcsm-back pcsm-disable"><i class="penciicon-left-chevron"></i>' . penci_get_setting( 'penci_trans_back' ) . '</span>';
			$content .= '<a class="pcsml-button pcsm-next" rel="next" href="' . $this->_wp_link_page( $current_page + 1 ) . '">' . penci_get_setting( 'penci_trans_next' ) . '<i class="penciicon-right-chevron"></i></a>';
			$content .= '</div>';
		} elseif ( $current_page == $total_pages ) {
			$content .= '<div class="pcsml-pagination">';
			$content .= '<a class="pcsml-button pcsm-back" rel="prev" href="' . $this->_wp_link_page( $current_page - 1 ) . '"><i class="penciicon-left-chevron"></i>' . penci_get_setting( 'penci_trans_back' ) . '</a>';
			$content .= '<span class="pcsml-button pcsm-next pcsm-disable">' . penci_get_setting( 'penci_trans_next' ) . '<i class="penciicon-right-chevron"></i></span>';
			$content .= '</div>';
		} else {
			$content .= '<div class="pcsml-pagination">';
			$content .= '<a class="pcsml-button pcsm-back" rel="prev" href="' . $this->_wp_link_page( $current_page - 1 ) . '"><i class="penciicon-left-chevron"></i>' . penci_get_setting( 'penci_trans_back' ) . '</a>';
			$content .= '<a class="pcsml-button pcsm-next" rel="next" href="' . $this->_wp_link_page( $current_page + 1 ) . '">' . penci_get_setting( 'penci_trans_next' ) . '<i class="penciicon-right-chevron"></i></a>';
			$content .= '</div>';
		}

		return $content;
	}


	protected function callback_render_drop_down_pagination() {
		$content = '';


		$current_page = $this->get_current_page( $this->list_items );
		$total_pages  = count( $this->list_items['list_items'] );

		if ( $total_pages == 1 ) {
			return '';
		}


		$content .= '<div class="pcsml-dropdown-wrap">';


		if ( $current_page == 1 ) {
			$content .= '<span class="pcsml-button pcsm-back pcsm-disable"><i class="penciicon-left-chevron"></i><span>' . penci_get_setting( 'penci_trans_back' ) . '</span></span>';
		} else {
			$content .= '<a class="pcsml-button pcsm-back" href="' . $this->_wp_link_page( $current_page - 1 ) . '"><i class="penciicon-left-chevron"></i><span>' . penci_get_setting( 'penci_trans_back' ) . '</span></a>';
		}


		$content .= '<div class="pcsml-container"><select class="pcsml-dropdown">';
		foreach ( $this->list_items['list_items'] as $index => $list_item ) {
			$list_item_page_nr = $index + 1;
			$selected          = '';

			if ( $current_page == $list_item_page_nr ) {
				$selected = 'selected';
			}

			$content .= '<option ' . $selected . ' value="' . esc_attr( $this->_wp_link_page( $list_item_page_nr ) ) . '">' . $list_item['current_item_number'] . ' - ' . $list_item['title'] . '</option>';
		}
		$content .= '<select></div>';


		if ( $current_page == $total_pages ) {
			$content .= '<span class="pcsml-button pcsm-next pcsm-disable"><span>' . penci_get_setting( 'penci_trans_next' ) . '</span><i class="penciicon-right-chevron"></i></span>';
		} else {
			$content .= '<a class="pcsml-button pcsm-next" href="' . $this->_wp_link_page( $current_page + 1 ) . '"><span>' . penci_get_setting( 'penci_trans_next' ) . '</span><i class="penciicon-right-chevron"></i></a>';
		}


		$content .= '</div>';

		return $content;
	}

	private function get_current_page( $list_items ) {

		$current_page = get_query_var( 'list' );

		if ( empty( $current_page ) ) {
			return 1;
		}

		$total_pages = count( $list_items['list_items'] );
		if ( $current_page > $total_pages ) {
			$current_page = $total_pages;
		}

		return $current_page;
	}

	public function _wp_link_page( $i ) {

		$url = get_permalink();

		if ( 1 < $i ) {
			$url = add_query_arg( [ 'list' => $i ], $url );
		}

		return esc_url( $url );
	}

	protected function render_before_list_wrap() {
		return '';
	}

	protected function render_after_list_wrap() {
		return '';
	}

	function get_formatted_list_items( $smart_list_settings ) {
		$this->counting_order_asc          = $smart_list_settings['counting_order_asc'];
		$penci_tokenizer                   = new Penci_Splitter();
		$penci_tokenizer->list_title_start = $smart_list_settings['penci_smart_list_h'];
		$penci_tokenizer->list_title_end   = $smart_list_settings['penci_smart_list_h'];


		$list_items = $penci_tokenizer->split_to_list_items( array(
				'content'             => $smart_list_settings['post_content'],
				'extract_first_image' => $smart_list_settings['extract_first_image']
			)
		);

		if ( empty( $list_items['list_items'] ) ) {
			return $smart_list_settings['post_content'];
		}

		return $this->add_numbers_to_list_items( $list_items );
	}

}