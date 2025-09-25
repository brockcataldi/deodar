<?php
/**
 * Class file for Deodar Post Type
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
 * The class for Deodar Post Type
 *
 * @since 2.0.0
 */
abstract class Deodar_Post_Type {

	/**
	 * The name of the post type.
	 *
	 * @since 2.0.0
	 * @var string $type the slug or name of the post type.
	 */
	public string $type = '';

	/**
	 * The field group set for the specific post type.
	 *
	 * @since 2.0.0
	 * @var string $group the acf field group.
	 */
	public array $group = array();

	/**
	 * The arguments for the post type.
	 *
	 * @since 2.0.0
	 * @return array The arguments for the post type.
	 */
	abstract public function arguments(): array;

	/**
	 * Register's the post type
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function register() {
		if ( '' !== $this->type ) {
			register_post_type(
				$this->type,
				$this->arguments()
			);
		}
	}

	/**
	 * Add's the ACF Field Group to the Post Type
	 *
	 * @see https://www.advancedcustomfields.com/resources/register-fields-via-php/
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function add() {

		if ( '' === $this->type ) {
			return;
		}

		$args  = $this->arguments();
		$title = ! empty( $args['label'] ) ? $args['label'] : $args['labels']['name'];

		$group = _deodar_format_group(
			$this->group,
			'post_type',
			$this->type,
			$title,
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $this->type,
			)
		);

		if ( false === is_null( $group ) ) {
			acf_add_local_field_group( $group );
		}
	}
}
