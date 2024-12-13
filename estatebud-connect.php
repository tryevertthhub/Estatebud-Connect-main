<?php
/**
 * Estatebud connect
 *
 * @package           EstatebudConnect
 * @author            Estatebud
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Estatebud connect
 * Plugin URI:        https://estatebud.com
 * Description:       Estatebud connect.
 * Version:           1.0.0
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://estatebud.com
 * Text Domain:       estatebud-connect
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://example.com/estatebud-connect/
 */

/*
|--------------------------------------------------------------------------
| If this file is called directly, abort.
|--------------------------------------------------------------------------
*/
if ( ! defined( 'WPINC' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Load class autoloader
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Define default constants
|--------------------------------------------------------------------------
*/
define( 'EstatebudConnect\SLUG', 'estatebud-connect' );
define( 'EstatebudConnect\VERSION', '1.0.0' );
define( 'EstatebudConnect\FILE', __FILE__ );


/*
|--------------------------------------------------------------------------
| Activation, deactivation and uninstall event.
|--------------------------------------------------------------------------
*/
register_activation_hook( __FILE__, array( \EstatebudConnect\Plugin::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \EstatebudConnect\Plugin::class, 'deactivate' ) );
register_uninstall_hook( __FILE__, array( \EstatebudConnect\Plugin::class, 'uninstall' ) );

/*
|--------------------------------------------------------------------------
| Start the plugin
|--------------------------------------------------------------------------
*/
try {
	\EstatebudConnect\Plugin::init();
} catch ( Exception $e ) {
	throw $e;
}
