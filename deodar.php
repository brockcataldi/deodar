<?php
/**
 * Plugin Name:  Deodar
 * Plugin URI:
 * Description:  Modular Theme Building Framework
 * Author:       Brock Cataldi
 * Version:      1.0.0
 * Text Domain:  deodar
 * Requires PHP: 7.4
 * License:
 *
 * @package Deodar
 */

// Ensure there is no direct execution.
if ( ! function_exists( 'add_action' ) ) {
	echo 'Nice Try';
	exit;
}

// Defining basic constants.
define( 'DEODAR_VERSION', '1.0' );
define( 'DEODAR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DEODAR_ACF_BLOCKS_DIR', get_stylesheet_directory() . '/blocks/acf/' );
define( 'DEODAR_INCLUDES_DIR', get_stylesheet_directory() . '/includes/' );

// Default Configuration Options.
define(
	'DEODAR_DEFAULT_CONFIGURATION',
	array(
		'blocks'         => array(
			'acf'  => 'auto',
			'core' => 'auto',
		),
		'styles'         => array(),
		'scripts'        => array(),
		'supports'       => array(),
		'menus'          => array(),
		'sidebars'       => array(),
		'post-types'     => 'auto',
		'taxonomies'     => 'auto',
		'customizations' => 'auto',
		'bundles'        => 'auto',
		'walkers'        => 'auto',
	)
);


// Requiring the Base class for ACF Blocks.
require_once DEODAR_PLUGIN_DIR . 'models/class-deodar-block.php';

// Requiring the abstrct class for Bundles.
require_once DEODAR_PLUGIN_DIR . 'models/class-deodar-bundle.php';

// Requiring the abstract class for Customizations.
require_once DEODAR_PLUGIN_DIR . 'models/class-deodar-customization.php';

// Requiring the abstract class for Post Types.
require_once DEODAR_PLUGIN_DIR . 'models/class-deodar-post-type.php';

// Requiring the abstract class for Taxonomies.
require_once DEODAR_PLUGIN_DIR . 'models/class-deodar-taxonomy.php';

// Including Various Utility Functions.
require_once DEODAR_PLUGIN_DIR . 'deodar-functions.php';

// Including the Deodar Configuration Class.
require_once DEODAR_PLUGIN_DIR . 'class-deodar-configuration.php';

// Including the Deodar ACF Class.
require_once DEODAR_PLUGIN_DIR . 'class-deodar-acf.php';

// Including the Deodar Enqueuer Class.
require_once DEODAR_PLUGIN_DIR . 'class-deodar-enqueuer.php';

// Including the Deodar Includer Class.
require_once DEODAR_PLUGIN_DIR . 'class-deodar-includer.php';

// Including the Deodar Theme Class.
require_once DEODAR_PLUGIN_DIR . 'class-deodar-theme.php';

// Including the Deodar Base Class.
require_once DEODAR_PLUGIN_DIR . 'class-deodar.php';

// Initial Creation Hook.
add_action( 'plugins_loaded', array( 'Deodar', 'plugins_loaded' ) );

// Plugin Activation and Deactivation Hooks.
register_activation_hook( __FILE__, array( 'Deodar', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Deodar', 'plugin_deactivation' ) );
