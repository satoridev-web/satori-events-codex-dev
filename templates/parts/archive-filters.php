<?php
/**
 * Archive filters bar.
 *
 * @package Satori\Events
 */

use Satori\Events\Frontend\Archive_Controller;

$filters   = Archive_Controller::get_filters();
$view      = isset( $view ) ? $view : Archive_Controller::get_view_mode();
$action    = get_post_type_archive_link( 'event' );
$categories = get_terms(
[
'taxonomy'   => 'event_category',
'hide_empty' => false,
]
);
$locations = get_terms(
[
'taxonomy'   => 'event_location',
'hide_empty' => false,
]
);
$has_filters = array_filter( $filters );
?>
<form class="satori-events-filters" method="get" action="<?php echo esc_url( $action ); ?>">
<div class="satori-events-filters__field satori-events-filters__field--search">
<label for="satori-events-filter-keyword" class="screen-reader-text"><?php esc_html_e( 'Search events', 'satori-events' ); ?></label>
<input type="search" id="satori-events-filter-keyword" name="s" value="<?php echo esc_attr( $filters['s'] ); ?>" placeholder="<?php echo esc_attr_x( 'Search events…', 'placeholder', 'satori-events' ); ?>" />
</div>

<div class="satori-events-filters__field">
<label for="satori-events-filter-category" class="screen-reader-text"><?php esc_html_e( 'Filter by category', 'satori-events' ); ?></label>
<select id="satori-events-filter-category" name="event_category">
<option value=""><?php esc_html_e( 'All categories', 'satori-events' ); ?></option>
<?php if ( ! is_wp_error( $categories ) ) : ?>
<?php foreach ( $categories as $category ) : ?>
<option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $filters['event_category'], $category->slug ); ?>><?php echo esc_html( $category->name ); ?></option>
<?php endforeach; ?>
<?php endif; ?>
</select>
</div>

<div class="satori-events-filters__field">
<label for="satori-events-filter-location" class="screen-reader-text"><?php esc_html_e( 'Filter by location', 'satori-events' ); ?></label>
<select id="satori-events-filter-location" name="event_location">
<option value=""><?php esc_html_e( 'All locations', 'satori-events' ); ?></option>
<?php if ( ! is_wp_error( $locations ) ) : ?>
<?php foreach ( $locations as $location ) : ?>
<option value="<?php echo esc_attr( $location->slug ); ?>" <?php selected( $filters['event_location'], $location->slug ); ?>><?php echo esc_html( $location->name ); ?></option>
<?php endforeach; ?>
<?php endif; ?>
</select>
</div>

<?php if ( $view ) : ?>
<input type="hidden" name="view" value="<?php echo esc_attr( $view ); ?>" />
<?php endif; ?>

<div class="satori-events-filters__actions">
<button type="submit" class="button satori-events-filters__apply"><?php esc_html_e( 'Apply filters', 'satori-events' ); ?></button>
<?php if ( $has_filters ) : ?>
<a class="satori-events-filters__clear" href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>"><?php esc_html_e( 'Clear filters', 'satori-events' ); ?></a>
<?php endif; ?>
</div>
</form>
