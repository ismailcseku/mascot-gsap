<?php
namespace MascotGSAP;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Shortcode_Loader {

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

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		if( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_script( 'swiper' );
			wp_enqueue_style( 'swiper' );
		}
		wp_register_script( 'mascot-core-hellojs', plugins_url( '/assets/js/elementor-mascot.js', __FILE__ ), [ 'jquery' ], false, true );
	}

	public function widget_styles() {
		wp_enqueue_style( 'mascot-core-elementor-css', plugins_url( '/assets/css/elementor-mascot.css', __FILE__ ) );
	}

	public function widget_styles_frontend() {
		$direction_suffix = is_rtl() ? '.rtl' : '';
		wp_enqueue_style( 'tm-header-search', MASCOT_CORE_HOTELIN_ASSETS_URI . '/css/shortcodes/header-search' . $direction_suffix . '.css' );
		wp_enqueue_style( 'mascot-core-widgets-style', MASCOT_CORE_HOTELIN_ASSETS_URI . '/css/widgets-core/mascot-core-widgets-style' . $direction_suffix . '.css' );
	}

	public function widgets_list() {
		$this->widgets = array(
			'gsap-scroll-pin',
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
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\GSAP_Scroll_Pin_Widget() );
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

		// Register widget scripts
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'widget_scripts' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'widget_styles' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'widget_styles_frontend' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'widget_styles_frontend' ] );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}
}

// Instantiate Shortcode_Loader Class
Shortcode_Loader::instance();

//elementor elements
//require_once( __DIR__ . '/elementor-elements/loader.php' );
