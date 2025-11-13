<?php
/**
 * Archive shortcode implementation.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Shortcodes;

use Satori\Events\Templates\Template_Loader;

defined( 'ABSPATH' ) || exit;

/**
 * Provides the [satori_events_archive] shortcode.
 */
class Archive_Shortcode {
	/**
	 * Register shortcode.
	 *
	 * @return void
	 */
	public function register() {
		add_shortcode( 'satori_events_archive', [ $this, 'render' ] );
	}

	/**
	 * Render the shortcode output.
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			[
				'view'           => 'grid',
				'category'       => '',
				'location'       => '',
				'posts_per_page' => get_option( 'posts_per_page', 10 ),
			],
			$atts,
			'satori_events_archive'
		);

		$view = in_array( $atts['view'], [ 'grid', 'list' ], true ) ? $atts['view'] : 'grid';

		$posts_per_page = intval( $atts['posts_per_page'] );

		if ( 0 === $posts_per_page ) {
			$posts_per_page = (int) get_option( 'posts_per_page', 10 );
		}

		$query_args = [
			'post_type'           => 'event',
			'post_status'         => 'publish',
			'order'               => 'ASC',
			'orderby'             => [ 'meta_value' => 'ASC', 'title' => 'ASC' ],
			'ignore_sticky_posts' => true,
			'meta_key'            => '_satori_events_date',
			'meta_type'           => 'DATE',
			'meta_query'          => [
				[
					'key'     => '_satori_events_date',
					'value'   => current_time( 'Y-m-d' ),
					'compare' => '>=',
					'type'    => 'DATE',
				],
			],
			'posts_per_page'      => $posts_per_page,
		];

		$tax_query = [];

		if ( ! empty( $atts['category'] ) ) {
			$tax_query[] = [
				'taxonomy' => 'event_category',
				'field'    => 'slug',
				'terms'    => sanitize_title( $atts['category'] ),
			];
		}

		if ( ! empty( $atts['location'] ) ) {
			$tax_query[] = [
				'taxonomy' => 'event_location',
				'field'    => 'slug',
				'terms'    => sanitize_title( $atts['location'] ),
			];
		}

		if ( ! empty( $tax_query ) ) {
			$query_args['tax_query'] = $tax_query;
		}

		$query_args = apply_filters( 'satori_events_shortcode_query_args', $query_args, $atts );

		$events = new \WP_Query( $query_args );

		ob_start();

		$wrapper_classes = [ 'satori-events-archive-shortcode', 'view-' . $view ];
		?>
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>">
			<?php if ( $events->have_posts() ) : ?>
				<div class="satori-events-list satori-events-view-<?php echo esc_attr( $view ); ?>">
					<?php
					while ( $events->have_posts() ) {
						$events->the_post();
						Template_Loader::get_template_part( 'parts/content-event-' . ( 'grid' === $view ? 'card' : 'list' ) );
					}
					?>
				</div>
			<?php else : ?>
				<p class="satori-events-empty"><?php esc_html_e( 'No upcoming events found.', 'satori-events' ); ?></p>
			<?php endif; ?>
		</div>
		<?php

		wp_reset_postdata();

		return ob_get_clean();
	}
}
