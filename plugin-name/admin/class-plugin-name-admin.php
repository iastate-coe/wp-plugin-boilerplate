<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Register plugin settings.
	 *
	 * @return void
	 * @since    1.0.0
	 */
	public function register_settings() {
		$options               = Plugin_Name_Options::get_instance();
		$general_settings_page = 'general';

		/*
		 * Sections
		 * ========================================
		 */
		$general_section = $this->plugin_name . '_reading_section';
		add_settings_section(
			$general_section,
			__( 'Automated Page Redirect Settings', 'plugin-name' ),
			'__return_empty_string',
			$general_settings_page
		);

		/*
		 * Option: checkbox_example
		 * ========================================
		 */
		$option_name = $this->plugin_name . '_checkbox-example';
		register_setting(
			$general_settings_page,
			$option_name,
			array(
				'type'              => 'array',
				'sanitize_callback' => 'rest_sanitize_array',
				'default'           => $options->get( 'checkbox_example' ),
				'show_in_rest'      => false,
			)
		);
		add_settings_field(
			$option_name,
			__( 'Checkbox Example', 'plugin-name' ),
			array( $this, 'callback_checkbox' ),
			$general_settings_page,
			$general_section,
			array(
				'name'          => 'checkbox_example',
				'screen_reader' => _x( 'Checkbox Example', 'screen reader', 'plugin-name' ),
				'description'   => __( 'Checkbox example description.', 'plugin-name' ),
				'options'       => wp_roles()->get_names(),
			)
		);

		/*
		 * Option: Text Example
		 * ========================================
		 */
		$option_name = $this->plugin_name . '_text-example';
		register_setting(
			$general_settings_page,
			$option_name,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => $options->get( 'text_example' ),
				'show_in_rest'      => false,
			)
		);
		add_settings_field(
			$option_name,
			__( 'Text Example', 'plugin-name' ),
			array( $this, 'callback_input' ),
			$general_settings_page,
			$general_section,
			array(
				'type'          => 'text',
				'name'          => 'text-example',
				'input_label'   => __( 'Label', 'plugin-name' ),
				'screen_reader' => _x( 'Text Example', 'screen reader', 'plugin-name' ),
				'input_class'   => 'small-text',
			)
		);
	}

	/**
	 * Settings HTML for user management of options.
	 *
	 * @param array $args args sent from {@link add_settings_field()}.
	 *
	 * @access   private
	 * @since    1.0.0
	 */
	public function callback_input( $args = array() ) {
		load_template(
			plugin_dir_path( __FILE__ ) . 'partials/plugin-name-input.php',
			false,
			$args
		);
	}

	/**
	 * Settings HTML for user management of options.
	 *
	 * @param array $args args sent from {@link add_settings_field()}.
	 *
	 * @access   private
	 * @since    1.0.0
	 */
	public function callback_checkbox( $args = array() ) {
		load_template(
			plugin_dir_path( __FILE__ ) . 'partials/plugin-name-checkboxes.php',
			false,
			$args
		);
	}
}
