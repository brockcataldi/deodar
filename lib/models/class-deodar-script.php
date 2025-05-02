<?php
/**
 * Base class for Deodar Script
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
 * The class for Deodar Script
 *
 * The utility for loading JavaScript files.
 *
 * @since 2.0.0
 */
class Deodar_Script {

	/**
	 * The handle (name) of the script.
	 *
	 * @since 2.0.0
	 * @var string $handle The script's handle.
	 */
	private string|null $handle = null;

	/**
	 * The url of the script.
	 *
	 * @since 2.0.0
	 * @var string|null $url The script's url.
	 */
	private string|null $url = null;

	/**
	 * The file path of the script.
	 *
	 * @since 2.0.0
	 * @var string|null $file The script's file path.
	 */
	private string|null $file = null;

	/**
	 * The dependencies of the script.
	 *
	 * @since 2.0.0
	 * @var array $dependencies The script's dependencies.
	 */
	private array $dependencies = array();

	/**
	 * The version number of the script.
	 *
	 * @since 2.0.0
	 * @var string|bool|null $version The script's version.
	 */
	private string|bool|null $version = false;

	/**
	 * Arguments for the script.
	 *
	 * Can be a boolean for 'in_footer' or an array of arguments.
	 *
	 * @since 2.0.0
	 * @var array|bool $args The script's arguments.
	 */
	private array|bool $args = false;

	/**
	 * The template(s) the script will load against.
	 *
	 * If null, it will always load
	 *
	 * @since 2.0.0
	 * @var string|array|null $template The script's templates.
	 */
	private string|array|null $template = null;

	/**
	 * Deodar Script constructor.
	 *
	 * @since 2.0.0
	 * @param array $data The script data.
	 * @throws InvalidArgumentException Only if the script object is invalid.
	 * @return void
	 */
	public function __construct( array $data ) {

		if ( true === isset( $data['handle'] ) ) {
			if ( false === is_string( $data['handle'] ) ) {
				throw new InvalidArgumentException( '"handle" must be a string.' );
			}
			$this->handle = $data['handle'];
		} else {
			throw new InvalidArgumentException( '"handle" is a required field.' );
		}

		$either = false;

		if ( true === isset( $data['url'] ) ) {
			if ( false === is_string( $data['url'] ) ) {
				throw new InvalidArgumentException( '"url" must be a string.' );
			}
			$this->url = $data['url'];
			$either    = true;
		}

		if ( true === isset( $data['file'] ) ) {
			if ( false === is_string( $data['file'] ) ) {
				throw new InvalidArgumentException( '"file" must be a string.' );
			}
			$this->file = $data['file'];
			$either     = true;
		}

		if ( false === $either ) {
			throw new InvalidArgumentException( '"url" or "file" must be set.' );
		}

		if ( true === isset( $data['dependencies'] ) ) {
			if ( false === is_array( $data['dependencies'] ) ) {
				throw new InvalidArgumentException( '"dependencies" must be an array or removed.' );
			}
			$this->dependencies = $data['dependencies'];
		}

		if ( true === isset( $data['version'] ) ) {
			if ( false === is_string( $data['version'] ) && false === is_bool( $data['version'] ) && false === is_null( $data['version'] ) ) {
				throw new InvalidArgumentException( '"version" must be a string, boolean (false), null, or removed.' );
			}
			$this->version = $data['version'];
		}

		if ( true === isset( $data['args'] ) ) {
			if ( false === is_array( $data['args'] ) && false === is_bool( $data['args'] ) ) {
				throw new InvalidArgumentException( '"args" must be an array, boolean, or removed.' );
			}
			$this->args = $data['args'];
		}

		if ( true === isset( $data['template'] ) ) {
			if ( false === is_string( $data['template'] ) && false === is_array( $data['template'] ) && false === is_null( $data['template'] ) ) {
				throw new InvalidArgumentException( '"template" must be a string, array, null, or removed.' );
			}
			$this->template = $data['template'];
		}
	}

	/**
	 * Enqueue the script to the theme.
	 *
	 * If file is set and url isn't then we'll need the
	 * $url_root to actually enqueue the file.
	 *
	 * @since 2.0.0
	 * @param string $url_root The base source url. Required if 'file' is used instead of 'url'.
	 * @return void
	 */
	public function enqueue( string $url_root = '' ): void {

		$source = $this->url;

		if ( true === is_null( $source ) ) {
			$source = $url_root . $this->file;
		}

		if ( true === isset( $this->template ) ) {
			if ( true === is_array( $this->template ) ) {
				if ( false === in_array( _deodar_get_template_name(), $this->template, true ) ) {
					return;
				}
			}

			if ( true === is_string( $this->template ) ) {
				if ( _deodar_get_template_name() !== $this->template ) {
					return;
				}
			}
		}

		if ( ! empty( $this->handle ) && ! empty( $source ) ) {
			wp_enqueue_script(
				$this->handle,
				$source,
				$this->dependencies,
				$this->version,
				$this->args
			);
		}
	}
}
