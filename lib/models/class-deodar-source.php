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
	public string $base_path = '';

	/**
	 * The url location of the source.
	 *
	 * @since 2.0.0
	 * @var string $base_url The source path.
	 */
	public string $base_url = '';

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
	 * The cached post types class names.
	 *
	 * @since 2.0.0
	 * @var null|string[] $post_types The names of the included post types.
	 */
	private null|array $post_types = null;

	/**
	 * The cached includes
	 *
	 * @since 2.0.0
	 * @var array[] $includes the cache of get include
	 */
	private array $includes = array();

	/**
	 * Deodar Source constructor.
	 *
	 * @since 2.0.0
	 * @param array $data Deodar config array.
	 * @throws InvalidArgumentException Only throws when the ['path'] and ['url'] aren't set.
	 * @return void
	 */
	public function __construct( array $data ) {

		if ( empty( $data['path'] ) || empty( $data['url'] ) ) {
			throw new InvalidArgumentException(
				'Deodar source requires both "path" and "url".'
			);
		}
		$this->base_path = $data['path'];
		$this->base_url  = $data['url'];

		$this->styles   = $this->hydrate( $data['styles'], 'Deodar_Style' );
		$this->scripts  = $this->hydrate( $data['scripts'], 'Deodar_Script' );
		$this->supports = $this->hydrate( $data['supports'], 'Deodar_Support' );
	}

	/**
	 * Hydrate function.
	 *
	 * Hydrate the config data, into actual usable data.
	 *
	 * @since 2.0.0
	 * @param array  $items the config data to hydrate.
	 * @param string $class_name the class to hydrate the data to.
	 * @return array The hydrated data.
	 */
	private function hydrate( array $items, string $class_name ): array {
		$objects = array();

		if ( Deodar_Array_Type::SEQUENTIAL === _deodar_array_type( $items ) ) {
			foreach ( $items as $item ) {
				$objects[] = new $class_name( $item );
			}
		}

		return $objects;
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
		add_action( 'acf/include_fields', array( $this, 'acf_include_fields' ) );
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

		foreach ( $this->get_post_types() as $post_type ) {
			if ( method_exists( $post_type, 'register' ) ) {
				$post_type->register();
			}
		}

		foreach ( $this->get_taxonomies() as $taxonomy ) {
			if ( method_exists( $taxonomy, 'register' ) ) {
				$taxonomy->register();
			}
		}
	}

	/**
	 * Acf_init function.
	 *
	 * Called at the `acf/init` hook.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function acf_include_fields() {
		if ( false === function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$this->register_block_field_groups();

		foreach ( $this->get_post_types() as $post_type ) {
			if ( method_exists( $post_type, 'add' ) ) {
				$post_type->add();
			}
		}

		foreach ( $this->get_taxonomies() as $taxonomy ) {
			if ( method_exists( $taxonomy, 'add' ) ) {
				$taxonomy->add();
			}
		}
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

		$acf_blocks       = _deodar_scan_for_directories( $acf_blocks_dir_path );
		$acf_blocks_paths = array();

		foreach ( $acf_blocks as $acf_block ) {
			$acf_blocks_paths[] = path_join( $acf_blocks_dir_path, $acf_block );
		}

		$this->acf_blocks_paths = $acf_blocks_paths;
		return $this->acf_blocks_paths;
	}

	/**
	 * Get_includes function
	 *
	 * Loads and caches classes within the includes folder.
	 *
	 * @param string $type The type and folder of the includes.
	 * @param string $pattern The file regex to match against.
	 * @param string $suffix The end of the classname that's enforced.
	 */
	private function get_includes( string $type, string $pattern, string $suffix ) {
		if ( true === isset( $this->includes[ $type ] ) ) {
			return $this->includes[ $type ];
		}

		$includes_dir_path = path_join( $this->base_path, path_join( 'includes', $type ) );

		if ( false === is_dir( $includes_dir_path ) ) {
			$this->includes[ $type ] = array();
			return $this->includes[ $type ];
		}

		$includes = _deodar_scan_for_files( $includes_dir_path );
		$loaded   = array();

		foreach ( $includes as [$name, $path] ) {

			if ( preg_match( $pattern, $name, $matches ) ) {
				include $path;

				$class_name = _deodar_classify( $matches[1] ) . $suffix;

				if ( class_exists( $class_name ) ) {
					$loaded[] = new $class_name();
				}
			}
		}

		$this->includes[ $type ] = $loaded;
		return $this->includes[ $type ];
	}

	/**
	 * Get_post_types function.
	 *
	 * Loads and returns all of the Post Type classes in an array.
	 *
	 * @since 2.0.0
	 * @return array The loaded post types.
	 */
	private function get_post_types() {
		return $this->get_includes(
			'post-types',
			'/^class-([A-Za-z0-9-]+)\.post-type\.php$/',
			'_Post_Type'
		);
	}


	/**
	 * Get_taxonomies function.
	 *
	 * Loads and returns all of the Taxonomy classes in an array.
	 *
	 * @since 2.0.0
	 * @return array The loaded post types.
	 */
	private function get_taxonomies() {
		return $this->get_includes(
			'taxonomies',
			'/^class-([A-Za-z0-9-]+)\.taxonomy\.php$/',
			'_Taxonomy'
		);
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

			$block_json_path = path_join( $acf_block, 'block.json' );

			if ( false === is_readable( $block_json_path ) ) {
				continue;
			}

			$block_json = wp_json_file_decode(
				$block_json_path,
				array( 'associative' => true )
			);

			$group = _deodar_format_group(
				$block_json['acf']['group'],
				'block',
				$block_json['name'],
				$block_json['title'],
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => $block_json['name'],
				)
			);

			if ( false === is_null( $group ) ) {
				acf_add_local_field_group( $group );
			}
		}
	}
}
