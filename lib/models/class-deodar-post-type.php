<?php
/**
 * Class file for Deodar Post Type
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
 * The class for Deodar Post Type
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
 */
abstract class Deodar_Post_Type {

	/**
	 * The name of the post type.
	 *
	 * @since 2.0.0
	 * @var string $type the slug or name of the post type.
	 */
	public static string $type = '';

	/**
	 * The field group set for the specific post type.
	 *
	 * @since 2.0.0
	 * @var string $group the acf field group.
	 */
	public static array $group = array();

	/**
	 * The arguments for the post type.
	 *
	 * @since 2.0.0
	 * @return array The arguments for the post type.
	 */
	abstract public static function arguments(): array;

	/**
	 * Register's the post type
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public static function register() {
		if ( '' !== static::$type ) {
			register_post_type(
				static::$type,
				static::arguments()
			);
		}
	}
}
