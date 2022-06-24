<?php

namespace Island\Includes;

use WP_REST_Request;

class Trade_Center_Router extends Router {
	/**
	 * Register API endpoints.
	 */
	public function register_endpoints() {
		$this->register_route(
			'/trade_center/sell',
			'POST',
			[ $this, 'sell_item' ]
		);

		$this->register_route(
			'/trade_center/buy',
			'POST',
			[ $this, 'buy_item' ]
		);

		$this->register_route(
			'/trade_center/items',
			'GET',
			[ $this, 'get_items_for_sale' ]
		);
	}

	/**
	 * Get items for sale.
	 *
	 * @return array|mixed|void
	 */
	public function get_items_for_sale() {
		$trade_center = get_option( 'island_trade_center' );

		if ( empty( $trade_center ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'There are no items for sale.', 'island' ),
			];
		}

		return $trade_center;
	}

	/**
	 * Buy item.
	 *
	 * @param WP_REST_Request $request Request.
	 *
	 * @return array
	 */
	public function buy_item( WP_REST_Request $request ) {
		$items_list      = Items::get_list();
		$buy_item_slug   = $request->get_param( 'buy_item_slug' );
		$sell_item_slugs = $request->get_param( 'sell_item_slugs' );
		$buyer_id        = $request->get_param( 'buyer_id' );
		$seller_id       = $request->get_param( 'seller_id' );
		$users_items     = get_option( 'island_users_items' );
		$trade_center    = get_option( 'island_trade_center' );

		$sell_items_value = 0;
		$buy_items_value  = $items_list[ $buy_item_slug ]['value'];

		foreach ( explode( ',', $sell_item_slugs ) as $item ) {
			$sell_items_value = $sell_items_value + $items_list[ $item ]['value'];
		}

		if ( $sell_items_value >= $buy_items_value ) {
			$users_items[ $buyer_id ][ $buy_item_slug ] = $items_list[ $buy_item_slug ];
			unset( $users_items[ $seller_id ][ $buy_item_slug ] );

			foreach ( explode( ',', $sell_item_slugs ) as $item ) {
				$users_items[ $seller_id ][ $item ] = $items_list[ $item ];
				unset( $users_items[ $buyer_id ][ $item ] );
			}
		}

		unset( $trade_center[ $seller_id ][ array_search( $buy_item_slug, $trade_center[ $seller_id ], true ) ] );

		$update_trade_center = update_option( 'island_trade_center', $trade_center );
		$update_users_items  = update_option( 'island_users_items', $users_items );

		if ( $update_trade_center && $update_users_items ) {
			return [
				'type'    => 'success',
				'message' => esc_html__( 'The item has been buy', 'island' ),
			];
		} else {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'Failed to buy item.', 'island' ),
			];
		}
	}

	/**
	 * Sell item.
	 *
	 * @param WP_REST_Request $request Request.
	 *
	 * @return array
	 */
	public function sell_item( WP_REST_Request $request ) {
		$check_user   = $this->check_user( $request );
		$item_slug    = $request->get_param( 'item_slug' );
		$user_id      = $request->get_param( 'user_id' );
		$users_items  = get_option( 'island_users_items' );
		$trade_center = get_option( 'island_trade_center' );

		if ( $check_user ) {
			return $check_user;
		}

		if ( empty( $item_slug ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You need to set item_slug.', 'island' ),
			];
		}

		if ( ! isset( $users_items[ $user_id ] ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You need to generate items firstly try to use /users/generate_items endpoint.', 'island' ),
			];
		}

		if ( ! isset( $users_items[ $user_id ][ $item_slug ] ) ) {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'You do not have such an item.', 'island' ),
			];
		}

		$trade_center[ $user_id ][] = $item_slug;

		$update_trade_center = update_option( 'island_trade_center', $trade_center );

		if ( $update_trade_center ) {
			return [
				'type'    => 'success',
				'message' => esc_html__( 'The item has been successfully put up for sale.', 'island' ),
			];
		} else {
			return [
				'type'    => 'error',
				'message' => esc_html__( 'Failed to put item for sale.', 'island' ),
			];
		}
	}
}
