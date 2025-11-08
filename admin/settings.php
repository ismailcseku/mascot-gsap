<?php
/**
 * Admin Settings Page
 *
 * @package Mascot_GSAP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Mascot GSAP Admin Settings Class
 */
class Mascot_GSAP_Admin_Settings {

	/**
	 * Settings page slug
	 */
	const PAGE_SLUG = 'mascot-gsap-settings';

	/**
	 * Option name
	 */
	const OPTION_NAME = 'mascot_gsap_settings';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
	}

	/**
	 * Add settings page to admin menu
	 */
	public function add_settings_page() {
		add_options_page(
			esc_html__( 'Mascot GSAP Settings', 'mascot-gsap' ),
			esc_html__( 'Mascot GSAP', 'mascot-gsap' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		register_setting(
			self::OPTION_NAME,
			self::OPTION_NAME,
			array( $this, 'sanitize_settings' )
		);

		// General Settings Section
		add_settings_section(
			'mascot_gsap_general_section',
			esc_html__( 'General Settings', 'mascot-gsap' ),
			array( $this, 'render_general_section' ),
			self::PAGE_SLUG
		);

		// Elementor Widgets Section
		add_settings_section(
			'mascot_gsap_elementor_section',
			esc_html__( 'Elementor Widgets', 'mascot-gsap' ),
			array( $this, 'render_elementor_section' ),
			self::PAGE_SLUG
		);

		// Enable Elementor Widgets
		add_settings_field(
			'enable_elementor_widgets',
			esc_html__( 'Enable Elementor Widgets', 'mascot-gsap' ),
			array( $this, 'render_enable_elementor_widgets_field' ),
			self::PAGE_SLUG,
			'mascot_gsap_elementor_section'
		);

		// GSAP Scroll Pin Widget
		add_settings_field(
			'enable_gsap_scroll_pin_widget',
			esc_html__( 'GSAP Scroll Pin Widget', 'mascot-gsap' ),
			array( $this, 'render_enable_gsap_scroll_pin_widget_field' ),
			self::PAGE_SLUG,
			'mascot_gsap_elementor_section'
		);

		// GSAP Image Gallery Widget
		add_settings_field(
			'enable_gsap_image_gallery_widget',
			esc_html__( 'GSAP Image Gallery Widget', 'mascot-gsap' ),
			array( $this, 'render_enable_gsap_image_gallery_widget_field' ),
			self::PAGE_SLUG,
			'mascot_gsap_elementor_section'
		);
	}

	/**
	 * Render general section description
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure general GSAP plugin settings.', 'mascot-gsap' ) . '</p>';
	}

	/**
	 * Render Elementor section description
	 */
	public function render_elementor_section() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			echo '<div class="notice notice-warning inline"><p>';
			echo '<strong>' . esc_html__( 'Elementor is not installed or activated.', 'mascot-gsap' ) . '</strong> ';
			echo esc_html__( 'These settings will only take effect when Elementor is active.', 'mascot-gsap' );
			echo '</p></div>';
		}
		echo '<p>' . esc_html__( 'Enable or disable Elementor widgets provided by Mascot GSAP plugin.', 'mascot-gsap' ) . '</p>';
	}

	/**
	 * Render enable Elementor widgets field
	 */
	public function render_enable_elementor_widgets_field() {
		$settings = get_option( self::OPTION_NAME, $this->get_default_settings() );
		$enabled = isset( $settings['enable_elementor_widgets'] ) ? $settings['enable_elementor_widgets'] : true;
		?>
		<label>
			<input type="checkbox"
				   name="<?php echo esc_attr( self::OPTION_NAME ); ?>[enable_elementor_widgets]"
				   value="1"
				   <?php checked( $enabled, true ); ?>>
			<?php esc_html_e( 'Enable all Elementor widgets', 'mascot-gsap' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'Master switch to enable or disable all Elementor widgets. Uncheck to disable all widgets.', 'mascot-gsap' ); ?>
		</p>
		<?php
	}

	/**
	 * Render enable GSAP scroll pin widget field
	 */
	public function render_enable_gsap_scroll_pin_widget_field() {
		$settings = get_option( self::OPTION_NAME, $this->get_default_settings() );
		$enabled = isset( $settings['enable_gsap_scroll_pin_widget'] ) ? $settings['enable_gsap_scroll_pin_widget'] : true;
		$master_enabled = isset( $settings['enable_elementor_widgets'] ) ? $settings['enable_elementor_widgets'] : true;
		?>
		<label>
			<input type="checkbox"
				   name="<?php echo esc_attr( self::OPTION_NAME ); ?>[enable_gsap_scroll_pin_widget]"
				   value="1"
				   <?php checked( $enabled, true ); ?>
				   <?php disabled( ! $master_enabled ); ?>>
			<?php esc_html_e( 'Enable GSAP Scroll Pin Widget', 'mascot-gsap' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'Scroll-triggered pinned title animation widget with customizable scale effects.', 'mascot-gsap' ); ?>
		</p>
		<?php
	}

	/**
	 * Render enable GSAP image gallery widget field
	 */
	public function render_enable_gsap_image_gallery_widget_field() {
		$settings = get_option( self::OPTION_NAME, $this->get_default_settings() );
		$enabled = isset( $settings['enable_gsap_image_gallery_widget'] ) ? $settings['enable_gsap_image_gallery_widget'] : true;
		$master_enabled = isset( $settings['enable_elementor_widgets'] ) ? $settings['enable_elementor_widgets'] : true;
		?>
		<label>
			<input type="checkbox"
				   name="<?php echo esc_attr( self::OPTION_NAME ); ?>[enable_gsap_image_gallery_widget]"
				   value="1"
				   <?php checked( $enabled, true ); ?>
				   <?php disabled( ! $master_enabled ); ?>>
			<?php esc_html_e( 'Enable GSAP Image Gallery Widget', 'mascot-gsap' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'Animated gallery layout where the main image scales on scroll and surrounding thumbs frame it.', 'mascot-gsap' ); ?>
		</p>
		<?php
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Show success message
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'mascot_gsap_messages',
				'mascot_gsap_message',
				esc_html__( 'Settings saved successfully.', 'mascot-gsap' ),
				'success'
			);
		}

		settings_errors( 'mascot_gsap_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="mascot-gsap-settings-header">
				<h2><?php esc_html_e( 'GSAP Animation Settings', 'mascot-gsap' ); ?></h2>
				<p class="description">
					<?php esc_html_e( 'Configure GSAP plugin settings and manage Elementor widgets.', 'mascot-gsap' ); ?>
				</p>
			</div>

			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_NAME );
				do_settings_sections( self::PAGE_SLUG );
				submit_button( esc_html__( 'Save Settings', 'mascot-gsap' ) );
				?>
			</form>

			<div class="mascot-gsap-info-box">
				<h3><?php esc_html_e( 'Plugin Information', 'mascot-gsap' ); ?></h3>
				<table class="form-table">
					<tr>
						<th><?php esc_html_e( 'Plugin Version:', 'mascot-gsap' ); ?></th>
						<td><?php echo esc_html( MASCOT_GSAP_VERSION ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'GSAP Version:', 'mascot-gsap' ); ?></th>
						<td><?php echo esc_html( mascot_gsap()->get_gsap_version() ); ?></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Elementor Status:', 'mascot-gsap' ); ?></th>
						<td>
							<?php
							if ( did_action( 'elementor/loaded' ) ) {
								echo '<span style="color: #46b450;">● ' . esc_html__( 'Active', 'mascot-gsap' ) . '</span>';
							} else {
								echo '<span style="color: #dc3232;">● ' . esc_html__( 'Not Active', 'mascot-gsap' ) . '</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Available Widgets:', 'mascot-gsap' ); ?></th>
						<td><?php echo esc_html( $this->count_available_widgets() ); ?></td>
					</tr>
				</table>
			</div>

			<div class="mascot-gsap-info-box">
				<h3><?php esc_html_e( 'Documentation & Support', 'mascot-gsap' ); ?></h3>
				<ul>
					<li>
						<a href="https://greensock.com/docs/" target="_blank">
							<?php esc_html_e( 'GSAP Documentation', 'mascot-gsap' ); ?>
						</a>
					</li>
					<li>
						<a href="https://greensock.com/docs/v3/Plugins/ScrollTrigger" target="_blank">
							<?php esc_html_e( 'ScrollTrigger Documentation', 'mascot-gsap' ); ?>
						</a>
					</li>
					<li>
						<a href="https://thememascot.com/" target="_blank">
							<?php esc_html_e( 'ThemeMascot Support', 'mascot-gsap' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Sanitize settings
	 */
	public function sanitize_settings( $settings ) {
		$sanitized = array();

		// Sanitize checkbox fields
		$sanitized['enable_elementor_widgets'] = ! empty( $settings['enable_elementor_widgets'] ) ? true : false;
		$sanitized['enable_gsap_scroll_pin_widget'] = ! empty( $settings['enable_gsap_scroll_pin_widget'] ) ? true : false;
		$sanitized['enable_gsap_image_gallery_widget'] = ! empty( $settings['enable_gsap_image_gallery_widget'] ) ? true : false;

		return $sanitized;
	}

	/**
	 * Get default settings
	 */
	public function get_default_settings() {
		return array(
			'enable_elementor_widgets' => true,
			'enable_gsap_scroll_pin_widget' => true,
			'enable_gsap_image_gallery_widget' => true,
		);
	}

	/**
	 * Count available widgets
	 */
	private function count_available_widgets() {
		$count = 0;
		$settings = get_option( self::OPTION_NAME, $this->get_default_settings() );

		if ( ! empty( $settings['enable_elementor_widgets'] ) ) {
			if ( ! empty( $settings['enable_gsap_scroll_pin_widget'] ) ) {
				$count++;
			}
			if ( ! empty( $settings['enable_gsap_image_gallery_widget'] ) ) {
				$count++;
			}
			// Add more widgets count here as they are added
		}

		return $count;
	}

	/**
	 * Enqueue admin styles
	 */
	public function enqueue_admin_styles( $hook ) {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		// Inline CSS for settings page
		$custom_css = "
			.mascot-gsap-settings-header {
				background: #fff;
				border-left: 4px solid #2271b1;
				padding: 15px 20px;
				margin: 20px 0;
				box-shadow: 0 1px 1px rgba(0,0,0,.04);
			}
			.mascot-gsap-settings-header h2 {
				margin: 0 0 10px 0;
				font-size: 18px;
			}
			.mascot-gsap-info-box {
				background: #fff;
				border: 1px solid #c3c4c7;
				padding: 15px 20px;
				margin: 20px 0;
				box-shadow: 0 1px 1px rgba(0,0,0,.04);
			}
			.mascot-gsap-info-box h3 {
				margin-top: 0;
				font-size: 16px;
				border-bottom: 1px solid #dcdcde;
				padding-bottom: 10px;
			}
			.mascot-gsap-info-box .form-table th {
				padding-left: 0;
				font-weight: 600;
			}
			.mascot-gsap-info-box ul {
				list-style: disc;
				padding-left: 20px;
			}
			.mascot-gsap-info-box ul li {
				margin: 8px 0;
			}
		";
		wp_add_inline_style( 'wp-admin', $custom_css );
	}

	/**
	 * Check if Elementor widgets are enabled
	 */
	public static function are_elementor_widgets_enabled() {
		$settings = get_option( self::OPTION_NAME );

		if ( false === $settings ) {
			// No settings saved yet, return default (true)
			return true;
		}

		return ! empty( $settings['enable_elementor_widgets'] );
	}

	/**
	 * Check if specific widget is enabled
	 */
	public static function is_widget_enabled( $widget_name ) {
		$settings = get_option( self::OPTION_NAME );

		if ( false === $settings ) {
			// No settings saved yet, return default (true)
			return true;
		}

		// Check master switch first
		if ( empty( $settings['enable_elementor_widgets'] ) ) {
			return false;
		}

		// Check specific widget setting
		$widget_key = 'enable_' . $widget_name . '_widget';

		return ! empty( $settings[ $widget_key ] );
	}
}

// Initialize admin settings
new Mascot_GSAP_Admin_Settings();

