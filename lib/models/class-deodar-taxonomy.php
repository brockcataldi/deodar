<?php
/**
 * Class file for Deodar Taxonomy
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
 * The class for Deodar Taxonomy
 *
 * @since 2.0.0
 */
abstract class Deodar_Taxonomy {

	/**
	 * The name of the taxonomy.
	 *
	 * @since 2.0.0
	 * @var string $taxon the slug or name of the taxonomy.
	 */
	public string $taxon = '';

	/**
	 * The post types of the taxonomy.
	 *
	 * @since 2.0.0
	 * @var array $post_types the post types utilizing the taxonomy.
	 */
	public array $post_types = array();

	/**
	 * The field group set for the specific taxonomy.
	 *
	 * @since 2.0.0
	 * @var string $group The acf field group.
	 */
	public array $group = array();

	/**
	 * The arguments for the taxonomy.
	 *
	 * @since 2.0.0
	 * @return array The arguments for the taxonomy.
	 */
	abstract public function arguments(): array;

	/**
	 * Register's the taxonomy
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register() {
		if ( '' !== $this->taxon ) {
			register_taxonomy(
				$this->taxon,
				$this->post_types,
				$this->arguments()
			);
		}
	}

	/**
	 * Add's the ACF Field Group to the taxonomy
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function add() {

		if ( '' === $this->taxon ) {
			return;
		}

		$args  = $this->arguments();
		$title = ! empty( $args['label'] ) ? $args['label'] : $args['labels']['name'];

		$group = _deodar_format_group(
			$this->group,
			'taxonomy',
			$this->taxon,
			$title,
			array(
				'param'    => 'taxonomy',
				'operator' => '==',
				'value'    => $this->taxon,
			)
		);

		if ( false === is_null( $group ) ) {
			acf_add_local_field_group( $group );
		}
	}
}
