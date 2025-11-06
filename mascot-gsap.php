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

		// Elementor integration
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
		}
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
		require_once( 'shortcode-loader.php' );
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
		// GSAP Scroll Pin Widget Style
		wp_register_style(
			'mascot-gsap-scroll-pin',
			MASCOT_GSAP_ASSETS_URL . 'css/gsap-scroll-pin.css',
			array(),
			MASCOT_GSAP_VERSION
		);

		// GSAP Core
		wp_register_script(
			'gsap',
			MASCOT_GSAP_ASSETS_URL . 'js/gsap.min.js',
			array(),
			'3.12.5',
			true
		);

		// ScrollTrigger Plugin
		wp_register_script(
			'gsap-scrolltrigger',
			MASCOT_GSAP_ASSETS_URL . 'js/ScrollTrigger.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// ScrollToPlugin
		wp_register_script(
			'gsap-scrollto',
			MASCOT_GSAP_ASSETS_URL . 'js/ScrollToPlugin.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// Draggable Plugin
		wp_register_script(
			'gsap-draggable',
			MASCOT_GSAP_ASSETS_URL . 'js/Draggable.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// MotionPathPlugin
		wp_register_script(
			'gsap-motionpath',
			MASCOT_GSAP_ASSETS_URL . 'js/MotionPathPlugin.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// EasePack
		wp_register_script(
			'gsap-easepack',
			MASCOT_GSAP_ASSETS_URL . 'js/EasePack.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// TextPlugin
		wp_register_script(
			'gsap-text',
			MASCOT_GSAP_ASSETS_URL . 'js/TextPlugin.min.js',
			array( 'gsap' ),
			'3.12.5',
			true
		);

		// Custom animation helper
		wp_register_script(
			'mascot-gsap-helper',
			MASCOT_GSAP_ASSETS_URL . 'js/mascot-gsap-helper.js',
			array( 'jquery', 'gsap' ),
			MASCOT_GSAP_VERSION,
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

