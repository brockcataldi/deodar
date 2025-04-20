<?php
/**
 * Function file for _deodar_get_directories
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
 * Get all of the directories in at a given path.
 *
 * Will return empty array even if the path doesn't exist.
 *
 * @since 2.0.0
 * @param string $path The path to search.
 * @return string[]
 */
function _deodar_get_directories( string $path ): array {

	if ( false === is_dir( $path ) ) {
		return array();
	}

	$paths    = array();
	$iterator = new DirectoryIterator( $path );

	foreach ( $iterator as $entry ) {
		if ( true === $entry->isDot() || false === $entry->isDir() ) {
			continue;
		}

		$paths[] = path_join( $path, $entry->getFileName() );
	}

	return $paths;
}
