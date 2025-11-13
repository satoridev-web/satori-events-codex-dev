<?php
/**
 * Plugin Name:       Satori Events
 * Plugin URI:        https://satoriwp.com/plugins/satori-events
 * Description:       Provides a clean events post type with archives, templates, and shortcode for showcasing upcoming events.
 * Version:           1.0.0
 * Author:            Satori WP
 * Author URI:        https://satoriwp.com/
 * Text Domain:       satori-events
 * Domain Path:       /languages
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Satori\Events
 */

define( 'SATORI_EVENTS_VERSION', '1.0.0' );
define( 'SATORI_EVENTS_PLUGIN_FILE', __FILE__ );
define( 'SATORI_EVENTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SATORI_EVENTS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
	add_action( 'admin_notices', static function () {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'Satori Events requires PHP 7.4 or higher.', 'satori-events' ) . '</p></div>';
	} );
	return;
}

require_once SATORI_EVENTS_PLUGIN_DIR . 'includes/autoloader.php';

add_action( 'plugins_loaded', static function () {
	if ( ! class_exists( '\\Satori\\Events\\Plugin' ) ) {
		return;
	}

	$plugin = new \Satori\Events\Plugin();
	$plugin->init();
} );
