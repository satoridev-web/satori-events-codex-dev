<?php
/**
 * Event card layout for grid views.
 *
 * @package Satori\Events
 */

$event_id       = get_the_ID();
$date_value     = get_post_meta( $event_id, '_satori_events_date', true );
$date_end_value = get_post_meta( $event_id, '_satori_events_date_end', true );
$date_format    = get_option( 'date_format', 'M j, Y' );
$time_format    = get_option( 'time_format', 'g:i a' );
$event_date     = \Satori\Events\Frontend\Date_Helper::format_event_date_range( $date_value, $date_end_value, $date_format );
$time_start  = get_post_meta( $event_id, '_satori_events_time_start', true );
$time_start  = $time_start ? wp_date( $time_format, strtotime( $time_start ) ) : '';
$categories  = get_the_term_list( $event_id, 'event_category', '', ', ' );
$locations   = get_the_term_list( $event_id, 'event_location', '', ', ' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'satori-event-card' ); ?>>
<a class="satori-event-card__link" href="<?php the_permalink(); ?>">
<?php if ( has_post_thumbnail() ) : ?>
<div class="satori-event-card__image">
<?php the_post_thumbnail( 'medium_large' ); ?>
</div>
<?php endif; ?>

<header class="satori-event-card__header">
<?php if ( $event_date ) : ?>
<span class="satori-event-card__date"><?php echo esc_html( $event_date ); ?></span>
<?php endif; ?>
<h2 class="satori-event-card__title"><?php the_title(); ?></h2>
<?php if ( $time_start ) : ?>
<span class="satori-event-card__time"><?php echo esc_html( $time_start ); ?></span>
<?php endif; ?>
</header>

<div class="satori-event-card__excerpt">
<?php the_excerpt(); ?>
</div>
</a>

<footer class="satori-event-card__footer">
<?php if ( $categories ) : ?>
<span class="satori-event-card__taxonomy satori-event-card__taxonomy--category"><?php echo wp_kses_post( $categories ); ?></span>
<?php endif; ?>
<?php if ( $locations ) : ?>
<span class="satori-event-card__taxonomy satori-event-card__taxonomy--location"><?php echo wp_kses_post( $locations ); ?></span>
<?php endif; ?>
<a class="satori-event-card__read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'satori-events' ); ?></a>
</footer>
</article>
