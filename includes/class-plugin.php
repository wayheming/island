<?php

namespace Island\Includes;

defined( 'ABSPATH' ) || exit;

final class Plugin {
	use Trait_Singleton;

	/**
	 * Constructor.
	 */
	public function __construct() {
		new User_Router();
		new Trade_Center_Router();
	}
}
