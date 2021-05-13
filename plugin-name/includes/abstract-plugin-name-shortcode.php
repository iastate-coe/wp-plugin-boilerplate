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
 * @subpackage includes
 * @author     Your Name <email@example.com>
 */
abstract class Abstract_Plugin_Name_Shortcode
{

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
	public static function get_instance()
	{
		if (!static::$instance)
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Loads required scripts when called.
	 *
	 * @used-by Abstract_Plugin_Name_Shortcode::load_shortcode
	 */
	public function load_scripts()
	{
		wp_enqueue_script(Plugin_Name::get_plugin_name() . '-public');
	}

	/**
	 * @param array $attributes
	 *
	 * @return string
	 *
	 * @uses    Abstract_Plugin_Name_Shortcode::load_scripts
	 * @uses    Abstract_Plugin_Name_Shortcode::content
	 * @uses    shortcode_atts
	 * @used-by add_shortcode
	 */
	public function load_shortcode($attributes)
	{
		if ($this->get_instance_count() === 0)
		{
			$this->load_scripts();
		}
		$content = $this->content(
			shortcode_atts($this->get_default_options(), $attributes, static::SHORTCODE_NAME)
		);
		$this->increase_instance_count();
		return $content;
	}

	/**
	 * Shortcode output.
	 *
	 * @param array $attributes
	 *
	 * @return string
	 */
	protected function content($attributes)
	{
		return '';
	}

	/**
	 * Any default options. Synced with `shortcode_atts` to the `content` function when shortcode is loaded.
	 *
	 * @return array
	 *
	 * @used-by Abstract_Plugin_Name_Shortcode::load_shortcode
	 */
	protected function get_default_options()
	{
		return [];
	}

	/**
	 * @return int
	 */
	protected function get_instance_count()
	{
		return $this->instance_counter;
	}

	/**
	 * @return int
	 */
	protected function increase_instance_count()
	{
		$this->instance_counter++;
		return $this->get_instance_count();
	}
}
