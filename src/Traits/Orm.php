<?php

namespace EstatebudConnect\Traits;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}
trait Orm {

	/**
	 * Get the table name with WordPress prefix.
	 *
	 * @return string
	 */
	protected static function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . self::$table;
	}

	/**
	 * Insert a new record into the database.
	 *
	 * @param array $data Associative array of column data to insert.
	 * @return array Array of success status and ID of inserted record.
	 */
	public static function insert( array $data ): array {
		global $wpdb;
		$table_name = self::get_table_name();
		$wpdb->insert( $table_name, $data );
		if ( empty( $wpdb->last_error ) ) {
			return array(
				'success' => true,
				'id'      => $wpdb->insert_id,
			);
		}
		return array(
			'success' => false,
			'message' => $wpdb->last_error,
			'query'   => $wpdb->last_query,
		);
	}

	/**
	 * Update records in the database.
	 *
	 * @param array $data Associative array of column data to update.
	 * @param array $where Associative array of column data for WHERE clause.
	 * @return array Array of success status and ID of inserted record.
	 */
	public static function update( array $data, array $where ): array {
		global $wpdb;
		$table_name = self::get_table_name();
		$wpdb->update( $table_name, $data, $where );
		if ( empty( $wpdb->last_error ) ) {
			return array(
				'success' => true,
				'id'      => $wpdb->insert_id,
			);
		}
		return array(
			'success' => false,
			'message' => $wpdb->last_error,
			'query'   => $wpdb->last_query,
		);
	}

	/**
	 * Delete records from the database.
	 *
	 * @param array $where Associative array of column data for WHERE clause.
	 * @return array Array of success status and ID of inserted record.
	 */
	public static function delete( array $where ): array {
		global $wpdb;
		$table_name = self::get_table_name();
		$wpdb->delete( $table_name, $where );
		if ( empty( $wpdb->last_error ) ) {
			return array(
				'success' => true,
				'id'      => $wpdb->insert_id,
			);
		}
		return array(
			'success' => false,
			'message' => $wpdb->last_error,
			'query'   => $wpdb->last_query,
		);
	}

	/**
	 * Select records from the database.
	 *
	 * @param array $where Associative array of column data for WHERE clause.
	 * @param array $columns Optional. Columns to select. Default is empty array.
	 * @return array Database query results or null if none.
	 */
	public static function select( $where = array(), $columns = array() ) {
		global $wpdb;
		$table_name = self::get_table_name();

		if ( empty( $columns ) ) {
			$columns = '*';
		} else {
			$columns = implode( ', ', $columns );
		}

		$sql = "SELECT $columns FROM $table_name WHERE 1=1";
		if ( empty( $where ) ) {
			$prepared_sql = $sql;
		} else {
			foreach ( $where as $key => $value ) {
				$sql .= " AND $key = %s";
			}
			$prepared_sql = $wpdb->prepare( $sql, array_values( $where ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}
		$wpdb->get_results( $prepared_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if ( empty( $wpdb->last_error ) ) {
			return array(
				'success' => true,
				'data'    => $wpdb->last_result,
			);
		}
		return array(
			'success' => false,
			'message' => $wpdb->last_error,
			'query'   => $wpdb->last_query,
		);
	}
}
