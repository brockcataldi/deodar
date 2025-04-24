<?php
/**
 * Function file for _deodar_get_template_name
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
 * Returns the name of the php template used on the page. Which includes the
 * extension.
 *
 * @return string the template name.
 */
function _deodar_get_template_name() {
	return basename( get_page_template() );
}
