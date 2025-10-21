<?php
/**
 * Loader file for Deodar Models.
 *
 * @package Deodar
 * @author Brock Cataldi
 * @copyright 2025 Brock Cataldi
 * @license GPL-2.0-or-later
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( false === enum_exists( 'Deodar_Array_Type' ) ) {
	require DEODAR_MODELS_PATH . '/enum-deodar-array-type.php';
}

if ( false === enum_exists( 'Deodar_Scan_Type' ) ) {
	require DEODAR_MODELS_PATH . '/enum-deodar-scan-type.php';
}

if ( false === class_exists( 'Deodar_Block_Style' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-block-style.php';
}

if ( false === class_exists( 'Deodar_Enqueuable' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-enqueuable.php';
}

if ( false === class_exists( 'Deodar_Script' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-script.php';
}

if ( false === class_exists( 'Deodar_Style' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-style.php';
}

if ( false === class_exists( 'Deodar_Customization' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-customization.php';
}

if ( false === class_exists( 'Deodar_Support' ) ) {
	require DEODAR_MODELS_PATH . '/class-deodar-support.php';
}
