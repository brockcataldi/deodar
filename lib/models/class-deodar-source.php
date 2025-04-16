<?php
/**
 * Base class for Deodar Source
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
 * Base class for Deodar Source
 *
 * A "source" is either a plugin or a theme. Meant to enable mixing of multiple Deodar builds for portability.
 *
 * @since 2.0.0
 */
class Deodar_Source {

	/**
	 * The location of the source.
	 *
	 * @var string $path
	 */
	public string $path;

	/**
	 * Deodar Source constructor.
	 *
	 * @param string $path The path of the source.
	 *
	 * @return void
	 */
	public function __construct( string $path ) {
		$this->path = $path;
	}

	/**
	 * Source bind function.
	 *
	 * Meant to bind all of the actions for each source.
	 *
	 * @return void
	 */
	public function bind() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'acf/init', array( $this, 'acf_init' ) );
	}

	/**
	 * Init function.
	 *
	 * Called at the `init` hook.
	 */
	public function init() {
		$blocks_dir_path = path_join( $this->path, 'blocks' );
		if ( false === is_dir( $blocks_dir_path ) ) {
			return;
		}

		$acf_blocks_dir_path = path_join( $blocks_dir_path, 'acf' );
		if ( false === is_dir( $acf_blocks_dir_path ) ) {
			return;
		}

		$acf_blocks = _deodar_get_directories( $acf_blocks_dir_path );
		foreach ( $acf_blocks as $acf_block ) {
			register_block_type( path_join( $acf_blocks_dir_path, $acf_block ) );
		}
	}

	/**
	 * Acf_init function.
	 *
	 * Called at the `acf/init` hook.
	 */
	public function acf_init() {
	}
}
