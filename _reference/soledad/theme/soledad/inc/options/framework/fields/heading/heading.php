<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.
/**
 *
 * Field: heading
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_heading' ) ) {
  class CSF_Field_heading extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {
      echo $this->field_before();
      echo '<div class="csf-heading-expand"><a href="#" class="csf-heading-expand-btn">Expand</a></div>';
      echo $this->field_after();
    }

  }
}
