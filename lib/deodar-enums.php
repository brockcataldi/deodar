<?php
/**
 * All of the Enums in Deodar.
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( false === enum_exists( 'Deodar_Array_Type' ) ) {
	require DEODAR_TYPES_PATH . '/enum-deodar-array-type.php';
}

if ( false === enum_exists( 'Deodar_Scan_Type' ) ) {
	require DEODAR_TYPES_PATH . '/enum-deodar-scan-type.php';
}
