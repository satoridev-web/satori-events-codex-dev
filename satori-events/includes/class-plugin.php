<?php
/**
 * Core plugin bootstrap.
 *
 * @package Satori\Events
 */

namespace Satori\Events;

use Satori\Events\Frontend\Archive_Controller;
use Satori\Events\Frontend\Single_Controller;
use Satori\Events\Meta\Event_Meta;
use Satori\Events\Post_Types\Event_Post_Type;
use Satori\Events\Shortcodes\Archive_Shortcode;
use Satori\Events\Taxonomies\Event_Taxonomies;
use Satori\Events\Templates\Template_Loader;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin loader.
 */
class Plugin {
	/**
	 * Bootstraps the plugin.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'load_textdomain' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		( new Event_Post_Type() )->register();
		( new Event_Taxonomies() )->register();
		( new Event_Meta() )->register();

		$template_loader = new Template_Loader();
		$template_loader->register();

		( new Archive_Controller() )->register();
		( new Single_Controller() )->register();
		( new Archive_Shortcode() )->register();
	}

	/**
	 * Load plugin text domain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'satori-events', false, dirname( plugin_basename( SATORI_EVENTS_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		if ( is_post_type_archive( 'event' ) || is_singular( 'event' ) ) {
			wp_enqueue_style( 'satori-events', SATORI_EVENTS_PLUGIN_URL . 'assets/css/satori-events.css', [], SATORI_EVENTS_VERSION );
		}
	}
}
