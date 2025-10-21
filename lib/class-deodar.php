<?php
/**
 * Base class for Deodar
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
 * Base class for Deodar
 *
 * @since 2.0.0
 */
class Deodar {

	/**
	 * The 'name' of the source.
	 *
	 * Basically the folder name of the plugin or theme.
	 *
	 * @since 2.0.0
	 * @var string $name The name of the source.
	 */
	public string $name = '';

	/**
	 * The file path location of the source.
	 *
	 * @since 2.0.0
	 * @var string $path_base The source path.
	 */
	public string $path_base = '';

	/**
	 * The directory path of /blocks/
	 *
	 * @since 2.0.0
	 * @var string $path_blocks_dir The path for the blocks folder.
	 */
	public string $path_blocks_dir = '';

	/**
	 * The directory path of /blocks/acf/
	 *
	 * @since 2.0.0
	 * @var string $path_acf_blocks_dir The path for the acf folder.
	 */
	public string $path_acf_blocks_dir = '';

	/**
	 * The directory path of /includes/
	 *
	 * @since 2.0.0
	 * @var string $path_includes_dir The path for the includes folder.
	 */
	public string $path_includes_dir = '';

	/**
	 * The directory path of /includes/field-groups/
	 *
	 * @since 2.0.0
	 * @var string $path_field_groups_dir The path for the field-groups folder.
	 */
	public string $path_field_groups_dir = '';

	/**
	 * The directory path of /includes/post-types/
	 *
	 * @since 2.0.0
	 * @var string $path_post_types_dir The path for the field-groups folder.
	 */
	public string $path_post_types_dir = '';

	/**
	 * The directory path of /includes/taxonomies/
	 *
	 * @since 2.0.0
	 * @var string $path_taxonomies_dir The path for the field-groups folder.
	 */
	public string $path_taxonomies_dir = '';

	/**
	 * The directory path of /includes/options-pages/
	 *
	 * @since 2.0.0
	 * @var string $path_options_pages_dir The path for the field-groups folder.
	 */
	public string $path_options_pages_dir = '';

	/**
	 * The url location of the source.
	 *
	 * @since 2.0.0
	 * @var string $url_base The source path.
	 */
	public string $url_base = '';

	/**
	 * The url path of /blocks/
	 *
	 * @since 2.0.0
	 * @var string $blocks_dir_url The url for the blocks folder.
	 */
	public string $url_blocks_dir = '';

	/**
	 * The menus registered to the source.
	 *
	 * @since 2.0.0
	 * @var array $menus Associative array of menus to be registered.
	 */
	public array $menus = array();

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
	 * @var null|string[] $paths_acf_blocks The paths of the ACF blocks.
	 */
	private null|array $paths_acf_blocks = null;

	/**
	 * The cached block styles
	 *
	 * @since 2.0.0
	 * @var null|array[] $styles_blocks The cached block styles.
	 */
	private null|array $styles_blocks = null;

	/**
	 * The cached includes
	 *
	 * @since 2.0.0
	 * @var array[] $includes the cache of get include
	 */
	private array $includes = array();

	/**
	 * Deodar constructor.
	 *
	 * Meant to be empty
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Bind function
	 *
	 * Meant to bind needed hooks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function bind() {
		add_filter( 'acf/json/save_file_name', array( $this, 'save_file_name' ), 10, 2 );
		add_filter( 'acf/json/save_paths', array( $this, 'save_paths' ), 10, 2 );
		add_filter( 'acf/settings/load_json', array( $this, 'load_json' ) );
		add_filter( 'get_block_type_variations', array( $this, 'get_block_type_variations' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	/**
	 * Acf/json/save_file_name hook.
	 *
	 * Used to custom name acf sync json.
	 *
	 * TODO SHOULD NOT BE TIED TO NAME, LOCATION, THEN NAME
	 *
	 * @since 2.0.0
	 * @param string $filename The current filename.
	 * @param array  $post The field group data.
	 * @return string The filename.
	 */
	public function save_file_name( $filename, $post ) {
		return sanitize_title( $post['title'] ) . '.field-group.json';
	}

	/**
	 * Acf/json/save_paths hook.
	 *
	 * Sorts where the ACF sync should be saved to.
	 *
	 * @since 2.0.0
	 * @param array $paths The paths to save to.
	 * @param array $post The field group data.
	 * @return array The save location.
	 */
	public function save_paths( $paths, $post ) {

		if ( false === isset( $post['key'] ) ) {
			return $paths;
		}

		$post_type = _deodar_get_type_from_key( $post['key'] );

		switch ( $post_type ) {
			case 'post_type':
				return array( $this->path_post_types_dir );
			case 'taxonomy':
				return array( $this->path_taxonomies_dir );
			case 'ui_options_page':
				return array( $this->path_options_pages_dir );
			default:
				break;
		}

		if ( false === isset( $post['location'] ) ) {
			return $paths;
		}

		$location = _deodar_flatten_location( $post['location'] );

		if ( 0 === count( $location ) ) {
			return $paths;
		}

		if ( 1 < count( $location ) ) {
			return array( $this->path_field_groups_dir );
		}

		$location = $location[0];

		if ( 'block' === $location['param'] ) {
			return array( path_join( $this->path_acf_blocks_dir, basename( $location['value'] ) ) );
		}

		return array( $this->path_field_groups_dir );
	}

	/**
	 * Acf/settings/load_json hook.
	 *
	 * Loads the JSON from various paths.
	 *
	 * @since 2.0.0
	 * @param array $paths The default load paths.
	 * @return array The load paths.
	 */
	public function load_json( $paths ) {
		unset( $paths[0] );
		$paths = array(
			$this->path_field_groups_dir,
			$this->path_taxonomies_dir,
			$this->path_post_types_dir,
			$this->path_options_pages_dir,
		);

		foreach ( $this->get_paths_acf_blocks() as $block_path ) {
			$paths[] = $block_path;
		}

		return $paths;
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
			$style->enqueue( $this->url_base, false );
		}

		foreach ( $this->scripts as $script ) {
			$script->enqueue( $this->url_base, false );
		}
	}

	/**
	 * After_setup_theme function.
	 *
	 * Meant to bind to the `after_setup_theme` hook.
	 *
	 * @since 2.0.0
	 */
	public function after_setup_theme() {
		$source_data = apply_filters( 'deodar', array() );

		if ( Deodar_Array_Type::ASSOCIATIVE !== _deodar_array_type( $source_data ) ) {
			return;
		}

		$this->configure( $source_data );
		$this->load_walkers();

		foreach ( $this->supports as $support ) {
			$support->add();
		}

		if ( false === empty( $this->menus ) ) {
			register_nav_menus( $this->menus );
		}

		foreach ( $this->get_block_styles() as $block_style ) {
			$block_style->enqueue(
				$this->path_blocks_dir,
				$this->url_blocks_dir
			);
		}
	}

	/**
	 * Customize_register function.
	 *
	 * Called at the `customize_register` hook.
	 *
	 * @since 2.0.0
	 * @param WP_Customize_Manager $wp_customize The WP_Customize_Manager.
	 * @return void
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		foreach ( $this->get_customizations() as $customization ) {
			if ( method_exists( $customization, 'register' ) ) {
				$customization->register( $wp_customize );
			}
		}
	}

	/**
	 * Get_block_type_variations function.
	 *
	 * @since 2.0.0
	 * @param array $variations The variations.
	 * @param array $block_type The block type.
	 * @return array The variations.
	 */
	public function get_block_type_variations( $variations, $block_type ) {
		$blocks_styles = $this->get_block_styles();

		if ( false === isset( $blocks_styles[ $block_type->name ] ) ) {
			return $variations;
		}

		$block_style = $blocks_styles[ $block_type->name ];

		$result = _deodar_safe_include( $block_style->get_variations_path( $this->path_blocks_dir ) );

		if ( true === $result ) {
			$filter     = sprintf( 'deodar_%s_variations', str_replace( '/', '_', $block_type->name ) );
			$variations = apply_filters( $filter, $variations );
		}

		return $variations;
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
		foreach ( $this->get_paths_acf_blocks() as $acf_block ) {
			register_block_type( $acf_block );
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
			$style->enqueue( $this->url_base, true );
		}

		foreach ( $this->scripts as $script ) {
			$script->enqueue( $this->url_base, true );
		}
	}


	/**
	 * Bind the deodar settings to the object.
	 *
	 * @since 2.0.0
	 * @param array $data Deodar config array.
	 * @throws InvalidArgumentException Only throws when the ['path'] and ['url'] aren't set.
	 * @return void
	 */
	private function configure( $data ) {
		if ( empty( $data['path'] ) || empty( $data['url'] ) ) {
			throw new InvalidArgumentException(
				'Deodar source requires both "path", "url" and "name".'
			);
		}

		$this->path_base = $data['path'];
		$this->url_base  = $data['url'];
		$this->name      = basename( $data['path'] );

		$this->path_blocks_dir        = path_join( $this->path_base, 'blocks' );
		$this->path_acf_blocks_dir    = path_join( $this->path_blocks_dir, 'acf' );
		$this->path_includes_dir      = path_join( $this->path_base, 'includes' );
		$this->path_field_groups_dir  = path_join( $this->path_includes_dir, 'field-groups' );
		$this->path_post_types_dir    = path_join( $this->path_includes_dir, 'post-types' );
		$this->path_taxonomies_dir    = path_join( $this->path_includes_dir, 'taxonomies' );
		$this->path_options_pages_dir = path_join( $this->path_includes_dir, 'options-pages' );

		$this->url_blocks_dir = sprintf( '%s/blocks', $this->url_base );

		if ( true === isset( $data['menus'] ) && Deodar_Array_Type::ASSOCIATIVE === _deodar_array_type( $data['menus'] ) ) {
			$this->menus = $data['menus'];
		}

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
	 * Get_paths_acf_blocks function.
	 *
	 * Returns all of the directories in the root/blocks/acf path.
	 *
	 * @since 2.0.0
	 * @return string[]
	 */
	private function get_paths_acf_blocks() {
		if ( true === isset( $this->paths_acf_blocks ) ) {
			return $this->paths_acf_blocks;
		}

		if ( false === is_dir( $this->path_acf_blocks_dir ) ) {
			$this->paths_acf_blocks = array();
			return $this->paths_acf_blocks;
		}

		$acf_blocks       = _deodar_scan_for_directories( $this->path_acf_blocks_dir );
		$paths_acf_blocks = array();

		foreach ( $acf_blocks as $acf_block ) {
			$paths_acf_blocks[] = path_join( $this->path_acf_blocks_dir, $acf_block );
		}

		$this->paths_acf_blocks = $paths_acf_blocks;
		return $this->paths_acf_blocks;
	}

	/**
	 * Get_block_styles function.
	 *
	 * Creates Deodar_Block_Style objects and eventually caches the paths.
	 *
	 * @since 2.0.0
	 * @return array Array of Deodar_Block_Style.
	 */
	private function get_block_styles(): array {
		if ( true === isset( $this->styles_blocks ) ) {
			return $this->styles_blocks;
		}

		$blocks_dir_children = _deodar_scan_for_directories(
			$this->path_blocks_dir,
			Deodar_Scan_Type::BOTH
		);

		$acf_index = _deodar_2d_array_search( $blocks_dir_children, 0, 'acf' );

		if ( false !== $acf_index ) {
			unset( $blocks_dir_children[ $acf_index ] );
		}

		$styles_blocks = array();

		foreach ( $blocks_dir_children as [$block_namespace, $block_namespace_path] ) {
			$child_dir_children = _deodar_scan_for_directories(
				$block_namespace_path,
				Deodar_Scan_Type::NAMES
			);

			foreach ( $child_dir_children as $block_name ) {
				$block_style = new Deodar_Block_Style( $block_name, $block_namespace );
				$styles_blocks[ $block_style->get_block_type_name() ] = $block_style;
			}
		}

		$this->styles_blocks = $styles_blocks;
		return $this->styles_blocks;
	}

	/**
	 * Get_deodar_includes function.
	 *
	 * Loads and caches Deoddar abstract classes within the includes folder.
	 *
	 * @since 2.0.0
	 * @param string $type The type and folder of the includes.
	 * @param string $pattern The file regex to match against.
	 * @param string $suffix The end of the classname that's enforced.
	 * @return Deodar_Customization[] The array of loaded includes.
	 */
	private function get_deodar_includes( string $type, string $pattern, string $suffix ): array {
		if ( true === isset( $this->includes[ $type ] ) ) {
			return $this->includes[ $type ];
		}

		$includes_dir_path = path_join( $this->path_base, path_join( 'includes', $type ) );

		if ( false === is_dir( $includes_dir_path ) ) {
			$this->includes[ $type ] = array();
			return $this->includes[ $type ];
		}

		$includes = _deodar_scan_for_files( $includes_dir_path );
		$loaded   = array();

		foreach ( $includes as [$name, $path] ) {

			if ( preg_match( $pattern, $name, $matches ) ) {
				$result = _deodar_safe_include( $path );
				if ( true === $result ) {
					$class_name = _deodar_classify( $matches[1] ) . $suffix;
					if ( class_exists( $class_name ) ) {
						$loaded[] = new $class_name();
					}
				}
			}
		}

		$this->includes[ $type ] = $loaded;
		return $this->includes[ $type ];
	}

	/**
	 * Load_walkers function.
	 *
	 * Loads all of the walkers within the walkers folder.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function load_walkers(): void {
		$includes = _deodar_scan_for_files( path_join( $this->path_includes_dir, 'walkers' ) );

		foreach ( $includes as [$name, $path] ) {
			if ( preg_match( '/^class-([A-Za-z0-9-]+)\.walker\.php$/', $name, $matches ) ) {
				_deodar_safe_include( $path );
			}
		}
	}

	/**
	 * Get_customizations function.
	 *
	 * Loads and returns all of the Customization classes in an array.
	 *
	 * @since 2.0.0
	 * @return array The loaded post types.
	 */
	private function get_customizations() {
		return $this->get_deodar_includes(
			'customizations',
			'/^class-([A-Za-z0-9-]+)\.customization\.php$/',
			'_Customization'
		);
	}
}
