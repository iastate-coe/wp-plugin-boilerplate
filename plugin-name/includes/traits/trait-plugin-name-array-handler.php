<?php
/**
 * The file that defines traits .
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
 * @subpackage includes/traits
 * @author     Your Name <email@example.com>
 */
trait Trait_Plugin_Name_Array_Handler {

	/**
	 * Merge two arrays recursively.
	 * If an integer key exists in both arrays, the value from the second array will be appended the first array.
	 * If both values are arrays, they are merged together, else the value of the second array overwrites the one of the first array.
	 *
	 * @param array $a
	 * @param array $b
	 *
	 * @return array
	 *
	 * @since 2.0.0
	 */
	protected static function merge_arrays( array $a, array $b ) {
		foreach ( $b as $key => $value ) {
			if ( array_key_exists( $key, $a ) ) {
				if ( is_int( $key ) ) {
					$a[] = $value;
				} elseif ( is_array( $value ) && is_array( $a[ $key ] ) ) {
					$a[ $key ] = static::merge_arrays( $a[ $key ], $value );
				} else {
					$a[ $key ] = $value;
				}
			} else {
				$a[ $key ] = $value;
			}
		}

		return $a;
	}

	/**
	 * Determine whether the given value is array accessible.
	 *
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public static function accessible( $value ) {
		return is_array( $value ) || $value instanceof ArrayAccess;
	}

	/**
	 * Determine if the given key exists in the provided array.
	 *
	 * @param ArrayAccess|array $array
	 * @param string|int $key
	 *
	 * @return bool
	 */
	public static function exists( $array, $key ) {
		if ( $array instanceof ArrayAccess ) {
			return $array->offsetExists( $key );
		}

		return array_key_exists( $key, $array );
	}

	/**
	 * Returns the given value. If a closure, it will return the closure's value.
	 *
	 * @param mixed $value
	 * @param ...$args
	 *
	 * @return mixed
	 */
	protected static function value( $value, ...$args ) {
		return $value instanceof Closure ? $value( ...$args ) : $value;
	}

}
