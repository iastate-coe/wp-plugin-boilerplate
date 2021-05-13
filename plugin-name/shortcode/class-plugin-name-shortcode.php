<?php
/**
 * The file that uses the abstract shortcode class.
 *
 * An basic implementation of the abstract shortcode class.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage shortcode
 */

/**
 * Shortcode implementation.
 *
 * An basic implementation of the abstract shortcode class.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage shortcode
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Shortcode extends Abstract_Plugin_Name_Shortcode
{

	/**
	 * {@inheritdoc }
	 *
	 * @var bool|Plugin_Name_Shortcode
	 */
	public static $instance = false;

	/**
	 * Name of the shortcode to call.
	 */
	const SHORTCODE_NAME = 'plugin_name';

	/**
	 * {@inheritDoc}
	 */
	protected function content($atts)
	{
		return 'Hello World!';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_default_options()
	{
		return [];
	}
	// Additional Shortcode logic.
}
