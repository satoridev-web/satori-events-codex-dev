<?php
/**
 * Single event template.
 *
 * @package Satori\Events
 */

use Satori\Events\Frontend\Single_Controller;

get_header();

do_action( 'satori_events_before_single' );

if ( have_posts() ) :
while ( have_posts() ) :
the_post();
$meta        = Single_Controller::get_event_meta( get_the_ID() );
$date_format = get_option( 'date_format', 'F j, Y' );
$time_format = get_option( 'time_format', 'g:i a' );
$event_date  = ! empty( $meta['event_date'] ) ? wp_date( $date_format, strtotime( $meta['event_date'] ) ) : '';
$time_start  = ! empty( $meta['time_start'] ) ? wp_date( $time_format, strtotime( $meta['time_start'] ) ) : '';
$time_end    = ! empty( $meta['time_end'] ) ? wp_date( $time_format, strtotime( $meta['time_end'] ) ) : '';
$categories  = get_the_term_list( get_the_ID(), 'event_category', '', ', ' );
$locations   = get_the_term_list( get_the_ID(), 'event_location', '', ', ' );
?>
<main id="primary" class="site-main satori-event-single">
<article id="post-<?php the_ID(); ?>" <?php post_class( 'satori-event' ); ?>>
<header class="satori-event__header">
<?php if ( has_post_thumbnail() ) : ?>
<div class="satori-event__featured-image">
<?php the_post_thumbnail( 'large' ); ?>
</div>
<?php endif; ?>

<div class="satori-event__summary">
<h1 class="satori-event__title"><?php the_title(); ?></h1>

<ul class="satori-event__meta">
<?php if ( $event_date ) : ?>
<li>
<strong><?php esc_html_e( 'Date', 'satori-events' ); ?>:</strong>
<span><?php echo esc_html( $event_date ); ?></span>
</li>
<?php endif; ?>
<?php if ( $time_start ) : ?>
<li>
<strong><?php esc_html_e( 'Time', 'satori-events' ); ?>:</strong>
<span>
<?php echo esc_html( $time_start ); ?>
<?php if ( $time_end ) : ?>
<span class="satori-event__time-separator"><?php esc_html_e( 'to', 'satori-events' ); ?></span>
<?php echo esc_html( $time_end ); ?>
<?php endif; ?>
</span>
</li>
<?php endif; ?>
<?php if ( ! empty( $meta['venue'] ) ) : ?>
<li>
<strong><?php esc_html_e( 'Venue', 'satori-events' ); ?>:</strong>
<span><?php echo esc_html( $meta['venue'] ); ?></span>
</li>
<?php endif; ?>
<?php if ( ! empty( $meta['address'] ) ) : ?>
<li>
<strong><?php esc_html_e( 'Address', 'satori-events' ); ?>:</strong>
<span><?php echo wp_kses_post( nl2br( esc_html( $meta['address'] ) ) ); ?></span>
</li>
<?php endif; ?>
<?php if ( ! empty( $meta['external_url'] ) ) : ?>
<li>
<strong><?php esc_html_e( 'More Info', 'satori-events' ); ?>:</strong>
<a href="<?php echo esc_url( $meta['external_url'] ); ?>" target="_blank" rel="noopener">
<?php esc_html_e( 'Visit event website', 'satori-events' ); ?>
</a>
</li>
<?php endif; ?>
<?php if ( $categories ) : ?>
<li>
<strong><?php esc_html_e( 'Categories', 'satori-events' ); ?>:</strong>
<span><?php echo wp_kses_post( $categories ); ?></span>
</li>
<?php endif; ?>
<?php if ( $locations ) : ?>
<li>
<strong><?php esc_html_e( 'Locations', 'satori-events' ); ?>:</strong>
<span><?php echo wp_kses_post( $locations ); ?></span>
</li>
<?php endif; ?>
<?php if ( ! empty( $meta['organizer_name'] ) || ! empty( $meta['organizer_email'] ) ) : ?>
<li>
<strong><?php esc_html_e( 'Organizer', 'satori-events' ); ?>:</strong>
<span>
<?php if ( ! empty( $meta['organizer_name'] ) ) : ?>
<?php echo esc_html( $meta['organizer_name'] ); ?>
<?php endif; ?>
<?php if ( ! empty( $meta['organizer_email'] ) ) : ?>
<?php if ( ! empty( $meta['organizer_name'] ) ) : ?>
<span class="satori-event__organizer-separator" aria-hidden="true">&middot;</span>
<?php endif; ?>
<a href="mailto:<?php echo esc_attr( $meta['organizer_email'] ); ?>"><?php echo esc_html( $meta['organizer_email'] ); ?></a>
<?php endif; ?>
</span>
</li>
<?php endif; ?>
</ul>

<a class="satori-event__back" href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>">&larr; <?php esc_html_e( 'Back to events', 'satori-events' ); ?></a>
</div>
</header>

<div class="satori-event__content">
<?php the_content(); ?>
</div>
</article>
</main>
<?php
endwhile;
else :
?>
<main id="primary" class="site-main satori-event-single">
<p class="satori-event-empty"><?php esc_html_e( 'Event not found.', 'satori-events' ); ?></p>
</main>
<?php
endif;

do_action( 'satori_events_after_single' );

get_footer();
