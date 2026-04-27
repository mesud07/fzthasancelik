<?php

namespace WPFunnelsPro\Shortcodes;


use WPFunnelsPro\OfferProduct\Wpfnl_Offer_Product;
use WPFunnels\Wpfnl_functions;
use WPFunnelsPro\Wpfnl_Pro_functions;
/**
 * Class Wpfnl_Shortcode_Offer_Description
 * @package WPFunnelsPro\Shortcodes
 */
class Wpfnl_Shortcode_Offer_Button {

    /**
     * Attributes
     *
     * @var array
     */
    protected $attributes = array();


    /**
     * Wpfnl_Shortcode_Order_details constructor.
     * @param array $attributes
     */
    public function __construct( $attributes = array() ) {
        $this->attributes = $this->parse_attributes( $attributes );
    }


    /**
     * Get shortcode attributes.
     *
     * @since  3.2.0
     * @return array
     */
    public function get_attributes() {
        return $this->attributes;
    }


    /**
     * parse attributes
     *
     * @param $attributes
     * @return array
     */
    protected function parse_attributes( $attributes ) {
        $attributes = shortcode_atts(
            array(
                'btn_text' 	                   => '',
				'offer_type'                   => 'upsell',
				'action'                       => 'accept',
				'class'                        => '',
                'dynamic_data_template_layout' => 'style1',
                'show_product_price'           => 'no',
                'variation_tbl_title'          => '',
                'show_product_data'            => 'no',
                'btn_font_size'                => '',
                'btn_margin'                   => '',
                'btn_padding'                  => '',
                'btn_background_color'         => '',
                'btn_color'                    => '',
                'btn_radius'                   => '',
                'btn_width'                    => '',
                'btn_border'                   => '',
                'align'                        => 'center',
                'btn_icon'                     => '',
                'btn_icon_position'            => 'left',
                'btn_font_family'              => '',
                'btn_font_weight'              => '',
                'btn_text_transform'           => '',
                'btn_text_decoration'          => '',
                'btn_letter_spacing'           => '',
                'btn_line_height'              => '',
                'btn_box_shadow'               => '',
                'btn_display'                  => 'inline-block',
                'btn_custom_id'                => '',
                'btn_custom_attrs'             => '',
                'price_class'                  => '',
            ),
            $attributes
        );
        return $attributes;
    }


    /**
     * retrieve offer product description
     */
    public function get_content() {
        if( Wpfnl_functions::check_if_this_is_step_type('upsell') || Wpfnl_functions::check_if_this_is_step_type('downsell')) {
            
            $offer_product = Wpfnl_Offer_Product::getInstance()->get_offer_product();

            ob_start();
            $data        = \WPFunnels\Wpfnl_functions::get_sanitized_get_post();
            $step_id 	= isset($data['post']['current_page']['id']) ? $data['post']['current_page']['id'] : get_the_ID();

            if( isset($step_id) && $step_id ){
                $step_type = get_post_meta($step_id, '_step_type', true);
                $offer_product_data = Wpfnl_Pro_functions::get_offer_product( $step_id, $step_type );
                $offer_product = null;

                if( is_array($offer_product_data) ) {
                    foreach ( $offer_product_data as $pr_index => $pr_data ) {
                        $product_id = $pr_data['id'];
                        $offer_product    = wc_get_product( $product_id );
                        break;
                    }
                }

            }else{
                $offer_product = Wpfnl_Offer_Product::getInstance()->get_offer_product();
            }

            $response = Wpfnl_Pro_functions::get_product_data_for_widget( $step_id );
            $offer_product       = isset($response['offer_product']) && $response['offer_product'] ? $response['offer_product'] : '';
            $get_product_type    = isset($response['get_product_type']) && $response['get_product_type'] ? $response['get_product_type'] : '';
            $is_gbf              = isset($response['is_gbf']) && $response['is_gbf'] ? $response['is_gbf'] : '';
            $builder = 'shortcode';

            $button_style = '';
            $style_props = array();

            if (!empty($this->attributes['btn_font_size'])) {
                $style_props[] = 'font-size: ' . $this->attributes['btn_font_size'];
            }
            if (!empty($this->attributes['btn_margin'])) {
                $style_props[] = 'margin: ' . $this->attributes['btn_margin'];
            }
            if (!empty($this->attributes['btn_padding'])) {
                $style_props[] = 'padding: ' . $this->attributes['btn_padding'];
            }
            if (!empty($this->attributes['btn_background_color'])) {
                $style_props[] = 'background-color: ' . $this->attributes['btn_background_color'];
            }
            if (!empty($this->attributes['btn_color'])) {
                $style_props[] = 'color: ' . $this->attributes['btn_color'];
            }
            if (!empty($this->attributes['btn_radius'])) {
                $style_props[] = 'border-radius: ' . $this->attributes['btn_radius'];
            }
            if (!empty($this->attributes['btn_width'])) {
                $style_props[] = 'width: ' . $this->attributes['btn_width'];
            }
            if (!empty($this->attributes['btn_border'])) {
                $style_props[] = 'border: ' . $this->attributes['btn_border'];
            }
            if (!empty($this->attributes['btn_font_family'])) {
                $style_props[] = 'font-family: ' . $this->attributes['btn_font_family'];
            }
            if (!empty($this->attributes['btn_font_weight'])) {
                $style_props[] = 'font-weight: ' . $this->attributes['btn_font_weight'];
            }
            if (!empty($this->attributes['btn_text_transform'])) {
                $style_props[] = 'text-transform: ' . $this->attributes['btn_text_transform'];
            }
            if (!empty($this->attributes['btn_text_decoration'])) {
                $style_props[] = 'text-decoration: ' . $this->attributes['btn_text_decoration'];
            }
            if (!empty($this->attributes['btn_letter_spacing'])) {
                $style_props[] = 'letter-spacing: ' . $this->attributes['btn_letter_spacing'];
            }
            if (!empty($this->attributes['btn_line_height'])) {
                $style_props[] = 'line-height: ' . $this->attributes['btn_line_height'];
            }
            if (!empty($this->attributes['btn_box_shadow'])) {
                $style_props[] = 'box-shadow: ' . $this->attributes['btn_box_shadow'];
            }
            if (!empty($this->attributes['btn_display'])) {
                $style_props[] = 'display: ' . $this->attributes['btn_display'];
            }

            if (!empty($this->attributes['align'])) {
                $style_props[] = 'text-align: ' . $this->attributes['align'];
            }

            if (!empty($style_props)) {
                $button_style = ' style="' . implode('; ', $style_props) . '"';
            }

            $custom_attrs  = !empty($this->attributes['btn_custom_attrs']) ? $this->attributes['btn_custom_attrs'] : '';
            $icon_html     = '';
            $btn_text      = $this->render_text($this->attributes['btn_text'] ? $this->attributes['btn_text'] : 'Accept');

            // Icon support
            if (!empty($this->attributes['btn_icon'])) {
                $icon_position = $this->attributes['btn_icon_position'] === 'right' ? 'right' : 'left';
                $icon_img      = '<img src="' . esc_url($this->attributes['btn_icon']) . '" alt="icon" class="btn-icon ' . $icon_position . '">';
                $icon_html     = ($icon_position === 'left') ? $icon_img . $btn_text : $btn_text . $icon_img;
            } else {
                $icon_html = $btn_text;
            }

            if( 'yes' === $is_gbf && isset($this->attributes['show_product_data']) &&  'yes' === $this->attributes['show_product_data'] && 'accept' === $this->attributes['action'] ){
                require WPFNL_PRO_DIR . 'public/modules/dynamic-offer-templates/styles/offer-'.$this->attributes['dynamic_data_template_layout'].'.php';
            }else{
                require WPFNL_PRO_DIR . 'public/modules/dynamic-offer-templates/shortcode/offer-button.php';
            }
            return ob_get_clean();
        }
        return false;
    }

    public function render_text( $text ){
        $html = '';
        $html .= '<span>';
        $html .= '<span>'.$text.'</span>';
        $html .= '</span>';
        return $html;
    }
}