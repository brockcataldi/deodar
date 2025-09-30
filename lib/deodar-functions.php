<?php
/**
 * All of the Deodar Functions.
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

if ( false === function_exists( '_deodar_array_type' ) ) {
	/**
	 * Determining if the value is an array and what type it is.
	 *
	 *  Return types:
	 *  - null: either doesn't exist or isn't an array
	 *  - true: sequential array
	 *  - false: associative array
	 *
	 * @since 2.0.0
	 * @param mixed $value The value to be checked.
	 * @return Deodar_Array_Type The result of the process.
	 */
	function _deodar_array_type( mixed $value ): Deodar_Array_Type {
		if ( false === is_array( $value ) ) {
			return Deodar_Array_Type::NEITHER;
		}

		return array_is_list( $value ) ?
			Deodar_Array_Type::SEQUENTIAL :
			Deodar_Array_Type::ASSOCIATIVE;
	}
}

if ( false === function_exists( '_deodar_classify' ) ) {
	/**
	 * Similar to slugify but converts it to a valid PHP Class format
	 *
	 * @since 2.0.0
	 * @param string $value The string to classify.
	 * @return string The classified string.
	 */
	function _deodar_classify( string $value ): string {
		$value = str_replace( '-', '_', $value );
		$parts = preg_split( '/_+/', $value, -1, PREG_SPLIT_NO_EMPTY );
		return implode( '_', array_map( 'ucfirst', $parts ) );
	}
}

if ( false === function_exists( '_deodar_scan_for_directories' ) ) {

	/**
	 * Get all of the directories in at a given path.
	 *
	 * Will return empty array even if the path doesn't exist.
	 *
	 * @since 2.0.0
	 * @param string           $path The path to search.
	 * @param Deodar_Scan_Type $type The expected return value (defaults to PATHS).
	 * @return string|array[]
	 */
	function _deodar_scan_for_directories(
		string $path,
		Deodar_Scan_Type $type = Deodar_Scan_Type::PATHS
	): array {

		if ( false === is_dir( $path ) ) {
			return array();
		}

		$paths    = array();
		$iterator = new DirectoryIterator( $path );

		foreach ( $iterator as $entry ) {
			if ( true === $entry->isDot() || false === $entry->isDir() ) {
				continue;
			}

			$paths[] = $type->resolve( $entry->getFileName(), $path );
		}

		return $paths;
	}
}

if ( false === function_exists( '_deodar_scan_for_files' ) ) {
	/**
	 * Get all of the files in at a given path.
	 *
	 * Will return empty array even if the path doesn't exist.
	 *
	 * @since 2.0.0
	 * @param string           $path The path to search.
	 * @param Deodar_Scan_Type $type The expected return value (defaults to BOTH).
	 * @param bool             $include_index Whether or not to include index.php (defaults to false).
	 * @return string|array[]
	 */
	function _deodar_scan_for_files(
		string $path,
		Deodar_Scan_Type $type = Deodar_Scan_Type::BOTH,
		bool $include_index = false
	): array {

		if ( false === is_dir( $path ) ) {
			return array();
		}

		$paths    = array();
		$iterator = new DirectoryIterator( $path );

		foreach ( $iterator as $entry ) {
			if ( true === $entry->isDot() || false === $entry->isFile() ) {
				continue;
			}

			$file_name = $entry->getFileName();

			if ( false === $include_index && 'index.php' === $file_name ) {
				continue;
			}

			$paths[] = $type->resolve( $file_name, $path );
		}

		return $paths;
	}
}


if ( false === function_exists( '_deodar_get_template_name' ) ) {
	/**
	 * Returns the name of the php template used on the page. Which includes the
	 * extension.
	 *
	 * @since 2.0.0
	 * @return string the template name.
	 */
	function _deodar_get_template_name(): string {
		return basename( get_page_template() );
	}
}


if ( false === function_exists( '_deodar_flatten_location' ) ) {
	/**
	 * Returns the locations array and flattens it.
	 *
	 * @since 2.0.0
	 * @param array $location The ACF location data.
	 * @return array The locations.
	 */
	function _deodar_flatten_location( array $location ): array {
		$flat = array();
		foreach ( $location as $group ) {
			foreach ( $group as $rule ) {
				if ( false === isset( $rule['param'] ) ) {
					continue;
				}

				if ( false === isset( $rule['operator'] ) ) {
					continue;
				}

				if ( false === isset( $rule['value'] ) ) {
					continue;
				}

				$flat[] = $rule;
			}
		}
		return $flat;
	}
}


if ( false === function_exists( '_deodar_get_type_from_key' ) ) {
	/**
	 * Get type of save based on the ACF key.
	 *
	 * @since 2.0.0
	 * @param string $key The ACF key.
	 * @return string|null Either the key or null if there is none.
	 */
	function _deodar_get_type_from_key( string $key ): string|null {
		$pos = strrpos( $key, '_' );

		if ( false !== $pos ) {
			return substr( $key, 0, $pos );
		}

		return null;
	}
}
