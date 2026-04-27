<?php
namespace CmsmastersElementor\Modules\EntranceAnimation;

use CmsmastersElementor\Base\Base_Module;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Entrance Animation module.
 *
 * @since 1.0.0
 */
class Module extends Base_Module {

	public function get_name() {
		return 'entrance-animation';
	}

	protected function init_filters() {
		add_filter( 'elementor/controls/animations/additional_animations', array( $this, 'extend_entrance_animations' ) );
	}

	public function extend_entrance_animations( $additional_animations ) {
		$animations = array_merge(
			$additional_animations,
			array(
				esc_html__( 'CMSMasters Fading', 'cmsmasters-elementor' ) => array(
					'cmsmasters-fade-in' => esc_html__( 'Fade In (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-fade-in-down' => esc_html__( 'Fade In Down (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-fade-in-left' => esc_html__( 'Fade In Left (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-fade-in-right' => esc_html__( 'Fade In Right (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-fade-in-up' => esc_html__( 'Fade In Up (CMS)', 'cmsmasters-elementor' ),
				),
				esc_html__( 'CMSMasters Popping', 'cmsmasters-elementor' ) => array(
					'cmsmasters-pop-in' => esc_html__( 'Pop In (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-pop-in-down' => esc_html__( 'Pop In Down (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-pop-in-left' => esc_html__( 'Pop In Left (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-pop-in-right' => esc_html__( 'Pop In Right (CMS)', 'cmsmasters-elementor' ),
					'cmsmasters-pop-in-up' => esc_html__( 'Pop In Up (CMS)', 'cmsmasters-elementor' ),
				)
			)
		);

		return $animations;
	}

}