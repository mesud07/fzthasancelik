<?php
namespace EyeCareSpace\Admin\Installer\Importer;

use EyeCareSpace\Admin\Installer\Importer\WPClever_Importer_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPClever_Smart_Wishlist handler class is responsible for different methods on importing "WPClever Smart Wishlist" plugin.
 */
class WPClever_Smart_Wishlist extends WPClever_Importer_Base {

	/**
	 * Module data.
	 */
	const MODULE_NAME = 'smart-wishlist';
	const MODULE_OPTION_NAME = 'woosw_settings';

	/**
	 * Activation status.
	 *
	 * @return bool Activation status.
	 */
	public static function activation_status() {
		return class_exists( 'WPCleverWoosw' );
	}

}
