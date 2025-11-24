<?php
/**
 * Class file for Deodar Enqueuable
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
 * The class for Deodar Enqueuable
 *
 * The utility for static assets via either wp_enqueue_style or wp_enqueue_script.
 *
 * @since 2.0.0
 */
abstract class Deodar_Enqueuable {
	/**
	 * The handle (name) of the enqueuable.
	 *
	 * @since 2.0.0
	 * @var string $handle The enqueuable's handle.
	 */
	public string|null $handle = null;

	/**
	 * The url of the enqueuable.
	 *
	 * @since 2.0.0
	 * @var string|null $url The enqueuable's url.
	 */
	private string|null $url = null;

	/**
	 * The file path of the enqueuable.
	 *
	 * @since 2.0.0
	 * @var string|null $file The enqueuable's file path.
	 */
	private string|null $file = null;

	/**
	 * The dependencies of the enqueuable.
	 *
	 * @since 2.0.0
	 * @var array $dependencies The enqueuable's dependencies.
	 */
	public array $dependencies = array();

	/**
	 * The version number of the enqueuable.
	 *
	 * @since 2.0.0
	 * @var string|bool|null $version The enqueuable's version.
	 */
	public string|bool|null $version = false;

	/**
	 * The template(s) the enqueuable will load against.
	 *
	 * If null, it will always load
	 *
	 * @since 2.0.0
	 * @var string|array|null $template The enqueuable's templates.
	 */
	private string|array|null $template = null;

	/**
	 * Whether or not the enqueuable needs to be loaded on the frontend
	 *
	 * @since 2.0.0
	 * @var bool $frontend Should the enqueuable loaded on the frontend.
	 */
	private bool $frontend = true;

	/**
	 * Whether or not the enqueuable needs to be loaded on the backend
	 *
	 * @since 2.0.0
	 * @var bool $template Should the enqueuable be loaded on the backend.
	 */
	private bool $backend = false;

	/**
	 * Whether or not the enqueuable needs to be loaded on the editor
	 *
	 * @since 2.1.0
	 * @var bool $editor Should the enqueuable be loaded on the editor.
	 */
	private bool $editor = false;

	/**
	 * Deodar Enqueuable constructor.
	 *
	 * @since 2.0.0
	 * @param array $data The enqueuable data.
	 * @throws InvalidArgumentException Only if the enqueuable object is invalid.
	 * @return void
	 */
	public function __construct( array $data ) {

		if ( Deodar_Array_Type::ASSOCIATIVE !== _deodar_array_type( $data ) ) {
			throw new InvalidArgumentException( '"$data" must be an associative array.' );
		}

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

		if ( true === isset( $data['template'] ) ) {
			if ( false === is_string( $data['template'] ) && false === is_array( $data['template'] ) && false === is_null( $data['template'] ) ) {
				throw new InvalidArgumentException( '"template" must be a string, array, null, or removed.' );
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

		if ( true === isset( $data['editor'] ) ) {
			if ( false === is_bool( $data['editor'] ) ) {
				throw new InvalidArgumentException( '"editor" must be a bool or removed.' );
			}
			$this->editor = $data['editor'];
		}
	}

	/**
	 * The enqueue function.
	 *
	 * Meant to call one of the enqueue functions.
	 *
	 * @since 2.0.0
	 * @param string     $url_root The root url in the event the enqueuable file has a relative path.
	 * @param Deodar_End $end Which end is being loaded, frontend is true, backend is false.
	 * @return void
	 */
	abstract public function enqueue( string $url_root, Deodar_End $end ): void;

	/**
	 * The get_url function.
	 *
	 * Converts relative paths to usable urls.
	 *
	 * @since 2.0.0
	 * @param string $url_root The root url in the event the enqueuable file has a relative path.
	 * @return string The full url.
	 */
	public function get_url( string $url_root ): string {
		$source = $this->url;

		if ( true === is_null( $source ) ) {
			$source = $url_root . $this->file;
		}

		return $source;
	}

	/**
	 * The should_enqueue function.
	 *
	 * If the template set, determine's if the script should enqueue.
	 *
	 * @since 2.0.0
	 * @param Deodar_End $end Which end is being loaded, frontend is true, backend is false.
	 * @return bool should the script enqueue.
	 */
	public function should_enqueue( Deodar_End $end ): bool {

		switch ( $end ) {
			case Deodar_End::FRONT:
				if ( false === $this->frontend ) {
					return false;
				}
				break;
			case Deodar_End::BACK:
				if ( false === $this->backend ) {
					return false;
				}
				break;
			case Deodar_End::EDITOR:
				if ( false === $this->editor ) {
					return false;
				}
				break;
		}

		if ( true === isset( $this->template ) ) {
			if ( true === is_array( $this->template ) ) {
				if ( false === in_array( _deodar_get_template_name(), $this->template, true ) ) {
					return false;
				}
			}

			if ( true === is_string( $this->template ) ) {
				if ( _deodar_get_template_name() !== $this->template ) {
					return false;
				}
			}
		}
		return true;
	}
}
