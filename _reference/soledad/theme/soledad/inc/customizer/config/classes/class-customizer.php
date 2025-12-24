<?php
/**
 * @author : PenciDesign
 */

namespace SoledadFW\Customizer;

/**
 * Class Theme Soledad Customizer
 */
abstract class CustomizerOptionAbstract {
	/**
	 * @var Customizer
	 */
	protected $customizer;

	protected $id;

	public function __construct( $customizer, $id ) {
		$this->id         = $id;
		$this->customizer = $customizer;
		$this->set_option();
	}

	abstract public function set_option();

	public function add_lazy_section( $id, $title, $panel, $desc = '', $dependency = [] ) {
		$section = [
			'id'          => $id,
			'title'       => $title,
			'panel'       => $panel,
			'description' => $desc,
			'priority'    => $this->id,
			'type'        => 'soledad-fw-lazy-section',
			'dependency'  => $dependency,
		];
		$this->customizer->add_section( $section );
	}

	public function add_link_section( $id, $title, $panel, $url ) {
		$section = [
			'id'       => $id,
			'title'    => $title,
			'panel'    => $panel,
			'priority' => $this->id,
			'type'     => 'soledad-fw-link-section',
			'url'      => $url,
		];
		$this->customizer->add_section( $section );
	}
}
