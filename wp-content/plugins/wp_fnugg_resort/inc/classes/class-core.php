<?php

/**
 * Core class.
 *
 * @package Wordpress
 * @subpackage Ilia
 */

namespace Ilia\Fnugg_Resort;

use Error;
use stdClass;
use WP_Error;

/**
 * Core class implementation.
 */
class Core {

	/**
	 * Base path for classes within package.
	 *
	 * @var string
	 */
	public static $classes_base_path;

	/**
	 * Core class instance (singleton).
	 *
	 * @var Core
	 */
	protected static $instance = null;

	/**
	 * Project config.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Classes registry.
	 *
	 * @var array
	 */
	protected $registry = [];

	/**
	 * Data storage.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * Constructor. Initializes class autoloading.
	 */
	private function __construct() {

		self::$classes_base_path = rtrim( dirname( __FILE__ ), '/\\' );
		spl_autoload_register( __CLASS__ . '::autoload' );

		//$this->registry = new stdClass();
		if ( file_exists( WP_FNUGGRESORT_PATH . '/config.json' ) ) {
			$this->config = json_decode( file_get_contents( WP_FNUGGRESORT_PATH . '/config.json' ), true );
		}

		do_action( 'qm/info', print_r( $this->config, true)	);
		if ( isset( $this->config['classes'] ) && $this->config['classes'] ) {
			foreach ( $this->config['classes'] as $class ) {
				$this->registry[$class['name']] = new $class['class']();
			}
		}

		foreach ( $this->config as $name => $config ) {
			if ( 'classes' === $name ) {
				continue;
			}

			if ( method_exists( $this, 'handle_' . $name ) ) {
				$method = 'handle_' . $name;
				$this->{$method}( $config );
				continue;
			}

			do_action( 'ilia/datakeeper/config/handle/' . $name, $config );
		}
	}


	public static function render( string $name, ... $args ) {

		if ( ! file_exists( get_template_directory() . '/components/' . $name . '.com.php' ) ) {
			throw new Error( sprintf( 'Could not find template at components/%s.', $name ) );
		}

		ob_start();

		//require WP_DATAKEEPER_PATH . '/components/'. $name. '.com.php';

		echo ob_get_clean(); // phpcs:ignore
	}

	/**
	 * Get class instance. Ensure there can be only one.
	 *
	 * @return Core
	 */
	public static function instance() : Core {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Autoload handler.
	 *
	 * @param string $class_name - name of the class to load.
	 * @throws Error If can't load class.
	 * @return void
	 */
	public static function autoload( $class_name ) {

		if ( 0 !== strpos( $class_name, __NAMESPACE__ ) ) {
			return;
		}

		$class_name = str_replace( __NAMESPACE__ . '\\', '', $class_name );
		$class = explode( '\\', $class_name );

		$not_found = true;
		$class_file = str_replace( '_', '-', strtolower( array_pop( $class ) ) ) . '.php';
		$class_path = implode( DIRECTORY_SEPARATOR, $class );
		if ( ! empty( $class_path ) ) {
			$class_path .= DIRECTORY_SEPARATOR;
		}
		foreach ( [ 'trait', 'interface', 'class' ] as $type ) {
			$file_location = self::$classes_base_path . DIRECTORY_SEPARATOR . $class_path . "$type-$class_file";
			if ( file_exists( $file_location ) ) {
				require_once $file_location;
				$not_found = false;
			}
		}

		if ( $not_found ) {
			throw new Error( sprintf( "\n\n\nCould not load %s from %s.\n\n\n", $class_name, self::$classes_base_path ) );
		}

	}

	public function fetch( string $class_name, bool $strict = true ) {
		if ( ! key_exists( $class_name, $this->registry ) ) {
			if ( $strict ) {
				throw new Error( sprintf( 'Class instance "%s" not found', $class_name ) );
			}

			return null;
		}
		return $this->registry[ $class_name ];
	}

	public function conf( $sections = [] ) {

		if ( ! empty( $sections ) ) {
			$selection = [];
			foreach ( $sections as $section ) {
				if ( key_exists( $section, $this->config ) ) {
					$selection[ $section ] = $this->config[ $section ];
				}
			}
			return $selection;
		}

		return $this->config;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {}

	/**
	 * Unserializing instance is forbidden.
	 */
	public function __wakeup() {}
}
