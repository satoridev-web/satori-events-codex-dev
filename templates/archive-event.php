<?php
/**
 * Event archive template.
 *
 * @package Satori\Events
 */

use Satori\Events\Frontend\Archive_Controller;
use Satori\Events\Templates\Template_Loader;

get_header();

do_action( 'satori_events_before_archive' );

$view = Archive_Controller::get_view_mode();
?>
<main id="primary" class="site-main satori-events-archive satori-events-view-<?php echo esc_attr( $view ); ?>">
<header class="satori-events-archive__header">
<?php Template_Loader::get_template_part( 'parts/archive-filters', [ 'view' => $view ] ); ?>
<?php Template_Loader::get_template_part( 'parts/archive-view-toggle', [ 'view' => $view ] ); ?>
</header>

<?php if ( have_posts() ) : ?>
<div class="satori-events-results satori-events-view-<?php echo esc_attr( $view ); ?>">
<?php
while ( have_posts() ) {
the_post();
Template_Loader::get_template_part( 'parts/content-event-' . ( 'list' === $view ? 'list' : 'card' ) );
}
?>
</div>

<?php the_posts_pagination(); ?>
<?php else : ?>
<p class="satori-events-empty"><?php esc_html_e( 'No upcoming events found.', 'satori-events' ); ?></p>
<?php endif; ?>
</main>
<?php
do_action( 'satori_events_after_archive' );

get_footer();
