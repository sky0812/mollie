<?php
/*
Plugin Name: WP Fnugg resort
Description: Provide gutenberg block for getting information about resorts from fnugg.no.
Author: Ilia Griniuk
Author URI: https://www.linkedin.com/in/iliagriniuk/
Text Domain: wp_fnugg_resort
Domain Path: /languages/
Version: 1.0
*/

define( 'WP_FNUGGRESORT_VERSION', '1.0.0' );
define( 'WP_FNUGGRESORT_PATH', dirname( __FILE__ ) );
define( 'WP_FNUGGRESORT_ASSETS_PATH', WP_FNUGGRESORT_PATH . '/assets' );
define( 'WP_FNUGGRESORT_ASSETS_URL', plugins_url('wp_fnugg_resort') . '/assets' );

require_once __DIR__ . '/inc/classes/class-core.php';

function Core() { //phpcs:ignore
	return \Ilia\Fnugg_Resort\Core::instance();
}
Core();
