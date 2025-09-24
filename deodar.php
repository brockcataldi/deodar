<?php
/**
 * Entry point to the Deodar Plugin
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:       Deodar
 * Plugin URI:        https://deodar.io
 * Description:       Developer friendly bridge to the ACF Pro and WordPress APIs
 * Version:           2.0.0
 * Requires at least: 6.7.2
 * Requires PHP:      8.2
 * Author:            Brock Cataldi
 * Author URI:        https://brockcataldi.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       deodar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'DEODAR_PATH' ) ) {
	define( 'DEODAR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'DEODAR_LIB_PATH' ) ) {
	define( 'DEODAR_LIB_PATH', path_join( DEODAR_PATH, 'lib' ) );
}

if ( ! defined( 'DEODAR_FUNCTIONS_PATH' ) ) {
	define( 'DEODAR_FUNCTIONS_PATH', path_join( DEODAR_LIB_PATH, 'functions' ) );
}

if ( ! defined( 'DEODAR_MODELS_PATH' ) ) {
	define( 'DEODAR_MODELS_PATH', path_join( DEODAR_LIB_PATH, 'models' ) );
}

if ( ! defined( 'DEODAR_TYPES_PATH' ) ) {
	define( 'DEODAR_TYPES_PATH', path_join( DEODAR_LIB_PATH, 'types' ) );
}


require DEODAR_LIB_PATH . '/deodar-enums.php';
require DEODAR_LIB_PATH . '/deodar-functions.php';
require DEODAR_LIB_PATH . '/deodar-models.php';
require DEODAR_LIB_PATH . '/class-deodar.php';

( new Deodar() )->bind();
