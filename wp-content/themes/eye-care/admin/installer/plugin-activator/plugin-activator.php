<?php
namespace EyeCareSpace\Admin\Installer\Plugin_Activator;

use EyeCareSpace\Core\Utils\API_Requests;
use EyeCareSpace\Core\Utils\Utils;
use EyeCareSpace\Core\Utils\Logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Plugin Activator.
 *
 * Main class for plugin activator.
 */
class Plugin_Activator {

	/**
	 * Plugin_Activator constructor.
	 */
	public function __construct() {
		// Include the TGM_Plugin_Activation class.
		require_once get_template_directory() . '/admin/installer/plugin-activator/class-tgm-plugin-activation.php';

		add_action( 'tgmpa_register', array( $this, 'tgmpa_run' ) );

		add_action( 'cmsmasters_remove_temp_data', array( $this, 'remove_plugins_list' ) );

		add_filter( 'acf/settings/show_updates', '__return_false', 100 );
	}

	/**
	 * Run TGMPA.
	 */
	public function tgmpa_run() {
		$plugins_list = $this->get_plugins_list();

		if ( empty( $plugins_list ) ) {
			$plugins_list = $this->set_plugins_list();
		}

		$config = $this->get_config();

		tgmpa( $plugins_list, $config );
	}

	/**
	 * Get plugins list.
	 */
	public function get_plugins_list() {
		return get_transient( 'cmsmasters_plugins_list' );
	}

	/**
	 * Remove plugins list.
	 */
	public function remove_plugins_list() {
		delete_transient( 'cmsmasters_plugins_list' );
	}

	/**
	 * Set plugins list.
	 */
	public function set_plugins_list() {
		$plugins_list = $this->get_api_plugins();

		set_transient( 'cmsmasters_plugins_list', $plugins_list, DAY_IN_SECONDS );

		return $plugins_list;
	}

	/**
	 * Get plugins list from API.
	 *
	 * @return array Plugins list.
	 */
	private function get_api_plugins() {
		if ( API_Requests::is_empty_token_status() ) {
			return array();
		}

		$data = API_Requests::post_request( 'get-plugins-list', array( 'demo' => Utils::get_demo() ) );

		if ( is_wp_error( $data ) ) {
			Logger::error( $data->get_error_message() );

			return array();
		}

		if ( empty( $data ) || ! is_array( $data ) ) {
			return array();
		}

		return $data;
	}

	/**
	 * Get configuration settings list.
	 *
	 * @return array Configuration settings list.
	 */
	private function get_config() {
		return array(
			'id' => 'eye-care', // Unique ID for hashing notices for multiple instances of TGMPA.
			'menu' => 'tgmpa-install-plugins', // Menu slug.
			'has_notices' => true, // Show admin notices or not.
			'dismissable' => true, // If false, a user cannot dismiss the nag message.
			'is_automatic' => false, // Automatically activate plugins after installation or not.
		);
	}

}
