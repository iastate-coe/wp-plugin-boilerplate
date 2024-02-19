<?php
/**
 * The REST functionality of the plugin.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage wp-json
 */

/**
 * The REST functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage wp-json
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Wp_Rest {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string the ID of this plugin
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var string the current version of this plugin
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name the name of the plugin
	 * @param string $version the version of this plugin
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register REST routes.
	 *
	 * @since    1.0.0
	 */
	public function register_routes() {
		( new Plugin_Name_Wp_Rest_Controller() )->register_routes();
	}
}
