<?php
/**
 * Plugin Name: Island API.
 * Plugin URI: https://github.com/wayheming/island
 * Description: Island API.
 * Author: Ernest Beginov
 * Version: 0.0.1
 * Author URI: https://github.com/wayheming
 */

defined( 'ABSPATH' ) || exit;

/**
 * Path to the plugin root directory.
 */
define( 'ISLAND_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Url to the plugin root directory.
 */
define( 'ISLAND_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin version.
 */
define( 'ISLAND_VERSION', '0.0.1' );

require_once ISLAND_PATH . 'vendor/autoload.php';

Island\Includes\Plugin::get_instance();
