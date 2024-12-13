<?php

namespace EstatebudConnect;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}

use EstatebudConnect\Traits\Singleton;

class Schema {
	use Singleton;

	/**
	 * Create tables in database.
	 *
	 * @return void
	 */
	public function create() {
		global $wpdb;

		$tables = array(
			'table_name' => "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}table_name (
				id INT(11) NOT NULL AUTO_INCREMENT,
				column_name VARCHAR(255) NOT NULL,
				created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY (id)
			);",
		);

		foreach ( $tables as $sql ) {
			$wpdb->query( $sql );
			if ( ! empty( $wpdb->last_error ) ) {
				Log::instance()->write(
					'error',
					array(
						'error' => $wpdb->last_error,
						'query' => $sql,
					)
				);
				break;
			}
		}
	}

	/**
	 * Delete tables in database.
	 *
	 * @return void
	 */
	public function delete() {
		global $wpdb;

		$tables = array(
			'table_name' => "DROP TABLE IF EXISTS {$wpdb->prefix}table_name",
		);

		foreach ( $tables as $sql ) {
			$wpdb->query( $sql );
			if ( ! empty( $wpdb->last_error ) ) {
				Log::instance()->write(
					'error',
					array(
						'error' => $wpdb->last_error,
						'query' => $sql,
					)
				);
				break;
			}
		}
	}

	/**
	 * Truncate tables in database.
	 *
	 * @return void
	 */
	public function truncate() {
		global $wpdb;

		$tables = array(
			'table_name' => "TRUNCATE TABLE {$wpdb->prefix}table_name",
		);

		foreach ( $tables as $sql ) {
			$wpdb->query( $sql );
			if ( ! empty( $wpdb->last_error ) ) {
				Log::instance()->write(
					'error',
					array(
						'error' => $wpdb->last_error,
						'query' => $sql,
					)
				);
				break;
			}
		}
	}
}
