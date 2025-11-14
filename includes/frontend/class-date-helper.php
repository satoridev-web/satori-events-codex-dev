<?php
/**
 * Date formatting helpers for events.
 *
 * @package Satori\Events
 */

namespace Satori\Events\Frontend;

defined( 'ABSPATH' ) || exit;

/**
 * Provides helpers to format event dates and ranges.
 */
class Date_Helper {
        /**
         * Format an event date range for display.
         *
         * @param string      $start_date Start date (Y-m-d).
         * @param string|null $end_date   End date (Y-m-d) optional.
         * @param string      $format     Preferred date format.
         *
         * @return string
         */
        public static function format_event_date_range( $start_date, $end_date = null, $format = '' ) {
                if ( empty( $start_date ) ) {
                        return '';
                }

                $format    = $format ? $format : get_option( 'date_format', 'F j, Y' );
                $start_ts  = strtotime( $start_date );
                $end_date  = $end_date ?: '';
                $end_ts    = $end_date ? strtotime( $end_date ) : false;

                if ( ! $start_ts ) {
                        return '';
                }

                $single_day = wp_date( $format, $start_ts );

                $month_token = self::get_month_token( $format );
                $day_token   = self::get_day_token( $format );
                $year_token  = self::get_year_token( $format );

                $start_day   = wp_date( $day_token, $start_ts );
                $start_month = wp_date( $month_token, $start_ts );
                $start_year  = wp_date( $year_token, $start_ts );

                if ( $end_ts && $end_ts > $start_ts ) {
                        $end_day        = wp_date( $day_token, $end_ts );
                        $end_month      = wp_date( $month_token, $end_ts );
                        $end_year       = wp_date( $year_token, $end_ts );
                        $start_year_raw = wp_date( 'Y', $start_ts );
                        $end_year_raw   = wp_date( 'Y', $end_ts );
                        $start_month_no = wp_date( 'n', $start_ts );
                        $end_month_no   = wp_date( 'n', $end_ts );

                        if ( $start_year_raw === $end_year_raw && $start_month_no === $end_month_no ) {
                                $formatted = sprintf(
                                        '%1$s–%2$s %3$s %4$s',
                                        $start_day,
                                        $end_day,
                                        $end_month,
                                        $end_year
                                );
                        } else {
                                $formatted = sprintf(
                                        '%1$s %2$s %3$s – %4$s %5$s %6$s',
                                        $start_day,
                                        $start_month,
                                        $start_year,
                                        $end_day,
                                        $end_month,
                                        $end_year
                                );
                        }
                } else {
                        $formatted = $single_day;
                }

                /**
                 * Filter the formatted event date range.
                 *
                 * @param string $formatted Formatted string.
                 * @param string $start_date Start date.
                 * @param string $end_date End date.
                 * @param string $format Original format provided.
                 */
                return apply_filters( 'satori_events_formatted_date_range', $formatted, $start_date, $end_date, $format );
        }

        /**
         * Determine a day token based on the preferred format.
         *
         * @param string $format Preferred format.
         *
         * @return string
         */
        protected static function get_day_token( $format ) {
                if ( false !== strpos( $format, 'd' ) ) {
                        return 'd';
                }

                if ( false !== strpos( $format, 'j' ) ) {
                        return 'j';
                }

                return 'd';
        }

        /**
         * Determine a month token based on the preferred format.
         *
         * @param string $format Preferred format.
         *
         * @return string
         */
        protected static function get_month_token( $format ) {
                if ( false !== strpos( $format, 'F' ) ) {
                        return 'F';
                }

                if ( false !== strpos( $format, 'M' ) ) {
                        return 'M';
                }

                if ( false !== strpos( $format, 'n' ) ) {
                        return 'n';
                }

                if ( false !== strpos( $format, 'm' ) ) {
                        return 'm';
                }

                return 'M';
        }

        /**
         * Determine a year token based on the preferred format.
         *
         * @param string $format Preferred format.
         *
         * @return string
         */
        protected static function get_year_token( $format ) {
                if ( false !== strpos( $format, 'Y' ) ) {
                        return 'Y';
                }

                if ( false !== strpos( $format, 'y' ) ) {
                        return 'y';
                }

                return 'Y';
        }
}
