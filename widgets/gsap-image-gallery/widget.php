<?php
namespace MascotGSAP\Widgets;

use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor GSAP Image Gallery Widget
 *
 * Provides a scroll-triggered image gallery animation powered by GSAP.
 *
 * @since 1.0.0
 */
class GSAP_Image_Gallery_Widget extends Widget_Base {
	/**
	 * Track whether scripts/styles have been registered.
	 *
	 * @var bool
	 */
	private static $assets_registered = false;

	/**
	 * Constructor.
	 *
	 * @param array      $data Widget data.
	 * @param array|null $args Widget arguments.
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		if ( ! self::$assets_registered ) {
			$direction_suffix = is_rtl() ? '.rtl' : '';

			wp_register_style(
				'mascot-gsap-image-gallery',
				MASCOT_GSAP_ASSETS_URL . 'css/widgets/gsap-image-gallery' . $direction_suffix . '.css',
				[],
				MASCOT_GSAP_VERSION
			);

			wp_register_script(
				'mascot-gsap-image-gallery',
				MASCOT_GSAP_ASSETS_URL . 'js/widgets/gsap-image-gallery.js',
				[ 'jquery', 'gsap', 'tm-scroll-trigger' ],
				MASCOT_GSAP_VERSION,
				true
			);

			self::$assets_registered = true;
		}
	}

	/**
	 * Retrieve the widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mascot-gsap-image-gallery';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'GSAP Image Gallery - Mascot', 'mascot-gsap' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'tm-elementor-widget-icon';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'tm-gsap' ];
	}

	/**
	 * Retrieve the list of scripts the widget depends on.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [ 'mascot-gsap-image-gallery' ];
	}

	/**
	 * Retrieve the list of styles the widget depends on.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'mascot-gsap-image-gallery' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Images', 'mascot-gsap' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'main_image',
			[
				'label'   => esc_html__( 'Main Image', 'mascot-gsap' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'item_image',
			[
				'label'   => esc_html__( 'Image', 'mascot-gsap' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$repeater->add_responsive_control(
			'horizontal_anchor',
			[
				'label' => __( 'Horizontal Orientation', 'mascot-gsap' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => is_rtl() ? 'right' : 'left',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'mascot-gsap' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'mascot-gsap' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false,
			]
		);
		$repeater->add_responsive_control(
			'horizontal_offset',
			[
				'label'      => esc_html__( 'Horizontal Offset', 'mascot-gsap' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 0,
				],
			]
		);
		$repeater->add_responsive_control(
			'vertical_anchor',
			[
				'label' => __( 'Vertical Orientation', 'mascot-gsap' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'mascot-gsap' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'mascot-gsap' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false,
			]
		);
		$repeater->add_responsive_control(
			'vertical_offset',
			[
				'label'      => esc_html__( 'Vertical Offset', 'mascot-gsap' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => '%',
					'size' => 0,
				],
			]
		);
		$repeater->add_responsive_control(
			'image_width',
			[
				'label'      => esc_html__( 'Image Width', 'mascot-gsap' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
					'size' => 160,
				],
			]
		);
		$repeater->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Image Height', 'mascot-gsap' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'unit' => 'px',
					'size' => 160,
				],
			]
		);
		$repeater->add_control(
			'device_visibility',
			[
				'label'        => esc_html__( 'Desktop Only', 'mascot-gsap' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Hide this image on screens smaller than the ScrollTrigger breakpoint.', 'mascot-gsap' ),
				'label_on'     => esc_html__( 'Yes', 'mascot-gsap' ),
				'label_off'    => esc_html__( 'No', 'mascot-gsap' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'surrounding_images',
			[
				'label'       => esc_html__( 'Surrounding Images', 'mascot-gsap' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_image.url ? item_image.url.split("/").pop() : "' . esc_html__( 'Surrounding Image', 'mascot-gsap' ) . '" }}}',
				'default'     => [
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'left',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => 7,
						],
						'vertical_anchor'   => 'top',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => -45,
						],
						'image_width'       => [
							'unit' => 'px',
							'size' => 500,
						],
						'image_height'      => [
							'unit' => 'px',
							'size' => 230,
						],
					],
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'left',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => -72,
						],
						'vertical_anchor'   => 'top',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => -31,
						],
					],
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'left',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => -63,
						],
						'vertical_anchor'   => 'bottom',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => 5,
						],
					],
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'right',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => -95,
						],
						'vertical_anchor'   => 'bottom',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => 6,
						],
					],
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'left',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => -28,
						],
						'vertical_anchor'   => 'bottom',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => -58,
						],
					],
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'right',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => -16,
						],
						'vertical_anchor'   => 'bottom',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => -58,
						],
					],
					[
						'item_image'        => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'horizontal_anchor' => 'right',
						'horizontal_offset' => [
							'unit' => '%',
							'size' => -56,
						],
						'vertical_anchor'   => 'top',
						'vertical_offset'   => [
							'unit' => '%',
							'size' => -37,
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'animation_settings',
			[
				'label' => esc_html__( 'Animation', 'mascot-gsap' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'trigger_start',
			[
				'label'       => esc_html__( 'Trigger Start', 'mascot-gsap' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'top 30%',
				'description' => esc_html__( 'Scroll position that starts the animation (e.g., "top 30%").', 'mascot-gsap' ),
			]
		);

		$this->add_control(
			'trigger_end',
			[
				'label'       => esc_html__( 'Trigger End', 'mascot-gsap' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'bottom 100%',
				'description' => esc_html__( 'Scroll position that ends the animation (e.g., "bottom 100%").', 'mascot-gsap' ),
			]
		);

		$this->add_control(
			'enable_pin',
			[
				'label'        => esc_html__( 'Pin Section', 'mascot-gsap' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mascot-gsap' ),
				'label_off'    => esc_html__( 'No', 'mascot-gsap' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => esc_html__( 'Enable ScrollTrigger pinning for the gallery section.', 'mascot-gsap' ),
			]
		);

		$this->add_control(
			'pin_spacing',
			[
				'label'        => esc_html__( 'Pin Spacing', 'mascot-gsap' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mascot-gsap' ),
				'label_off'    => esc_html__( 'Off', 'mascot-gsap' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Keep page spacing while the section is pinned.', 'mascot-gsap' ),
			]
		);

		$this->add_control(
			'show_markers',
			[
				'label'        => esc_html__( 'Debug Markers', 'mascot-gsap' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'mascot-gsap' ),
				'label_off'    => esc_html__( 'Hide', 'mascot-gsap' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'animation_duration',
			[
				'label'   => esc_html__( 'Animation Duration', 'mascot-gsap' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'   => [
					'px' => [
						'min'  => 0.5,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
			]
		);

		$this->add_control(
			'animation_scrub',
			[
				'label'   => esc_html__( 'Scrub Amount', 'mascot-gsap' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'   => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'description' => esc_html__( 'Smoothness of the animation. Set to 0 to disable scrubbing.', 'mascot-gsap' ),
			]
		);

		$this->add_control(
			'final_image_size',
			[
				'label'   => esc_html__( 'Final Image Size', 'mascot-gsap' ),
				'type'    => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'   => [
					'px' => [
						'min' => 200,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 580,
				],
				'description' => esc_html__( 'Width/height applied to the main image at the end of the animation.', 'mascot-gsap' ),
			]
		);

		$this->add_control(
			'reverse_animation',
			[
				'label'        => esc_html__( 'Reverse Animation', 'mascot-gsap' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'mascot-gsap' ),
				'label_off'    => esc_html__( 'No', 'mascot-gsap' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Animate the main image from the final size back to its original size as you scroll forward.', 'mascot-gsap' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general_settings',
			[
				'label' => esc_html__( 'General', 'mascot-gsap' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_css_class',
			[
				'label'       => esc_html__( 'Custom CSS Class', 'mascot-gsap' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add an optional CSS class to the outer wrapper.', 'mascot-gsap' ),
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => esc_html__( 'Alignment', 'mascot-gsap' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'mascot-gsap' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'mascot-gsap' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'mascot-gsap' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .gallery-area' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [ 'mascot-gsap-image-gallery' ];
		if ( ! empty( $settings['custom_css_class'] ) ) {
			$classes[] = sanitize_html_class( $settings['custom_css_class'] );
		}

		$animation_data = [
			'trigger-start' => ! empty( $settings['trigger_start'] ) ? $settings['trigger_start'] : 'top 30%',
			'trigger-end'   => ! empty( $settings['trigger_end'] ) ? $settings['trigger_end'] : 'bottom 100%',
			'pin'           => ( isset( $settings['enable_pin'] ) && 'yes' === $settings['enable_pin'] ) ? 'true' : 'false',
			'pin-spacing'   => ( isset( $settings['pin_spacing'] ) && 'yes' === $settings['pin_spacing'] ) ? 'true' : 'false',
			'markers'       => ( isset( $settings['show_markers'] ) && 'yes' === $settings['show_markers'] ) ? 'true' : 'false',
			'duration'      => isset( $settings['animation_duration']['size'] ) ? $settings['animation_duration']['size'] : 3,
			'scrub'         => isset( $settings['animation_scrub']['size'] ) ? $settings['animation_scrub']['size'] : 1,
			'final-size'    => isset( $settings['final_image_size']['size'] ) ? $settings['final_image_size']['size'] : 580,
			'reverse'       => ( isset( $settings['reverse_animation'] ) && 'yes' === $settings['reverse_animation'] ) ? 'true' : 'false',
			'breakpoint'    => 1200,
		];

		$main_image = $this->prepare_image( isset( $settings['main_image'] ) ? $settings['main_image'] : [], esc_html__( 'Main gallery image', 'mascot-gsap' ), true );

		$surrounding_images = [];
		if ( ! empty( $settings['surrounding_images'] ) && is_array( $settings['surrounding_images'] ) ) {
			foreach ( $settings['surrounding_images'] as $index => $item ) {
				$image = $this->prepare_image(
					isset( $item['item_image'] ) ? $item['item_image'] : [],
					sprintf(
						/* translators: %d: Thumb index */
						esc_html__( 'Gallery image %d', 'mascot-gsap' ),
						$index + 1
					),
					false
				);

				$surrounding_images[] = [
					'image'             => $image,
					'horizontal_anchor' => isset( $item['horizontal_anchor'] ) ? $item['horizontal_anchor'] : 'left',
					'vertical_anchor'   => isset( $item['vertical_anchor'] ) ? $item['vertical_anchor'] : 'top',
					'horizontal_offset' => isset( $item['horizontal_offset'] ) ? $item['horizontal_offset'] : [],
					'vertical_offset'   => isset( $item['vertical_offset'] ) ? $item['vertical_offset'] : [],
					'image_width'       => isset( $item['image_width'] ) ? $item['image_width'] : [],
					'image_height'      => isset( $item['image_height'] ) ? $item['image_height'] : [],
					'desktop_only'      => ( isset( $item['device_visibility'] ) && 'yes' === $item['device_visibility'] ),
				];
			}
		}

		include MASCOT_GSAP_PATH . 'widgets/gsap-image-gallery/tpl/gsap-image-gallery.php';
	}

	/**
	 * Prepare image data for output.
	 *
	 * @param array  $image_control     Control value.
	 * @param string $default_alt       Default alt text.
	 * @param bool   $allow_placeholder Allow using a placeholder image when no image provided.
	 *
	 * @return array
	 */
	private function prepare_image( $image_control, $default_alt = '', $allow_placeholder = true ) {
		$url = '';
		$alt = $default_alt;

		if ( isset( $image_control['id'] ) && $image_control['id'] ) {
			$url = wp_get_attachment_image_url( $image_control['id'], 'full' );
			if ( empty( $alt ) ) {
				$alt = get_post_meta( $image_control['id'], '_wp_attachment_image_alt', true );
			}

			if ( empty( $alt ) ) {
				$alt = get_the_title( $image_control['id'] );
			}
		} elseif ( isset( $image_control['url'] ) && ! empty( $image_control['url'] ) ) {
			$url = $image_control['url'];
		}

		if ( empty( $url ) ) {
			if ( ! $allow_placeholder ) {
				return [
					'url' => '',
					'alt' => '',
				];
			}
			$url = Utils::get_placeholder_image_src();
		}

		if ( empty( $alt ) && $allow_placeholder ) {
			$alt = esc_html__( 'Gallery image', 'mascot-gsap' );
		}

		return [
			'url' => $url,
			'alt' => $alt,
		];
	}
}


