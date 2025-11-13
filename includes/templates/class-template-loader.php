<?php
/**
 * Template loading utilities.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Templates;

defined( 'ABSPATH' ) || exit;

/**
 * Handles locating plugin templates with theme overrides.
 */
class Template_Loader {
	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'template_include', [ $this, 'maybe_use_templates' ] );
	}

	/**
	 * Determine whether to load plugin templates.
	 *
	 * @param string $template Current template path.
	 *
	 * @return string
	 */
	public function maybe_use_templates( $template ) {
		if ( is_post_type_archive( 'event' ) || is_tax( [ 'event_category', 'event_location' ] ) ) {
			return self::locate_template( 'archive-event.php', $template );
		}

		if ( is_singular( 'event' ) ) {
			return self::locate_template( 'single-event.php', $template );
		}

		return $template;
	}

	/**
	 * Locate a template with theme overrides.
	 *
	 * @param string $template_name Template name.
	 * @param string $default       Default template path.
	 *
	 * @return string
	 */
	public static function locate_template( $template_name, $default = '' ) {
		$paths = [];

		$stylesheet_dir = get_stylesheet_directory();
		$template_dir   = get_template_directory();

		if ( $stylesheet_dir ) {
			$paths[] = trailingslashit( $stylesheet_dir ) . 'satori-events/' . $template_name;
		}

		if ( $template_dir && $template_dir !== $stylesheet_dir ) {
			$paths[] = trailingslashit( $template_dir ) . 'satori-events/' . $template_name;
		}

		$paths[] = SATORI_EVENTS_PLUGIN_DIR . 'templates/' . $template_name;

		foreach ( $paths as $path ) {
			if ( file_exists( $path ) ) {
				return $path;
			}
		}

		return $default ? $default : $template_name;
	}

	/**
	 * Load a template part.
	 *
	 * @param string $slug Template slug relative to templates directory.
	 * @param array  $args Optional arguments passed to template.
	 *
	 * @return void
	 */
	public static function get_template_part( $slug, $args = [] ) {
		$template = self::locate_template( $slug . '.php' );

		if ( $args ) {
			extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		}

		if ( $template ) {
			include $template;
		}
	}
}
