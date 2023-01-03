<?php
/**
 * Adds support for multi-language.
 *
 * @package post-list-with-load-more
 */

namespace Post_List_With_Load_More;

use Post_List_With_Load_More\Traits\Singleton;

/**
 * I18n class.
 */
class I18n {

	use Singleton;

	/**
	 * Constructor method.
	 */
	protected function __construct() {
		$this->load_plugin_textdomain();
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @return void
	 */
	public function load_plugin_textdomain(): void {
		load_plugin_textdomain(
			'post-list-with-load-more',
			false,
			POST_LIST_WITH_LOAD_MORE_PLUGIN_PATH . 'languages/'
		);
	}
}

I18n::get_instance();
