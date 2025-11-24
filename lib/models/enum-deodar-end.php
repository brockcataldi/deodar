<?php
/**
 * File for Deodar_End.
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

/**
 * Deodar_End.
 *
 * For use in wp_enqueue_scripts and admin_enqueue_scripts hooks.
 *
 * @since 2.1.0
 */
enum Deodar_End {
	case FRONT;
	case BACK;
	case EDITOR;
}
