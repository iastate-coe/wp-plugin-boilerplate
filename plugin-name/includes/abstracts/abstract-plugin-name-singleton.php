<?php
/**
 * The file that defines the abstract shortcode class.
 *
 * An abstract definition to make registering shortcodes quicker.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage includes
 */

/**
 * The abstract shortcode class.
 *
 * An abstract definition to make registering shortcodes quicker.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage includes/abstracts
 * @author     Your Name <email@example.com>
 */
abstract class Abstract_Plugin_Name_Singleton {

	/**
	 * Static property to hold our singleton instance.
	 */
	protected static $instance = null;

	/**
	 * If an instance exists, this returns it. If not, it creates one and returns it.
	 * @return static
	 */
	public static function get_instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * The Singleton's constructor should always be private to prevent direct
	 * construction calls with the `new` operator.
	 */
	protected function __construct() {
	}

	/**
	 * Singletons should not be cloneable.
	 */
	protected function __clone() {
	}
}
