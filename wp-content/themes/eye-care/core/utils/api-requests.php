<?php
namespace EyeCareSpace\Core\Utils;

use EyeCareSpace\Core\Utils\File_Manager;
use EyeCareSpace\ThemeConfig\Theme_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * API Requests handler class is responsible for different utility methods.
 */
class API_Requests {

	/**
	 * Check token status.
	 *
	 * @return bool true if token is valid and false if token is invalid.
	 */
	public static function check_token_status() {
		return get_option( 'cmsmasters_eye-care_token_status', 'invalid' ) === 'valid';
	}

	public static function is_empty_token_status() {
		return empty( get_option( 'cmsmasters_eye-care_token_status' ) );
	}

	/**
	 * CMSMasters API GET request.
	 *
	 * @param string $route API route.
	 * @param array $args request args.
	 *
	 * @return object API response.
	 */
	public static function get_request( $route, $args = array() ) {
		$cache_key = 'cmsmasters_eye-care_cached_request_' . $route . '_' . md5( serialize( $args ) );
		$cached_response = get_transient( $cache_key );

		if ( $cached_response ) {
			return $cached_response;
		}

		$args = wp_parse_args( $args, array(
			'product_key' => Theme_Config::PRODUCT_KEY,
		) );

		$response = wp_remote_get(
			CMSMASTERS_API_ROUTES_URL . $route,
			array(
				'body' => $args,
				'timeout' => 60,
			)
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new \WP_Error( $route, $route . ': ' . wp_remote_retrieve_response_message( $response ) );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		$data = $response_body['data'];

		set_transient( $cache_key, $data, 300 );

		return $data;
	}

	/**
	 * CMSMasters API POST request.
	 *
	 * @param string $route API route.
	 * @param array $args request args.
	 *
	 * @return mixed Json decode response body data.
	 */
	public static function post_request( $route, $args = array() ) {
		if ( self::is_empty_token_status() ) {
			return false;
		}

		$cache_key = 'cmsmasters_eye-care_cached_request_' . $route . '_' . md5( serialize( $args ) );
		$cached_response = get_transient( $cache_key );

		if ( $cached_response ) {
			return $cached_response;
		}

		if ( ! self::check_token_status() ) {
			if ( ! self::regenerate_token() ) {
				return false;
			}
		}

		$args = wp_parse_args( $args, array(
			'product_key' => Theme_Config::PRODUCT_KEY,
		) );

		$response = wp_remote_post(
			CMSMASTERS_API_ROUTES_URL . $route,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . get_option( 'cmsmasters_eye-care_token', 'invalid' ),
				),
				'body' => $args,
				'timeout' => 120,
			)
		);

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( 'regenerated' !== get_transient( 'cmsmasters_eye-care_token_regeneration_status' ) ) {
				self::regenerate_token();

				return self::post_request( $route, $args );
			} else {
				return new \WP_Error( $route, $route . ': ' . wp_remote_retrieve_response_message( $response ) );
			}
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		$data = $response_body['data'];

		set_transient( $cache_key, $data, 300 );

		return $data;
	}

	/**
	 * Regenerate token.
	 *
	 * @param bool $die Run wp_send_json or return false if invalid data.
	 */
	public static function regenerate_token( $die = false ) {
		if ( 'regenerated' === get_transient( 'cmsmasters_eye-care_token_regeneration_status' ) ) {
			if ( ! $die ) {
				return false;
			}

			wp_send_json_error( esc_html__( 'Token not regenerated. The token was regenerated earlier.', 'eye-care' ), 403 );
		}

		$token_data = self::get_token_data( $die );

		$response = wp_remote_post(
			CMSMASTERS_API_ROUTES_URL . 'regenerate-token',
			array(
				'body' => $token_data,
				'timeout' => 60,
			)
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );
		$response_code = wp_remote_retrieve_response_code( $response );

		set_transient( 'cmsmasters_eye-care_token_regeneration_status', 'regenerated', 1200 );

		if ( 200 !== $response_code ) {
			if ( isset( $response_body['data']['code'] ) && 'regenerate_token__invalid_license_code' === $response_body['data']['code'] ) {
				Logger::error( 'Invalid license code. Please re-register your license.' );

				self::delete_token_data();
			}

			if ( ! self::is_empty_token_status() ) {
				update_option( 'cmsmasters_eye-care_token_status', 'invalid' );
			}

			if ( ! $die ) {
				return false;
			}

			wp_send_json_error( $response_body, $response_code );
		}

		update_option( 'cmsmasters_eye-care_token', $response_body['data']['token'] );
		update_option( 'cmsmasters_eye-care_token_status', 'valid' );

		do_action( 'cmsmasters_remove_temp_data' );

		return true;
	}

	/**
	 * Generate token.
	 *
	 * @param array $args Arguments.
	 */
	public static function generate_token( $args = array() ) {
		$current_user = wp_get_current_user();

		$args['admin_email'] = $current_user->user_email;

		if ( ! empty( $args['user_email'] ) && false === is_email( $args['user_email'] ) ) {
			wp_send_json(
				array(
					'success' => false,
					'code' => 'invalid_email',
					'error_field' => 'email',
					'message' => esc_html__( 'Oops, looks like you made a mistake with the email address', 'eye-care' ),
				)
			);
		}

		$args['domain'] = home_url();
		$args['product_key'] = Theme_Config::PRODUCT_KEY;

		$response = wp_remote_post(
			CMSMASTERS_API_ROUTES_URL . 'generate-token',
			array(
				'body' => $args,
				'timeout' => 60,
			)
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			wp_send_json(
				array(
					'success' => false,
					'error_field' => 'license_key',
					'message' => $response_body['data']['message'],
				)
			);
		}

		$token_data = array(
			'user' => $response_body['data']['user'],
			'user_name' => $args['user_name'],
			'user_email' => $args['user_email'],
			'source_code' => $args['source_code'],
			'purchase_code' => $args['purchase_code'],
			'envato_elements_token' => $args['envato_elements_token'],
		);

		update_option( 'cmsmasters_eye-care_token_data', $token_data );
		update_option( 'cmsmasters_eye-care_token', $response_body['data']['token'] );

		File_Manager::write_file( wp_json_encode( $token_data ), 'token-data', 'token-data', 'json' );

		update_option( 'cmsmasters_eye-care_token_status', 'valid' );

		do_action( 'cmsmasters_remove_temp_data' );
	}

	/**
	 * Remove token.
	 */
	public static function remove_token() {
		do_action( 'cmsmasters_remove_temp_data' );

		$data = self::post_request( 'remove-token' );

		self::delete_token_data();

		if ( is_wp_error( $data ) ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => $data->get_error_message(),
				)
			);
		}
	}

	/**
	 * Get token data.
	 *
	 * @param bool $die Send json or return empty array if invalid data.
	 *
	 * @return array Token data.
	 */
	public static function get_token_data( $die = true ) {
		$data = get_option( 'cmsmasters_eye-care_token_data', array() );

		if (
			is_array( $data ) &&
			isset( $data['user'] ) &&
			( ! empty( $data['purchase_code'] ) || ! empty( $data['envato_elements_token'] ) )
		) {
			$data['source_code'] = ( empty( $data['source_code'] ) ? 'purchase-code' : $data['source_code'] );

			return $data;
		}

		$file = File_Manager::get_upload_path( 'token-data', 'token-data.json' );
		$data = File_Manager::get_file_contents( $file );
		$data = json_decode( $data, true );

		$data['source_code'] = ( empty( $data['source_code'] ) ? 'purchase-code' : $data['source_code'] );

		if (
			! is_array( $data ) ||
			! isset( $data['user'] ) ||
			( empty( $data['purchase_code'] ) && empty( $data['envato_elements_token'] ) )
		) {
			if ( ! $die ) {
				return array();
			}

			wp_send_json( array(
				'success' => false,
				'code' => 'invalid_token_data',
				'message' => esc_html__( 'Your token data is invalid.', 'eye-care' ),
			) );
		}

		update_option( 'cmsmasters_eye-care_token_data', $data );

		return $data;
	}

	/**
	 * Delete token data.
	 */
	protected static function delete_token_data() {
		File_Manager::delete_uploaded_dir( 'token-data' );
		delete_option( 'cmsmasters_eye-care_token_data' );
		delete_option( 'cmsmasters_eye-care_token' );
		delete_option( 'cmsmasters_eye-care_token_status' );
		delete_transient( 'cmsmasters_plugins_list' );
		delete_transient( 'cmsmasters_eye-care_token_regeneration_status' );
		do_action( 'cmsmasters_remove_temp_data' );
	}

}
