<?php
/**
 * All of the Deodar Functions.
 *
 * @package           Deodar
 * @author            Brock Cataldi
 * @copyright         2025 Brock Cataldi
 * @license           GPL-2.0-or-later
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

if ( false === function_exists( '_deodar_format_fields' ) ) {
	/**
	 * Formatting ACF Fields
	 *
	 * Will return a properly formatted fields array, ensure keys are added to all fields.
	 *
	 * @since 2.0.0
	 * @param array[] $fields The fields to be formatted.
	 * @param string  $id The prefix string to use when automatic keying, ie "block".
	 * @param int     $depth The depth of the fields.
	 * @return array[] The formatted fields
	 */
	function _deodar_format_fields( array $fields, string $id, int $depth = 0 ): array {

		if ( false === is_array( $fields ) ) {
			return array();
		}

		if ( false === array_is_list( $fields ) ) {
			$fields = array( $fields );
		}

		$results = array();
		$prefix  = str_repeat( 'sub_', $depth );

		foreach ( $fields as $field ) {

			if ( false === isset( $field['key'] ) ) {
				$field['key'] = sprintf(
					'%sfield_%s_%s',
					$prefix,
					$id,
					$field['name']
				);
			}

			if ( true === isset( $field['sub_fields'] ) ) {
				$field['sub_fields'] = _deodar_format_fields(
					$field['sub_fields'],
					$id,
					$depth + 1
				);
			}

			$results[] = $field;
		}

		return $results;
	}
}

if ( false === function_exists( '_deodar_scan_for_directories' ) ) {

	/**
	 * Get all of the directories in at a given path.
	 *
	 * Will return empty array even if the path doesn't exist.
	 *
	 * @since 2.0.0
	 * @param string           $path The path to search (defaults to PATHS).
	 * @param Deodar_Scan_Type $type The expected return value.
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
	 * @return string the template name.
	 */
	function _deodar_get_template_name() {
		return basename( get_page_template() );
	}
}
