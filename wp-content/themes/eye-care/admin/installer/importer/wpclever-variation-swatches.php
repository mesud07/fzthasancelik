<?php
namespace EyeCareSpace\Admin\Installer\Importer;

use EyeCareSpace\Admin\Installer\Importer\WPClever_Importer_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPClever_Variation_Swatches handler class is responsible for different methods on importing "WPClever Variation Swatches" plugin.
 */
class WPClever_Variation_Swatches extends WPClever_Importer_Base {

	/**
	 * Module data.
	 */
	const MODULE_NAME = 'variation-swatches';
	const MODULE_OPTION_NAME = 'wpcvs_settings';

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return class_exists( 'WPCleverWpcvs' );
	}

}
