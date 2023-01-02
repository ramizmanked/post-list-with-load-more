<?php
/**
 * Handles block related things.
 *
 * @package post-list-with-load-more
 */

namespace Post_List_With_Load_More;

use Post_List_With_Load_More\Traits\Singleton;

/**
 * Block class.
 */
class Block {

	use Singleton;

	/**
	 * Constructor method.
	 */
	protected function __construct() {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register_block(): void {
		register_block_type( POST_LIST_WITH_LOAD_MORE_PLUGIN_PATH . '/build' );
	}
}

Block::get_instance();
