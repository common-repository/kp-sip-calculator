<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Settings Class.
 *
 * @package KP SIP Calculator
 * @since 1.0
 */
class Kp_Sip_Calculator_Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( 'Kp_Sip_Calculator_Settings', 'register_settings' ) );
		add_action( 'wp_ajax_kp_sip_calculator_save_settings', array( 'Kp_Sip_Calculator_Settings', 'save_settings' ) );
		add_action( 'wp_ajax_kp_sip_calculator_restore_defaults', array( 'Kp_Sip_Calculator_Settings', 'restore_defaults' ) );
		add_action( 'wp_ajax_kp_sip_calculator_export_settings', array( 'Kp_Sip_Calculator_Settings', 'export_settings' ) );
		add_action( 'wp_ajax_kp_sip_calculator_import_settings', array( 'Kp_Sip_Calculator_Settings', 'import_settings' ) );
	}

	/**
	 * Register Settings.
	 */
	public static function register_settings() {

		if ( get_option( 'kp_sip_calculator_options' ) == false ) {
			add_option( 'kp_sip_calculator_options', self::default_options() );
		}

		$kp_sip_calculator_options = get_option( 'kp_sip_calculator_options' );
		$kp_sip_calculator_tools   = get_option( 'kp_sip_calculator_tools' );

		/** Genral Settings */
		add_settings_section( 'general', __( 'General', 'kp-sip-calculator' ), '__return_false', 'kp_sip_calculator_options' );

		// sip monthly investment.
		add_settings_field(
			'sip_monthly_investment',
			self::title( esc_html__( 'Default Monthly Investment', 'kp-sip-calculator' ), 'sip_monthly_investment', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_options',
			'general',
			array(
				'id'          => 'sip_monthly_investment',
				'input'       => 'text',
				'validate'    => '^[0-9]',
				'placeholder' => 'Default Amount',
				'tooltip'     => esc_html__( 'Default monthly investment amount which will be used for initial calculation.', 'kp-sip-calculator' ),
			)
		);

		// sip expected return rate.
		add_settings_field(
			'sip_expected_return_rate',
			self::title( esc_html__( 'Default Expected Return Rate (%)', 'kp-sip-calculator' ), 'sip_expected_return_rate', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_options',
			'general',
			array(
				'id'          => 'sip_expected_return_rate',
				'input'       => 'text',
				'validate'    => '^[0-9]',
				'placeholder' => 'Default Return Rate',
				'tooltip'     => esc_html__( 'Default expected return rate without percentage which will be used for initial calculation.', 'kp-sip-calculator' ),
			)
		);

		// sip time period.
		add_settings_field(
			'sip_time_period',
			self::title( esc_html__( 'Default Time Period (Years)', 'kp-sip-calculator' ), 'sip_time_period', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_options',
			'general',
			array(
				'id'          => 'sip_time_period',
				'input'       => 'text',
				'validate'    => '^[0-9]',
				'placeholder' => 'Default Time Period',
				'tooltip'     => esc_html__( 'Default time period which will be used for initial calculation.', 'kp-sip-calculator' ),
			)
		);

		// minify css.
		add_settings_section( 'customize', esc_html__( 'Customize', 'kp-sip-calculator' ), '__return_false', 'kp_sip_calculator_options' );

		add_settings_field(
			'sip_currency',
			self::title( esc_html__( 'Currency', 'kp-sip-calculator' ), 'sip_currency', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_options',
			'customize',
			array(
				'id'      => 'sip_currency',
				'input'   => 'select',
				'options' => array(
					'INR' => __( '&#8377; Indian Rupee (INR)', 'kp-sip-calculator' ),
					'USD' => __( '&#36; US Dollar (USD)', 'kp-sip-calculator' ),
					'EUR' => __( '&euro; Euro (EUR)', 'kp-sip-calculator' ),
					'GBP' => __( '&pound; British Pound (GBP)', 'kp-sip-calculator' ),
					'JPY' => __( '&yen; Japanese Yen (JPY)', 'kp-sip-calculator' ),
					'CAD' => __( '&#36; Canadian Dollar (CAD)', 'kp-sip-calculator' ),
					'AUD' => __( '&#36; Australian Dollar (AUD)', 'kp-sip-calculator' ),
				),
				'tooltip' => esc_html__( 'Select Currency to show in frontend', 'kp-sip-calculator' ),
			)
		);

		register_setting( 'kp_sip_calculator_options', 'kp_sip_calculator_options' );

		// settings.
		add_settings_section( 'tools', esc_html__( 'Settings', 'kp-sip-calculator' ), '__return_false', 'kp_sip_calculator_tools' );

		// clean uninstall.
		add_settings_field(
			'clean_uninstall',
			self::title( esc_html__( 'Clean Uninstall', 'kp-sip-calculator' ), 'clean_uninstall', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_tools',
			'tools',
			array(
				'id'      => 'clean_uninstall',
				'option'  => 'kp_sip_calculator_tools',
				'tooltip' => esc_html__( 'When enabled, this will cause all settings data to be removed from your database when the plugin is uninstalled.', 'kp-sip-calculator' ),
			)
		);

		// restore defaults.
		add_settings_field(
			'restore_defaults',
			self::title( esc_html__( 'Restore Default Options', 'kp-sip-calculator' ), 'restore_defaults', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_tools',
			'tools',
			array(
				'id'           => 'restore_defaults',
				'input'        => 'button',
				'action'       => 'restore_defaults',
				'title'        => esc_html__( 'Restore Default Options', 'kp-sip-calculator' ),
				'confirmation' => esc_html__( 'Are you sure? This will remove all existing plugin options and restore them to their default states.', 'kp-sip-calculator' ),
				'option'       => 'kp_sip_calculator_tools',
				'tooltip'      => esc_html__( 'Restore all plugin options to their default settings.', 'kp-sip-calculator' ),
			)
		);

		// export settings.
		add_settings_field(
			'export_settings',
			self::title( esc_html__( 'Export Settings', 'kp-sip-calculator' ), 'export_settings', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_input' ),
			'kp_sip_calculator_tools',
			'tools',
			array(
				'id'      => 'export_settings',
				'input'   => 'button',
				'action'  => 'export_settings',
				'title'   => esc_html__( 'Export Plugin Settings', 'kp-sip-calculator' ),
				'option'  => 'kp_sip_calculator_tools',
				'tooltip' => esc_html__( 'Export plugin settings for this site as a .json file. This lets you easily import the configuration into another site.', 'kp-sip-calculator' ),
			)
		);

		// import settings.
		add_settings_field(
			'import_settings',
			self::title( esc_html__( 'Import Settings', 'kp-sip-calculator' ), 'import_settings', true ),
			array( 'Kp_Sip_Calculator_Settings', 'print_import_settings' ),
			'kp_sip_calculator_tools',
			'tools',
			array(
				'tooltip' => esc_html__( 'Import plugin settings from an exported .json file.', 'kp-sip-calculator' ),
			)
		);

		register_setting( 'kp_sip_calculator_tools', 'kp_sip_calculator_tools' );
	}

	/**
	 * Options default values.
	 */
	public static function default_options() {
		$defaults = array(
			'sip_monthly_investment'   => 25000,
			'sip_expected_return_rate' => 12,
			'sip_time_period'          => 10,
			'sip_currency'             => 'INR',
		);
		return $defaults;
	}

	/**
	 * Print settings header.
	 *
	 * @param string $text setting header text.
	 * @param string $dashicon icon class.
	 */
	public static function settings_header( $text, $dashicon = '' ) {
		echo '<h2 class="kp-sip-calculator-settings-header">' . ( esc_html( $dashicon ) ? '<span class="dashicons ' . esc_attr( $dashicon ) . '"></span>' : '' ) . esc_html( $text ) . '</h2>';
	}

	/**
	 * Print settings section.
	 *
	 * @param string $page page name.
	 * @param string $section section name.
	 * @param string $dashicon icon class name.
	 * @param string $class Class name.
	 */
	public static function settings_section( $page, $section, $dashicon = '', $class = '' ) {

		global $wp_settings_sections;

		if ( ! empty( $wp_settings_sections[ $page ][ $section ] ) ) {

			global $wp_settings_fields;

			echo '<div class="kp-sip-calculator-settings-section">';
			if ( ! empty( $wp_settings_sections[ $page ][ $section ]['title'] ) ) {
				echo '<h2>' . ( esc_html( $dashicon ) ? '<span class="dashicons ' . esc_attr( $dashicon ) . '"></span>' : '' ) . esc_html( $wp_settings_sections[ $page ][ $section ]['title'] ) . '</h2>';
			}
			if ( ! empty( $wp_settings_fields[ $page ][ $section ] ) ) {
				echo '<table class="form-table">';
					echo '<tbody>';
						do_settings_fields( $page, $section );
					echo '</tbody>';
				echo '</table>';
			}
			echo '</div>';
		}
	}

	/**
	 * Print form inputs.
	 *
	 * @param array $args argument to the function.
	 */
	public static function print_input( $args ) {

		$selection_id = $args['id'];

		if ( ! empty( $args['option'] ) ) {
			$option  = $args['option'];
			$options = get_option( $args['option'] );
		} else {
			$option  = 'kp_sip_calculator_options';
			$options = get_option( 'kp_sip_calculator_options' );
		}

		if ( ! empty( $args['option'] ) && 'kp_sip_calculator_tools' == $args['option'] ) {
			$tools = $options;
		} else {
			$tools = get_option( 'kp_sip_calculator_tools' );
		}

		// set section variables.
		if ( ! empty( $args['section'] ) ) {
			$selection_id = $args['section'] . '-' . $args['id'];
			$option       = $option . '[' . $args['section'] . ']';
			$options      = isset( $options[ $args['section'] ] ) ? $options[ $args['section'] ] : array();
		}

		// text.
		if ( ! empty( $args['input'] ) && ( $args['input'] == 'text' || $args['input'] == 'color' ) ) {
			echo "<input type='text' id='" . esc_attr( $selection_id ) . "' name='" . esc_attr( $option ) . '[' . esc_attr( $args['id'] ) . "]' value='" . ( ! empty( $options[ $args['id'] ] ) ? esc_attr( $options[ $args['id'] ] ) : '' ) . "' placeholder='" . ( ! empty( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : '' ) . "'" . ( ! empty( $args['validate'] ) ? " kp_sip_calculator_validate='" . esc_attr( $args['validate'] ) . "'" : '' ) . ' />';
		}

		// select.
		elseif ( ! empty( $args['input'] ) && $args['input'] == 'select' ) {
			echo "<select id='" . esc_attr( $selection_id ) . "' name='" . esc_attr( $option ) . '[' . esc_attr( $args['id'] ) . "]'>";
			foreach ( $args['options'] as $value => $title ) {
				echo "<option value='" . esc_html( $value ) . "' ";
				if ( ! empty( $options[ $args['id'] ] ) && $options[ $args['id'] ] == $value ) {
					echo 'selected';
				}
				echo '>' . esc_html( $title ) . '</option>';
			}
			echo '</select>';
		}

		// button.
		elseif ( ! empty( $args['input'] ) && $args['input'] == 'button' ) {
			self::action_button( $args['action'] ?? '', $args['title'], 'secondary', $args['confirmation'] ?? '' );
		}

		// text area.
		elseif ( ! empty( $args['input'] ) && $args['input'] == 'textarea' ) {
			echo "<textarea id='" . esc_attr( $selection_id ) . "' name='" . esc_attr( $option ) . '[' . esc_attr( $args['id'] ) . "]' placeholder='" . ( ! empty( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : '' ) . "'>";
			if ( ! empty( $options[ $args['id'] ] ) ) {
				if ( ! empty( $args['textareatype'] ) && $args['textareatype'] == 'oneperline' ) {
					foreach ( $options[ $args['id'] ] as $line ) {
						echo esc_html( $line ) . "\n";
					}
				} else {
					echo esc_html( $options[ $args['id'] ] );
				}
			}
			echo '</textarea>';
		}

		// checkbox + toggle.
		else {
			if ( empty( $args['input'] ) || $args['input'] != 'checkbox' ) {
				echo "<label for='" . esc_attr( $selection_id ) . "' class='kp-sip-calculator-switch'>";
			}
			echo "<input type='checkbox' id='" . esc_attr( $selection_id ) . "' name='" . esc_attr( $option ) . '[' . esc_attr( $args['id'] ) . "]' value='1' style='display: inline-block; margin: 0px;' ";
			if ( ! empty( $options[ $args['id'] ] ) ) {
				echo 'checked';
			}
			if ( ! empty( $args['confirmation'] ) ) {
				echo " onChange=\"this.checked=this.checked?confirm('" . esc_html( $args['confirmation'] ) . "'):false;\"";
			}
			echo '>';
			if ( empty( $args['input'] ) || $args['input'] != 'checkbox' ) {
				echo "<div class='kp-sip-calculator-slider'></div>";
				echo '</label>';
			}
		}

		// tooltip.
		if ( ! empty( $args['tooltip'] ) ) {
			self::tooltip( $args['tooltip'] );
		}
	}

	/**
	 * Print import settings.
	 *
	 * @param array $args argument to the function.
	 */
	public static function print_import_settings( $args ) {

		// input + button.
		echo "<input type='file' id='kp-sip-calculator-import-settings-file' name='kp_sip_calculator_import_settings_file' /><br />";
		self::action_button( 'import_settings', esc_html__( 'Import Plugin Settings', 'kp-sip-calculator' ), 'secondary' );

		// tooltip.
		if ( ! empty( $args['tooltip'] ) ) {
			self::tooltip( $args['tooltip'] );
		}
	}

	/**
	 * Print tooltip.
	 *
	 * @param string $tooltip tooltip text.
	 * @param bool   $tooltip_link tooltip to dispaly or not.
	 */
	public static function tooltip( $tooltip, $tooltip_link = false ) {
		if ( ! empty( $tooltip ) ) {
			echo "<span class='kp-sip-calculator-tooltip-text'>" . esc_html( $tooltip );
			if ( $tooltip_link ) {
				/* translators: %s tooltip icon */
				echo "<span class='kp-sip-calculator-tooltip-subtext'>" . sprintf( esc_html__( 'Click %s to view documentation.', 'kp-sip-calculator' ), "<span class='kp-sip-calculator-tooltip-icon'>?</span>" ) . '</span>';
			}
			echo '</span>';
		}
	}

	/**
	 * Print title.
	 *
	 * @param string $title title.
	 * @param string $id id.
	 * @param string $link anchor link.
	 */
	public static function title( $title, $id = false, $link = false ) {

		if ( ! empty( $title ) ) {

			$var = "<span class='kp-sip-calculator-title-wrapper'>";

				// label + title.
			if ( ! empty( $id ) ) {
				$var .= "<label for='" . esc_attr( $id ) . "'>" . esc_html( $title ) . '</label>';
			} else {
				$var .= $title;
			}

				// tooltip icon + link.
			if ( ! empty( $link ) ) {
				if ( $link === true ) {
					$var .= '<span' . ( ! empty( $link ) ? " href='" . esc_url( $link ) . "'" : '' ) . " class='kp-sip-calculator-tooltip' target='_blank'>?</span>";
				} else {
					$var .= '<a' . ( ! empty( $link ) ? " href='" . esc_url( $link ) . "'" : '' ) . " class='kp-sip-calculator-tooltip' target='_blank'>?</a>";
				}
			}

			$var .= '</span>';

			return $var;
		}
	}

	/**
	 * Action button.
	 *
	 * @param string $action action of button.
	 * @param string $label label of button.
	 * @param string $type type of button.
	 * @param string $confirmation confirmation to show on click of button.
	 */
	public static function action_button( $action, $label, $type = 'primary', $confirmation = '' ) {
		echo '<div class="kp-sip-calculator-button-container">';
			echo '<button name="submit" id="submit" class="button button-' . esc_attr( $type ) . '" data-kp-sip-calculator-action="' . esc_attr( $action ) . '"' . ( ! empty( $confirmation ) ? ' data-kp-sip-calculator-confirmation="' . esc_attr( $confirmation ) . '"' : '' ) . ' style="display: flex; align-items: center;">';
				echo '<span class="kp-sip-calculator-button-text">' . esc_html( $label ) . '</span>';
				echo '<svg class="kp-sip-calculator-button-spinner" viewBox="0 0 100 100" role="presentation" focusable="false" style="background: rgba(0,0,0,.1); border-radius: 100%; width: 16px; height: 28px; margin: 0px 2px; overflow: visible; opacity: 1; background-color: transparent; display: none;"><circle cx="50" cy="50" r="50" vector-effect="non-scaling-stroke" style="fill: transparent; stroke-width: 1.5px; stroke: #fff;"></circle><path d="m 50 0 a 50 50 0 0 1 50 50" vector-effect="non-scaling-stroke" style="fill: transparent; stroke-width: 1.5px; stroke: #4A89DD; stroke-linecap: round; transform-origin: 50% 50%; animation: 1.4s linear 0s infinite normal both running kp-sip-calculator-spinner;"></path></svg>';
			echo '</button>';
			echo '<div class="kp-sip-calculator-button-message" style="display: none; margin-left: 10px; "></div>';
		echo '</div>';
	}

	/**
	 * Save settings ajax action.
	 */
	public static function save_settings() {

		self::security_check();
        // phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( isset( $_POST['action'] ) && 'kp_sip_calculator_save_settings' === $_POST['action'] ) {
			$kp_sip_calculator_options = [];
			$kp_sip_calculator_options['sip_monthly_investment'] = isset( $_POST['kp_sip_calculator_options']['sip_monthly_investment'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_sip_calculator_options']['sip_monthly_investment'] ) ) : '';
			$kp_sip_calculator_options['sip_expected_return_rate'] = isset( $_POST['kp_sip_calculator_options']['sip_expected_return_rate'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_sip_calculator_options']['sip_expected_return_rate'] ) ) : '';
			$kp_sip_calculator_options['sip_time_period'] = isset( $_POST['kp_sip_calculator_options']['sip_time_period'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_sip_calculator_options']['sip_time_period'] ) ) : '';
			$kp_sip_calculator_options['sip_currency'] = isset( $_POST['kp_sip_calculator_options']['sip_currency'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_sip_calculator_options']['sip_currency'] ) ) : '';			

			$kp_sip_calculator_tools = [];
			$kp_sip_calculator_tools['clean_uninstall'] = isset( $_POST['kp_sip_calculator_tools']['clean_uninstall'] ) ? sanitize_text_field( wp_unslash( $_POST['kp_sip_calculator_tools']['clean_uninstall'] ) ) : '';
			// phpcs:enable

			if ( ! empty( $kp_sip_calculator_options ) ) {
				update_option( 'kp_sip_calculator_options', $kp_sip_calculator_options );
			}

			if ( ! empty( $kp_sip_calculator_tools ) ) {
				update_option( 'kp_sip_calculator_tools', $kp_sip_calculator_tools );
			}

			wp_send_json_success(
				array(
					'message' => esc_html__( 'Settings saved.', 'kp-sip-calculator' ),
				)
			);
		}
	}

	/**
	 * Restore defaults ajax action.
	 */
	public static function restore_defaults() {

		self::security_check();

		$defaults = self::default_options();

		if ( ! empty( $defaults ) ) {
			update_option( 'kp_sip_calculator_options', $defaults );
			update_option( 'kp_sip_calculator_tools', array() );
		}

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Successfully restored default options.', 'kp-sip-calculator' ),
				'reload'  => true,
			)
		);
	}

	/**
	 * Export settings ajax settings.
	 */
	public static function export_settings() {

		self::security_check();

		$settings = array();

		$settings['kp_sip_calculator_options'] = get_option( 'kp_sip_calculator_options' );
		$settings['kp_sip_calculator_tools']   = get_option( 'kp_sip_calculator_tools' );

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Settings exported.', 'kp-sip-calculator' ),
				'export'  => wp_json_encode( $settings ),
			)
		);
	}

	/**
	 * Import settings ajax action.
	 */
	public static function import_settings() {

		self::security_check();

        // phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_FILES ) && isset( $_FILES['kp_sip_calculator_import_settings_file']['tmp_name'] ) ) {
			$import_file = sanitize_text_field( wp_unslash( $_FILES['kp_sip_calculator_import_settings_file']['tmp_name'] ) );
		}
        // phpcs:enable

		// cancel if there's no file.
		if ( empty( $import_file ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No import file given.', 'kp-sip-calculator' ),
				)
			);
		}

        // phpcs:disable WordPress.Security.NonceVerification.Missing
		// check if uploaded file is valid.
		if ( isset( $_FILES['kp_sip_calculator_import_settings_file']['name'] ) ) {
			$file_parts = explode( '.', sanitize_text_field( wp_unslash( $_FILES['kp_sip_calculator_import_settings_file']['name'] ) ) );
            // phpcs:enable
			$extension = end( $file_parts );
			if ( $extension != 'json' ) {
				wp_send_json_error(
					array(
						'message' => esc_html__( 'Please upload a valid .json file.', 'kp-sip-calculator' ),
					)
				);
			}
		}

		// unpack settings from file.
		$settings = (array) json_decode( file_get_contents( $import_file ), true );

		if ( isset( $settings['kp_sip_calculator_options'] ) ) {
			update_option( 'kp_sip_calculator_options', $settings['kp_sip_calculator_options'] );
		}

		if ( isset( $settings['kp_sip_calculator_tools'] ) ) {
			update_option( 'kp_sip_calculator_tools', $settings['kp_sip_calculator_tools'] );
		}

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Successfully imported plugin settings.', 'kp-sip-calculator' ),
				'reload'  => true,
			)
		);
	}

	/**
	 * Ajax security check.
	 *
	 * @param string $nonce nonce to verify
	 */
	public static function security_check( $nonce = 'kp-sip-calculator-nonce' ) {

		if ( ! current_user_can( 'manage_options' ) ) {

			wp_send_json_error(
				array(
					'message' => esc_html__( 'Permission denied.', 'kp-sip-calculator' ),
				)
			);
		}

		if ( ! check_ajax_referer( $nonce, 'nonce', false ) ) {

			wp_send_json_error(
				array(
					'message' => esc_html__( 'Nonce is invalid.', 'kp-sip-calculator' ),
				)
			);
		}
	}
}
