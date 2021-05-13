<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_wp_json_hooks();

		if (defined('WP_CLI') && WP_CLI)
		{
			$this->define_wp_cli_hooks();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once static::get_package_dir_path() . 'includes/class-plugin-name-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once static::get_package_dir_path() . 'includes/class-plugin-name-i18n.php';

		/**
		 * load options required for the plugin.
		 */
		require_once static::get_package_dir_path() . 'includes/class-plugin-name-options.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once static::get_package_dir_path() . 'admin/class-plugin-name-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once static::get_package_dir_path() . 'public/class-plugin-name-public.php';

		/**
		 * The classes for loading any shortcodes.
		 */
		require_once static::get_package_dir_path() . 'includes/abstract-plugin-name-shortcode.php';
		require_once static::get_package_dir_path() . 'shortcode/class-plugin-name-shortcode.php';

		/**
		 * The classes for loading any hooks into the REST API.
		 */
		require_once static::get_package_dir_path() . 'wp-json/class-plugin-name-wp-rest-controller.php';
		require_once static::get_package_dir_path() . 'wp-json/class-plugin-name-wp-rest.php';

		$this->loader = new Plugin_Name_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{
		$plugin_i18n = new Plugin_Name_i18n(static::get_plugin_name(), static::get_version());

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Plugin_Name_Admin(static::get_plugin_name(), static::get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Plugin_Name_Public(static::get_plugin_name(), static::get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_shortcode(Plugin_Name_Shortcode::SHORTCODE_NAME, Plugin_Name_Shortcode::get_instance(), 'load_shortcode');
	}

	/**
	 * Register all of the hooks related to the rest functionality
	 * of the plugin.
	 *
	 * @since    1.1.0
	 * @access   private
	 */
	private function define_wp_json_hooks()
	{
		$plugin_wp_json = new Plugin_Name_Wp_Rest(static::get_plugin_name(), static::get_version());

		$this->loader->add_filter('rest_api_init', $plugin_wp_json, 'register_routes');
	}

	/**
	 * Register all of the hooks related to the WP-CLI commands
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_wp_cli_hooks()
	{
		require_once static::get_package_dir_path() . 'wp-cli/class-plugin-name-wp-cli-command.php';

		try
		{
			WP_CLI::add_command(static::get_plugin_name(), 'Pods_Plugin_Name_Wp_Cli_Command');
		}
		catch (Exception $exception)
		{
			wp_die(esc_html($exception->getMessage()));
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public static function get_plugin_name()
	{
		return 'plugin-name';
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public static function get_version()
	{
		return defined('PLUGIN_NAME_VERSION') ? PLUGIN_NAME_VERSION : '1.0.0';
	}

	/**
	 * Get the plugin's root path.
	 *
	 * @return    string             path to root of plugin without trailing slash.
	 * @uses      plugin_dir_path()
	 * @since     1.0.0
	 */
	public static function get_package_dir_path()
	{
		return plugin_dir_path(dirname(__FILE__));
	}

}
