<?php
/**
 * Loader file for Deodar Models.
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( false === class_exists( 'Deodar_Source' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-source.php';
}
