<?php
namespace EyeCareSpace\Admin\Installer\Importer;

use EyeCareSpace\Admin\Installer\Importer\WPClever_Importer_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPClever_Smart_Quick_View handler class is responsible for different methods on importing "WPClever Smart Quick View" plugin.
 */
class WPClever_Smart_Quick_View extends WPClever_Importer_Base {

	/**
	 * Module data.
	 */
	const MODULE_NAME = 'smart-quick-view';
	const MODULE_OPTION_NAME = 'woosq_settings';

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return class_exists( 'WPCleverWoosq' );
	}

}
