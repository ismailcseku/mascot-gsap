<?php
/**
 * Plugin Name: Mascot GSAP Animation
 * Plugin URI: https://thememascot.com/
 * Description: GSAP (GreenSock Animation Platform) integration for WordPress. Provides GSAP library and animation utilities for themes and plugins.
 * Version: 1.0.0
 * Author: ThemeMascot
 * Author URI: https://thememascot.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mascot-gsap
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Define plugin constants
define( 'MASCOT_GSAP_VERSION', '1.0.0' );
define( 'MASCOT_GSAP_FILE', __FILE__ );
define( 'MASCOT_GSAP_PATH', plugin_dir_path( __FILE__ ) );
define( 'MASCOT_GSAP_URL', plugin_dir_url( __FILE__ ) );
define( 'MASCOT_GSAP_ASSETS_URL', MASCOT_GSAP_URL . 'assets/' );

/**
 * Main Mascot_GSAP Class
 */
final class Mascot_GSAP {

	/**
	 * Plugin instance
	 *
	 * @var Mascot_GSAP
	 */
	private static $_instance = null;

	/**
	 * Main Mascot_GSAP Instance
	 *
	 * Ensures only one instance of Mascot_GSAP is loaded or can be loaded.
	 *
	 * @return Mascot_GSAP - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init_hooks();
		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

		// Load admin settings (needed globally for settings checks)
		$this->load_admin();

		// Elementor integration
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
		}
	}

	/**
	 * Load admin functionality
	 */
	private function load_admin() {
		require_once MASCOT_GSAP_PATH . 'admin/settings.php';

		// Add settings link on plugins page (admin only)
		if ( is_admin() ) {
			add_filter( 'plugin_action_links_' . plugin_basename( MASCOT_GSAP_FILE ), array( $this, 'add_plugin_action_links' ) );
		}
	}

	/**
	 * Add settings link on plugins page
	 */
	public function add_plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=mascot-gsap-settings' ),
			esc_html__( 'Settings', 'mascot-gsap' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}
	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		require_once( 'widgets-loader.php' );
	}

	/**
	 * Load plugin textdomain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'mascot-gsap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register GSAP scripts and styles
	 */
	public function register_scripts() {

		// GSAP Core
		wp_register_script(
			'gsap',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/gsap.min.js',
			array(),
			'3.12.5',
			true
		);

		// ScrollTrigger Plugin
		wp_register_script(
			'gsap-scrolltrigger',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/ScrollTrigger.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// ScrollToPlugin
		wp_register_script(
			'gsap-scrollto',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/ScrollToPlugin.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// Draggable Plugin
		wp_register_script(
			'gsap-draggable',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/Draggable.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// MotionPathPlugin
		wp_register_script(
			'gsap-motionpath',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/MotionPathPlugin.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// EasePack
		wp_register_script(
			'gsap-easepack',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/EasePack.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// TextPlugin
		wp_register_script(
			'gsap-text',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/TextPlugin.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// Register alias for ScrollTrigger (for theme compatibility)
		wp_register_script(
			'tm-scroll-trigger',
			MASCOT_GSAP_ASSETS_URL . 'js/plugins/ScrollTrigger.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// Localize script with settings
		wp_localize_script( 'mascot-gsap-helper', 'mascotGSAP', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'mascot-gsap-nonce' ),
		) );
	}

	/**
	 * Get GSAP version
	 */
	public function get_gsap_version() {
		return '3.12.5';
	}
}

/**
 * Initialize the plugin
 */
function mascot_gsap() {
	return Mascot_GSAP::instance();
}

// Kick off the plugin
mascot_gsap();

