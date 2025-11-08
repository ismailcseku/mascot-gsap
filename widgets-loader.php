<?php
namespace MascotGSAP;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Widgets_Loader {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;
	public $widgets_gsap = array();

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function widgets_list() {
		$this->widgets = array(
			'gsap-scroll-pin',
			'gsap-image-gallery',
		);


		return $this->widgets;
	}


	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		foreach( $this->widgets_list() as $widget ) {
			// Check if this specific widget is enabled before including its file
			// Convert widget slug to settings key (e.g., 'gsap-scroll-pin' to 'gsap_scroll_pin')
			$widget_key = str_replace( '-', '_', $widget );

			if ( ! \Mascot_GSAP_Admin_Settings::is_widget_enabled( $widget_key ) ) {
				continue;
			}

			require_once( __DIR__ . '/widgets/'. $widget .'/widget.php' );

			foreach( glob( __DIR__ . '/widgets/'. $widget .'/skins/*.php') as $filepath ) {
				include $filepath;
			}
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Check if Elementor widgets are enabled
		if ( ! \Mascot_GSAP_Admin_Settings::are_elementor_widgets_enabled() ) {
			return;
		}

		// Its is now safe to include Widgets files
		$this->include_widgets_files();
		// Register widgets based on enabled settings
		foreach( $this->widgets_list() as $widget ) {
			// Convert widget slug to settings key (e.g., 'gsap-scroll-pin' to 'gsap_scroll_pin')
			$widget_key = str_replace( '-', '_', $widget );

			if ( \Mascot_GSAP_Admin_Settings::is_widget_enabled( $widget_key ) ) {
				$class_name = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $widget ) ) ) . '_Widget';
				$full_class = 'MascotGSAP\\Widgets\\' . $class_name;

				if ( class_exists( $full_class ) ) {
					\Elementor\Plugin::instance()->widgets_manager->register( new $full_class() );
				}
			}
		}
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {
		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}
}

// Instantiate Widgets_Loader Class
Widgets_Loader::instance();

