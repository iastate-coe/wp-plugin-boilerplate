<?php
/**
 * The file that ties into WP CLI functionality.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage wp-cli
 */

/**
 * Registers terminal functionality.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage wp-cli
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Wp_Cli_Command extends WP_CLI_Command {

	/**
	 * Prints a greeting.
	 *
	 * ## OPTIONS
	 *
	 * <name>
	 * : The name of the person to greet.
	 *
	 * [--type=<type>]
	 * : Whether or not to greet the person with success or error.
	 * ---
	 * default: success
	 * options:
	 *   - success
	 *   - error
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp example hello Newman
	 *
	 * @when after_wp_load
	 */
	function hello( $args, $assoc_args ) {
		list( $name ) = $args;

		// Print the message with type
		$type = $assoc_args['type'];
		WP_CLI::$type( "Hello, $name!" );
	}

}
