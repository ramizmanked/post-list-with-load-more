<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ramizmanked.com
 * @since      1.0.0
 *
 * @package    Post_List_With_Load_More
 * @subpackage Post_List_With_Load_More/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Post_List_With_Load_More
 * @subpackage Post_List_With_Load_More/includes
 * @author     Ramiz Manked <ramiz.manked@gmail.com>
 */
class Post_List_With_Load_More_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain(): void {

		load_plugin_textdomain(
			'post-list-with-load-more',
			false,
			dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
		);

	}
}
