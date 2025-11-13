<?php
/**
 * Single event helpers.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Provides helpers for single event templates.
 */
class Single_Controller {
	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
	}

	/**
	 * Add custom body class for event pages.
	 *
	 * @param array $classes Body classes.
	 *
	 * @return array
	 */
	public function add_body_class( $classes ) {
		if ( is_singular( 'event' ) ) {
			$classes[] = 'satori-event-single';
		}

		return $classes;
	}

	/**
	 * Get formatted meta for display.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return array<string,mixed>
	 */
	public static function get_event_meta( $post_id ) {
		$meta = [
			'event_date'      => get_post_meta( $post_id, '_satori_events_date', true ),
			'time_start'      => get_post_meta( $post_id, '_satori_events_time_start', true ),
			'time_end'        => get_post_meta( $post_id, '_satori_events_time_end', true ),
			'venue'           => get_post_meta( $post_id, '_satori_events_venue', true ),
			'address'         => get_post_meta( $post_id, '_satori_events_address', true ),
			'external_url'    => get_post_meta( $post_id, '_satori_events_external_url', true ),
			'organizer_name'  => get_post_meta( $post_id, '_satori_events_organizer_name', true ),
			'organizer_email' => get_post_meta( $post_id, '_satori_events_organizer_email', true ),
		];

		return apply_filters( 'satori_events_single_meta_output', $meta, $post_id );
	}
}
