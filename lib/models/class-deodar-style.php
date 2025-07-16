<?php
/**
 * Base class for Deodar Style
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
 * The class for Deodar Style
 *
 * The utility for loading stylesheets.
 *
 * @since 2.0.0
 */
class Deodar_Style {

	/**
	 * The handle (name) of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var string $handle The source path.
	 */
	private string|null $handle = null;

	/**
	 * The url of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var string|null $url The stylesheet's url.
	 */
	private string|null $url = null;

	/**
	 * The file path of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var string|null $file The stylesheet's file path.
	 */
	private string|null $file = null;

	/**
	 * The dependencies of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var array $dependencies The stylesheet's dependencies.
	 */
	private array $dependencies = array();

	/**
	 * The version number of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var string|bool|null $version The stylesheet's version.
	 */
	private string|bool|null $version = null;

	/**
	 * The media attribute of the stylesheet.
	 *
	 * @since 2.0.0
	 * @var string $media The stylesheet's version.
	 */
	private string $media = '';

	/**
	 * The template(s) the stylesheet will load against.
	 *
	 * If null, it will always load
	 *
	 * @since 2.0.0
	 * @var string|array|null $template The stylesheet's templates.
	 */
	private string|array|null $template = null;

	/**
	 * Whether or not the style needs to be loaded on the frontend
	 *
	 * @since 2.0.0
	 * @var bool $frontend Should the file loaded on the frontend.
	 */
	private bool $frontend = true;

	/**
	 * Whether or not the style needs to be loaded on the backend
	 *
	 * @since 2.0.0
	 * @var bool $template Should the file be loaded on the backend.
	 */
	private bool $backend = false;

	/**
	 * Deodar Style constructor.
	 *
	 * @since 2.0.0
	 * @param array $data The style data.
	 * @throws InvalidArgumentException Only if the style object is invalid.
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
			if ( false === string( $data['version'] ) && false === is_bool( $data['version'] ) ) {
				throw new InvalidArgumentException( '"version" must be an string, boolean or removed.' );
			}
			$this->version = $data['version'];
		}

		if ( true === isset( $data['media'] ) ) {
			if ( false === is_string( $data['media'] ) ) {
				throw new InvalidArgumentException( '"media" must be a string or removed.' );
			}
			$this->media = $data['media'];
		}

		if ( true === isset( $data['template'] ) ) {
			if ( false === is_string( $data['template'] ) && false === is_array( $data['template'] ) ) {
				throw new InvalidArgumentException( '"template" must be a string, array, or removed.' );
			}
			$this->template = $data['template'];
		}

		if ( true === isset( $data['frontend'] ) ) {
			if ( false === is_bool( $data['frontend'] ) ) {
				throw new InvalidArgumentException( '"frontend" must be a bool or removed.' );
			}
			$this->frontend = $data['frontend'];
		}

		if ( true === isset( $data['backend'] ) ) {
			if ( false === is_bool( $data['backend'] ) ) {
				throw new InvalidArgumentException( '"backend" must be a bool or removed.' );
			}
			$this->backend = $data['backend'];
		}
	}

	/**
	 * Enqueue the style to the theme.
	 *
	 * If file isset and url isn't then we'll need the
	 * $url_root to actually enqueue the file.
	 *
	 * @since 2.0.0
	 * @param string $url_root The base source url.
	 * @param bool   $end Which end is being loaded, frontend is true, backend is false.
	 * @return void
	 */
	public function enqueue( string $url_root, bool $end ) {

		if ( true === $end && false === $this->frontend ) {
			return;
		}

		if ( false === $end && false === $this->backend ) {
			return;
		}

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
			wp_enqueue_style(
				$this->handle,
				$source,
				$this->dependencies,
				$this->version,
				$this->media
			);
		}
	}
}
