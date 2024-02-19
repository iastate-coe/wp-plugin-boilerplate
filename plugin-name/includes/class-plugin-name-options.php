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
class Plugin_Name_Options extends Abstract_Plugin_Name_Singleton implements Countable, Iterator, JsonSerializable {

	use Trait_Plugin_Name_Array_Handler;

	/**
	 * Delimiter used to explode string on for multidimensional array calls.
	 *
	 * @example
	 *  get('parent.child') would pull $options['parent']['child']
	 *
	 * @since 1.1.0
	 */
	const STRING_TO_ARRAY_DELIMITER = '.';
	/**
	 * Holds option data in class.
	 * @since      1.0.0
	 * @var array
	 */
	protected $options;

	/**
	 * @since      1.0.0
	 */
	public function __construct() {
		$this->options = $this::default_options();
		$this->sync_options();
		parent::__construct();
	}

	/**
	 * @return array
	 * @since      1.0.0
	 */
	public static function default_options() {
		return array();
	}

	/**
	 * Sync registered default options with WordPress.
	 * @return self
	 * @since      1.0.0
	 * @private
	 */
	protected function sync_options() {
		$prefix = Plugin_Name::get_plugin_name();

		foreach ( static::default_options() as $name => $value ) {
			$this->options[ $name ] = get_option( $prefix . $name, $value );
		}

		return $this;
	}

	/**
	 * Register any default options.
	 * @since      1.0.0
	 */
	public static function register_options() {
		$prefix = Plugin_Name::get_plugin_name();
		foreach ( static::default_options() as $name => $value ) {
			add_option( sprintf( '%s_%s', $prefix, $name ), $value );
		}
	}

	/**
	 * Delete registered options.
	 * @since      1.0.0
	 */
	public static function unregister_options() {
		$prefix = Plugin_Name::get_plugin_name();
		foreach ( array_keys( static::default_options() ) as $name ) {
			delete_option( sprintf( '%s_%s', $prefix, $name ) );
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
	public function set( $name, $value, $autoload = null ) {
		$prefix = Plugin_Name::get_plugin_name();
		if ( true === update_option( sprintf( '%s_%s', $prefix, $name ), $value, $autoload ) ) {
			$this->options[ $name ] = $value;
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
	public function get( $name, $default = null ) {
		return $this->options[ $name ] ?? $default;
	}

	/**
	 * Get a data by key.
	 *
	 * @param string $key The key data to retrieve
	 *
	 * @return mixed
	 *
	 * @since 1.2.0
	 */
	public function &__get( $key ) {
		return $this->options[ $key ];
	}

	/**
	 * Assigns a value to the specified data.
	 *
	 * @param string $key The data key to assign the value to
	 * @param mixed $value The value to set
	 *
	 * @since 1.2.0
	 */
	public function __set( $key, $value ) {
		$this->options[ $key ] = $value;
	}

	/**
	 * Whether data exists by key.
	 *
	 * @param string $key A data key to check for
	 *
	 * @return bool
	 *
	 * @since 1.2.0
	 */
	public function __isset( $key ) {
		return isset( $this->options[ $key ] );
	}

	/**
	 * Unsets a data by key.
	 *
	 * @param string $key The key to unset
	 *
	 * @since 1.2.0
	 */
	public function __unset( $key ) {
		unset( $this->options[ $key ] );
	}

	/**
	 * @return string
	 *
	 * @since 1.2.0
	 */
	public function __toString() {
		return $this->jsonSerialize();
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return json_encode( $this->toArray() );
	}

	/**
	 * @return array
	 *
	 * @since 1.2.0
	 */
	public function toArray() {
		return $this->options;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since 1.2.0
	 */
	public function count() {
		return count( $this->options );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since 1.2.0
	 */
	public function rewind() {
		return reset( $this->options );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since 1.2.0
	 */
	public function current() {
		return current( $this->options );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since 1.2.0
	 */
	public function key() {
		return key( $this->options );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since 1.2.0
	 */
	public function next() {
		return next( $this->options );
	}

	/**
	 * {@inheritdoc}
	 *
	 * @since 1.2.0
	 */
	public function valid() {
		return null !== key( $this->options );
	}

	/**
	 * Gets a specific property within a multidimensional array.
	 *
	 * @param string $name The name of the property to find
	 * @param mixed $default Optional. Value that should be returned if the property is not set or empty. Defaults to null.
	 *
	 * @return string|mixed|null
	 *
	 * @example
	 * get('parent.child', 'default') is similar to
	 * isset($array['parent']) && isset($array['parent']['child]) ? $array['parent']['child'] : 'default'
	 *
	 * @since 1.2.0
	 */
	/*
	public function get( $name, $default = null ) {
		$array = $this->options;

		if ( ! static::accessible( $array ) ) {
			return static::value( $default );
		}

		if ( is_null( $name ) ) {
			return $array;
		}

		if ( static::exists( $array, $name ) ) {
			return $array[ $name ];
		}

		if ( strpos( $name, static::STRING_TO_ARRAY_DELIMITER ) === false ) {
			return static::value( $default );
		}

		foreach ( explode( static::STRING_TO_ARRAY_DELIMITER, $name ) as $segment ) {
			if ( static::accessible( $array ) && static::exists( $array, $segment ) ) {
				$array = $array[ $segment ];
			} else {
				return static::value( $default );
			}
		}

		return $array;
	}
	*/

	/**
	 * Set an array item to a given value using the constant separator.
	 *
	 * If no name is given to the method, the entire array will be replaced.
	 *
	 * @param string|null $name
	 * @param mixed $value
	 * @param bool $append If a value already exists for the given name, append the value rather than overwrite
	 *
	 * @return Plugin_Name_Options
	 *
	 * @since 1.2.0
	 */
	/*
	public function set( $name, $value, $append = false ) {
		$array = &$this->options;
		if ( is_null( $name ) ) {
			$this->options = ( is_array( $value ) ? $value :array( $value ) );

			return $this;
		}

		$segments = explode( static::STRING_TO_ARRAY_DELIMITER, $name );
		foreach ( $segments as $i => $name ) {
			if ( count( $segments ) === 1 ) {
				break;
			}

			unset( $segments[ $i ] );

			// If the key doesn't exist at this depth, we will just create an empty array
			// to hold the next value, allowing us to create the arrays to hold final
			// values at the correct depth. Then we'll keep digging into the array.
			if ( ! isset( $array[ $name ] ) || ! is_array( $array[ $name ] ) ) {
				$array[ $name ] = array();
			}

			$array = &$array[ $name ];
		}

		$segmentKey = array_shift( $segments );
		if ( $append ) {
			$existingValue = $array[ $segmentKey ];
			$value         = is_array( $existingValue ) ? static::merge_arrays( $existingValue, is_array( $value ) ? $value :array( $value ) ) : $value;
		}
		$array[ $segmentKey ] = $value;

		return $this;
	}
	*/

}
