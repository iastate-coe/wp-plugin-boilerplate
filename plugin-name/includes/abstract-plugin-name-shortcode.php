<?php

abstract class Abstract_Plugin_Name_Shortcode {

	/**
	 * Static property to hold our singleton instance.
	 *
	 * @var bool|Abstract_Plugin_Name_Shortcode
	 */
	public static $instance = false;

	/**
	 * @var int
	 */
	protected $instance_counter = 0;

	/**
	 * @var string
	 */
	const SHORTCODE_NAME = 'plugin_name';

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return Abstract_Plugin_Name_Shortcode
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	public function load_scripts() {
		wp_enqueue_script( 'plugin-name' );
	}

	/**
	 * @param array $shortcode_attributes
	 *
	 * @return string
	 */
	public function load_shortcode( $shortcode_attributes ) {
		if ( $this->get_instance_count() === 0 ) {
			$this->load_scripts();
		}
		$content = $this->shortcode_content(
			shortcode_atts( $this->get_default_options(), $shortcode_attributes, static::SHORTCODE_NAME )
		);
		$this->increase_instance_count();
		return $content;
	}

	/**
	 * @param array $shortcode_attributes
	 *
	 * @return string
	 */
	protected function shortcode_content( $shortcode_attributes ) {
		return '';
	}

	/**
	 * @return array
	 */
	protected function get_default_options() {
		return array();
	}

	/**
	 * @return int
	 */
	protected function get_instance_count() {
		return $this->instance_counter;
	}

	/**
	 * @return int
	 */
	protected function increase_instance_count() {
		$this->instance_counter++;
		return $this->get_instance_count();
	}
}
