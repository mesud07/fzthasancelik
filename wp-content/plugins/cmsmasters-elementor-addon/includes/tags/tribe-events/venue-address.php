<?php
namespace CmsmastersElementor\Tags\TribeEvents;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Tags\TribeEvents\Traits\Tribe_Events_Group;

use Elementor\Core\DynamicTags\Tag;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters venue address.
 *
 * Retrieve the venue address.
 *
 * @since 1.13.0
 */
class Venue_Address extends Tag {

	use Base_Tag, Tribe_Events_Group;

	/**
	* Get tag name.
	*
	* Returns the name of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return string Tag name.
	*/
	public static function tag_name() {
		return 'venue-address';
	}

	/**
	* Get tag venue address.
	*
	* Returns the venue address of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return string Tag venue address.
	*/
	public static function tag_title() {
		return __( 'Venue Address', 'cmsmasters-elementor' );
	}

	/**
	* Tag render.
	*
	* Prints out the value of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return void Tag render result.
	*/
	public function render() {
		$event_data = tribe_get_event();

		if ( ! $event_data ) {
			return;
		}

		$venue = $event_data->venues[0];

		$address = $venue->address;

		if ( $address ) {
			echo wp_kses_post( $address );
		}
	}

}
