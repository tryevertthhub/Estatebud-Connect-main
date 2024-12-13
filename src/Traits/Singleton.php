<?php

namespace EstatebudConnect\Traits;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}

trait Singleton {
	/**
	 *  Singleton instance
	 *
	 * @var null|static
	 */
	private static $instance;


	/**
	 * Get the singleton instance of the class.
	 *
	 * @return static
	 */
	public static function instance() {
		$args = func_get_args();
		if ( null === static::$instance ) {
			static::$instance = new static( ...$args );
		}

		return static::$instance;
	}


	/**
	 * Prevent cloning of the singleton instance.
	 *
	 * @return void
	 */
	final public function __clone() {
	}


	/**
	 * Prevent serializing of the singleton instance.
	 *
	 * @return void
	 */
	final public function __wakeup() {
	}
}
