<?php
/**
 * Class file for Deodar Style
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
 * The class for Deodar Style
 *
 * The utility for loading stylesheets.
 *
 * @since 2.0.0
 */
class Deodar_Style extends Deodar_Enqueuable {
	/**
	 * The media attribute of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var string $media The stylesheet's media attribute.
	 */
	private string $media = '';

	/**
	 * Deodar Style constructor.
	 *
	 * @since 2.0.0
	 * @param array $data The style data.
	 * @throws InvalidArgumentException Only if the style object is invalid.
	 * @return void
	 */
	public function __construct( array $data ) {

		Deodar_Enqueuable::__construct( $data );

		if ( true === isset( $data['media'] ) ) {
			if ( false === is_string( $data['media'] ) ) {
				throw new InvalidArgumentException( '"media" must be a string or removed.' );
			}
			$this->media = $data['media'];
		}
	}

	/**
	 * Enqueue the style to the theme.
	 *
	 * If file isset and url isn't then we'll need the
	 * $url_root to actually enqueue the file.
	 *
	 * @since 2.0.0
	 * @param string     $url_root The base source url.
	 * @param Deodar_End $end Which end is being loaded, frontend is true, backend is false.
	 * @return void
	 */
	public function enqueue( string $url_root, Deodar_End $end ): void {
		if ( false === $this->should_enqueue( $end ) ) {
			return;
		}

		if ( ! empty( $this->handle ) ) {
			wp_enqueue_style(
				$this->handle,
				$this->get_url( $url_root ),
				$this->dependencies,
				$this->version,
				$this->media
			);
		}
	}
}
