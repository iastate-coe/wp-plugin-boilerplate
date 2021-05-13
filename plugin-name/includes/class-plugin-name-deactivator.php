<?php
/**
 * Fired during plugin deactivation
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		if (!empty(Plugin_Name_Options::default_options()))
		{
			Plugin_Name_Options::unregister_options();
		}
	}

}
