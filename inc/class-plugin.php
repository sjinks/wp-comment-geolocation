<?php

namespace WildWolf\WordPress\CommentGeolocation;

use MaxMind\Db\Reader;
use Throwable;
use WildWolf\Utils\Singleton;

final class Plugin {
	use Singleton;

	private ?Reader $reader = null;

	private function __construct() {
		if ( is_admin() ) {
			add_action( 'init', [ $this, 'init' ] );
			add_action( 'admin_init', [ $this, 'admin_init' ] );
		}
	}

	public function init(): void {
		load_plugin_textdomain( 'wwipgeoc', false, plugin_basename( dirname( __DIR__ ) ) . '/lang/' );
	}

	public function admin_init(): void {
		add_filter( 'manage_edit-comments_columns', [ $this, 'manage_edit_comments_columns' ] );
		add_action( 'manage_comments_custom_column', [ $this, 'manage_comments_custom_column' ], 10, 2 );
	}

	/**
	 * @param string[] $columns
	 */
	public function manage_edit_comments_columns( $columns ): array {
		$filename = (string) apply_filters( 'ww_ipgeo_geoip_city_database', '/usr/share/GeoIP/GeoIP2-City.mmdb' );
		if ( ! $filename || ! is_readable( $filename ) ) {
			return $columns;
		}

		try {
			$this->reader = new Reader( $filename );
			return $columns + [ 'ww_ipgeo' => __( 'IP Geolocation', 'wwipgeoc' ) ];
		} catch ( Throwable $e ) {
			return $columns;
		}
	}

	/**
	 * @param string $column
	 * @param int    $comment_id
	 */
	public function manage_comments_custom_column( $column, $comment_id ): void {
		if ( 'ww_ipgeo' === $column ) {
			assert( $this->reader instanceof Reader );

			$na = __( 'N/A', 'wwipgeoc' );
			$ip = get_comment_author_IP( $comment_id );

			/** @var array */
			$record = $this->reader->get( $ip );
			/** @psalm-suppress MixedArrayAccess */
			$country = (string) ( $record['country']['names']['en'] ?? ( $record['registered_country']['names']['en'] ?? ( $record['represented_country']['names']['en'] ) ?? $na ) );
			$code    = (string) ( $record['country']['iso_code'] ?? ( $record['registered_country']['iso_code'] ?? ( $record['represented_country']['iso_code'] ) ?? '' ) );
			/** @psalm-suppress MixedArrayAccess */
			$city = (string) ( $record['city']['names']['en'] ?? $na );

			printf(
				// translators: %1$s - IP address, %2$s - country flag, %3$s - country name, %4$s - city name
				__( '<strong style="vertical-align: middle;">%1$s</strong> %2$s<br/>%3$s, %4$s', 'wwipgeoc' ),
				esc_html( $ip ),
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$code ? ( '<img src="https://www.countryflags.io/' . esc_url( strtolower( $code ) ) . '/flat/24.png" alt="" style="vertical-align: middle"/>' ) : '',
				esc_html( $country ),
				esc_html( $city )
			);
		}
	}
}
