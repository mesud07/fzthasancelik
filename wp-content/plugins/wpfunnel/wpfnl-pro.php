<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://getwpfunnels.com
 * @since             1.0.0
 * @package           Wpfnl_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       WPFunnels Pro
 * Plugin URI:        https://getwpfunnels.com
 * Description:       Get advanced WPFunnels features such as 🔥 one-click upsell 🔥, premium funnel templates, custom steps, detailed analytics, and many more.
 * Version:           2.5.8
 * Author:            WPFunnels Team
 * Author URI:        https://getwpfunnels.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpfnl-pro
 * Domain Path:       /languages
 */
update_site_option('wpfunnels_pro_license_key', 'GPL001122334455AA6677BB8899CC000');
update_site_option('wpfunnels_pro_licence_data', ['key' => 'GPL001122334455AA6677BB8899CC000', 'last_check' => time(), 'start_date' => time(), 'end_date' => '27.06.2052',] );
update_site_option('wpfunnels_pro_license_status', 'active' );
update_site_option('wpfunnels_pro_is_premium', 'yes' );
// If this file is called directly, abort.
use WPFunnelsPro\Wpfnl_Pro;
use WPFunnelsPro\Wpfnl_Pro_Dependency;
use WPFunnelsPro\Wpfnl_Pro_Updater;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPFNL_PRO_VERSION', '2.5.8' );

if ( ! defined( 'WPFNL_SECURITY_KEY' ) ) {
	define( 'WPFNL_SECURITY_KEY', get_option( '_wpfnl_security_key', '' ) );
}

define( 'WPFNL_PRO_FILE', __FILE__ );
define( 'WPFNL_PRO_BASE', plugin_basename( WPFNL_PRO_FILE ) );
define( 'WPFNL_PRO_DIR', plugin_dir_path( WPFNL_PRO_FILE ) );
define( 'WPFNL_PRO_URL', plugins_url( '/', WPFNL_PRO_FILE ) );
define( 'WPFNL_PRO_DIR_URL', plugin_dir_url( WPFNL_PRO_FILE ) );
define( 'WPFNL_PRO_DB_VERSION', '1.0' );
define( 'WPFNL_PRO_ANALYTICS_TABLE', 'wpfnl_analytics' );
define( 'WPFNL_PRO_ANALYTICS_META_TABLE', 'wpfnl_analytics_meta' );
define( 'WPFNL', '/wpfunnels/wpfnl.php' );
define( 'WPFNL_REQUIRED_VERSION', '2.1.9' );
define( 'WPFNL_AB_TESTING_COOKIE_KEY', 'wpfnl_ab_testings_' );

$protocol = ( ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] ) || ( ! empty( $_SERVER['SERVER_PORT'] ) && 443 === $_SERVER['SERVER_PORT'] ) ) ? 'https://' : 'http://';
define( 'WPFNL_PRO_INSTANCE', str_replace( $protocol, '', get_bloginfo( 'wpurl' ) ) );

// License middleman api url.
define( 'WPFNL_PRO_LICENSE_URL', 'https://license.getwpfunnels.com/api/v1/licence' );

// The url where the WooCommerce Software License plugin is being installed.
define( 'WPFNL_PRO_API_URL', 'https://useraccount.getwpfunnels.com/' );

// The Software Unique ID as defined within product admin page.
define( 'WPFNL_PRO_PRODUCT_ID', 'wpf' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpfnl-pro-activator.php
 */
function activate_wpfnl_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/utils/class-wpfnl-pro-activator.php';
	Wpfnl_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpfnl-pro-deactivator.php
 */
function deactivate_wpfnl_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/utils/class-wpfnl-pro-deactivator.php';
	Wpfnl_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpfnl_pro' );
register_deactivation_hook( __FILE__, 'deactivate_wpfnl_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpfnl-pro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpfnl_pro() {

	/**
	 * Deactivate webhook addon if activated.
	 *
	 * @since 1.3.2
	 */
	if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	if ( is_plugin_active( 'wpfunnels-pro-webhook/wpfunnels-pro-webhook.php' ) ) {
		Wpfnl_Pro_Dependency::deactivate_self( 'wpfunnels-pro-webhook/wpfunnels-pro-webhook.php' );
	}

	$installed_plugins = get_plugins();
	$dependency        = new Wpfnl_Pro_Dependency( 'wpfunnels/wpfnl.php', WPFNL_PRO_FILE, '2.4.7', 'wpfnl-pro' );

	if ( ! isset( $installed_plugins['wpfunnels/wpfnl.php'] ) || ! is_plugin_active( 'wpfunnels/wpfnl.php' ) ) {
		$is_active = $dependency->is_active();

		// If the plugin is not installed or not active, deactivate the pro plugin.
		if ( ! isset( $installed_plugins['wpfunnels/wpfnl.php'] ) || ! $is_active ) {
			Wpfnl_Pro_Dependency::deactivate_self( 'wpfunnels-pro/wpfnl-pro.php' );
		}
	}
	$plugin = new Wpfnl_Pro();
	$plugin->run();
}

run_wpfnl_pro();


/**
 * Redirect after plugin activation.
 */
function wpfnl_pro_redirect() {
	if ( get_option( 'wpfunnels_pro_do_activation_redirect', false ) ) {
		delete_option( 'wpfunnels_pro_do_activation_redirect' );

		// On these pages, or during these events, postpone the redirect.
		$do_redirect = true;
		if ( wp_doing_ajax() || is_network_admin() ) {
			$do_redirect = false;
		}
		if ( $do_redirect ) {
			wp_safe_redirect( 'admin.php?page=wpf-license' );
		}
	}
}
add_action( 'admin_init', 'wpfnl_pro_redirect' );

/**
 * Fires after the theme is loaded.
 *
 * @since 1.0.0
 */
function wpfnl_pro_run_updater() {
	new Wpfnl_Pro_Updater( WPFNL_PRO_API_URL, 'wpfunnels-pro', 'wpfunnels-pro/wpfnl-pro.php' );
}

add_action( 'after_setup_theme', 'wpfnl_pro_run_updater' );

/**
 * Add custom step data to the step.
 *
 * @param int $step_id The step id.
 *
 * @return array
 *
 * @since 1.0.0
 */
function wpf_get_total_visit( $step_id ) {
	global $wpdb;
	$analytics_db      = $wpdb->prefix . WPFNL_PRO_ANALYTICS_TABLE;
	$analytics_meta_db = $wpdb->prefix . WPFNL_PRO_ANALYTICS_META_TABLE;
	$analytics_columns = array(
		'step_id'      => 'wpft1.step_id',
		'total_visits' => 'COUNT( DISTINCT( wpft1.id ) ) AS total_visits',
		'conversion'   => "COUNT( CASE WHEN wpft2.meta_key = 'conversion' AND wpft2.meta_value = 'yes' THEN wpft1.step_id ELSE NULL END ) AS conversions ",
	);

	$cache_key_visits = 'wpfnl_pro_visits_' . $step_id;
	$visits_data      = wp_cache_get( $cache_key_visits, 'wpfnl_pro' );

	if ( false === $visits_data ) {
		$query = $wpdb->prepare(
			"SELECT {$analytics_columns['step_id']}, COUNT( DISTINCT( wpft1.id ) ) AS total_visits
			FROM {$analytics_db} as wpft1
			WHERE wpft1.step_id = %s
			ORDER BY NULL",
			$step_id
		); // phpcs:ignore

		$visits_data = $wpdb->get_row( $query ); // phpcs:ignore
		wp_cache_set( $cache_key_visits, $visits_data, 'wpfnl_pro', 3600 ); // Cache for 1 hour.
	}

	$cache_key_conversion = 'wpfnl_pro_conversion_' . $step_id;
	$conversion_data      = wp_cache_get( $cache_key_conversion, 'wpfnl_pro' );

	if ( false === $conversion_data ) {
		$query = $wpdb->prepare(
			"SELECT {$analytics_columns['step_id']}, COUNT( CASE WHEN wpft2.meta_key = 'conversion' AND wpft2.meta_value = 'yes' THEN wpft1.step_id ELSE NULL END ) AS conversions
			FROM {$analytics_db} as wpft1
			INNER JOIN {$analytics_meta_db} as wpft2 ON wpft1.id = wpft2.analytics_id
			WHERE wpft1.step_id = %s
			ORDER BY NULL",
			$step_id
		); // phpcs:ignore

		$conversion_data = $wpdb->get_row( $query ); // phpcs:ignore
		wp_cache_set( $cache_key_conversion, $conversion_data, 'wpfnl_pro', 3600 ); // Cache for 1 hour.
	}

	$total = array(
		'visit'      => 0,
		'conversion' => 0,
	);
	if ( $visits_data ) {
		$total['visit'] = $visits_data->total_visits;
	}

	if ( $conversion_data ) {
		$total['conversion'] = $conversion_data->conversions;
	}

	return $total;
}

/**
 * Returns the step data.
 *
 * @param  string $step The step data.
 * @param  int    $step_id The step id.
 *
 * @return array
 *
 * @access public
 *
 * @since 1.0.0
 */
function wpfunnels_step_data( $step, $step_id ) {
	$total                     = wpf_get_total_visit( $step_id );
	$step['visit']             = $total['visit'];
	$step['conversion']        = $total['conversion'];
	$controllers['automation'] = 'AutomationController';
	$controllers['mint']       = 'MintController';

	return $step;
}
add_filter( 'wpfunnels/step_data', 'wpfunnels_step_data', 10, 2 );


/**
 * Get all the controllers.
 * Controllers are the classes that handle the API requests.
 *
 * @param  array $controllers The controllers array.
 *
 * @return array
 *
 * @since 2.0.0
 */
function wpfunnels_add_analytics_controller( $controllers ) {
	$controllers['analytics']    = 'AnalyticsController';
	$controllers['offer']        = 'OfferController';
	$controllers['automation']   = 'AutomationController';
	$controllers['webhook']      = 'WebhookController';
	$controllers['abtesting']    = 'AbTestingController';
	$controllers['mint']         = 'MintController';
	$controllers['importexport'] = 'ImportExportController';
	return $controllers;
}
add_filter( 'wpfunnels/rest_api_controllers', 'wpfunnels_add_analytics_controller' );


/**
 * Register the custom post type to filter shop orders by WPFunnels funnel ID.
 *
 * This function modifies the query for the 'edit.php' page when viewing 'shop_order' post type.
 * It adds a meta query to filter the shop orders by the '_wpfunnels_funnel_id' meta key,
 * using the 'id' parameter passed via the GET request.
 *
 * @param WP_Query $query The current query object.
 *
 * @return void
 */
function wpfunnel_order_query( $query ) {
	global $pagenow;
	$type = 'shop_order';

	// Check if a specific post type is requested.
	if ( isset( $_GET['post_type'] ) ) { // phpcs:ignore
		$type = wp_unslash( sanitize_text_field( $_GET['post_type']  )); // phpcs:ignore
	}

	// Apply the custom query only when viewing 'shop_order' post type in the admin 'edit.php' page.
	if ( 'shop_order' === $type && is_admin() && 'edit.php' === $pagenow && isset( $_GET['id'] ) ) { // phpcs:ignore
		// Add a meta query to filter the shop orders by the '_wpfunnels_funnel_id' meta key.
		$query->query_vars['meta_key']   = '_wpfunnels_funnel_id'; // phpcs:ignore
		$query->query_vars['meta_value'] = wp_unslash( sanitize_text_field( $_GET['id'] ) ); // phpcs:ignore
	}
}
add_filter( 'parse_query', 'wpfunnel_order_query' );
