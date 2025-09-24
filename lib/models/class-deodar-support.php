<?php
/**
 * Base class for Deodar Support
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
 * The class for Deodar Support
 *
 * The utility for formatting and register theme supports
 *
 * @since 2.0.0
 */
class Deodar_Support {

	/**
	 * The feature (name) of the theme support.
	 *
	 * @since 2.0.0
	 * @var string $feature The script's handle.
	 */
	private string|null $feature;

	/**
	 * The arguments for the theme support.
	 *
	 * @since 2.0.0
	 * @var string|array|null $args The arguments for the theme support.
	 */
	private string|array|null $args = null;

	/**
	 * Deodar Support constructor.
	 *
	 * @since 2.0.0
	 * @param string|array $data The theme support data.
	 * @return void
	 */
	public function __construct( string|array $data ) {

		if ( true === is_string( $data ) ) {
			$this->feature = $data;
			return;
		}

		if ( Deodar_Array_Type::ASSOCIATIVE === _deodar_array_type( $data ) ) {
			if ( false === isset( $data['feature'] ) || false === is_string( $data['feature'] ) ) {
				throw new InvalidArgumentException(
					'Support configuration array must contain a "feature" string.'
				);
			}

			$this->feature = $data['feature'];
			$this->args    = $data['args'];

			// if (!is_array($this->args)) {
			// throw new InvalidArgumentException(
			// '"args" must be an array if provided in support configuration.'
			// );
			// }

			return;
		}

		throw new InvalidArgumentException(
			'Support data must be either a string or an associative array with "feature".'
		);
	}

	/**
	 * Adds the theme support.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function add() {
		if ( true === is_null( $this->args ) ) {
			add_theme_support( $this->feature );
		}

		add_theme_support( $this->feature, $this->args );
	}
}
