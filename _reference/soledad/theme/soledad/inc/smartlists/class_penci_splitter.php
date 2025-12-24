<?php


class Penci_Splitter {

	var $list_title_start = 'h3';
	var $list_title_end = 'h3';
	private $list_title_is_open = false;
	private $list_penci_end_smartlists = false;


	private $current_list_item = array();

	private $content = array();


	function __construct() {
		$this->current_list_item = $this->get_empty_list_item();


	}


	function split_to_list_items( $params ) {

		$content             = $params['content'];
		$extract_first_image = $params['extract_first_image'];


		$img_regex = '';
		if ( $extract_first_image === true ) {
			$img_regex = "(<figure.*</figure>)|" .
			             "(<p>.*<a.*<img.*</a>.*</p>)|" .
			             "(<a.*<img.*</a>)|" .
			             "(<p>.*<img.*/>.*</p>)|" .
			             "(<div>.*<img.*/>.*</div>)|" .
			             "(<img.*/>)|";
		}


		$penci_magic_regex = $this->fix_regex(
			"(<$this->list_title_start.*?>)|" .
			"(</$this->list_title_end>)|" .
			$img_regex .
			"(<p>.*[.*penci_end_smartlists.*].*</p>)|" .
			"([.*penci_end_smartlists.*])" );

		$pc_splitter_lists = preg_split( '/' . $penci_magic_regex . '/', $content, - 1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		$pc_splitter_lists = array_map( 'trim', $pc_splitter_lists );
		$pc_splitter_lists = array_filter( $pc_splitter_lists, 'strlen' );

		foreach ( $pc_splitter_lists as $list ) {

			if ( $this->is_title_open( $list ) ) {
			} elseif ( $this->is_content_after_smart_list( $list ) ) {
			} elseif ( $this->is_content_before_smart_list( $list ) ) {

			} elseif ( $this->is_title_close( $list ) ) {
			} elseif ( $this->is_title_text( $list ) ) {
			} elseif ( $extract_first_image === true and $this->is_first_image( $list ) ) {
			} elseif ( $this->is_smart_list_end( $list ) ) {
			} elseif ( $this->is_description( $list ) ) {
			}
		}

		if ( ! empty( $this->current_list_item['title'] ) ) {
			$this->buffy['list_items'][] = $this->current_list_item;
		}


		return $this->buffy;
	}


	private function get_empty_list_item() {
		return array(
			'title'                 => '',
			'first_img_id'          => '',
			'description'           => '',
			'read_more_link'        => '',
			'first_img_link'        => '',
			'first_img_link_target' => '',
			'first_img_caption'     => '',
		);
	}

	private function is_title_open( $token ) {
		$matches = array();
		preg_match( '/<' . $this->list_title_start . '.*?>/', $token, $matches );


		if ( ! empty( $matches ) && $this->list_penci_end_smartlists === false ) {
			$this->list_title_is_open = true;

			return true;
		} else {
			return false;
		}


	}

	private function is_title_close( $token ) {
		if ( $token == '</' . $this->list_title_end . '>' ) {
			$this->list_title_is_open = false; //make sure we change the h3 state

			return true;
		} else {
			return false;
		}
	}

	private function is_title_text( $token ) {
		if ( $this->list_title_is_open === true ) {


			if ( ! empty( $this->current_list_item['title'] ) ) {
				$this->buffy['list_items'][] = $this->current_list_item;
			}

			$this->current_list_item          = $this->get_empty_list_item();
			$this->current_list_item['title'] = $token;

			$this->list_title_is_open = false;

			return true;
		} else {
			return false;
		}
	}

	private function is_smart_list_end( $token ) {

		$matches = array();
		preg_match( '/\[.*penci_end_smartlists.*\]/', $token, $matches );

		if ( ! empty( $matches[0] ) ) {
			$this->list_penci_end_smartlists = true;

			return true;
		} else {
			return false;
		}
	}

	private function is_content_before_smart_list( $token ) {
		if ( ( $this->list_title_is_open === true or ! empty( $this->current_list_item['title'] ) ) and $this->list_penci_end_smartlists === false ) {
			return false;

		} else {
			$this->buffy['before_list'][] = $token;

			return true;

		}
	}

	private function is_content_after_smart_list( $token ) {
		if ( $this->list_penci_end_smartlists === true ) {
			$this->buffy['after_list'][] = $token;

			return true;

		} else {
			return false;
		}
	}

	private function is_first_image( $token ) {
		if ( ! empty( $this->current_list_item['first_img_id'] ) ) {
			return false;
		}


		$matches = array();
		preg_match( '/wp-image-([0-9]+)/', $token, $matches );

		if ( ! empty( $matches[1] ) ) {

			$tmp_description = $this->extract_description_from_first_image( $token );

			if ( $tmp_description != '' ) {
				$this->current_list_item['description'] .= $tmp_description;
			}


			$this->current_list_item['first_img_id']          = $this->get_image_id_from_token( $token );
			$this->current_list_item['first_img_link']        = $this->get_image_link_from_token( $token );
			$this->current_list_item['first_img_link_target'] = $this->get_image_link_target_from_token( $token );
			$this->current_list_item['first_img_caption']     = $this->get_caption_from_token( $token );

			return true;
		} else {
			return false;
		}
	}

	private function extract_description_from_first_image( $token ) {
		$matches = array();
		$content = '';


		if ( strpos( $token, '<figure' ) !== false ) {
			return '';
		}


		preg_match_all( '/<a.*\/a>/U', $token, $matches ); //extract all links
		if ( ! empty( $matches[0] ) and is_array( $matches[0] ) ) {
			foreach ( $matches[0] as $match ) {
				if ( strpos( $match, '<img' ) !== false ) {
					$special_chars = array( "(", ")", "^", "$", "|", "?", "*", "+", "{", "}" );
					foreach ( $special_chars as $char ) {
						$escaped_char = '\\' . $char;
						$match        = str_replace( $char, $escaped_char, $match );
					}
					$content = preg_replace( '/' . $this->fix_regex( $match ) . '/', '', $token, 1 );
					break;
				}
			}
		}

		if ( $content == '' ) {
			$matches = array();
			preg_match( '/<img.*\/>/U', $token, $matches ); //extract first image
			if ( ! empty( $matches[0] ) ) {
				$special_chars = array( "(", ")", "^", "$", "|", "?", "*", "+", "{", "}" );
				$char_count    = 0;
				foreach ( $special_chars as $char ) {
					$escaped_char = '\\' . $char;
					if ( $char_count == 0 ) {
						$input_regex = str_replace( $char, $escaped_char, $matches[0] );
						$char_count ++;
					} else {
						$input_regex = str_replace( $char, $escaped_char, $input_regex );
					}
				}
				$content = preg_replace( '/' . $this->fix_regex( $input_regex ) . '/', '', $token, 1 );
			}
		}

		$content = trim( $content );

		return $content;
	}


	private function is_description( $token ) {
		if ( ! empty( $this->current_list_item['title'] ) and $this->list_penci_end_smartlists === false ) {
			$this->current_list_item['description'] .= $token;

			return true;
		} else {
			return false;
		}
	}


	private function get_image_id_from_token( $token ) {
		$matches = array();
		preg_match( '/wp-image-([0-9]+)/', $token, $matches );
		if ( ! empty( $matches[1] ) ) {
			return $matches[1];
		} else {
			return '';
		}
	}

	private function get_image_url_from_token( $token ) {
		$matches = array();
		$return  = '';
		preg_match( '/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $token, $matches );
		if ( ! empty( $matches['src'] ) ) {
			$return = $matches['src'];
		}

		return $return;
	}


	private function get_image_link_from_token( $token ) {
		$matches = array();

		if ( strpos( $token, '</figcaption>' ) !== false ) {
			preg_match( '/<figure(.*)href="([^\\"]+)(.*)<figcaption/', $token, $matches );
			if ( ! empty( $matches[2] ) ) {
				return $matches[2];
			} else {
				return '';
			}
		}

		preg_match( '/href="([^\\"]+)"/', $token, $matches );
		if ( ! empty( $matches[1] ) ) {
			return $matches[1];
		} else {
			return '';
		}
	}


	private function get_image_link_target_from_token( $token ) {
		$matches = array();
		preg_match( '/target="([^\\"]+)"/', $token, $matches );
		if ( ! empty( $matches[1] ) ) {
			return 'target="' . $matches[1] . '"';
		} else {
			return '';
		}
	}


	private function get_caption_from_token( $token ) {
		$matches = $matches2 = array();
		preg_match( '/<figcaption[^<>]*>(.*)<\/figcaption>/', $token, $matches );
		preg_match( '/<p id="caption-attachment-[^<>]*>(.*)<\/p>/', $token, $matches2 );
		if ( ! empty( $matches[1] ) ) {
			return $matches[1];
		} else if ( ! empty( $matches2[1] ) ) {
			return $matches2[1];
		} else {
			return '';
		}
	}

	private function fix_regex( $input_regex ) {
		$input_regex = str_replace( '/', '\/', $input_regex );
		$input_regex = str_replace( ']', '\]', $input_regex );
		$input_regex = str_replace( '[', '\[', $input_regex );

		return $input_regex;
	}


}
