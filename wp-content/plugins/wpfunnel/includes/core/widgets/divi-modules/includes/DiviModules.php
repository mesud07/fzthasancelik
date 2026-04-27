<?php

namespace WPFunnelsPro\Widgets\DiviModules;


use DiviExtension;
use WPFunnels\Wpfnl_functions;
use ET_Post_Stack;
use WPFunnelsPro\Widgets\DiviModules\Modules\WPFNL_Lms_Pay_Button;
use WPFunnelsPro\Widgets\DiviModules\Modules\WPFNL_Offer_Button;

class WPFNL_DiviModules extends DiviExtension {

	private static $instance;

	public static function get_instance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'wpfnl-pro';


	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'divi-modules';


	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * WPFNL_DiviModules constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct() {
		add_action('et_builder_ready', [$this, 'load_modules'], 9);
    }

	public function load_modules()
	{
		$post_id = $this->get_current_post_id();
		$post_type = get_post_type($post_id);
		$step_type = get_post_meta($post_id, '_step_type', true);

		if (WPFNL_STEPS_POST_TYPE !== $post_type) {
			return;
		}

		add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_builder_scripts'));

		if ('upsell' === $step_type || 'downsell' === $step_type) {
			new WPFNL_Offer_Button;
			new WPFNL_Lms_Pay_Button;
		}
	}

	private function get_current_post_id()
	{
		if (wp_doing_ajax() && $this->array_get($_POST, 'current_page.id')) {
			return absint($this->array_get($_POST, 'current_page.id'));
		}

		if (wp_doing_ajax() && isset($_POST['et_post_id'])) {
			return absint($_POST['et_post_id']);
		}

		if (isset($_POST['post'])) {
			return absint($_POST['post']);
		}

		if (self::_should_respect_post_interference()) {
			return get_the_ID();
		}

		return ET_Post_Stack::get_main_post_id();
	}

	/**
	 * Retrieve a value from a nested array using a dot-separated address.
	 *
	 * @param array  $array   The array to search.
	 * @param string $address The dot-separated address of the value.
	 * @param mixed  $default The default value to return if the address is not found.
	 *
	 * @return mixed The value found at the address, or the default value.
	 * @since 3.5.11
	 */
	public function array_get($array, $address, $default = '')
	{
		$keys   = is_array($address) ? $address : explode('.', $address);
		$value  = $array;

		foreach ($keys as $key) {
			if (! empty($key) && isset($key[0]) && '[' === $key[0]) {
				$index = substr($key, 1, -1);

				if (is_numeric($index)) {
					$key = (int) $index;
				}
			}

			if (! isset($value[$key])) {
				return $default;
			}

			$value = $value[$key];
		}

		return $value;
	}

	/**
	 * Determine if post interference should be respected.
	 *
	 * This function checks if the current post ID should be respected
	 * based on the post stack and the current post ID.
	 *
	 * @return bool True if post interference should be respected, false otherwise.
	 * @since 3.5.11
	 */
	protected static function _should_respect_post_interference()
	{
		$post = ET_Post_Stack::get();
		return null !== $post && get_the_ID() !== $post->ID;
	}

	/**
	 * Enqueue frontend scripts and styles for the Divi modules.
	 *
	 * This function enqueues the necessary JavaScript and CSS files
	 * for the frontend of the Divi modules.
	 *
	 * @return void
	 * @since 3.5.11
	 */
	public function enqueue_frontend_scripts()
	{
		wp_enqueue_script(
			'wpfnl-divi-frontend',
			WPFNL_DIR_URL . 'includes/core/widgets/divi-modules/scripts/frontend.js',
			array('jquery'),
			WPFNL_VERSION,
			true
		);
		wp_enqueue_script(
			'wpfnl-divi-frontend-bundle',
			WPFNL_DIR_URL . 'includes/core/widgets/divi-modules/scripts/frontend-bundle.min.js',
			array('jquery'),
			WPFNL_VERSION,
			true
		);

		// Modules CSS
		$styles = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		$styles_url = WPFNL_DIR_URL . "includes/core/widgets/divi-modules/styles/{$styles}.min.css";
		wp_enqueue_style(
			'wpfnl-divi-frontend-styles',
			$styles_url,
			array(),
			WPFNL_VERSION,
			'all'
		);
	}

	/**
	 * Enqueue builder scripts and styles for the Divi modules.
	 *
	 * This function enqueues the necessary JavaScript files
	 * for the builder interface of the Divi modules.
	 *
	 * @return void
	 * @since 3.5.11
	 */
	public function enqueue_builder_scripts()
	{
		$bundle_url = WPFNL_DIR_URL . "includes/core/widgets/divi-modules/scripts/builder-bundle.min.js";
		wp_enqueue_script(
			"{$this->name}-builder-bundle",
			$bundle_url,
			array('react-dom', 'wpfnl-divi-frontend-bundle'),
			WPFNL_VERSION,
			true
		);
	}
}
