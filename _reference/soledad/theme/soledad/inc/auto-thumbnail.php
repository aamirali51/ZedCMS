<?php
/*-----------------------------------------------------------------------------------*/
# Get Thumbnail URL for Post
/*-----------------------------------------------------------------------------------*/
if ( ! class_exists( 'Penci_Auto_Post_Thumbnail' ) ) {

	class Penci_Auto_Post_Thumbnail {

		public function __construct() {
			add_action( 'save_post', [ $this, 'get_video_thumbnail' ], 10, 3 );
		}

		public function get_video_thumbnail( $post_id, $post = null, $update = true ) {

			$_thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );


			if ( ! get_theme_mod( 'penci_enable_auto_featured_image' ) ) {
				return;
			}

			if ( 'revision' === $post->post_type || $_thumbnail_id ) {
				return;
			}

			$thumbnails = $auto_thumb_id = '';

			if ( 'video' == get_post_format( $post_id ) ) {

				$video_embed = get_post_meta( $post_id, '_format_video_embed', true );

				if ( wp_oembed_get( $video_embed ) ) {
					if ( preg_match( "#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $video_embed, $matches ) ) {

						$video_id = preg_replace( '/\s+/', '', $matches[0] );

						if ( getimagesize( 'https://i.ytimg.com/vi/' . $video_id . '/maxresdefault.jpg' ) ) {
							$thumbnails = 'https://i.ytimg.com/vi/' . $video_id . '/maxresdefault.jpg';
						} elseif ( getimagesize( 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg' ) ) {
							$thumbnails = 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg';
						}

					} // Vimeo
					elseif ( preg_match( "/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $video_embed, $matches ) ) {
						$video_id = preg_replace( '/\s+/', '', $matches[5] );

						if ( self::get_vimeo_info( $video_id ) ) {
							$thumbnails = self::get_vimeo_info( $video_id );
						}
					}
				}
			} else {
				$thumbnails_search = self::find_images( $post->post_content );
				if ( ! empty( $thumbnails_search ) && isset( $thumbnails_search[0] ) ) {

					if ( self::get_thumbnail_id( $thumbnails_search[0] ) ) {
						$auto_thumb_id = self::get_thumbnail_id( $thumbnails_search[0] );
					} else {
						$thumbnails = $thumbnails_search[0]['url'];
					}
				}
			}

			if ( $thumbnails ) {

				$auto_thumb_id = attachment_url_to_postid( $thumbnails );

				if ( ! $auto_thumb_id ) {
					$auto_thumb_id = self::rs_upload_from_url( $thumbnails );
				}
			}

			if ( $auto_thumb_id ) {
				update_post_meta( $post_id, '_thumbnail_id', $auto_thumb_id );
			}
		}

		private static function get_vimeo_info( $video_id ) {

			$video_thumb = '';
			// Build the Api request
			$api_url = 'https://vimeo.com/api/v2/video/' . $video_id . '.json';
			$request = wp_remote_get( $api_url );

			// Check if there is no any errors
			if ( is_wp_error( $request ) ) {
				return null;
			}

			// Prepare the data
			$result = json_decode( wp_remote_retrieve_body( $request ), true );

			// Check if the video title is exists -
			if ( empty( $result[0]['title'] ) ) {
				return null;
			}

			// Prepare the Video thumbnail
			if ( ! empty( $result[0]['thumbnail_medium'] ) ) {
				$video_thumb = @parse_url( $result[0]['thumbnail_medium'] );
				$video_thumb = 'https://i.vimeocdn.com/video/' . str_replace( '/video/', '', $video_thumb['path'] );
			}

			if ( ! empty( $result[0]['thumbnail_large'] ) ) {
				$video_thumb = @parse_url( $result[0]['thumbnail_large'] );
				$video_thumb = 'https://i.vimeocdn.com/video/' . str_replace( '/video/', '', $video_thumb['path'] );
			}

			return $video_thumb;
		}

		/**
		 * Get an array of images url, contained in the post
		 */
		private function find_images( $content ) {
			$matches = [];
			$images  = [];

			//do shortcodes before search images
			$post_content = apply_filters( 'the_content', do_shortcode( $content ) );

			// Get all images from post's body
			preg_match_all( '/<\s*img .*?src\s*=\s*[\"\']?([^\"\'> ]*).*?>/i', $post_content, $matches );

			if ( count( $matches ) ) {

				foreach ( $matches[0] as $key => $image ) {
					$title = '';
					preg_match_all( '/<\s*img [^\>]*title\s*=\s*[\"\']?([^\"\'> ]*)/i', $image, $matches_title );


					if ( count( $matches_title ) && isset( $matches_title[1] ) && isset( $matches_title[1][ $key ] ) ) {
						$title = $matches[1][ $key ];
					}

					$images[] = [
						'tag'   => $image,
						'url'   => $matches[1][ $key ],
						'title' => $title,
					];
				}
			} else {

				preg_match_all( "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $post_content, $matches );

				if ( count( $matches ) ) {

					foreach ( $matches[0] as $key => $image ) {
						$image = '<img src="//img.youtube.com/vi/' . $image . '/maxresdefault.jpg">';
						$title = '';
						preg_match_all( '/<\s*img [^\>]*title\s*=\s*[\"\']?([^\"\'> ]*)/i', $image, $matches_title );

						if ( count( $matches_title ) && isset( $matches_title[1] ) && isset( $matches_title[1][ $key ] ) ) {
							$title = $matches[1][ $key ];
						}

						$images[] = [
							'tag'   => $image,
							'url'   => $matches[1][ $key ],
							'title' => $title,
						];
					}
				}
			}

			return $images;
		}

		public function get_thumbnail_id( $image ) {
			global $wpdb;
			$thumb_id = 0;

			/**
			 * If the image is from the WordPress own media gallery, then it appends the thumbnail id to a css class.
			 * Look for this id in the IMG tag.
			 */
			if ( isset( $image['tag'] ) && ! empty( $image['tag'] ) ) {
				preg_match( '/wp-image-([\d]*)/i', $image['tag'], $thumb_id );

				if ( $thumb_id ) {
					$thumb_id = $thumb_id[1];

					if ( ! get_post( $thumb_id ) ) {
						$thumb_id = false;
					}
				}
			}

			if ( ! $thumb_id ) {
				// If thumb id is not found, try to look for the image in DB.
				if ( isset( $image['url'] ) && ! empty( $image['url'] ) ) {
					$image_url = $image['url'];
					// если ссылка на миниатюру, то регулярка сделает ссылку на оригинал. убирает в конце названия файла -150x150
					$image_url = preg_replace( '/-[0-9]{1,}x[0-9]{1,}\./', ' . ', $image_url );
					$thumb_id  = $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} WHERE guid LIKE ' % " . esc_sql( $image_url ) . " % '" );
				}
			}

			return is_numeric( $thumb_id ) ? $thumb_id : false;
		}

		public static function rs_upload_from_url( $url, $title = null ) {
			require_once( ABSPATH . "/wp-load.php" );
			require_once( ABSPATH . "/wp-admin/includes/image.php" );
			require_once( ABSPATH . "/wp-admin/includes/file.php" );
			require_once( ABSPATH . "/wp-admin/includes/media.php" );

			// Download url to a temp file
			$tmp = download_url( $url );
			if ( is_wp_error( $tmp ) ) {
				return false;
			}

			// Get the filename and extension ("photo.png" => "photo", "png")
			$filename  = pathinfo( $url, PATHINFO_FILENAME );
			$extension = pathinfo( $url, PATHINFO_EXTENSION );

			// An extension is required or else WordPress will reject the upload
			if ( ! $extension ) {
				// Look up mime type, example: "/photo.png" -> "image/png"
				$mime = mime_content_type( $tmp );
				$mime = is_string( $mime ) ? sanitize_mime_type( $mime ) : false;

				// Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
				$mime_extensions = array(
					// mime_type         => extension (no period)
					'text/plain'         => 'txt',
					'text/csv'           => 'csv',
					'application/msword' => 'doc',
					'image/jpg'          => 'jpg',
					'image/jpeg'         => 'jpeg',
					'image/gif'          => 'gif',
					'image/png'          => 'png',
					'video/mp4'          => 'mp4',
				);

				if ( isset( $mime_extensions[ $mime ] ) ) {
					// Use the mapped extension
					$extension = $mime_extensions[ $mime ];
				} else {
					// Could not identify extension
					@unlink( $tmp );

					return false;
				}
			}


			// Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
			$args = array(
				'name'     => "$filename.$extension",
				'tmp_name' => $tmp,
			);

			// Do the upload
			$attachment_id = media_handle_sideload( $args, 0, $title );

			// Cleanup temp file
			@unlink( $tmp );

			// Error uploading
			if ( is_wp_error( $attachment_id ) ) {
				return false;
			}

			// Success, return attachment ID (int)
			return (int) $attachment_id;
		}
	}
}
new Penci_Auto_Post_Thumbnail;