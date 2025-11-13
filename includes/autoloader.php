<?php
/**
 * Autoloader for Satori Events.
 *
 * @package Satori\Events
 */

namespace Satori\Events;

defined( 'ABSPATH' ) || exit;

/**
 * Register autoloader for plugin classes.
 *
 * @return void
 */
function register_autoloader() {
	spl_autoload_register( static function ( $class ) {
		$prefix = __NAMESPACE__ . '\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$parts    = explode( '\\', $relative );
		$parts    = array_map( static function ( $part ) {
			return strtolower( str_replace( '_', '-', $part ) );
		}, $parts );

		$filename = 'class-' . array_pop( $parts ) . '.php';
		$path     = SATORI_EVENTS_PLUGIN_DIR . 'includes/';

		if ( ! empty( $parts ) ) {
			$path .= implode( '/', $parts ) . '/';
		}

		$path .= $filename;

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	} );
}

register_autoloader();
