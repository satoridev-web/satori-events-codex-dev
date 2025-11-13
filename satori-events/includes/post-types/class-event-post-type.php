<?php
/**
 * Event post type registration.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Post_Types;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the `event` custom post type.
 */
class Event_Post_Type {
	/**
	 * Hook registration.
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_post_type' ] );
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = [
			'name'                  => __( 'Events', 'satori-events' ),
			'singular_name'         => __( 'Event', 'satori-events' ),
			'add_new'               => __( 'Add New', 'satori-events' ),
			'add_new_item'          => __( 'Add New Event', 'satori-events' ),
			'edit_item'             => __( 'Edit Event', 'satori-events' ),
			'new_item'              => __( 'New Event', 'satori-events' ),
			'view_item'             => __( 'View Event', 'satori-events' ),
			'view_items'            => __( 'View Events', 'satori-events' ),
			'search_items'          => __( 'Search Events', 'satori-events' ),
			'not_found'             => __( 'No events found.', 'satori-events' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'satori-events' ),
			'all_items'             => __( 'All Events', 'satori-events' ),
			'archives'              => __( 'Event Archives', 'satori-events' ),
			'attributes'            => __( 'Event Attributes', 'satori-events' ),
			'insert_into_item'      => __( 'Insert into event', 'satori-events' ),
			'uploaded_to_this_item' => __( 'Uploaded to this event', 'satori-events' ),
			'menu_name'             => __( 'Events', 'satori-events' ),
			'name_admin_bar'        => __( 'Event', 'satori-events' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-calendar-alt',
			'menu_position'      => 20,
			'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ],
			'rewrite'            => [ 'slug' => 'events' ],
			'publicly_queryable' => true,
			'show_in_nav_menus'  => true,
		];

		register_post_type( 'event', $args );
	}
}
