=== Post List with Load More ===

Contributors:      ramizmanked
Donate link:       https://paypal.me/ramizmanked
Tags:              post list with load more, lazy loading posts, ajax posts, list block, post list block, load more posts, infinite posts, post list, post with read more
Requires at least: 6.1.0
Tested up to:      6.1.1
Requires PHP:      8.0
License:           GPLv3
License URI:       http://www.gnu.org/licenses/gpl-3.0.html

Simple yet powerful WordPress plugin that allows you to display built-in/custom posts and pages anywhere on your website.

== Description ==

Loading more posts is made asynchronous (using AJAX) to ensure seamless user experience.

The plugin is shipped with two basic layouts: Grid and List.

For ease and simplicity layouts styles are kept basic, so it should reflect whatever styles provided by your active theme and you don't have to overwrite styles for each and every element of the layout.

### Features

1. Asynchronous pagination to ensure unwanted reloading and best reading experience.
1. Comes with flexible custom block with a bunch of settings.
1. Pre-built layouts for getting started easily.
1. Option to load more posts 'On Scroll' or 'On Button Click'.
1. Dynamic shortcode generator so you don't have to remember different shortcode parameters.

== Installation ==

1. Go to Dashboard > Plugins > Add New.
1. Search for 'Post List with Load More' in Search plugins... textbox and hit Enter.
1. Install & Active the 'Post List with Load More' plugin.
1. You're done!

### Usage

There are a couple of ways you can display posts:
1. **Custom Block** - If your website is running on recent WordPress version and is using Block Editor for content editing then using Block is the easiest way to display posts. I hope you would love intuitive block settings which should show live preview of the changes you make.
   * Go to Dashboard > Edit post/page > Click 'Block Inserter' from top-left corner > Search or Select 'Post List with Load More'.
   * Try changing block settings per your need and you're done.
1. **Shortcode** - If your website is running on older WordPress version (i.e. before v5) then you can still make most out of the plugin through Shortcode. You can generate shortcode dynamically by changing several fields depending on your needs via plugin settings page.
   * Go to Dashboard > Settings > Post List with Load More.
   * 'Settings' tab will let you choose Layout Style and Load More posts option.
   * 'Generate Shortcode' by changing fields depending on your requirement and copy the generated shortcode.
   * Go to Dashboard > Edit post/page > Click 'Block Inserter' from top-left corner > Search or Select 'Shortcode' block and paste the copied shortcode.

== Frequently Asked Questions ==

= Does this plugin support custom post type? =

Yes, it does.

= Does this plugin allow me to list the posts filtered by custom taxonomy? =

Yes. Absolutely! You can use custom taxonomies registered with your WordPress installation in order to filter the posts by.

= What if I wish to use shortcode in file? =

You can always add snippet like `<?php echo do_shortcode('[post_list_with_load_more]'); ?>` in your code in order to render post list from your code.

== Screenshots ==

1. Admin - Block with settings.
2. Admin - Plugin settings (for shortcode version).
3. Admin - Generate shortcode.
4. Frontend - Grid layout.
5. Frontend - List layout

== Changelog ==

= 2.0.1 - January 03, 2023 =
* Fixed broken shortcode generator.
* Updated screenshots.

= 2.0.0 - January 01, 2023 =
* Added block support.
* Restructured code and use of composer for developers' ease.
* Updated assets with consistent design.

= 1.0.5 - December 16, 2022 =
* Fix – Load more functionality broken with recent WordPress release.
* Fix – minor phpcs warnings.

= 1.0.4 - April 19, 2021 =
* Fix – Empty post listing if no categories selected.
* Fix – Empty space before ending shortcode.

= 1.0.3 - June 03, 2020 =
* Translation support for ‘Load More’ button added.

= 1.0.2 - June 02, 2020 =
* Add multilingual support.

= 1.0.1 - December 04, 2019 =
* Follow standard naming conventions.

= 1.0.0 - December 04, 2019 =
* Initial release

== Upgrade Notice ==

= 2.0.1 - January 03, 2023 =
* Fixed broken shortcode generator.
* Updated screenshots.

= 2.0.0 - January 01, 2023 =
* Added block support.
* Restructured code and use of composer for developers' ease.
* Updated assets with consistent design.