<?php
/**
 * Class file for Deodar Script
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
 * The class for Deodar Script
 *
 * The utility for loading JavaScript files.
 *
 * @since 2.0.0
 */
class Deodar_Script extends Deodar_Enqueuable {

	/**
	 * Arguments for the script.
	 *
	 * Can be a boolean for 'in_footer' or an array of arguments.
	 *
	 * @since 2.0.0
	 * @var array|bool $args The script's arguments.
	 */
	private array|bool $args = false;

	/**
	 * Deodar Script constructor.
	 *
	 * @since 2.0.0
	 * @param array $data The script data.
	 * @throws InvalidArgumentException Only if the script object is invalid.
	 * @return void
	 */
	public function __construct( array $data ) {
		Deodar_Enqueuable::__construct( $data );

		if ( true === isset( $data['args'] ) ) {
			if ( false === is_array( $data['args'] ) && false === is_bool( $data['args'] ) ) {
				throw new InvalidArgumentException( '"args" must be an array, boolean, or removed.' );
			}
			$this->args = $data['args'];
		}
	}

	/**
	 * Enqueue the script to the theme.
	 *
	 * If file is set and url isn't then we'll need the
	 * $url_root to actually enqueue the file.
	 *
	 * @since 2.0.0
	 * @param string     $url_root The base source url. Required if 'file' is used instead of 'url'.
	 * @param Deodar_End $end Which end is being loaded, frontend is true, backend is false.
	 * @return void
	 */
	public function enqueue( string $url_root, Deodar_End $end ): void {
		if ( false === $this->should_enqueue( $end ) ) {
			return;
		}

		if ( ! empty( $this->handle ) ) {
			wp_enqueue_script(
				$this->handle,
				$this->get_url( $url_root ),
				$this->dependencies,
				$this->version,
				$this->args
			);
		}
	}
}
