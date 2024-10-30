<?php
/**
 * Handle frontend functionality.
 *
 * @package Kp SIP Calculator
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Public Class.
 */
class Kp_Sip_Calculator_Public {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Enqueue the CSS styles for the SIP calculator.
		add_action( 'wp_enqueue_scripts', array( $this, 'kp_sip_calculator_enqueue_script' ) );

		// Add the SIP calculator shortcode.
		add_shortcode( 'kp_sip_calculator', array( $this, 'kp_sip_calculator_shortcode' ) );
	}

	/**
	 * Enqueue Script.
	 */
	public function kp_sip_calculator_enqueue_script() {
		wp_register_script( 'kp_chartjs-script', KP_SIP_CALCULATOR_URL . '/public/js/chart.js', array(), KP_SIP_CALCULATOR_VERSION, true );

		wp_register_style( 'kp-sip-calculator-style', KP_SIP_CALCULATOR_URL . '/public/css/kp-sip-calculator-public.css', array(), KP_SIP_CALCULATOR_VERSION );
		wp_register_script( 'kp-sip-calculator-public-script', KP_SIP_CALCULATOR_URL . '/public/js/kp-sip-calculator-public.js', array( 'jquery' ), KP_SIP_CALCULATOR_VERSION, true );

		$kp_sip_calculator_options = get_option( 'kp_sip_calculator_options' );
		$currency                  = kp_sip_calculator_init()->admin->kp_sip_calculator_get_currency_htmlcode( $kp_sip_calculator_options['sip_currency'] );
		wp_localize_script(
			'kp-sip-calculator-public-script',
			'KPSIPCALCULATOR',
			array(
				'currency' => $currency,
			)
		);
	}

	/**
	 * Add Shortcode.
	 */
	public function kp_sip_calculator_shortcode() {

		$kp_sip_calculator_options = get_option( 'kp_sip_calculator_options' );
		$currency                  = kp_sip_calculator_init()->admin->kp_sip_calculator_get_currency_htmlcode( $kp_sip_calculator_options['sip_currency'] );

		// Enqueue necessary scripts.
		wp_enqueue_script( 'kp_chartjs-script' );
		wp_enqueue_style( 'kp-sip-calculator-style' );
		wp_enqueue_script( 'kp-sip-calculator-public-script' );

		ob_start();
		?>
		<div class="kp-sip-calculator">
			<div class="kp-sip-calculator-contain">
				<div>
					<label for="sip-monthly-investment"><?php echo esc_html__( 'Monthly Investment', 'kp-sip-calculator' ) . ' (' . esc_html( $currency ) . ')'; ?></label>
					<input type="number" id="sip-monthly-investment" value="<?php echo esc_html( $kp_sip_calculator_options['sip_monthly_investment'] ); ?>" min="1000" max="100000" step="1000">
					<input type="range" class="kp-sip-calculator-slider" id="sip-monthly-range" value="<?php echo esc_html( $kp_sip_calculator_options['sip_monthly_investment'] ); ?>" min="1000" max="100000" step="1000">
				</div>
				<div>
					<label for="sip-return-rate"><?php esc_html_e( 'Expected Return Rate (p.a.) (%)', 'kp-sip-calculator' ); ?></label>
					<input type="number" id="sip-return-rate" value="<?php echo esc_html( $kp_sip_calculator_options['sip_expected_return_rate'] ); ?>" min="1" max="30" step="0.1">
					<input type="range" class="kp-sip-calculator-slider" id="sip-return-range" value="<?php echo esc_html( $kp_sip_calculator_options['sip_expected_return_rate'] ); ?>" min="1" max="30" step="0.1">
				</div>
				<div>
					<label for="sip-time-period"><?php esc_html_e( 'Time Period (years)', 'kp-sip-calculator' ); ?></label>
					<input type="number" id="sip-time-period" value="<?php echo esc_html( $kp_sip_calculator_options['sip_time_period'] ); ?>" min="1" max="40" step="1">
					<input type="range" class="kp-sip-calculator-slider" id="sip-time-range" value="<?php echo esc_html( $kp_sip_calculator_options['sip_time_period'] ); ?>" min="1" max="40" step="1">
				</div>
				<div class="kp-sip-calculator-result">
					<div><?php esc_html_e( 'Invested Amount', 'kp-sip-calculator' ); ?><span id="sip-invested-amount"></span></div>
					<div><?php esc_html_e( 'Estimated Returns', 'kp-sip-calculator' ); ?><span id="sip-estimated-returns"></span></div>
					<div><?php esc_html_e( 'Total Value', 'kp-sip-calculator' ); ?><span id="sip-total-value"></span></div>
				</div>
			</div>
			<div class="kp-sip-calculator-chart">
				<canvas id="kp-sip-calculator-pie-chart"></canvas>
			</div>
		</div>
		<?php
		return ob_get_clean(); // Return the buffered output.
	}
}
