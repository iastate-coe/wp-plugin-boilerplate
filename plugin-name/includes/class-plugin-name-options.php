<?php
/**
 * Interface to register and get options for to the plugin.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Plugin_Name_Options
 * @subpackage includes
 */

/**
 * Class Plugin_Name_Options
 *
 * @since      1.0.0
 * @package    Plugin_Name_Options
 * @subpackage includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Options
{

	/**
	 * @since      1.0.0
	 * @var Plugin_Name_Options
	 */
	protected static $instance = null;

	/**
	 * Prefix options to prevent collision.
	 * @since      1.0.0
	 * @var string
	 */
	protected $prefix;

	/**
	 * Holds option data in class.
	 * @since      1.0.0
	 * @var array
	 */
	protected $options;

	/**
	 * @return Plugin_Name_Options
	 * @since      1.0.0
	 */
	public static function get_instance()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * @since      1.0.0
	 */
	public function __construct()
	{
		$this->options = $this::default_options();
		$this->prefix = Plugin_Name::get_plugin_name() . '_';
		$this->sync_options();
	}

	/**
	 * @return array
	 * @since      1.0.0
	 */
	public static function default_options()
	{
		return [];
	}

	/**
	 * Register any default options.
	 * @since      1.0.0
	 */
	public static function register_options()
	{
		$prefix = Plugin_Name::get_plugin_name();
		foreach (static::default_options() as $name => $value)
		{
			add_option(sprintf('%s_%s', $prefix, $name), $value);
		}
	}

	/**
	 * Delete registered options.
	 * @since      1.0.0
	 */
	public static function unregister_options()
	{
		$prefix = Plugin_Name::get_plugin_name();
		foreach (array_keys(static::default_options()) as $name)
		{
			delete_option(sprintf('%s_%s', $prefix, $name));
		}
	}

	/**
	 * Set options value.
	 *
	 * @param string $name option name.
	 * @param mixed $value option value.
	 *
	 * @since      1.0.0
	 */
	public function set($name, $value, $autoload = null)
	{
		if (true === update_option(sprintf('%s_%s', $this->prefix, $name), $value, $autoload))
		{
			$this->options[$name] = $value;
		}
		return $this;
	}

	/**
	 * Get option value.
	 *
	 * @param string $name option name.
	 * @param mixed|null $default option value if not found.
	 *
	 * @return mixed|null return option if found or `$default` if not.
	 * @since      1.0.0
	 */
	public function get($name, $default = null)
	{
		return isset($this->options[$name]) ? $this->options[$name] : $default;
	}

	/**
	 * Sync registered default options with WordPress.
	 * @return self
	 * @since      1.0.0
	 * @private
	 */
	protected function sync_options()
	{
		foreach (static::default_options() as $name => $value)
		{
			$this->options[$name] = get_option($this->prefix . $name, $value);
		}

		return $this;
	}
}
