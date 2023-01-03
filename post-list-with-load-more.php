<?php
/**
 * Plugin Name:       Post List with Load More
 * Plugin URI:        https://wordpress.org/plugins/post-list-with-load-more
 * Description:       A simplified solution to your posts listing needs. This plugin lets you display the posts list anywhere on your site.
 * Version:           2.0.1
 * Author:            Ramiz Manked
 * Author URI:        https://ramizmanked.com
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
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
const POST_LIST_WITH_LOAD_MORE_VERSION                     = '2.0.0';
const POST_LIST_WITH_LOAD_MORE_PLUGIN_ADMIN_SCRIPT_HANDLE  = 'post-list-with-load-more-admin-script';
const POST_LIST_WITH_LOAD_MORE_PLUGIN_SHARED_SCRIPT_HANDLE = 'post-list-with-load-more-script';
const POST_LIST_WITH_LOAD_MORE_PLUGIN_ADMIN_STYLE_HANDLE   = 'post-list-with-load-more-admin-style';
const POST_LIST_WITH_LOAD_MORE_PLUGIN_SHARED_STYLE_HANDLE  = 'post-list-with-load-more-style';

define( 'POST_LIST_WITH_LOAD_MORE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'POST_LIST_WITH_LOAD_MORE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Autoload classes.
require_once POST_LIST_WITH_LOAD_MORE_PLUGIN_PATH . 'vendor/autoload.php';

require_once POST_LIST_WITH_LOAD_MORE_PLUGIN_PATH . 'classes/manifest.php';
