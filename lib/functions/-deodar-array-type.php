<?php
/**
 * Function file for _deodar_array_type
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determining if the value is an array and what type it is.
 *
 *  Return types:
 *  - null: either doesn't exist or isn't an array
 *  - true: sequential array
 *  - false: associative array
 *
 * @since 2.0.0
 * @param mixed $value The value to be checked.
 * @return bool|null The result of the process.
 */
function _deodar_array_type( mixed $value ): bool|null {

	if ( false === isset( $value ) ) {
		return null;
	}

	if ( false === is_array( $value ) ) {
		return null;
	}

	return array_is_list( $value );
}
