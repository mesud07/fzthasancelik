<?php
namespace CmsmastersElementor\Tags\TribeEvents;

use CmsmastersElementor\Base\Traits\Base_Tag;
use CmsmastersElementor\Tags\TribeEvents\Traits\Tribe_Events_Group;

use Elementor\Core\DynamicTags\Tag;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * CMSMasters organizer website.
 *
 * Retrieve the organizer website.
 *
 * @since 1.13.0
 */
class Organizer_Website extends Tag {

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
		return 'organizer-website';
	}

	/**
	* Get tag organizer website.
	*
	* Returns the organizer website of the dynamic tag.
	*
	* @since 1.13.0
	*
	* @return string Tag organizer website.
	*/
	public static function tag_title() {
		return __( 'Organizer Website', 'cmsmasters-elementor' );
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

		$website = $organizer->website;

		if ( $website ) {
			echo wp_kses_post( $website );
		}
	}

}
