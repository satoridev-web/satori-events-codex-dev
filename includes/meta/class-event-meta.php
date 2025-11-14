<?php
/**
 * Event meta boxes and saving.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Handles event metadata.
 */
class Event_Meta {
	/**
	 * Meta key definitions.
	 *
	 * @var array<string,string>
	 */
        protected $meta_keys = [
                'event_date'      => '_satori_events_date',
                'event_date_end'  => '_satori_events_date_end',
		'time_start'      => '_satori_events_time_start',
		'time_end'        => '_satori_events_time_end',
		'venue'           => '_satori_events_venue',
		'address'         => '_satori_events_address',
		'external_url'    => '_satori_events_external_url',
		'organizer_name'  => '_satori_events_organizer_name',
		'organizer_email' => '_satori_events_organizer_email',
	];

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
        public function register() {
                add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
                add_action( 'save_post_event', [ $this, 'save_meta' ] );
                add_action( 'admin_notices', [ $this, 'maybe_render_invalid_end_date_notice' ] );
	}

	/**
	 * Adds meta boxes for events.
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'satori-events-details',
			__( 'Event Details', 'satori-events' ),
			[ $this, 'render_meta_box' ],
			'event',
			'normal',
			'high'
		);
	}

	/**
	 * Render the event details meta box.
	 *
	 * @param \WP_Post $post Post object.
	 *
	 * @return void
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( 'satori_events_save_meta', 'satori_events_meta_nonce' );

		$values = [];

		foreach ( $this->meta_keys as $key => $meta_key ) {
			$values[ $key ] = get_post_meta( $post->ID, $meta_key, true );
		}
		?>
                <p>
                        <label for="satori-events-date"><strong><?php esc_html_e( 'Event Date', 'satori-events' ); ?></strong></label><br />
                        <input type="date" id="satori-events-date" name="satori_events[event_date]" value="<?php echo esc_attr( $values['event_date'] ); ?>" required />
                </p>
                <p>
                        <label for="satori-events-date-end"><strong><?php esc_html_e( 'End Date', 'satori-events' ); ?></strong></label><br />
                        <input type="date" id="satori-events-date-end" name="satori_events[event_date_end]" value="<?php echo esc_attr( $values['event_date_end'] ); ?>" />
                </p>
		<p>
			<label for="satori-events-time-start"><?php esc_html_e( 'Start Time', 'satori-events' ); ?></label><br />
			<input type="time" id="satori-events-time-start" name="satori_events[time_start]" value="<?php echo esc_attr( $values['time_start'] ); ?>" />
		</p>
		<p>
			<label for="satori-events-time-end"><?php esc_html_e( 'End Time', 'satori-events' ); ?></label><br />
			<input type="time" id="satori-events-time-end" name="satori_events[time_end]" value="<?php echo esc_attr( $values['time_end'] ); ?>" />
		</p>
		<p>
			<label for="satori-events-venue"><?php esc_html_e( 'Venue', 'satori-events' ); ?></label><br />
			<input type="text" class="widefat" id="satori-events-venue" name="satori_events[venue]" value="<?php echo esc_attr( $values['venue'] ); ?>" />
		</p>
		<p>
			<label for="satori-events-address"><?php esc_html_e( 'Address', 'satori-events' ); ?></label><br />
			<textarea class="widefat" rows="3" id="satori-events-address" name="satori_events[address]"><?php echo esc_textarea( $values['address'] ); ?></textarea>
		</p>
		<p>
			<label for="satori-events-external-url"><?php esc_html_e( 'External URL', 'satori-events' ); ?></label><br />
			<input type="url" class="widefat" id="satori-events-external-url" name="satori_events[external_url]" value="<?php echo esc_attr( $values['external_url'] ); ?>" placeholder="https://example.com" />
		</p>
		<p>
			<label for="satori-events-organizer-name"><?php esc_html_e( 'Organizer Name', 'satori-events' ); ?></label><br />
			<input type="text" class="widefat" id="satori-events-organizer-name" name="satori_events[organizer_name]" value="<?php echo esc_attr( $values['organizer_name'] ); ?>" />
		</p>
		<p>
			<label for="satori-events-organizer-email"><?php esc_html_e( 'Organizer Email', 'satori-events' ); ?></label><br />
			<input type="email" class="widefat" id="satori-events-organizer-email" name="satori_events[organizer_email]" value="<?php echo esc_attr( $values['organizer_email'] ); ?>" />
		</p>
		<?php
	}

	/**
	 * Save meta box values.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public function save_meta( $post_id ) {
		if ( ! isset( $_POST['satori_events_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['satori_events_meta_nonce'] ), 'satori_events_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['satori_events'] ) || ! is_array( $_POST['satori_events'] ) ) {
			return;
		}

		$data = wp_unslash( $_POST['satori_events'] );

		$date_raw = isset( $data['event_date'] ) ? sanitize_text_field( $data['event_date'] ) : '';

		if ( $date_raw && ! $this->is_valid_date( $date_raw ) ) {
			$date_raw = '';
		}

                if ( $date_raw ) {
                        update_post_meta( $post_id, $this->meta_keys['event_date'], $date_raw );
                } else {
                        delete_post_meta( $post_id, $this->meta_keys['event_date'] );
                }

                $end_date_raw = isset( $data['event_date_end'] ) ? sanitize_text_field( $data['event_date_end'] ) : '';
                $end_date     = '';

                if ( $end_date_raw ) {
                        if ( $this->is_valid_date( $end_date_raw ) && $date_raw && $end_date_raw >= $date_raw ) {
                                $end_date = $end_date_raw;
                        } else {
                                $this->flag_invalid_end_date();
                        }
                }

                $this->update_optional_meta( $post_id, 'event_date_end', $end_date );

		$time_start = isset( $data['time_start'] ) ? sanitize_text_field( $data['time_start'] ) : '';
		if ( $time_start && ! $this->is_valid_time( $time_start ) ) {
			$time_start = '';
		}
		$this->update_optional_meta( $post_id, 'time_start', $time_start );

		$time_end = isset( $data['time_end'] ) ? sanitize_text_field( $data['time_end'] ) : '';
		if ( $time_end && ! $this->is_valid_time( $time_end ) ) {
			$time_end = '';
		}
		$this->update_optional_meta( $post_id, 'time_end', $time_end );

		$this->update_optional_meta( $post_id, 'venue', isset( $data['venue'] ) ? sanitize_text_field( $data['venue'] ) : '' );
		$this->update_optional_meta( $post_id, 'address', isset( $data['address'] ) ? sanitize_textarea_field( $data['address'] ) : '' );

		$external_url = isset( $data['external_url'] ) ? esc_url_raw( $data['external_url'] ) : '';
		$this->update_optional_meta( $post_id, 'external_url', $external_url );

		$this->update_optional_meta( $post_id, 'organizer_name', isset( $data['organizer_name'] ) ? sanitize_text_field( $data['organizer_name'] ) : '' );

		$organizer_email = isset( $data['organizer_email'] ) ? sanitize_text_field( $data['organizer_email'] ) : '';
		if ( $organizer_email && ! is_email( $organizer_email ) ) {
			$organizer_email = '';
		}
		$this->update_optional_meta( $post_id, 'organizer_email', $organizer_email );
	}

	/**
	 * Update optional meta field.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $key     Field key index.
	 * @param string $value   Value to save.
	 *
	 * @return void
	 */
	protected function update_optional_meta( $post_id, $key, $value ) {
		$meta_key = $this->meta_keys[ $key ];

		if ( '' !== $value && null !== $value ) {
			update_post_meta( $post_id, $meta_key, $value );
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}

	/**
	 * Validate Y-m-d date.
	 *
	 * @param string $value Input value.
	 *
	 * @return bool
	 */
        protected function is_valid_date( $value ) {
                $dt = \DateTime::createFromFormat( 'Y-m-d', $value );

                return $dt && $dt->format( 'Y-m-d' ) === $value;
        }

        /**
         * Flag invalid end date so we can show a notice.
         *
         * @return void
         */
        protected function flag_invalid_end_date() {
                if ( ! has_filter( 'redirect_post_location', [ $this, 'add_invalid_end_date_notice_arg' ] ) ) {
                        add_filter( 'redirect_post_location', [ $this, 'add_invalid_end_date_notice_arg' ] );
                }
        }

        /**
         * Append query arg to trigger invalid end date notice.
         *
         * @param string $location Redirect location.
         *
         * @return string
         */
        public function add_invalid_end_date_notice_arg( $location ) {
                remove_filter( 'redirect_post_location', [ $this, __FUNCTION__ ] );

                return add_query_arg( 'satori_invalid_end_date', 1, $location );
        }

        /**
         * Maybe render invalid end date admin notice.
         *
         * @return void
         */
        public function maybe_render_invalid_end_date_notice() {
                if ( empty( $_GET['satori_invalid_end_date'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                        return;
                }

                $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

                if ( $screen && 'event' !== $screen->post_type ) {
                        return;
                }

                printf(
                        '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                        esc_html__( 'End date must be the same as or after the start date.', 'satori-events' )
                );
        }

	/**
	 * Validate H:i time.
	 *
	 * @param string $value Input value.
	 *
	 * @return bool
	 */
	protected function is_valid_time( $value ) {
		$dt = \DateTime::createFromFormat( 'H:i', $value );

		return $dt && $dt->format( 'H:i' ) === $value;
	}
}
