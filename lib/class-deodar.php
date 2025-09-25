<?php
/**
 * Base class for Deodar
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
 * Base class for Deodar
 *
 * @since 2.0.0
 */
class Deodar {
	/**
	 * Deodar constructor.
	 *
	 * Meant to be empty
	 */
	public function __construct() {}

	/**
	 * Bind function
	 *
	 * Meant to bind needed hooks.
	 */
	public function bind() {
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
	}

	/**
	 *  After_setup_theme function.
	 *
	 * Meant to bind to the `after_setup_theme` hook.
	 */
	public function after_setup_theme() {
		$sources_data = apply_filters( 'deodar', array() );

		foreach ( $sources_data as $source_data ) {
			$source = new Deodar_Source( $source_data );
			$source->bind();
		}
	}
}
