<?php
if ( ! class_exists( 'Penci_Global_Data_Blocks' ) ):
	class Penci_Global_Data_Blocks {
		private static $container_layout = '11';

		public static function set_data_row( $new_data ) {
			self::$container_layout = $new_data;
		}

		public static function reset_data_row() {
			self::$container_layout = '';
		}

		public static function get_data_row() {
			return self::$container_layout;
		}
	}
endif;