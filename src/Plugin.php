<?php

namespace EstatebudConnect;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use EstatebudConnect\Schema;
use EstatebudConnect\Admin;
use EstatebudConnect\License;

class Plugin {

	/**
	 * Handle the plugin activation event.
	 *
	 * In this method you can add any code that needs to be executed when the plugin is activated. For example,
	 * creating tables, options, etc.
	 *
	 * @return void
	 */
	public static function activate() {
		License::instance( 'activate' );
		Schema::instance()->create();
	}

	/**
	 * Handle the plugin deactivation event.
	 *
	 * In this method you can add any code that needs to be executed when the plugin is deactivated. For example,
	 * deleting tables, options, etc.
	 *
	 * @return void
	 */
	public static function deactivate() {
		License::instance( 'deactivate' );
		Schema::instance()->truncate();
	}

	/**
	 * Handle the plugin uninstall event.
	 *
	 * In this method you can add any code that needs to be executed when the plugin is uninstalled. For example,
	 * deleting tables, options, etc.
	 *
	 * @return void
	 */
	public static function uninstall() {
		License::instance( 'uninstall' );
		Schema::instance()->delete();
	}

	/**
	 * Initialize the plugin by setting up necessary components.
	 * This method is called automatically when the plugin is activated.
	 *
	 * @return void
	 */
	public static function init() {
		if ( is_admin() ) {
			Admin::instance();
		}
	}
}
