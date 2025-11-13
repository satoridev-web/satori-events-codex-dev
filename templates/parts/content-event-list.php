<?php
/**
 * Event list layout.
 *
 * @package Satori\Events
 */

$event_id    = get_the_ID();
$date_value  = get_post_meta( $event_id, '_satori_events_date', true );
$date_format = get_option( 'date_format', 'M j, Y' );
$event_date  = $date_value ? wp_date( $date_format, strtotime( $date_value ) ) : '';
$locations   = get_the_term_list( $event_id, 'event_location', '', ', ' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'satori-event-list-item' ); ?>>
<div class="satori-event-list-item__date">
<?php if ( $event_date ) : ?>
<span><?php echo esc_html( $event_date ); ?></span>
<?php endif; ?>
</div>
<div class="satori-event-list-item__content">
<h2 class="satori-event-list-item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<div class="satori-event-list-item__excerpt">
<?php the_excerpt(); ?>
</div>
<?php if ( $locations ) : ?>
<div class="satori-event-list-item__location">
<strong><?php esc_html_e( 'Location:', 'satori-events' ); ?></strong>
<span><?php echo wp_kses_post( $locations ); ?></span>
</div>
<?php endif; ?>
<a class="satori-event-list-item__read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'satori-events' ); ?></a>
</div>
</article>
