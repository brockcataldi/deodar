<?php
/**
 * Base class for Deodar
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
 * Base class for Deodar
 */
class Deodar {


	/**
	 *  All of the registered source paths.
	 *
	 *  @var string[] $sources
	 */
	private array $sources = array();

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
		$source_paths = apply_filters( 'deodar', array() );

		foreach ( $source_paths as $source_path ) {
			$source = new Deodar_Source( $source_path );
			$source->bind();
			$this->sources[] = $source;
		}
	}
}
