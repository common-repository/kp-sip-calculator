<?php
/**
 * Plugin Name: KP SIP Calculator
 * Plugin URI: https://wordpress.org/plugins/kp-sip-calculator
 * Description: A SIP Calculator with customizable default values for Monthly Investment, Expected Return Rate, and Time Period.
 * Version: 1.0
 * Author: Kalpesh Prajapati
 * Author URI: https://profiles.wordpress.org/kprajapati22/
 * License: GPLv2
 * Text Domain: kp-sip-calculator
 * Domain Path: /languages
 *
 * @package KP SIP Calculator
 * @author Kalpesh Prajapati
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Kp_Sip_Calculator' ) ) :

	/**
	 * Main Class.
	 *
	 * @since 1.0
	 */
	final class Kp_Sip_Calculator {

		/**
		 * The single instance of the class.
		 *
		 * @var Kp_Sip_Calculator Instance.
		 * @since 1.0
		 */
		protected static $_instance = null; // phpcs:ignore

		/**
		 * Public instance.
		 *
		 * @var Kp_Sip_Calculator_Public
		 */
		public $public = null;

		/**
		 * Admin instance.
		 *
		 * @var Kp_Sip_Calculator_Admin
		 */
		public $admin = null;

		/**
		 * Settings instance.
		 *
		 * @var Kp_Sip_Calculator_Settings
		 */
		public $settings = null;

		/**
		 * Main KP SIP Calculator Instance.
		 *
		 * Ensures only one instance of KP SIP Calculator is loaded or can be loaded.
		 * Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @see KP_Sip_Calculator()
		 * @return KP SIP Calculator - Main instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * KP SIP Calculator Constructor.
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
			$this->set_locale();
			$this->init_hooks();
		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'kp-sip-calculator' ), '1.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'kp-sip-calculator' ), '1.0' );
		}

		/**
		 * Define Constants.
		 */
		private function define_constants() {

			if ( ! defined( 'KP_SIP_CALCULATOR_VERSION' ) ) {
				define( 'KP_SIP_CALCULATOR_VERSION', '1.0' ); // Plugin version number.
			}
			if ( ! defined( 'KP_SIP_CALCULATOR_DIR' ) ) {
				define( 'KP_SIP_CALCULATOR_DIR', plugin_dir_path( __FILE__ ) ); // plugin dir.
			}
			if ( ! defined( 'KP_SIP_CALCULATOR_URL' ) ) {
				define( 'KP_SIP_CALCULATOR_URL', plugin_dir_url( __FILE__ ) ); // plugin url.
			}
			if ( ! defined( 'KP_SIP_CALCULATOR_BASENAME' ) ) {
				define( 'KP_SIP_CALCULATOR_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name.
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function includes() {

			require_once KP_SIP_CALCULATOR_DIR . '/includes/class-kp-sip-calculator-i18n.php';
			include_once KP_SIP_CALCULATOR_DIR . '/admin/class-kp-sip-calculator-settings.php';
			include_once KP_SIP_CALCULATOR_DIR . '/admin/class-kp-sip-calculator-admin.php';
			include_once KP_SIP_CALCULATOR_DIR . '/public/class-kp-sip-calculator-public.php';
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Kp_Sip_Calc_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Kp_Sip_Calculator_i18n();
			add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );
		}

		/**
		 * Hook into actions and filters.
		 */
		private function init_hooks() {

			// Add filter to add link to plugins.
			add_filter( 'plugin_action_links_' . KP_SIP_CALCULATOR_BASENAME, array( $this, 'add_plugin_links' ) );

			// Load class instances.
			$this->settings = new Kp_Sip_Calculator_Settings();
			$this->admin    = new Kp_Sip_Calculator_Admin();
			$this->public   = new KP_Sip_Calculator_Public();
		}

		/**
		 * Adds a Settings, Support and Docs link to the plugin list.
		 *
		 * @param array $links array values.
		 */
		public function add_plugin_links( $links ) {
			$plugin_links = array(
				'<a href="admin.php?page=kp-sip-calculator-settings">' . esc_html__( 'Settings', 'kp-sip-calculator' ) . '</a>',
				'<a href="https://wordpress.org/plugins/kp-sip-calculator/">' . esc_html__( 'Docs', 'kp-sip-calculator' ) . '</a>',
			);

			return array_merge( $plugin_links, $links );
		}
	}

endif;

/**
 * Main instance of KP SIP Calculator
 *
 * @since  1.0
 * @return KP_Sip_Calculator
 */
function kp_sip_calculator_init() {
	return Kp_Sip_Calculator::instance();
}

kp_sip_calculator_init();
