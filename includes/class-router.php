<?php

namespace Island\Includes;

use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

abstract class Router {
	/**
	 * API namespace.
	 *
	 * @var string
	 */
	private $namespace = 'island/v1';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
	}

	/**
	 * Register route wrapper.
	 *
	 * @param  string $route  Route.
	 * @param  string $methods  Methods.
	 * @param  null   $callback  Callback.
	 * @param  array  $args  Args.
	 */
	public function register_route( $route = '', $methods = '', $callback = null, $args = [] ) {
		register_rest_route(
			$this->namespace,
			$route,
			[
				'methods'             => $methods,
				'callback'            => $callback,
				'permission_callback' => '__return_true',
				'args'                => $args,
			]
		);
	}

	/**
	 * Register API endpoints.
	 *
	 * @param  WP_REST_Request $request  Request.
	 *
	 * @return array|false
	 */
	public function check_user( WP_REST_Request $request ) {
		$user_id = $request->get_param( 'user_id' );
		$users   = get_option( 'island_users' );

		if ( empty( $user_id ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You need to set user_id.', 'island' ),
			];
		}

		if ( empty( $users ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__(
					'You need to generate users firstly try to use /users/generate endpoint.',
					'island'
				),
			];
		}

		if ( ! isset( $users[ $user_id ] ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'User with this id not exists.', 'island' ),
			];
		}

		return false;
	}

	/**
	 * Register API endpoints.
	 */
	public function register_endpoints() {
	}
}
