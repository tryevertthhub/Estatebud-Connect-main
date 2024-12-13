<?php

namespace EstatebudConnect\Traits;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}

use WP_Error;
use const EstatebudConnect\SLUG;
use const EstatebudConnect\VERSION;
trait Api {
	/**
	 * The namespace of the API.
	 *
	 * @var string
	 */
	private static $namespace = SLUG . '/v' . VERSION;
	/**
	 * Base response.
	 *
	 * @var array
	 */
	private $response = array(
		'success' => true,
		'message' => '',
		'data'    => array(),
	);


	/**
	 * Retrieves the URL for the API endpoint.
	 *
	 * @return string The URL for the API endpoint.
	 */
	public static function get_url() {
		return get_rest_url() . self::$namespace . '/' . self::$route;
	}


	/**
	 * Check if a given request has access to get data from custom table
	 *
	 * @return WP_Error|bool
	 */
	public function options_permissions_check() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_forbidden',
				__( 'Sorry, you are not allowed to use this endpoint', 'estatebud-connect' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return true;
	}
}
