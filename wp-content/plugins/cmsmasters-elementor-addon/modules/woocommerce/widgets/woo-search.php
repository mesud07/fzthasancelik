<?php

namespace CmsmastersElementor\Modules\Woocommerce\Widgets;

use CmsmastersElementor\Base\Base_Document;
use CmsmastersElementor\Controls_Manager as CmsmastersControls;
use CmsmastersElementor\Modules\TemplateSections\Widgets\Search;

use Elementor\Controls_Manager;
use Elementor\Plugin;

use DgoraWcas\Helpers;
use DgoraWcas\Multilingual;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon search widget.
 *
 * Addon widget that display site search.
 *
 * @since 1.0.0
*/
class Woo_Search extends Search {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		$woo_search_attr = $this->woo_search_attr();

		$this->form_role = $woo_search_attr['form-role'];
		$this->form_method = $woo_search_attr['form-method'];
		$this->form_add_class = $woo_search_attr['form-add-class'];
		$this->form_action = $woo_search_attr['form-action'];
		$this->input_type = $woo_search_attr['input-type'];
		$this->input_name = $woo_search_attr['input-name'];
		$this->input_value = $woo_search_attr['input-value'];
		$this->add_input_class = $woo_search_attr['add-input-class'];
		$this->custom_atts = $woo_search_attr['custom-atts'];
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @since 1.0.0
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array(
			Base_Document::WOO_WIDGETS_CATEGORY,
		);
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string The widget name.
	 */
	public function get_name() {
		return 'cmsmasters-woo-search';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Fibo Ajax Search for Woocommerce', 'cmsmasters-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'cmsicon-search';
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 1.16.0
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return array_merge( parent::get_style_depends(), array(
			'widget-cmsmasters-woocommerce',
		) );
	}

	/**
	 * Hides elementor widget container to the frontend if `Optimized Markup` is enabled.
	 *
	 * @since 1.16.4
	 */
	public function has_widget_inner_wrapper(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		parent::register_controls();

		$show_details_box = DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on';
		$show_product_image = DGWT_WCAS()->settings->getOption( 'show_product_image' ) === 'on';
		$show_product_price = DGWT_WCAS()->settings->getOption( 'show_product_price' ) === 'on';
		$show_product_desc = DGWT_WCAS()->settings->getOption( 'show_product_desc' ) === 'on';
		$show_product_sku = DGWT_WCAS()->settings->getOption( 'show_product_sku' ) === 'on';
		$show_product_tax_product_cat = DGWT_WCAS()->settings->getOption( 'show_product_tax_product_cat' ) === 'on';
		$show_product_tax_product_tag = DGWT_WCAS()->settings->getOption( 'show_product_tax_product_tag' ) === 'on';

		$selector_autocomplete = '#cmsmasters_body .dgwt-wcas-suggestions-wrapp, #cmsmasters_body .dgwt-wcas-details-wrapp';

		$this->start_controls_section(
			'section_autocomplete_wrapp',
			array(
				'label' => __( 'Autocomplete Box', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'autocomplete_wrapp_color',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-wrapp-color: {{VALUE}};",
				),
			)
		);

		if ( $show_details_box ) {
			$this->add_control(
				'autocomplete_wrapp__spt_color',
				array(
					'label' => __( 'Separator Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-spt-wrapp-color: {{VALUE}};",
					),
				)
			);
		}

		$this->add_group_control(
			CmsmastersControls::VARS_BORDER_GROUP,
			array(
				'name' => 'autocomplete_wrapp_border',
				'separator' => 'before',
				'selector' => $selector_autocomplete,
			)
		);

		if ( ! $show_details_box ) {
			$this->add_responsive_control(
				'autocomplete_wrapp_bdr',
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-wrapp-bdr: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
		}

		if ( $show_details_box ) {
			$this->add_responsive_control(
				'autocomplete_wrapp_spt_width',
				array(
					'label' => esc_html__( 'Separator Width', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 10,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-wrapp-spt-width: {{SIZE}}{{UNIT}};',
					),
				)
			);
		}

		$this->add_responsive_control(
			'autocomplete_v_pdd',
			array(
				'label' => esc_html__( 'Vertical Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'size_units' => array(
					'px',
				),
				'selectors' => array(
					$selector_autocomplete => '--autocomplete-v-wrapp-pdd: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'autocomplete_h_pdd',
			array(
				'label' => esc_html__( 'Horizontal Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'size_units' => array(
					'px',
				),
				'selectors' => array(
					$selector_autocomplete => '--autocomplete-h-wrapp-pdd: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		if ( $show_product_tax_product_cat || $show_product_tax_product_tag ) {

			$this->start_controls_section(
				'section_autocomplete_tax',
				array(
					'label' => __( 'Autocomplete Taxonomy', 'cmsmasters-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'tax_title',
				array(
					'label' => __( 'Title', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'autocomplete_title_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'autocomplete_title_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-title-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				'autocomplete_title_spt_color',
				array(
					'label' => __( 'Separator Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-title-spt-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_title_gap',
				array(
					'label' => esc_html__( 'Title Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'size_units' => array(
						'px',
					),
					'separator' => 'before',
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-title-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_title_spt_width',
				array(
					'label' => esc_html__( 'Separator Width', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 10,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-title-spt-width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_title_spt_gap',
				array(
					'label' => esc_html__( 'Separator gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-title-spt-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'tax_search_result',
				array(
					'label' => __( 'Search Result', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'autocomplete_search_result_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'autocomplete_search_result_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-search-result-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				'autocomplete_search_result_color_hover',
				array(
					'label' => __( 'Hover Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-search-result-color-hover: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_search_result_marg',
				array(
					'label' => __( 'Margin', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array(
						'px',
						'%',
					),
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-search-result-marg: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
					),
				)
			);

			$this->end_controls_section();
		}

		$this->start_controls_section(
			'section_autocomplete_product',
			array(
				'label' => __( 'Autocomplite Product', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_general',
			array(
				'label' => __( 'Box', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'autocomplete_product_box_color',
			array(
				'label' => __( 'Background Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-product-box-color: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'autocomplete_product_box_color_hover',
			array(
				'label' => __( 'Background Color Hover', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-product-box-color-hover: {{VALUE}};",
				),
			)
		);

		$this->add_group_control(
			CmsmastersControls::VARS_BORDER_GROUP,
			array(
				'name' => 'autocomplete_product_box_border',
				'separator' => 'before',
				'selector' => $selector_autocomplete,
			)
		);

		$this->add_responsive_control(
			'autocomplete_product_box_pdd',
			array(
				'label' => esc_html__( 'Vertical Padding', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array(
					'px',
				),
				'selectors' => array(
					$selector_autocomplete => '--autocomplete-product-box-pdd: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'autocomplete_product_box_marg',
			array(
				'label' => __( 'Margin', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array(
					'px',
					'%',
				),
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-product-box-marg: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};",
				),
			)
		);

		if ( $show_product_image ) {

			$this->add_control(
				'product_image',
				array(
					'label' => __( 'Image', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'autocomplete_product_image_width',
				array(
					'label' => esc_html__( 'Width', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 200,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-product-image-widt: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_product_image_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-product-image-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_BORDER_GROUP,
				array(
					'name' => 'autocomplete_product_image_border',
					'separator' => 'before',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_responsive_control(
				'autocomplete_product_image_bdr',
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-product-image-bdr: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
		}

		$this->add_control(
			'product_title',
			array(
				'label' => __( 'Title', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
			array(
				'name' => 'autocomplete_product_title_font',
				'selector' => $selector_autocomplete,
			)
		);

		$this->add_control(
			'autocomplete_product_title_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-product-title-color: {{VALUE}};",
				),
			)
		);

		if ( $show_product_sku ) {

			$this->add_control(
				'product_sku',
				array(
					'label' => __( 'SKU', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'autocomplete_product_sku_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'autocomplete_product_sku_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-product-sku-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_product_sku_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-product-sku-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);
		}

		if ( $show_product_desc ) {
			$this->add_control(
				'product_desc',
				array(
					'label' => __( 'Description', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'autocomplete_product_desc_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'autocomplete_product_desc_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-product-desc-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_product_desc_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-product-desc-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);
		}

		if ( $show_product_price ) {
			$this->add_control(
				'product_price',
				array(
					'label' => __( 'Price', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'autocomplete_product_price_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'autocomplete_product_price_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--autocomplete-product-price-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'autocomplete_product_price_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--autocomplete-product-price-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_autocomplete_more',
			array(
				'label' => __( 'Autocomplete Show More', 'cmsmasters-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
			array(
				'name' => 'autocomplete_more_font',
				'selector' => $selector_autocomplete,
			)
		);

		$this->add_control(
			'autocomplete_more_color',
			array(
				'label' => __( 'Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-more-color: {{VALUE}};",
				),
			)
		);

		$this->add_control(
			'autocomplete_more_hover_color',
			array(
				'label' => __( 'Hover Color', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					$selector_autocomplete => "--autocomplete-more-color-hover: {{VALUE}};",
				),
			)
		);

		$this->add_responsive_control(
			'autocomplete_more_gap',
			array(
				'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'size_units' => array(
					'px',
				),
				'separator' => 'before',
				'selectors' => array(
					$selector_autocomplete => '--autocomplete-more-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		if ( DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on' ) {
			$this->start_controls_section(
				'section_detalis_tax_product',
				array(
					'label' => __( 'Detalis Taxonomy', 'cmsmasters-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-tax-product-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_product_image',
				array(
					'label' => __( 'Image', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_image_width',
				array(
					'label' => esc_html__( 'Width', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 200,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-tax-product-image-width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_image_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-tax-product-image-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_BORDER_GROUP,
				array(
					'name' => 'detalis_tax_product_image_border',
					'separator' => 'before',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_image_bdr',
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors' => array(
						$selector_autocomplete => '--detalis-tax-product-image-bdr: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_tax_product_title',
				array(
					'label' => __( 'Title', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'detalis_tax_product_title_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'detalis_tax_product_title_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-tax-product-title-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				'detalis_tax_product_star',
				array(
					'label' => __( 'Rating', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_control(
				'detalis_tax_product_star_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-tax-product-star-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_star_size',
				array(
					'label' => esc_html__( 'Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-star-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_star_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-star-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_tax_product_price',
				array(
					'label' => __( 'Price', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'detalis_tax_product_price_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'detalis_tax_product_price_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-tax-product-price-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_tax_product_price_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-tax-product-price-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_detalis_product',
				array(
					'label' => __( 'Detalis Product', 'cmsmasters-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				)
			);

			$this->add_control(
				'detalis_woo_product_image',
				array(
					'label' => __( 'Image', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_responsive_control(
				'detalis_product_image_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-image-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_BORDER_GROUP,
				array(
					'name' => 'detalis_product_image_border',
					'separator' => 'before',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_responsive_control(
				'detalis_product_image_bdr',
				array(
					'label' => esc_html__( 'Border Radius', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-image-bdr: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_product_title',
				array(
					'label' => __( 'Title', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'detalis_product_title_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'detalis_product_title_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-product-title-color: {{VALUE}};",
					),
				)
			);

			$this->add_control(
				'detalis_product_title_color_hover',
				array(
					'label' => __( 'Hover Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-product-title-color-hover: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_product_title_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-title-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_product_sku',
				array(
					'label' => __( 'SKU', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'detalis_product_sku_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'detalis_product_sku_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-product-sku-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_product_sku_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-sku-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_woo_product_star',
				array(
					'label' => __( 'Rating', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_control(
				'detalis_woo_product_star_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-woo-product-star-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_woo_product_star_size',
				array(
					'label' => esc_html__( 'Size', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-woo-product-star-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'detalis_product_star_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-woo-product-star-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_product_desc',
				array(
					'label' => __( 'Description', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'detalis_product_desc_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'detalis_product_desc_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-product-desc-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_product_desc_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-desc-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->add_control(
				'detalis_product_price',
				array(
					'label' => __( 'Price', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::HEADING,
				)
			);

			$this->add_group_control(
				CmsmastersControls::VARS_TYPOGRAPHY_GROUP,
				array(
					'name' => 'detalis_product_price_font',
					'selector' => $selector_autocomplete,
				)
			);

			$this->add_control(
				'detalis_product_price_color',
				array(
					'label' => __( 'Color', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => array(
						$selector_autocomplete => "--detalis-product-price-color: {{VALUE}};",
					),
				)
			);

			$this->add_responsive_control(
				'detalis_product_price_gap',
				array(
					'label' => esc_html__( 'Gap', 'cmsmasters-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 50,
						),
					),
					'size_units' => array(
						'px',
					),
					'selectors' => array(
						$selector_autocomplete => '--detalis-product-price-gap: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->end_controls_section();
		}
	}

	/**
	 * Render search widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		parent::render();
	}

	public function woo_search_attr() {
		$unique_id = ++ DGWT_WCAS()->searchInstances;

		return array(
			'form-role' => 'search',
			'form-method' => 'get',
			'form-add-class' => 'dgwt-wcas-search-form',
			'form-action' => Helpers::searchFormAction(),
			'input-type' => 'search',
			'input-value' => apply_filters( 'dgwt/wcas/search_bar/value', get_search_query(), DGWT_WCAS()->searchInstances ),
			'input-name' => Helpers::getSearchInputName(),
			'add-input-class' => 'dgwt-wcas-search-input',
			'custom-atts' => array(
				'autocomplete' => 'off',
				'id' => "dgwt-wcas-search-input-{$unique_id}>",
			),
		);
	}

	/**
	 * Render search form.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 */
	public function get_form_search() {
		$settings = $this->get_settings_for_display();

		$unique_id = ++ DGWT_WCAS()->searchInstances;
		$wrapper_class = Helpers::searchWrappClasses( array() );
		$submit_button_view = $settings['submit_button_view'];
		$is_editor = Plugin::$instance->editor->is_edit_mode();

		$this->add_render_attribute( 'search-from', array(
			'role' => $this->form_role,
			'method' => $this->form_method,
			'class' => "elementor-widget-cmsmasters-search__form {$this->form_add_class}",
			'action' => $this->form_action,
		) );

		$this->add_render_attribute( 'search-form-container', 'class', array(
			'elementor-widget-cmsmasters-search__form-container',
			'cmsmasters-submit-button-view-' . $submit_button_view,
			'dgwt-wcas-sf-wrapp',
		) );

		if ( 'button' === $submit_button_view ) {
			$this->add_render_attribute( 'search-form-container', 'class', 'cmsmasters-buttons-type-' . $settings['submit_button_type'] );
		}

		echo "<div class='dgwt-wcas-search-wrapp {$wrapper_class}'>" .
			'<form ' . $this->get_render_attribute_string( 'search-from' ) . '>' .
				'<div ' . $this->get_render_attribute_string( 'search-form-container' ) . '>
					<div class="dgwt-wcas-voice-search"></div>';

					$this->get_search_fields();
					$this->get_submit_button( $loader = $this->woo_search_loader() );

					echo "<input type='hidden' name='post_type' value='product'/>
						<input type='hidden' name='dgwt_wcas' value='1'/>";

					if ( Multilingual::isWPML() ) {
						$current_language = Multilingual::getCurrentLanguage();
						echo "<input type='hidden' name='lang' value='{$current_language}'/>";
					}

					do_action( 'dgwt/wcas/form' );

				echo '</div>' .
			'</form>';

			if ( $is_editor ) {
				$this->woo_preview_autocomplite();
			}

		echo '</div>';
	}

	public function woo_preview_autocomplite() {
		$show_details_box = DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on';
		$show_product_image = DGWT_WCAS()->settings->getOption( 'show_product_image' ) === 'on';
		$show_product_price = DGWT_WCAS()->settings->getOption( 'show_product_price' ) === 'on';
		$show_product_desc = DGWT_WCAS()->settings->getOption( 'show_product_desc' ) === 'on';
		$show_product_sku = DGWT_WCAS()->settings->getOption( 'show_product_sku' ) === 'on';
		$show_product_tax_product_cat = DGWT_WCAS()->settings->getOption( 'show_product_tax_product_cat' ) === 'on';
		$show_product_tax_product_tag = DGWT_WCAS()->settings->getOption( 'show_product_tax_product_tag' ) === 'on';

		$detalis_box = ( ! $show_details_box ) ? ' cmsmasters-detalis-box-hide' : '';

		echo '<div class="cmsmasters-dgwt-wcas-autocomplete ' . $detalis_box . '">
			<div class="dgwt-wcas-suggestions-wrapp woocommerce dgwt-wcas-has-img dgwt-wcas-has-price dgwt-wcas-has-desc dgwt-wcas-has-sku dgwt-wcas-has-headings">';

				if ( $show_product_tax_product_cat ) {

					echo'<a href="#" class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline" data-index="0">
						<span class="dgwt-wcas-st">' . __( 'Categories', 'cmsmasters-elementor' ) . '</span>
					</a>
					<a href="#" class="dgwt-wcas-suggestion dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-cat dgwt-wcas-suggestion-selected" data-index="1">
						<span class="dgwt-wcas-st">' . __( 'Sample brand <strong>name</strong>', 'cmsmasters-elementor' ) . '</span>
					</a>
					<a href="#" class="dgwt-wcas-suggestion dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-cat" data-index="2">
						<span class="dgwt-wcas-st">' . __( 'Sample brand <strong>name</strong>', 'cmsmasters-elementor' ) . '</span>
					</a>';

				}

				if ( $show_product_tax_product_tag ) {

					echo '<a href="#" class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline" data-index="3">
						<span class="dgwt-wcas-st">' . __( 'Tags', 'cmsmasters-elementor' ) . '</span>
					</a>
					<a href="#" class="dgwt-wcas-suggestion dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-tag" data-index="4">
						<span class="dgwt-wcas-st">' . __( 'Sample brand <strong>name</strong>', 'cmsmasters-elementor' ) . '</span>
					</a>
					<a href="#" class="dgwt-wcas-suggestion dgwt-wcas-suggestion-tax dgwt-wcas-suggestion-tag" data-index="4">
						<span class="dgwt-wcas-st">' . __( 'Sample brand <strong>name</strong>', 'cmsmasters-elementor' ) . '</span>
					</a>';

				}

				echo '<a href="#" class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline" data-index="5">
					<span class="dgwt-wcas-st">' . __( 'Products', 'cmsmasters-elementor' ) . '</span>
				</a>
				<a href="#" class="dgwt-wcas-suggestion dgwt-wcas-suggestion-product" data-index="6" data-post-id="15">';

					if ( $show_product_image ) {
						echo '<span class="dgwt-wcas-si"><img src="' . DGWT_WCAS_URL . 'assets/img/product-preview.png"></span>';
					}

					echo '<div class="dgwt-wcas-content-wrapp">
						<div class="dgwt-wcas-st">
							<span class="dgwt-wcas-st-title">' . __( 'Sample brand <strong>name</strong>', 'cmsmasters-elementor' ) . '</span>';

							if ( $show_product_sku ) {
								echo '<span class="dgwt-wcas-sku">(SKU: 0101312)</span>';
							}

							if ( $show_product_desc ) {
								echo '<span class="dgwt-wcas-sd">' . __( '"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem…', 'cmsmasters-elementor' ) . '</span>';
							}

						echo '</div>';

						if ( $show_product_price ) {

						echo '<div class="dgwt-wcas-meta">
							<span class="dgwt-wcas-sp">
								<del aria-hidden="true">
									<span class="woocommerce-Price-amount amount"><bdi>100,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></bdi></span>
								</del>
								<ins>
									<span class="woocommerce-Price-amount amount"><bdi>50,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></bdi></span>
								</ins>
							</span>
						</div>';

						}

					echo '</div>
				</a>
				<a href="#" class="dgwt-wcas-suggestion dgwt-wcas-suggestion-product" data-index="6" data-post-id="15">';

					if ( $show_product_image ) {
						echo '<span class="dgwt-wcas-si"><img src="' . DGWT_WCAS_URL . 'assets/img/product-preview.png"></span>';
					}

					echo '<div class="dgwt-wcas-content-wrapp">
						<div class="dgwt-wcas-st">
							<span class="dgwt-wcas-st-title">' . __( 'Sample brand <strong>name</strong>', 'cmsmasters-elementor' ) . '</span>';

							if ( $show_product_sku ) {
								echo '<span class="dgwt-wcas-sku">(SKU: 0101312)</span>';
							}

							if ( $show_product_desc ) {
								echo '<span class="dgwt-wcas-sd">' . __( '"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem…', 'cmsmasters-elementor' ) . '</span>';
							}

						echo '</div>';

						if ( $show_product_price ) {

						echo '<div class="dgwt-wcas-meta">
							<span class="dgwt-wcas-sp">
								<del aria-hidden="true">
									<span class="woocommerce-Price-amount amount"><bdi>100,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></bdi></span>
								</del>
								<ins>
									<span class="woocommerce-Price-amount amount"><bdi>50,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></bdi></span>
								</ins>
							</span>
						</div>';

						}

					echo '</div>
				</a>
				<a href="#" class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-more dgwt-wcas-suggestion-more dgwt-wcas-suggestion-no-border-bottom" data-index="4">
					<span class="dgwt-wcas-st-more">' . __( 'See all products...', 'cmsmasters-elementor' ) . '<span class="dgwt-wcas-st-more-total"> (2)</span></span>
				</a>
			</div>';

			if ( $show_details_box ) {

			echo '<div class="dgwt-wcas-details-wrapp woocommerce">
				<div data-object="1335327184" class="dgwt-wcas-details-inner dgwt-wcas-details-inner-taxonomy dgwt-wcas-details-space dgwt-wcas-details-inner-active">
					<div class="dgwt-wcas-products-in-cat">
						<span class="dgwt-wcas-datails-title"><span class="dgwt-wcas-details-title-tax">' . __( 'Taxonomy: ', 'cmsmasters-elementor' ) . '</span>' . __( 'Sample brand', 'cmsmasters-elementor' ) . '</span>

						<a class="dgwt-wcas-tax-product-details" href="#">
							<div class="dgwt-wcas-tpd-image">
								<img src="' . DGWT_WCAS_URL . 'assets/img/product-preview.png">
							</div>
							<div class="dgwt-wcas-tpd-rest">
								<span class="dgwt-wcas-tpd-rest-title">' . __( 'Sample brand', 'cmsmasters-elementor' ) . '</span>
								<div class="dgwt-wcas-tpd-price">
									<del aria-hidden="true">
										<span class="woocommerce-Price-amount amount">1002,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></span>
									</del>
									<ins>
										<span class="woocommerce-Price-amount amount">502,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></span>
									</ins>
								</div>
							</div>
						</a>

						<a class="dgwt-wcas-tax-product-details" href="#">
							<div class="dgwt-wcas-tpd-image">
								<img src="' . DGWT_WCAS_URL . 'assets/img/product-preview.png">
							</div>
							<div class="dgwt-wcas-tpd-rest">
								<span class="dgwt-wcas-tpd-rest-title">' . __( 'Sample brand', 'cmsmasters-elementor' ) . '</span>
								<div class="dgwt-wcas-pd-rating">
									<div class="star-rating" role="img" aria-label="Rated 4 out of 5"><span style="width:80%">Rated <strong class="rating">4</strong> out of 5</span></div>
									<span class="dgwt-wcas-pd-review">(2)</span>
								</div>
								<div class="dgwt-wcas-tpd-price">
									<del aria-hidden="true">
										<span class="woocommerce-Price-amount amount">100,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></span>
									</del>
									<ins>
										<span class="woocommerce-Price-amount amount">50,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></span>
									</ins>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div data-object="817954317" class="dgwt-wcas-details-inner dgwt-wcas-details-inner-product">
					<div class="dgwt-wcas-product-details">
						<a href="#" title="Woo product">
							<div class="dgwt-wcas-details-main-image">
							<img src="' . DGWT_WCAS_URL . 'assets/img/product-preview.png">
							</div>
						</a>
						<div class="dgwt-wcas-details-space">
							<a class="dgwt-wcas-details-product-title" href="#" title="Woo product">
							' . __( 'Sample brand', 'cmsmasters-elementor' ) . '
							</a>
							<span class="dgwt-wcas-details-product-sku">0101312</span>
							<div class="dgwt-wcas-pd-rating">
							<div class="star-rating" role="img" aria-label="Rated 4 out of 5"><span style="width:80%">Rated <strong class="rating">4</strong> out of 5</span></div>
								<span class="dgwt-wcas-pd-review">(2)</span>
							</div>
							<div class="dgwt-wcas-pd-price">
								<del aria-hidden="true"><span class="woocommerce-Price-amount amount">100,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></span></del>
								<ins><span class="woocommerce-Price-amount amount">50,00&nbsp;<span class="woocommerce-Price-currencySymbol">₴</span></span></ins>
							</div>
							<div class="dgwt-wcas-details-hr"></div>
							<div class="dgwt-wcas-details-desc">
							' . __( '"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem…', 'cmsmasters-elementor' ) . '
							</div>
							<div class="dgwt-wcas-details-hr"></div>
							<div class="dgwt-wcas-pd-addtc js-dgwt-wcas-pd-addtc">
								<form class="dgwt-wcas-pd-addtc-form" action="" method="post" enctype="multipart/form-data">
									<div class="quantity">
										<label class="screen-reader-text" for="quantity_65cdecac65fda">' . __( 'Sample brand', 'cmsmasters-elementor' ) . '</label>
										<input type="number" id="quantity_65cdecac65fda" class="input-text qty text" name="js-dgwt-wcas-quantity" value="1" aria-label="Product quantity" size="4" min="0" max="" step="1" placeholder="" inputmode="numeric" autocomplete="off">
									</div>
									<p class="product woocommerce add_to_cart_inline " style="">
										<a href="#" data-quantity="1" class="button add_to_cart_button " data-product_id="15" data-product_sku="0101312" aria-label="Add to cart: “Woo product”" aria-describedby="" rel="nofollow">
											<span>'. __( 'Add to Cart', 'cmsmasters-elementor' ) . '</span>
										</a>
									</p>
								</form>
							</div>
						</div>
					</div>
				</div
			</div>';

			}

		echo '</div>';
	}

	public function woo_search_loader() {
		return '<div class="cmsmasters-woo-preloader-wrapper elementor-widget-cmsmasters-search__submit-icon">
			<svg class="cmsmasters-woo-preloader" version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		   width="40px" height="40px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
			<path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
		  		<animateTransform attributeType="xml"
				attributeName="transform"
				type="rotate"
				from="0 25 25"
				to="360 25 25"
				dur="0.6s"
				repeatCount="indefinite"/>
		  </path>
		</svg>
	  </div>';
	}

	public function get_form_search_template() {
		?>
		<#
		view.addRenderAttribute( 'search-from', {
			'role': 'search',
			'method': 'get',
			'class': 'elementor-widget-cmsmasters-search__form',
			'action': '<?php esc_url( home_url( '/' ) ); ?>',
		} );

		var $submit_button_view = settings.submit_button_view;

		view.addRenderAttribute( 'search-form-container', 'class', [
			'elementor-widget-cmsmasters-search__form-container',
			'cmsmasters-submit-button-view-' . $submit_button_view,
			'dgwt-wcas-sf-wrapp',
		] );

		if ( 'button' === $submit_button_view ) {
			view.addRenderAttribute( 'search-form-container', 'class', 'cmsmasters-buttons-type-' + settings.submit_button_type );
		}

		#><div class='dgwt-wcas-search-wrapp'>
		<form {{{ view.getRenderAttributeString( 'search-from' ) }}}>
			<div {{{ view.getRenderAttributeString( 'search-form-container' ) }}}><#
				view.addRenderAttribute( 'search-field', {
					'type': 'search',
					'class': 'elementor-widget-cmsmasters-search__field',
					'value': '<?php echo get_search_query(); ?>',
					'name': 's',
				} );

				if ( 'yes' === settings.form_input_show_icon ) {
					#><div class="elementor-widget-cmsmasters-search__form-input-icon-container">
						<span class="elementor-widget-cmsmasters-search__form-input-icon"><#

							var formInputIcon = settings.form_input_icon;

							iconHTML = elementor.helpers.renderIcon( view, formInputIcon );

							if ( '' !== formInputIcon.value ) {
								if ( 'svg' !== formInputIcon.library ) {
									#><i class="{{{formInputIcon.value}}}"></i><#
								} else {
									#>{{{ iconHTML.value }}}<#
								}
							} else {
								#><i class="fas fa-search"></i><#
							}

						#></span><#
				}

				var $search_placeholder = '';

				if ( '' !== settings.search_placeholder ) {
					$search_placeholder += settings.search_placeholder;
				} else {
					$search_placeholder += 'Search...';
				}

				view.addRenderAttribute( 'search-field', 'placeholder', $search_placeholder );

				#><input {{{ view.getRenderAttributeString( 'search-field' ) }}}><#

				if ( 'yes' === settings.form_input_show_icon ) {
					#></div><#
				}#>

				<?php
				$this->get_submit_button_template();
				?>
			</div>
		</form>
		<?php
			$this->woo_preview_autocomplite();
		?>
		</div>
		<?php
	}
}