<?php
/**
 * For Admin functionality.
 *
 * @package KP SIP Calculator
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class.
 */
class Kp_Sip_Calculator_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'kp_sip_calculator_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'kp_sip_calculator_enqueue_script' ) );
	}

	/**
	 * Eneueue script.
	 *
	 * @param string $hooks page hooks.
	 */
	public function kp_sip_calculator_enqueue_script( $hooks ) {
		if ( 'toplevel_page_kp-sip-calculator-settings' === $hooks ) {
			wp_enqueue_style( 'kp-sip-calculator-settings-style', KP_SIP_CALCULATOR_URL . '/admin/css/kp-sip-calculator-settings.css', array(), KP_SIP_CALCULATOR_VERSION );
			wp_enqueue_script( 'kp-sip-calculator-settings-script', KP_SIP_CALCULATOR_URL . '/admin/js/kp-sip-calculator-settings.js', array(), KP_SIP_CALCULATOR_VERSION, true );
			wp_localize_script(
				'kp-sip-calculator-settings-script',
				'KPSIPCALCULATORSETTINGS',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'kp-sip-calculator-nonce' ),
					'strings' => array(
						'failed' => esc_html__( 'Action failed.', 'kp-sip-calculator' ),
					),
				)
			);
		}
	}

	/**
	 * Admin menu.
	 */
	public function kp_sip_calculator_menu() {
		add_menu_page(
			'SIP Calculator Settings',
			'SIP Calculator',
			'manage_options',
			'kp-sip-calculator-settings',
			array( $this, 'kp_sip_calculator_settings_page' ),
			'dashicons-calculator'
		);
	}

	/**
	 * Admin menu page.
	 */
	public function kp_sip_calculator_settings_page() {
		include_once KP_SIP_CALCULATOR_DIR . '/admin/views/html-settings-page.php';
	}

	/**
	 * Convert symbol to htmlcode.
	 *
	 * @param string $symbol currency symbol.
	 */
	public function kp_sip_calculator_get_currency_htmlcode( $symbol ) {

		// Define a mapping of symbols to their corresponding HTML entity codes.
		$currency_entities = array(
			'INR'  => '&#8377;',
			'USD'  => '&#36;',
			'CAD'  => '&#36;',
			'AUD'  => '&#36;',
			'EURO' => '&euro;',
			'GBP'  => '&pound;',
			'JPY'  => '&yen;',
		);

		// Check if the symbol exists in the array, return the corresponding HTML entity, or the original symbol.
		return isset( $currency_entities[ $symbol ] ) ? $currency_entities[ $symbol ] : htmlspecialchars( $symbol );
	}
}
