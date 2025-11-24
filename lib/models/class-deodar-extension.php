<?php
/**
 * Class file for Deodar Extension
 *
 * @package Deodar
 * @author Brock Cataldi
 * @copyright 2025 Brock Cataldi
 * @license GPL-2.0-or-later
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Deodar Extension class
 *
 * @since 2.1.0
 */
abstract class Deodar_Extension {

	/**
	 * Constructor for Deodar Extension
	 *
	 * @since 2.1.0
	 */
	public function __construct() {}

	/**
	 * Register the extension.
	 *
	 * Called at 'after_setup_theme' hook.
	 *
	 * @since 2.1.0
	 * @return void
	 */
	abstract public function register(): void;
}
