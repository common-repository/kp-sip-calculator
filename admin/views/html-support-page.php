<?php
if ( ! defined( 'ABSPATH' ) ) exit;
Kp_Sip_Calculator_Settings::settings_header( esc_html__( 'Support', 'kp-sip-calculator' ), 'dashicons-editor-help' );
echo '<div class="kp-sip-calculator-settings-section">';

	// documentation.
	echo '<h2>' . esc_html__( 'Documentation', 'kp-sip-calculator' ) . '</h2>';
	echo '<div class="form-table">';
		echo '<div style="margin: 1em auto;">' . esc_html__( 'Need help? Check out our in-depth documentation. Every feature has a step-by-step walkthrough.', 'kp-sip-calculator' ) . '</div>';
		echo '<a class="button-secondary" href="https://wordpress.org/plugins/kp-sip-calculator" target="_blank">' . esc_html__( 'Documentation', 'kp-sip-calculator' ) . '</a>';
	echo '</div>';
echo '</div>';

// contact us.
echo '<div class="kp-sip-calculator-settings-section">';
	echo '<h2>' . esc_html__( 'Contact Us', 'kp-sip-calculator' ) . '</h2>';
	echo '<div class="form-table">';
		echo '<div style="margin: 1em auto;">' . esc_html__( 'If you have questions or problems, please send us a message. We’ll get back to you as soon as possible.', 'kp-sip-calculator' ) . '</div>';
		echo '<a class="button-secondary" href="https://wordpress.org/support/plugin/kp-sip-calculator" target="_blank">' . esc_html__( 'Contact Us', 'kp-sip-calculator' ) . '</a>';
	echo '</div>';
echo '</div>';
