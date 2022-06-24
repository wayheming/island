<?php

namespace Island\Includes;

defined( 'ABSPATH' ) || exit;

class Items {
	/**
	 * Get items list.
	 *
	 * @return array[]
	 */
	public static function get_list() {
		return [
			'water'     => [
				'name'  => 'Water',
				'value' => 1,
			],
			'shirt'     => [
				'name'  => 'Shirt',
				'value' => 3,
			],
			'pants'     => [
				'name'  => 'Pants',
				'value' => 4,
			],
			'dog'       => [
				'name'  => 'Dog',
				'value' => 5,
			],
			'soup'      => [
				'name'  => 'Soup',
				'value' => 8,
			],
			'developer' => [
				'name'  => 'BE Developer',
				'value' => 10,
			],
		];
	}
}
