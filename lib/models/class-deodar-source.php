<?php
/**
 * Class file for Deodar Source
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
 * The class for Deodar Source
 *
 * A "source" is either a plugin or a theme. Meant to enable mixing of
 * multiple Deodar builds for portability.
 *
 * @since 2.0.0
 */
class Deodar_Source {

	/**
	 * The file path location of the source.
	 *
	 * @since 2.0.0
	 * @var string $base_path The source path.
	 */
	public string $base_path;

	/**
	 * The url location of the source.
	 *
	 * @since 2.0.0
	 * @var string $base_url The source path.
	 */
	public string $base_url;

	/**
	 * The scripts bound to the the source.
	 *
	 * @since 2.0.0
	 * @var Deodar_Script[] $scripts The source scripts.
	 */
	public array $scripts = array();

	/**
	 * The styles bound to the the source.
	 *
	 * @since 2.0.0
	 * @var Deodar_Style[] $styles The source styles.
	 */
	public array $styles = array();

	/**
	 * The theme supports bound to the the source.
	 *
	 * @since 2.0.0
	 * @var Deodar_Support[] $supports The source theme supports.
	 */
	public array $supports = array();

	/**
	 * The cached ACF block paths.
	 *
	 * @since 2.0.0
	 * @var null|string[] $acf_blocks_paths The paths of the ACF blocks.
	 */
	private null|array $acf_blocks_paths = null;

	/**
	 * Deodar Source constructor.
	 *
	 * @since 2.0.0
	 * @param array $data The url and path of the source.
	 * @throws InvalidArgumentException Only if the style object is invalid.
	 * @return void
	 */
	public function __construct( array $data ) {
		if ( count( $data ) !== 2 || ! is_string( $data[0] ) || ! is_string( $data[1] ) ) {
			throw new InvalidArgumentException( 'The `$data` array must contain exactly two string elements.' );
		}

		[$path, $url] = $data;
		$this->parse( $path, $url );
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
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		$this->after_setup_theme();
	}

	/**
	 * After_setup_theme function.
	 *
	 * Called at the `after_setup_theme` hook.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function after_setup_theme() {
		foreach ( $this->supports as $support ) {
			$support->add();
		}
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
	 * Admin_enqueue_scripts function.
	 *
	 * Called at the `admin_enqueue_scripts` hook.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		foreach ( $this->styles as $style ) {
			$style->enqueue( $this->base_url, false );
		}

		foreach ( $this->scripts as $script ) {
			$script->enqueue( $this->base_url, false );
		}
	}

	/**
	 * Wp_enqueue_scripts function.
	 *
	 * Called at the `wp_enqueue_scripts` hook.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function wp_enqueue_scripts() {
		foreach ( $this->styles as $style ) {
			$style->enqueue( $this->base_url, true );
		}

		foreach ( $this->scripts as $script ) {
			$script->enqueue( $this->base_url, true );
		}
	}

	/**
	 * The parse function.
	 *
	 * Loading and parsing the deodar.json file and source data.
	 *
	 * @since 2.0.0
	 * @param string $path The root source path.
	 * @param string $url The root source url.
	 * @return void
	 */
	private function parse( string $path, string $url ) {
		$this->base_path = $path;
		$this->base_url  = $url;

		$deodar_json_path = path_join( $this->base_path, 'deodar.json' );

		if ( false === is_readable( $deodar_json_path ) ) {
			return;
		}

		$deodar_json = wp_json_file_decode(
			$deodar_json_path,
			array( 'associative' => true )
		);

		if ( true === _deodar_array_type( $deodar_json['styles'] ) ) {
			foreach ( $deodar_json['styles'] as $style ) {
				if ( false === _deodar_array_type( $style ) ) {
					$this->styles[] = new Deodar_Style( $style );
				}
			}
		}

		if ( true === _deodar_array_type( $deodar_json['scripts'] ) ) {
			foreach ( $deodar_json['scripts'] as $script ) {
				if ( false === _deodar_array_type( $script ) ) {
					$this->scripts[] = new Deodar_Script( $script );
				}
			}
		}

		if ( true === _deodar_array_type( $deodar_json['supports'] ) ) {
			foreach ( $deodar_json['supports'] as $support ) {
				if ( true === is_string( $support ) || false === _deodar_array_type( $support ) ) {
					$this->supports[] = new Deodar_Support( $support );
				}
			}
		}
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
		if ( true === isset( $this->acf_blocks_paths ) ) {
			return $this->acf_blocks_paths;
		}

		$acf_blocks_dir_path = path_join( $this->base_path, 'blocks/acf' );
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

		if ( false === function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		foreach ( $this->get_acf_blocks_paths() as $acf_block ) {

			$block_json_path = path_join( $acf_block, 'block.json' );

			if ( false === is_readable( $block_json_path ) ) {
				continue;
			}

			$block_json = wp_json_file_decode(
				$block_json_path,
				array( 'associative' => true )
			);

			if ( false === isset( $block_json['name'], $block_json['acf']['group']['fields'] ) || true === empty( $block_json['acf']['group']['fields'] ) ) {
				continue;
			}

			$block_name = $block_json['name'];
			$group      = $block_json['acf']['group'];

			$group['fields'] = _deodar_format_fields(
				$block_json['acf']['group']['fields'],
				sprintf( 'block_%s', $block_name )
			);

			$group['location'] = array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => $block_name,
					),
				),
			);

			if ( false === isset( $group['key'] ) ) {
				$group['key'] = sprintf( 'group_block_%s', $block_name );
			}

			acf_add_local_field_group( $group );
		}
	}
}
