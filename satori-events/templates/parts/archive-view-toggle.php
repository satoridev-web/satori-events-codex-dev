<?php
/**
 * Archive view toggle.
 *
 * @package Satori\Events
 */

use Satori\Events\Frontend\Archive_Controller;

$current_view = isset( $view ) ? $view : Archive_Controller::get_view_mode();
$filters      = Archive_Controller::get_filters();
$base_url     = get_post_type_archive_link( 'event' );
$args         = [];

foreach ( $filters as $key => $value ) {
if ( '' !== $value ) {
$args[ $key ] = $value;
}
}

$grid_url   = add_query_arg( array_merge( $args, [ 'view' => 'grid' ] ), $base_url );
$list_url   = add_query_arg( array_merge( $args, [ 'view' => 'list' ] ), $base_url );
$grid_class = 'satori-events-view-toggle__button' . ( 'grid' === $current_view ? ' is-active' : '' );
$list_class = 'satori-events-view-toggle__button' . ( 'list' === $current_view ? ' is-active' : '' );
?>
<div class="satori-events-view-toggle" role="group" aria-label="<?php esc_attr_e( 'Choose archive view', 'satori-events' ); ?>">
<a class="<?php echo esc_attr( $grid_class ); ?>" href="<?php echo esc_url( $grid_url ); ?>" aria-pressed="<?php echo esc_attr( 'grid' === $current_view ? 'true' : 'false' ); ?>"><?php esc_html_e( 'Grid view', 'satori-events' ); ?></a>
<a class="<?php echo esc_attr( $list_class ); ?>" href="<?php echo esc_url( $list_url ); ?>" aria-pressed="<?php echo esc_attr( 'list' === $current_view ? 'true' : 'false' ); ?>"><?php esc_html_e( 'List view', 'satori-events' ); ?></a>
</div>
