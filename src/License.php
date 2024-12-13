<?php

namespace EstatebudConnect;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}

use const EstatebudConnect\SLUG;
use const EstatebudConnect\VERSION;
use EstatebudConnect\Traits\Singleton;

final class License {
	use Singleton;

	/**
	 * The API URL.
	 *
	 * @var string
	 */
	private $api = 'https://example.com/wp-json/' . SLUG . '/license';

	/**
	 * Register event with license manager.
	 *
	 * @param string $event The event name. e.g 'activate', 'deactivate' and 'uninstall'.
	 */
	public function __construct( string $event ) {
		if ( ! in_array( $event, array( 'activate', 'deactivate', 'uninstall' ), true ) ) {
			return;
		}

		$site_url = esc_url_raw( home_url() );

		$data = array(
			'url'     => $site_url,
			'event'   => $event,
			'version' => VERSION,
			'name'    => SLUG,
		);

		$headers = array(
			'user-agent'    => SLUG . ';' . password_hash( $site_url, PASSWORD_BCRYPT ),
			'Accept'        => 'application/json',
			'Content-Type'  => 'application/json',
			'Origin'        => $site_url,
			'Referer'       => $site_url,
			'Cache-Control' => 'no-cache',
		);

		$response = wp_remote_post(
			$this->api,
			array(
				'timeout'     => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'headers'     => $headers,
				'body'        => wp_json_encode( $data ),
				'sslverify'   => false,
				'cookies'     => array(),
			)
		);

		if ( false === is_wp_error( $response ) && false === get_option( SLUG . '-license-key' ) ) {
			$response = wp_remote_retrieve_body( $response );
			update_option( SLUG . '-license-key', $response );
		}
	}
}
