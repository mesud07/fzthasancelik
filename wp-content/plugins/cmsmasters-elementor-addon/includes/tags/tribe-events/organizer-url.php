<?php
namespace CmsmastersElementor\Tags\TribeEvents;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Tags\TribeEvents\Traits\Tribe_Events_Group;

use Elementor\Core\DynamicTags\Tag;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters organizer url.
 *
 * Retrieve the organizer url.
 *
 * @since 1.13.0
 */
class Organizer_Url extends Tag {

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
		return 'organizer-url';
	}

	/**
	* Get tag organizer url.
	*
	* Returns the organizer url of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return string Tag organizer url.
	*/
	public static function tag_title() {
		return __( 'Organizer Url', 'cmsmasters-elementor' );
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

		$organizer = $event_data->organizers[0];

		if ( ! $organizer ) {
			return;
		}

		$permalink = $organizer->permalink;

		if ( $permalink ) {
			echo wp_kses_post( $permalink );
		}
	}

}
