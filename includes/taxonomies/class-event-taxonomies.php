<?php
/**
 * Event taxonomies registration.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Taxonomies;

defined( 'ABSPATH' ) || exit;

/**
 * Registers event taxonomies.
 */
class Event_Taxonomies {
	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_taxonomies' ] );
	}

	/**
	 * Register the taxonomies.
	 *
	 * @return void
	 */
	public function register_taxonomies() {
		register_taxonomy(
			'event_category',
			'event',
			[
				'labels'            => [
					'name'              => __( 'Event Categories', 'satori-events' ),
					'singular_name'     => __( 'Event Category', 'satori-events' ),
					'search_items'      => __( 'Search Event Categories', 'satori-events' ),
					'all_items'         => __( 'All Event Categories', 'satori-events' ),
					'edit_item'         => __( 'Edit Event Category', 'satori-events' ),
					'update_item'       => __( 'Update Event Category', 'satori-events' ),
					'add_new_item'      => __( 'Add New Event Category', 'satori-events' ),
					'new_item_name'     => __( 'New Event Category', 'satori-events' ),
					'menu_name'         => __( 'Categories', 'satori-events' ),
					'parent_item'       => __( 'Parent Event Category', 'satori-events' ),
					'parent_item_colon' => __( 'Parent Event Category:', 'satori-events' ),
				],
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => [ 'slug' => 'event-category' ],
			]
		);

		register_taxonomy(
			'event_location',
			'event',
			[
				'labels'            => [
					'name'              => __( 'Event Locations', 'satori-events' ),
					'singular_name'     => __( 'Event Location', 'satori-events' ),
					'search_items'      => __( 'Search Event Locations', 'satori-events' ),
					'all_items'         => __( 'All Event Locations', 'satori-events' ),
					'edit_item'         => __( 'Edit Event Location', 'satori-events' ),
					'update_item'       => __( 'Update Event Location', 'satori-events' ),
					'add_new_item'      => __( 'Add New Event Location', 'satori-events' ),
					'new_item_name'     => __( 'New Event Location', 'satori-events' ),
					'menu_name'         => __( 'Locations', 'satori-events' ),
					'parent_item'       => __( 'Parent Event Location', 'satori-events' ),
					'parent_item_colon' => __( 'Parent Event Location:', 'satori-events' ),
				],
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => [ 'slug' => 'event-location' ],
			]
		);
	}
}
