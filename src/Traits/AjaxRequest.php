<?php

namespace EstatebudConnect\Traits;

use const EstatebudConnect\SLUG;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}

use function is_user_logged_in;

trait AjaxRequest {
	/**
	 * Array to store registered AJAX response.
	 *
	 * @var array
	 */
	private $response = array();
	/**
	 * Array to store registered AJAX posted data.
	 *
	 * @var array
	 */
	private $data = array();
	/**
	 * Array to store registered AJAX errors.
	 *
	 * @var array
	 */
	private $errors = array();
	/**
	 * Sets an error message in the response.
	 *
	 * @param string $message The error message to set. Default is 'Something went wrong! Please try again later.
	 * @return self Returns the current object.
	 */
	private function set_error( string $message = 'Something went wrong! Please try again later.' ): self {
		$this->errors = $message;
		return $this;
	}
	/**
	 * Sets the data in the response array.
	 *
	 * @param mixed $data The data to be set.
	 * @return self Returns the current object.
	 */
	private function set_data( $data ): self {
		$this->response = $data;

		return $this;
	}

	/**
	 * Serve data to AJAX request.
	 */
	private function serve(): void {
		if ( empty( $this->errors ) ) {
			wp_send_json_success( $this->response );
		}
		wp_send_json_error( $this->errors );
	}

	/**
	 * Verify and validate AJAX request.
	 *
	 * @param array  $fields The fields to verify.
	 * @param string $key The key to use for verification. Default is 'data'.
	 */
	private function verify( array $fields = array(), string $key = 'data' ): void {
		check_ajax_referer( SLUG, 'security_token' );
		$this->response = array();
		$this->errors   = array();
		if ( ! empty( $fields ) ) {
			// Will sanitized data before use.
			$un_slashed = array_map( 'wp_unslash', $_REQUEST[ $key ] ?? array() ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$data       = array_column( $un_slashed, 'value', 'name' );
			$filtered   = array_filter(
				$data,
				function ( $value, $key ) {
					switch ( $key ) {
						case 'email':
							return sanitize_email( $value );
						case 'comment':
						case 'message':
							return sanitize_textarea_field( $value );
						default:
							return sanitize_text_field( $value );
					}
				},
				ARRAY_FILTER_USE_BOTH
			);
			$this->data = $this->extract_data( $filtered, $fields );
		}
	}

	/**
	 * Extracts data from an array using the given keys.
	 *
	 * @param array $sanitized The array from which to extract data.
	 * @param array $keys The keys to use for extraction.
	 *
	 * @return array The extracted data.
	 */
	public function extract_data( array $sanitized, array $keys ): array {
		return array_intersect_key( $sanitized, array_flip( $keys ) );
	}

	/**
	 * Generate AJAX action for based on logged-in users.
	 *
	 * @param string $action The action to register.
	 */
	public function action( string $action ): string {
		if ( is_user_logged_in() ) {
			return sprintf( 'wp_ajax_%s-%s', SLUG, $action );
		}
		return sprintf( 'wp_ajax_nopriv_%s-%s', SLUG, $action );
	}
}
