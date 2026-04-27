<?php
namespace CmsmastersElementor\Modules\TemplateSections;

use CmsmastersElementor\Base\Base_Module;
use CmsmastersElementor\Modules\TemplateDocuments\Module as DocumentsModule;
use CmsmastersElementor\Modules\TemplatePages\Module as PagesModule;
use CmsmastersElementor\Modules\TemplateSections\Documents;
use CmsmastersElementor\Modules\TemplateSections\Components\Megamenu_Options;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * CMSMasters template sections module.
 *
 * CMSMasters template documents module handler class is responsible for
 * registering and managing Elementor templates library document types.
 *
 * @since 1.0.0
 */
class Module extends Base_Module {

	/**
	 * Get module name.
	 *
	 * Retrieve the CMSMasters template documents module name.
	 *
	 * @since 1.0.0
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'template-sections';
	}

	/**
	 * Check if module is active.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function is_active() {
		return class_exists( DocumentsModule::class ) && class_exists( PagesModule::class );
	}

	/**
	 * Get widgets.
	 *
	 * Retrieve the modules widgets.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_widgets() {
		return array(
			'Site_Logo',
			'Nav_Menu',
			'Off_Canvas',
			'Search',
			// 'Search_Advanced',
			'Breadcrumbs',
		);
	}

	/**
	 * Module class constructor.
	 *
	 * @since 1.11.0
	 */
	public function __construct() {
		$this->add_component( 'megamenu_options', new Megamenu_Options() );

		parent::__construct();
	}

	/**
	 * Add filters initialization.
	 *
	 * Register filters for the Template Sections module.
	 *
	 * @since 1.0.0
	 */
	protected function init_filters() {
		// Common
		add_filter( 'cmsmasters_elementor/documents/set_document_types', array( $this, 'set_document_types' ) );
		add_filter( 'cmsmasters_elementor/documents/set_elementor_documents', array( $this, 'set_elementor_documents' ) );

		// Admin
		add_filter( 'cmsmasters_elementor/admin/settings', array( $this, 'filter_admin_settings' ) );

		// Frontend
		add_filter( 'cmsmasters_elementor/frontend/settings', array( $this, 'filter_frontend_settings' ) );
	}

	/**
	 * Add actions initialization.
	 *
	 * Register actions for the Template Sections module.
	 *
	 * @since 1.11.12
	 */
	protected function init_actions() {
		add_action( 'elementor/documents/register_controls', array( $this, 'register_container_document_controls' ) );
	}

	public function set_document_types( $document_types ) {
		$module_document_types = array(
			'cmsmasters_header' => Documents\Header::get_class_full_name(),
			'cmsmasters_footer' => Documents\Footer::get_class_full_name(),
			'section' => Documents\Elementor\Section::get_class_full_name(),
		);

		$document_types = array_merge( $document_types, $module_document_types );

		return $document_types;
	}

	public function set_elementor_documents( $elementor_documents ) {
		$elementor_documents[] = 'section';

		return $elementor_documents;
	}

	/**
	 * Filter admin settings.
	 *
	 * @since 1.11.0
	 *
	 * @param array $settings Settings.
	 *
	 * @return array Filtered settings.
	 */
	public function filter_admin_settings( $settings ) {
		$settings = array_replace_recursive( $settings, array(
			'i18n' => array(
				'megamenu' => array(
					'mega_menu' => __( 'Mega Menu', 'cmsmasters-elementor' ),
					'column' => __( 'Column', 'cmsmasters-elementor' ),
				),
			),
		) );

		return $settings;
	}

	/**
	 * Filter frontend settings.
	 *
	 * Filters the Addon settings for elementor frontend.
	 *
	 * Fired by `cmsmasters_elementor/frontend/settings` Addon filter hook.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Frontend settings.
	 *
	 * @return array Filtered frontend settings.
	 */
	public function filter_frontend_settings( $settings ) {
		$settings = array_replace_recursive( $settings, array(
			'i18n' => array(
				'saved_section' => __( 'Section Template', 'cmsmasters-elementor' ),
				'saved_page' => __( 'Page Template', 'cmsmasters-elementor' ),
			),
		) );

		return $settings;
	}

	/**
	 * Register controls for container document.
	 *
	 * @since 1.11.12
	 *
	 * @param object $document Document object.
	 */
	public function register_container_document_controls( $document ) {
		if ( 'container' !== $document->get_name() ) {
			return;
		}

		$document->start_injection( array(
			'of' => 'post_status',
			'fallback' => array( 'of' => 'post_title' ),
		) );

		$document->add_control(
			'cmsmasters_document_export_id',
			array(
				'label' => __( 'Template Document ID', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => $document->get_main_id(),
				'export' => true,
				'classes' => 'elementor-control-type-hidden',
			)
		);

		$document->add_control(
			'cmsmasters_document_export_url',
			array(
				'label' => __( 'Template Site URL', 'cmsmasters-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => get_site_url(),
				'export' => true,
				'classes' => 'elementor-control-type-hidden',
			)
		);

		$document->end_injection();
	}

}
