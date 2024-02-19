<?php
/**
 * Registered custom post type.
 *
 * @link       http://iastate.edu
 * @since      1.2.0
 * @see  https://developer.wordpress.org/reference/functions/register_post_type/
 * @package    Plugin_Name
 * @subpackage inludes/abstracts
 */

/**
 * Registered custom post type.
 *
 * Uses mustache style template format to display profiles.
 *
 * @package    Plugin_Name
 * @subpackage includes/abstracts
 * @author     Kevin Wickham <kwickham@iastate.edu>
 */
abstract class Abstract_Plugin_Name_Post_Type extends Abstract_Plugin_Name_Singleton {
	/**
	 * Object type
	 *
	 * @var string
	 */
	public static $name = 'abstract_template';

	/**
	 * The capability type.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/register_post_type
	 * @var string
	 */
	protected static $capability_type = 'manage_options';

	/**
	 * @return array
	 */
	public static function get_args() {
		return array();
	}

}
