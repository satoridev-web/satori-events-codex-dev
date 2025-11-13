<?php
/**
 * Archive controller.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Handles archive query modifications and helpers.
 */
class Archive_Controller {
	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'pre_get_posts', [ $this, 'modify_archive_query' ] );
	}

	/**
	 * Adjust the archive query.
	 *
	 * @param \WP_Query $query Query instance.
	 *
	 * @return void
	 */
	public function modify_archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( ! ( $query->is_post_type_archive( 'event' ) || $query->is_tax( [ 'event_category', 'event_location' ] ) ) ) {
			return;
		}

		$today      = current_time( 'Y-m-d' );
		$meta_query = [
			[
				'key'     => '_satori_events_date',
				'value'   => $today,
				'compare' => '>=',
				'type'    => 'DATE',
			],
		];

		$args = [
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'orderby'        => [ 'meta_value' => 'ASC', 'title' => 'ASC' ],
			'meta_key'       => '_satori_events_date',
			'meta_type'      => 'DATE',
			'meta_query'     => $meta_query,
			'posts_per_page' => $query->get( 'posts_per_page' ),
		];

		$args = apply_filters( 'satori_events_archive_query_args', $args, $query );

		foreach ( $args as $key => $value ) {
			$query->set( $key, $value );
		}
	}

	/**
	 * Get the chosen archive view.
	 *
	 * @return string
	 */
	public static function get_view_mode() {
		$view = isset( $_GET['view'] ) ? sanitize_key( wp_unslash( $_GET['view'] ) ) : 'grid';

		return in_array( $view, [ 'grid', 'list' ], true ) ? $view : 'grid';
	}

	/**
	 * Retrieve filter values from the request.
	 *
	 * @return array<string,string>
	 */
	public static function get_filters() {
		$filters = [
			's'              => isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '',
			'event_category' => isset( $_GET['event_category'] ) ? sanitize_text_field( wp_unslash( $_GET['event_category'] ) ) : '',
			'event_location' => isset( $_GET['event_location'] ) ? sanitize_text_field( wp_unslash( $_GET['event_location'] ) ) : '',
		];

		return $filters;
	}
}
