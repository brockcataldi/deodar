<?php
/**
 * Entry point to the Deodar Plugin
 *
 * @package           deodar
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
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       deodar
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined('DEODAR_PATH' ) ){
    define('DEODAR_PATH', trailingslashit(plugin_dir_path(__FILE__)));
}

if( ! defined('DEODAR_LIB_PATH' ) ) {
    define('DEODAR_LIB_PATH', trailingslashit(DEODAR_PATH . 'lib'));
}

require DEODAR_LIB_PATH . 'class-deodar.php';

(new Deodar())->bind();