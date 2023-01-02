<?php
/**
 * Handles hooks used throughout the plugin.
 *
 * @package post-list-with-load-more
 */

namespace Post_List_With_Load_More;

use Post_List_With_Load_More\Traits\Singleton;

/**
 * Hooks class.
 */
class Hooks {

	use Singleton;

	/**
	 * Constructor method.
	 */
	protected function __construct() {
		add_filter( 'plugin_action_links_post-list-with-load-more/post-list-with-load-more.php', [ $this, 'post_list_settings_link' ] );
	}

	/**
	 * Creating settings link on plugins page.
	 *
	 * @param ?array $links Links array.
	 *
	 * @return array
	 */
	public function post_list_settings_link( ?array $links ): array {
		// Build and escape the URL.
		$url = esc_url(
			add_query_arg(
				'page',
				'post_list_with_load_more_settings',
				get_admin_url() . 'admin.php'
			)
		);
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings', 'post-list-with-load-more' ) . '</a>';
		// Adds the link to the end of the array.
		$links[] = $settings_link;

		return $links;
	}
}

Hooks::get_instance();
