<?php
/**
 * Class file for Deodar Customization
 *
 * @package Deodar
 * @author Brock Cataldi
 * @copyright 2025 Brock Cataldi
 * @license GPL-2.0-or-later
 * @since 2.0.0
 */

/**
 * Deodar Customization class
 *
 * An abstract class to add customzier options
 *
 * @since 2.0.0
 */
abstract class Deodar_Customization {
	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	public function __construct(){}

	/**
	 * The actual registration for the customizer.
     *
     * @see https://developer.wordpress.org/themes/customize-api/customizer-objects/
	 * @since 2.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize the WP_Customize_Manager.
     * @return void
	 */
	abstract public function register( WP_Customize_Manager $wp_customize ): void;
}