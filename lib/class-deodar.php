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
	 * Whether or not the source is bound.
	 *
	 * @since 2.0.0
	 * @var bool $configured Whether or not the source is configured.
	 */
	public bool $configured = false;

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
	 * Whether or not the source is in production.
	 *
	 * @since 2.0.0
	 * @var bool $production Whether or not the source is in production.
	 */
	public bool $production = false;

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
	 * @var string $path_post_types_dir The path for the post-types folder.
	 */
	public string $path_post_types_dir = '';

	/**
	 * The directory path of /includes/taxonomies/
	 *
	 * @since 2.0.0
	 * @var string $path_taxonomies_dir The path for the taxonomies folder.
	 */
	public string $path_taxonomies_dir = '';

	/**
	 * The directory path of /includes/options-pages/
	 *
	 * @since 2.0.0
	 * @var string $path_options_pages_dir The path for the options-pages folder.
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
	 * The transient name for the ACF block paths.
	 *
	 * @since 2.0.0
	 * @var string $transient_acf_blocks The transient name for the ACF block paths.
	 */
	private string $transient_acf_blocks = 'deodar_paths_acf_blocks';

	/**
	 * The transient name for the walker paths.
	 *
	 * @since 2.0.0
	 * @var string $transient_walkers The transient name for the walker paths.
	 */
	private string $transient_walkers = 'deodar_paths_walkers';

	/**
	 * The transient name for the customization paths.
	 *
	 * @since 2.0.0
	 * @var string $transient_customizations The transient name for the customization paths.
	 */
	private string $transient_customizations = 'deodar_paths_customizations';

	/**
	 * The transient name for the block styles.
	 *
	 * @since 2.0.0
	 * @var string $transient_styles_blocks The transient name for the block styles.
	 */
	private string $transient_styles_blocks = 'deodar_styles_blocks';

	/**
	 * The cached ACF block paths.
	 *
	 * @since 2.0.0
	 * @var null|string[] $paths_acf_blocks The paths of the ACF blocks.
	 */
	private null|array $paths_acf_blocks = null;

	/**
	 * The cached walker paths
	 *
	 * @since 2.0.0
	 * @var null|string[] $paths_walkers The cached walkers.
	 */
	private null|array $paths_walkers = null;

	/**
	 * The cached customization paths
	 *
	 * @since 2.0.0
	 * @var null|array[] $paths_customizations The cached customizations.
	 */
	private null|array $paths_customizations = null;

	/**
	 * The cached block styles
	 *
	 * @since 2.0.0
	 * @var null|array[] $styles_blocks The cached block styles.
	 */
	private null|array $styles_blocks = null;

	/**
	 * Deodar constructor.
	 *
	 * Meant to be empty, because it's not needed.
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
		if ( false === isset( $post['title'] ) ) {
			return $filename;
		}

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
		$this->configure_if_not_configured();

		if ( false === isset( $post['key'] ) ) {
			return $paths;
		}

		$post_type = _deodar_get_type_from_key( $post['key'] );

		if ( null !== $post_type ) {
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
		$this->configure_if_not_configured();

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
	 * @return void
	 */
	public function after_setup_theme() {

		$this->configure_if_not_configured();

		foreach ( $this->get_walkers() as $walker ) {
			_deodar_safe_include( $walker );
		}

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
			$result = _deodar_safe_include( $customization['path'] );

			if ( true === $result ) {
				$class_name = $customization['class_name'];

				if ( class_exists( $class_name ) ) {
					$loaded = new $class_name();

					if ( method_exists( $loaded, 'register' ) ) {
						$loaded->register( $wp_customize );
					}
				}
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
	 * Configure the source if not configured.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function configure_if_not_configured(): void {
		if ( true === $this->configured ) {
			return;
		}

		$source_data = apply_filters( 'deodar', array() );

		if ( Deodar_Array_Type::ASSOCIATIVE !== _deodar_array_type( $source_data ) ) {
			return;
		}

		$this->configure( $source_data );
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

		if ( true === $this->configured ) {
			return;
		}

		if ( empty( $data['path'] ) || empty( $data['url'] ) ) {
			throw new InvalidArgumentException(
				'Deodar source requires both "path" and "url".'
			);
		}

		$this->path_base = $data['path'];
		$this->url_base  = $data['url'];

		if ( true === isset( $data['production'] ) && true === is_bool( $data['production'] ) ) {
			$this->production = $data['production'];
		}

		$this->name = basename( $data['path'] );

		$this->transient_acf_blocks     = sprintf( 'deodar_paths_acf_blocks_%s', $this->name );
		$this->transient_walkers        = sprintf( 'deodar_paths_walkers_%s', $this->name );
		$this->transient_customizations = sprintf( 'deodar_paths_customizations_%s', $this->name );
		$this->transient_styles_blocks  = sprintf( 'deodar_styles_blocks_%s', $this->name );

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

		$this->styles     = $this->hydrate( $data['styles'] ?? array(), 'Deodar_Style' );
		$this->scripts    = $this->hydrate( $data['scripts'] ?? array(), 'Deodar_Script' );
		$this->supports   = $this->hydrate( $data['supports'] ?? array(), 'Deodar_Support' );
		$this->configured = true;
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

		if ( true === $this->production ) {
			$paths_acf_blocks = get_transient( $this->transient_acf_blocks );

			if ( false !== $paths_acf_blocks ) {
				$this->paths_acf_blocks = $paths_acf_blocks;
				return $this->paths_acf_blocks;
			}
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

		if ( true === $this->production ) {
			set_transient( $this->transient_acf_blocks, $paths_acf_blocks, DAY_IN_SECONDS );
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

		$blocks_dir_children = false;

		if ( true === $this->production ) {
			$blocks_dir_children = get_transient( $this->transient_styles_blocks );
		}

		if ( false === $blocks_dir_children ) {
			$blocks_dir_children = _deodar_scan_for_directories(
				$this->path_blocks_dir,
				Deodar_Scan_Type::BOTH
			);

			$acf_index = _deodar_2d_array_search( $blocks_dir_children, 0, 'acf' );

			if ( false !== $acf_index ) {
				unset( $blocks_dir_children[ $acf_index ] );
			}

			if ( true === $this->production ) {
				set_transient( $this->transient_styles_blocks, $blocks_dir_children, DAY_IN_SECONDS );
			}
		}

		$styles_blocks = array();

		foreach ( $blocks_dir_children as [$block_namespace, $block_namespace_dir] ) {
			$child_dir_children = _deodar_scan_for_directories(
				$block_namespace_dir,
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
	 * Get_customizations function.
	 *
	 * Loads and caches customizations within the includes/customizations folder.
	 *
	 * @since 2.0.0
	 * @return array[] The array of loaded customizations.
	 */
	private function get_customizations(): array {
		if ( true === isset( $this->paths_customizations ) ) {
			return $this->paths_customizations;
		}

		$customizations_dir_path = path_join( $this->path_base, path_join( 'includes', 'customizations' ) );

		if ( true === $this->production ) {
			$customizations = get_transient( $this->transient_customizations );

			if ( false !== $customizations ) {
				$this->paths_customizations = $customizations;
				return $this->paths_customizations;
			}
		}

		if ( false === is_dir( $customizations_dir_path ) ) {
			$this->paths_customizations = array();
			return $this->paths_customizations;
		}

		$includes = _deodar_scan_for_files( $customizations_dir_path );
		$loaded   = array();

		foreach ( $includes as [$name, $path] ) {
			if ( preg_match( '/^class-([A-Za-z0-9-]+)\.customization\.php$/', $name, $matches ) ) {
				$loaded[] = array(
					'path'       => $path,
					'class_name' => _deodar_classify( $matches[1] ) . '_Customization',
				);
			}
		}

		if ( true === $this->production ) {
			set_transient( $this->transient_customizations, $loaded, DAY_IN_SECONDS );
		}

		$this->paths_customizations = $loaded;
		return $this->paths_customizations;
	}

	/**
	 * Get_walkers function.
	 *
	 * Returns all of the walkers within the walkers folder.
	 *
	 * @since 2.0.0
	 * @return array The loaded walkers.
	 */
	private function get_walkers(): array {
		if ( true === isset( $this->paths_walkers ) ) {
			return $this->paths_walkers;
		}

		if ( true === $this->production ) {
			$paths_walkers = get_transient( $this->transient_walkers );

			if ( false !== $paths_walkers ) {
				$this->paths_walkers = $paths_walkers;
				return $this->paths_walkers;
			}
		}

		$walkers_dir_path = path_join( $this->path_includes_dir, 'walkers' );

		if ( false === is_dir( $walkers_dir_path ) ) {
			$this->paths_walkers = array();
			return $this->paths_walkers;
		}

		$includes = _deodar_scan_for_files( $walkers_dir_path );

		$valid = array();

		foreach ( $includes as [$name, $path] ) {
			if ( preg_match( '/^class-([A-Za-z0-9-]+)\.walker\.php$/', $name, $matches ) ) {
				$valid[] = $path;
			}
		}

		if ( true === $this->production ) {
			set_transient( $this->transient_walkers, $valid, DAY_IN_SECONDS );
		}

		$this->paths_walkers = $valid;
		return $this->paths_walkers;
	}
}
