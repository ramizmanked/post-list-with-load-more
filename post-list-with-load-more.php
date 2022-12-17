<?php
/**
 * Plugin Name:       Post List with Load More
 * Plugin URI:        https://wordpress.org/plugins/post-list-with-load-more
 * Description:       A simplified solution to your posts listing needs. This plugin allows you to display the list of posts anywhere on your site. Load More feature retrieves posts without reloading the page to ensure seamless reading experience for end users.
 * Version:           1.0.5
 * Author:            Ramiz Manked
 * Author URI:        https://ramizmanked.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       post-list-with-load-more
 * Domain Path:       /languages
 *
 * @package post-list-with-load-more
 */

// Bail if file called directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Currently plugin version.
const POST_LIST_WITH_LOAD_MORE_VERSION = '1.0.5';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-post-list-with-load-more-activator.php
 */
function activate_post_list_with_load_more(): void {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-list-with-load-more-activator.php';
	Post_List_With_Load_More_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-post-list-with-load-more-deactivator.php
 */
function deactivate_post_list_with_load_more(): void {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-post-list-with-load-more-deactivator.php';
	Post_List_With_Load_More_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_post_list_with_load_more' );
register_deactivation_hook( __FILE__, 'deactivate_post_list_with_load_more' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-post-list-with-load-more.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_post_list_with_load_more(): void {

	$plugin = new Post_List_With_Load_More();
	$plugin->run();

}

run_post_list_with_load_more();

/**
 * Creating settings link on plugins page.
 *
 * @param ?array $links Links array.
 *
 * @return array
 */
function post_list_settings_link( ?array $links ): array {
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

add_filter( 'plugin_action_links_post-list-with-load-more/post-list-with-load-more.php', 'post_list_settings_link' );
