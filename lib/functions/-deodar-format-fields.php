<?php
/**
 * Function file for _deodar_format_fields
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

	foreach ( $fields as $field ) {

		if ( false === array_key_exists( 'key', $field ) ) {
			$field['key'] = sprintf(
				'%sfield_%s_%s',
				str_repeat( 'sub_', $depth ),
				$id,
				$field['name']
			);
		}

		if ( true === array_key_exists( 'sub_fields', $field ) ) {
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
