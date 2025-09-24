<?php
/**
 * File for Deodar_Array_Type.
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
 * Deodar_Array_Type.
 *
 * For use in _deodar_array_type;
 */
enum Deodar_Array_Type {
	case NEITHER;
	case SEQUENTIAL;
	case ASSOCIATIVE;
}
