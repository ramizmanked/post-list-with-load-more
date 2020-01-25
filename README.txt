=== Post List with Read More ===

Contributors: ramizmanked
Tags: post list with load more, lazy loading posts, ajax posts, posts with load more, load more posts, infinite posts, post list, post with read more
Requires at least: 4.5.0
Tested up to: 5.3
Stable tag: 1.0.0
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simplified solution to your posts listing needs.

== Description ==

This plugin allows you to display the list of posts anywhere on your site.

Load More feature retrieves posts without reloading the page to ensure seamless reading experience for end users.

### Features

1. Nicely tabbed and dedicated plugin settings page.
1. Ability to choose your preferred layout style whether it could be a List or Grid.
1. Ability to choose when more posts should be loaded after initial post list finished loading whether it could be a 'On Scroll' or 'On Button Click'.
1. Ability to generate a dynamic shortcode depending on your requirement.

== Installation ==

### Install Post List with Load More from within WordPress

1. Visit the plugins page within your dashboard and select 'Add New';
1. Search for 'Post List with Read More';
1. Activate Post List with Load More from your Plugins page;
1. You should see 'Post List with Load More' option under Settings;
1. You're done!

### Install Post List with Load More manually

1. Upload the 'Post List with Load More' folder to the /wp-content/plugins/ directory;
1. Activate the Post List with Load More plugin through the 'Plugins' menu in WordPress;
1. You should see 'Post List with Load More' option under Settings;
1. You're done!

### Usage

In order to get ready with your posts list, all you need to do is:

1. Install & activate the plugin as per described in Installation section below.
1. Go to plugin setting page via Dashboard > Settings > Post List with Load More.
1. Go to 'Settings' tab in order for plugin settings.
    1.1 Tick your preferred layout style (Default: List).
    1.2 Tick when more posts should be loaded after initial post list finished loading (Default: On Button Click).
1. Go to 'Generate Shortcode' tab in order to generate a shortcode dynamically.
    1.1 Select the Post Type you wish to list posts for.
    1.2 Select Category Type for Post Type selected in previous step.
    1.3 Select one more categories to filter the posts by. (Note: Hold down the Ctrl (windows) / Command (Mac) button and click to select multiple options.)
    1.4 If your post has tags, select one more tags to filter the posts by. (Note: Hold down the Ctrl (windows) / Command (Mac) button and click to select multiple options.)
    1.5 Enter the number of posts to display in each pagination.
    1.6 Select the field if you wish to reorder your posts by.
    1.7 Select the order of Order By field.
    1.8 That's it! You should have your shortcode ready to use below form.

== Frequently Asked Questions ==

= Does this plugin support custom post type? =

Yes, it does.

= Does this plugin allow me to list the posts filtered by custom taxonomy? =

Yes. Absolutely! You can use custom taxonomies registered with your WordPress installation in order to filter the posts by.

= What if I wish to use shortcode in file? =

You can always add snippet like `<?php echo do_shortcode('[post_list_with_load_more]'); ?>` in your code in order to render post list from your code.

== Screenshots ==

1. Admin - Plugin settings.
2. Admin - Generate a dynamic shortcode.
3. Post List with List Layout.
4. Post List with Grid Layout
5. Post List with Load More button.

== Changelog ==

= 1.0.0 - December 04, 2019 =

* Initial release

== Upgrade Notice ==

Upgrade notices describe the reason a user should upgrade.