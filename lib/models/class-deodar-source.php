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
 * A "source" is either a plugin or a theme. Meant to enable mixing of
 * multiple Deodar builds for portability.
 *
 * @since 2.0.0
 */
class Deodar_Source {

	/**
	 * The location of the source.
	 *
	 * @since 2.0.0
	 * @var string $path The source path.
	 */
	public string $path;

	/**
	 * The Cached ACF block paths
	 *
	 * @since 2.0.0
	 * @var null|string[] $acf_blocks_paths The paths of the ACF blocks.
	 */
	public null|array $acf_blocks_paths = null;

	/**
	 * Deodar Source constructor.
	 *
	 * @since 2.0.0
	 * @param string $path The path of the source.
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
	 * @since 2.0.0
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
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {
		$this->register_blocks();
	}

	/**
	 * Acf_init function.
	 *
	 * Called at the `acf/init` hook.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function acf_init() {
		$this->register_block_field_groups();
	}

	/**
	 * Get_acf_blocks_paths function.
	 *
	 * Returns all of the directories in the root/blocks/acf path.
	 *
	 * @since 2.0.0
	 * @return string[]
	 */
	private function get_acf_blocks_paths() {
		if ( false === is_null( $this->acf_blocks_paths ) ) {
			return $this->acf_blocks_paths;
		}

		$blocks_dir_path = path_join( $this->path, 'blocks' );
		if ( false === is_dir( $blocks_dir_path ) ) {
			$this->acf_blocks_paths = array();
			return $this->acf_blocks_paths;
		}

		$acf_blocks_dir_path = path_join( $blocks_dir_path, 'acf' );
		if ( false === is_dir( $acf_blocks_dir_path ) ) {
			$this->acf_blocks_paths = array();
			return $this->acf_blocks_paths;
		}

		$acf_blocks       = _deodar_get_directories( $acf_blocks_dir_path );
		$acf_blocks_paths = array();

		foreach ( $acf_blocks as $acf_block ) {
			$acf_blocks_paths[] = path_join( $acf_blocks_dir_path, $acf_block );
		}

		$this->acf_blocks_paths = $acf_blocks_paths;
		return $this->acf_blocks_paths;
	}

	/**
	 * Register_blocks function.
	 *
	 * Meant to register all of the ACF blocks located
	 * within the root/blocks/acf folder.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function register_blocks() {
		foreach ( $this->get_acf_blocks_paths() as $acf_block ) {
			register_block_type( $acf_block );
		}
	}

	/**
	 * Register_block_field_groups function.
	 *
	 * Meant to extend the "acf" key functionality in the block.json file,
	 * with the ability to embed field groups at a code level, and auto key the
	 * groups.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function register_block_field_groups() {
		foreach ( $this->get_acf_blocks_paths() as $acf_block ) {
			if ( false === function_exists( 'acf_add_local_field_group' ) ) {
				continue;
			}

			$block_json_path = path_join( $acf_block, 'block.json' );

			if ( false === is_file( $block_json_path ) ) {
				continue;
			}

			$block_json = wp_json_file_decode(
				$block_json_path,
				array( 'associative' => true )
			);

			if ( false === array_key_exists( 'name', $block_json ) ) {
				continue;
			}

			if ( false === array_key_exists( 'acf', $block_json ) ) {
				continue;
			}

			if ( false === array_key_exists( 'group', $block_json['acf'] ) ) {
				continue;
			}

			if ( false === array_key_exists( 'fields', $block_json['acf']['group'] ) ) {
				continue;
			}

			$group = $block_json['acf']['group'];

			if ( false === array_key_exists( 'fields', $group ) || 0 === count( $group['fields'] ) ) {
				continue;
			}

			$group['fields'] = _deodar_format_fields(
				$block_json['acf']['group']['fields'],
				sprintf( 'block_%s', $block_json['name'] )
			);

			$group['location'] = array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => $block_json['name'],
					),
				),
			);

			if ( false === array_key_exists( 'key', $group ) ) {
				$group['key'] = sprintf( 'group_block_%s', $block_json['name'] );
			}

			acf_add_local_field_group( $group );
		}
	}
}
