<?php

namespace Island\Includes;

use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

class User_Router extends Router {
	/**
	 * Register API endpoints.
	 */
	public function register_endpoints() {
		$this->register_route(
			'/users/list',
			'GET',
			[ $this, 'get_users_list' ]
		);

		$this->register_route(
			'/users/generate_users',
			'POST',
			[ $this, 'generate_users' ]
		);

		$this->register_route(
			'/users/generate_items',
			'POST',
			[ $this, 'generate_items' ]
		);

		$this->register_route(
			'/users/items',
			'GET',
			[ $this, 'get_user_items' ]
		);

		$this->register_route(
			'/users/rename',
			'POST',
			[ $this, 'user_rename' ]
		);
	}

	/**
	 * User rename.
	 *
	 * @param WP_REST_Request $request Request.
	 *
	 * @return array
	 */
	public function user_rename( WP_REST_Request $request ) {
		$check_user = $this->check_user( $request );
		$user_id    = $request->get_param( 'user_id' );
		$user_name  = $request->get_param( 'user_name' );
		$users      = get_option( 'island_users' );

		if ( $check_user ) {
			return $check_user;
		}

		if ( empty( $user_name ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You need to set user_name.', 'island' ),
			];
		}

		$users[ $user_id ] = $user_name;

		$update_users = update_option( 'island_users', $users );

		if ( $update_users ) {
			return [
				'type'    => 'success',
				'message' => esc_html__( 'User name successfully updated.', 'island' ),
			];
		} else {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'Could not update user name.', 'island' ),
			];
		}
	}

	/**
	 * Get user items.
	 *
	 * @param WP_REST_Request $request Request.
	 *
	 * @return array
	 */
	public function get_user_items( WP_REST_Request $request ) {
		$check_user = $this->check_user( $request );

		$user_id = $request->get_param( 'user_id' );

		if ( $check_user ) {
			return $check_user;
		}

		$users_items = get_option( 'island_users_items' );

		if ( ! isset( $users_items[ $user_id ] ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You need to generate items firstly try to use /users/generate_items endpoint.', 'island' ),
			];
		}

		return $users_items[ $user_id ];
	}

	/**
	 * Get users list.
	 *
	 * @return array
	 */
	public function get_users_list() {
		$users = get_option( 'island_users' );

		if ( empty( $users ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You need to generate users firstly try to use /users/generate_users endpoint.', 'island' ),
			];
		}

		return get_option( 'island_users' );
	}

	/**
	 * Generate users.
	 *
	 * @return array
	 */
	public function generate_users() {
		$users = get_option( 'island_users' );

		if ( ! empty( $users ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'Users are already generated.', 'island' ),
			];
		}

		$update_users = update_option(
			'island_users',
			[
				1 => 'Ernest Hemingway',
				2 => 'Darth Vader',
				3 => 'Baby Yoda',
			]
		);

		if ( $update_users ) {
			return [
				'type'    => 'success',
				'message' => esc_html__( 'Users successfully generated.', 'island' ),
			];
		} else {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'Could not generate users.', 'island' ),
			];
		}
	}

	/**
	 * Generate items.
	 *
	 * @param WP_REST_Request $request Request.
	 *
	 * @return array
	 */
	public function generate_items( WP_REST_Request $request ) {
		$check_user  = $this->check_user( $request );
		$items_list  = Items::get_list();
		$users_items = get_option( 'island_users_items' );
		$user_id     = $request->get_param( 'user_id' );

		if ( $check_user ) {
			return $check_user;
		}

		if ( isset( $users_items[ $user_id ] ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'This user already has generated items.', 'island' ),
			];
		}

		$total_value = wp_rand( 3, 20 );
		$output      = [];

		while ( $total_value ) {
			$item_slug  = array_rand( $items_list );
			$item       = $items_list[ $item_slug ];
			$item_value = $item['value'];

			if ( $item_value <= $total_value ) {
				$total_value          = $total_value - $item_value;
				$output[ $item_slug ] = $item;
			}
		}

		$users_items[ $user_id ] = $output;

		$update_users_items = update_option( 'island_users_items', $users_items );

		if ( $update_users_items ) {
			return [
				'type'    => 'success',
				'message' => esc_html__( 'Users items successfully generated.', 'island' ),
			];
		} else {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'Could not generate users items.', 'island' ),
			];
		}
	}
}
