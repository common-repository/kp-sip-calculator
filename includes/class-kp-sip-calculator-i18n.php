<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */
class Kp_Sip_Calculator_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'kp-sip-calculator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
