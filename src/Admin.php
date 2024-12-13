<?php
namespace EstatebudConnect;

// If this file is called directly, abort.
if ( ! defined( 'EstatebudConnect\SLUG' ) ) {
	exit;
}
use const EstatebudConnect\SLUG;
use const EstatebudConnect\FILE;
use const EstatebudConnect\VERSION;
use EstatebudConnect\Traits\Singleton;
use EstatebudConnect\Modules\ModuleX\Init as ModuleX;
final class Admin {
	use Singleton;

	/**
	 * AdminController constructor.
	 *
	 * Adds an admin menu and enqueues required admin scripts and styles.
	 *
	 * @return void
	 */
	private function __construct() {
		add_action(
			'admin_menu',
			function () {
				add_menu_page(
					__( 'Estatebud connect', 'estatebud-connect' ),
					__( 'Estatebud connect', 'estatebud-connect' ),
					'manage_options',
					'estatebud-connect',
					function () {
						// Enqueue admin scripts and styles.
						wp_enqueue_style( SLUG );
						wp_enqueue_script( SLUG );
						add_filter(
							'script_loader_tag',
							function ( string $tag, string $id ): string {
								if ( SLUG === $id ) {
									$tag = str_replace( '<script ', '<script type="module" ', $tag );
								}

								return $tag;
							},
							10,
							3
						);
						add_filter( 'admin_footer_text', '__return_empty_string', 11 );
						add_filter( 'update_footer', '__return_empty_string', 11 );
						echo wp_kses_post( '<h1 id="' . SLUG . '">Hello Dev!</h1>' );
					},
				);
			}
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		// Fire modules.
		$this->modules();
	}

	/**
	 * It initializes all the modules that are needed to run the plugin.
	 *
	 * @return void
	 */
	public function modules() {
		// TODO: Add new modules here.
		ModuleX::init();
	}
	/**
	 * It loads scripts based on plugin's mode, dev or prod.
	 *
	 * @return void
	 */
	public function scripts(): void {
		wp_register_style( SLUG, plugins_url( 'assets/index.css', FILE ), array(), VERSION );
		wp_register_script( SLUG, plugins_url( 'assets/index.js', FILE ), array(), VERSION, true );
		wp_localize_script(
			SLUG,
			str_replace( '-', '_', SLUG ),
			array(
				'security_token' => wp_create_nonce( 'wp_rest' ),
				'strings'        => $this->strings(),
			),
		);
	}

	/**
	 * It returns the sanitized i18n strings array for js templates.
	 *
	 * @return array The sanitized strings array.
	 */
	private function strings() {
		$string = include plugin_dir_path( FILE ) . 'strings.php';
		return $this->sanitize_array( $string );
	}

	/**
	 * Sanitizes all values in a multidimensional array recursively.
	 *
	 * @param array $data The array to sanitize.
	 * @return array The sanitized array.
	 */
	private function sanitize_array( array &$data ): array {
		foreach ( $data as &$value ) {
			if ( ! is_array( $value ) ) {
				$value = esc_attr( sanitize_text_field( $value ) );
			} else {
				$value = $this->sanitize_array( $value );
			}
		}
		unset( $value ); // Unset the reference to avoid potential bugs.
		return $data;
	}
}
