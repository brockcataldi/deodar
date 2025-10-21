<?php
/**
 * Class file for Deodar Block Style
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
 * Deodar Block Style class
 *
 * The object to hook into wp_enqueue_block_style
 *
 * @since 2.0.0
 */
class Deodar_Block_Style {

	/**
	 * The name of the block
	 *
	 * @since 2.0.0
	 * @var string $name The name of the block.
	 */
	public string $name = '';

	/**
	 * The namespace of the block
	 *
	 * @since 2.0.0
	 * @var string $block_namespace The namespace of the block.
	 */
	public string $block_namespace = '';

	/**
	 * Deodar Block Style Constructor
	 *
	 * @since 2.0.0
	 * @param string $name The name of the block.
	 * @param string $block_namespace The namespace of the block.
	 * @return void
	 */
	public function __construct( string $name, string $block_namespace ) {
		$this->name            = $name;
		$this->block_namespace = $block_namespace;
	}

	/**
	 * Get_block_type_name function.
	 *
	 * @since 2.0.0
	 * @return string The name of the block type.
	 */
	public function get_block_type_name(): string {
		return sprintf( '%s/%s', $this->block_namespace, $this->name );
	}

	/**
	 * Get_variations_path function.
	 *
	 * @since 2.0.0
	 * @param string $blocks_dir_path The file path of the blocks directory.
	 * @return string The path to the variations file.
	 */
	public function get_variations_path( string $blocks_dir_path ): string {
		return sprintf(
			'%s/%s/%s/%s.variations.php',
			$blocks_dir_path,
			$this->block_namespace,
			$this->name,
			$this->name
		);
	}

	/**
	 * Enqueues the block style
	 *
	 * @since 2.0.0
	 *
	 * @param string $blocks_dir_path The file path of the block style.
	 * @param string $blocks_dir_url The url path of the block style.
	 *
	 * @return void
	 */
	public function enqueue( string $blocks_dir_path, string $blocks_dir_url ) {
		wp_enqueue_block_style(
			sprintf( '%s/%s', $this->block_namespace, $this->name ),
			array(
				'handle' => sprintf( 'deodar-%s-%s', $this->block_namespace, $this->name ),
				'src'    => sprintf(
					'%s/%s/%s/build/%s.build.css',
					$blocks_dir_url,
					$this->block_namespace,
					$this->name,
					$this->name
				),
				'path'   => sprintf(
					'%s/%s/%s/build/%s.build.css',
					$blocks_dir_path,
					$this->block_namespace,
					$this->name,
					$this->name
				),
			)
		);
	}
}
