<?php
/**
 * File for Deodar_Scan_Type.
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
 * Deodar_Scan_Type
 *
 * An Emum to pass to _deodar_scan_for_directories and _deodar_scan_for_files,
 * to specify the return type.
 */
enum Deodar_Scan_Type {
	case PATHS;
	case NAMES;
	case BOTH;

	/**
	 * Resolve function.
	 *
	 * Resolves the return value of the _get functions.
	 *
	 * NAMES => the file names.
	 * PATHS => the full file paths.
	 * BOTH => both names and paths.
	 *
	 * @param string $name the filename.
	 * @param string $base the basepath.
	 *
	 * @return string|array The expected return
	 */
	public function resolve( string $name, string $base ): string|array {
		return match ( $this ) {
			self::NAMES => $name,
			self::PATHS => path_join( $base, $name ),
			self::BOTH => array(
				$name,
				path_join( $base, $name ),
			)
		};
	}
}
