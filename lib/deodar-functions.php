<?php
/**
 * Loader file for Deodar Functions.
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( false === function_exists( '_deodar_array_type' ) ) {
	require DEODAR_FUNCTIONS_PATH . '/-deodar-array-type.php';
}

if ( false === function_exists( '_deodar_format_fields' ) ) {
	require DEODAR_FUNCTIONS_PATH . '/-deodar-format-fields.php';
}

if ( false === function_exists( '_deodar_get_directories' ) ) {
	require DEODAR_FUNCTIONS_PATH . '/-deodar-get-directories.php';
}

if ( false === function_exists( '_deodar_get_template_name' ) ) {
	require DEODAR_FUNCTIONS_PATH . '/-deodar-get-template-name.php';
}
