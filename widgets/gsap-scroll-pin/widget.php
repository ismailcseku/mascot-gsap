<?php
namespace MascotCoreHotelin\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor GSAP Scroll Pin Widget
 *
 * Elementor widget for GSAP scroll-triggered pinned title animation.
 *
 * @since 1.0.0
 */
class TM_Elementor_GSAP_Scroll_Pin extends Widget_Base {
	public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_register_style( 'tm-gsap-scroll-pin-style', MASCOT_CORE_HOTELIN_ASSETS_URI . '/css/shortcodes/gsap-scroll-pin' . $direction_suffix . '.css' );
		wp_register_script( 'tm-gsap-scroll-pin', MASCOT_CORE_HOTELIN_ASSETS_URI . '/js/widgets/gsap-scroll-pin.js', array('jquery', 'gsap', 'tm-scroll-trigger'), false, true );
	}

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'tm-ele-gsap-scroll-pin';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return esc_html__( 'GSAP Scroll Pin', 'mascot-core-hotelin' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'tm-elementor-widget-icon';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 */
	public function get_categories() {
		return [ 'tm' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 */
	public function get_script_depends() {
		return [ 'gsap', 'tm-scroll-trigger', 'tm-gsap-scroll-pin' ];
	}

	public function get_style_depends() {
		return [ 'tm-gsap-scroll-pin-style' ];
	}

	/**
	 * Register the widget controls.
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'mascot-core-hotelin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( "Title Text", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( "Portfolio", 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'title_tag',
			[
				'label' => esc_html__( "Title Tag", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => mascot_core_hotelin_heading_tag_list(),
				'default' => 'h4',
			]
		);


		$this->add_control(
			'title_other_part',
			[
				'label' => esc_html__( 'Other Parts', 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title_other_text',
			[
				'label' => esc_html__( "Title Text", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
			]
		);
		$repeater->add_control(
			'title_other_text_color',
			[
				'label' => esc_html__( "Text Color", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}' => 'color: {{VALUE}};'
				]
			]
		);
		$repeater->add_control(
			'title_other_theme_colored',
			[
				'label' => esc_html__( "Theme Colored", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => mascot_core_hotelin_theme_color_list(),
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}' => 'color: var(--theme-color{{VALUE}});'
				],
			]
		);
		$repeater->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_other_typography',
				'label' => esc_html__( 'Typography', 'mascot-core-hotelin' ),
				'selector' => '{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}',
			]
		);
		$repeater->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Text Padding', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_responsive_control(
            'stroke_text_width_normal',
            [
                'label' => esc_html__( 'Stroke Width', 'mascot-core-hotelin' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'vw' ],
                'range' => [
                    'px' => [ 'min' => 0.1, 'max' => 10 ],
                ],
				'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$repeater->add_control(
			'stroke_text_color_normal',
			[
				'label' => esc_html__( 'Stroke Color', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}' => '-webkit-text-stroke-color: {{VALUE}};',
				],
			]
		);
		$repeater->add_control(
			'stroke_text_theme_colored',
			[
				'label' => esc_html__( "Stroke Theme Colored", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => mascot_core_hotelin_theme_color_list(),
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title  {{CURRENT_ITEM}}' => '-webkit-text-stroke-color: var(--theme-color{{VALUE}});',
				],
			]
		);
		$this->add_control(
			'title_list',
			[
				'label' => esc_html__( "Title Other Parts", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
		$this->end_controls_section();

		// Animation Settings
		$this->start_controls_section(
			'animation_settings',
			[
				'label' => esc_html__( 'Animation Settings', 'mascot-core-hotelin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'enable_scale_animation',
			[
				'label' => esc_html__( "Enable Scale Animation", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
				'description' => esc_html__( 'Animate the scale (zoom) effect while scrolling', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'initial_scale',
			[
				'label' => esc_html__( 'Initial Scale', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 2,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0.6,
				],
				'condition' => [
					'enable_scale_animation' => 'yes'
				],
			]
		);
		$this->add_control(
			'final_scale',
			[
				'label' => esc_html__( 'Final Scale', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition' => [
					'enable_scale_animation' => 'yes'
				],
			]
		);
		$this->add_control(
			'scale_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 20,
						'step' => 0.5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 4,
				],
				'condition' => [
					'enable_scale_animation' => 'yes'
				],
				'description' => esc_html__( 'Duration for the scale animation from initial to final (higher = slower)', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'hold_duration',
			[
				'label' => esc_html__( 'Hold Duration', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0.5,
						'max' => 20,
						'step' => 0.5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'description' => esc_html__( 'Duration to hold at final scale before next animation (higher = slower)', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'hold_delay',
			[
				'label' => esc_html__( 'Hold Delay', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
						'step' => 0.5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'description' => esc_html__( 'Delay before the hold animation starts (higher = more delay)', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'scrub',
			[
				'label' => esc_html__( 'Scrub Value', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'description' => esc_html__( 'Scrub value controls the smoothness of the animation. Higher values = smoother. Set to 0 for no scrub.', 'mascot-core-hotelin' ),
			]
		);
		$this->end_controls_section();

		// Scroll Trigger Settings
		$this->start_controls_section(
			'scroll_trigger_settings',
			[
				'label' => esc_html__( 'ScrollTrigger Settings', 'mascot-core-hotelin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'trigger_start',
			[
				'label' => esc_html__( 'Trigger Start', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'top center-=350',
				'description' => esc_html__( 'When the animation starts (e.g., "top center", "top center-=350")', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'trigger_end',
			[
				'label' => esc_html__( 'Trigger End', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'bottom 150%',
				'description' => esc_html__( 'When the animation ends (e.g., "bottom center", "bottom 150%")', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'pin_spacing',
			[
				'label' => esc_html__( "Pin Spacing", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Whether to add padding at the end of the pinned element', 'mascot-core-hotelin' ),
			]
		);
		$this->add_control(
			'show_markers',
			[
				'label' => esc_html__( "Show Debug Markers", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'no',
				'description' => esc_html__( 'Show ScrollTrigger markers for debugging (development only)', 'mascot-core-hotelin' ),
			]
		);
		$this->end_controls_section();

		// General Settings
		$this->start_controls_section(
			'general_settings',
			[
				'label' => esc_html__( 'General Settings', 'mascot-core-hotelin' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'custom_css_class',
			[
				'label' => esc_html__( "Custom CSS Class", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'description' => esc_html__( 'Add custom CSS class for styling', 'mascot-core-hotelin' ),
			]
		);
		$this->add_responsive_control(
			'text_alignment',
			[
				'label' => esc_html__( "Text Alignment", 'mascot-core-hotelin' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => mascot_core_hotelin_text_align_choose(),
				'selectors' => [
					'{{WRAPPER}} .gsap-scroll-pin-wrapper' => 'text-align: {{VALUE}};'
				]
			]
		);
		$this->end_controls_section();



		// Styling Section
		$this->start_controls_section(
			'title_styling',
			[
				'label' => esc_html__( 'Title Styling', 'mascot-core-hotelin' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label' => esc_html__( "Text Color", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title' => 'color: {{VALUE}};'
				]
			]
		);
		$this->add_control(
			'title_text_color_hover',
			[
				'label' => esc_html__( "Text Color (Hover)", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover .scroll-pin-title' => 'color: {{VALUE}};'
				]
			]
		);
		$this->add_control(
			'title_theme_colored',
			[
				'label' => esc_html__( "Theme Colored", 'mascot-core-hotelin' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => mascot_core_hotelin_theme_color_list(),
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title' => 'color: var(--theme-color{{VALUE}});'
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'mascot-core-hotelin' ),
				'selector' => '{{WRAPPER}} .scroll-pin-title',
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Title Padding', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Title Margin', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'label' => esc_html__( 'Background', 'mascot-core-hotelin' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .scroll-pin-title',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'label' => esc_html__( 'Border', 'mascot-core-hotelin' ),
				'selector' => '{{WRAPPER}} .scroll-pin-title',
			]
		);
		$this->add_responsive_control(
			'title_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .scroll-pin-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'title_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'mascot-core-hotelin' ),
				'selector' => '{{WRAPPER}} .scroll-pin-title',
			]
		);
		$this->end_controls_section();



		// Container Styling
		$this->start_controls_section(
			'container_styling',
			[
				'label' => esc_html__( 'Container Styling', 'mascot-core-hotelin' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Container Padding', 'mascot-core-hotelin' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .gsap-scroll-pin-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'container_background',
				'label' => esc_html__( 'Background', 'mascot-core-hotelin' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .gsap-scroll-pin-wrapper',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Build classes array
		$classes = array();
		if( !empty($settings['custom_css_class']) ) {
			$classes[] = $settings['custom_css_class'];
		}
		$settings['classes'] = $classes;

		// Build animation data attributes
		$animation_data = array(
			'enable-scale' => (isset($settings['enable_scale_animation']) && $settings['enable_scale_animation'] == 'yes') ? 'true' : 'false',
			'initial-scale' => isset($settings['initial_scale']['size']) ? $settings['initial_scale']['size'] : 0.6,
			'final-scale' => isset($settings['final_scale']['size']) ? $settings['final_scale']['size'] : 1,
			'duration' => isset($settings['scale_animation_duration']['size']) ? $settings['scale_animation_duration']['size'] : 4,
			'hold-duration' => isset($settings['hold_duration']['size']) ? $settings['hold_duration']['size'] : 4,
			'hold-delay' => isset($settings['hold_delay']['size']) ? $settings['hold_delay']['size'] : 4,
			'trigger-start' => isset($settings['trigger_start']) ? $settings['trigger_start'] : 'top center-=350',
			'trigger-end' => isset($settings['trigger_end']) ? $settings['trigger_end'] : 'bottom 150%',
			'scrub' => isset($settings['scrub']['size']) ? $settings['scrub']['size'] : 1,
			'markers' => (isset($settings['show_markers']) && $settings['show_markers'] == 'yes') ? 'true' : 'false',
			'pin-spacing' => (isset($settings['pin_spacing']) && $settings['pin_spacing'] == 'yes') ? 'true' : 'false',
		);
		$settings['animation_data'] = $animation_data;

		// Enqueue necessary scripts
		wp_enqueue_script( 'gsap' );
		wp_enqueue_script( 'tm-scroll-trigger' );

		// Produce HTML version by using the template
		$html = mascot_core_hotelin_get_shortcode_template_part( 'gsap-scroll-pin', null, 'gsap-scroll-pin/tpl', $settings, true );

		echo $html;
	}
}

