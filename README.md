# Post List with Read More

Simple yet powerful WordPress plugin that allows you to display built-in/custom posts and pages anywhere on your website.

Loading more posts is made asynchronous (using AJAX) to ensure seamless user experience.

The plugin is shipped with two basic layouts: Grid and List.

For ease and simplicity layouts styles are kept basic, so it should reflect whatever styles provided by your active theme and you don't have to overwrite styles for each and every element of the layout.

## Top Features
1. Asynchronous pagination to ensure unwanted reloading and best reading experience.
1. Comes with flexible custom block with a bunch of settings.
1. Pre-built layouts for getting started easily.
1. Option to load more posts 'On Scroll' or 'On Button Click'.
1. Dynamic shortcode generator so you don't have to remember different shortcode parameters.

## Installation

1. Go to Dashboard > Plugins > Add New.
1. Search for 'Post List with Load More' in Search plugins... textbox and hit Enter.
1. Install & Active the 'Post List with Load More' plugin.

## Usage

There are a couple of ways you can display posts:
1. **Custom Block** - If your website is running on recent WordPress version and is using Block Editor for content editing then using Block is the easiest way to display posts. I hope you would love intuitive block settings which should show live preview of the changes you make.
   * Go to Dashboard > Edit post/page > Click 'Block Inserter' from top-left corner > Search or Select 'Post List with Load More'.
   * Try changing block settings per your need and you're done.
1. **Shortcode** - If your website is running on older WordPress version (i.e. before v5) then you can still make most out of the plugin through Shortcode. You can generate shortcode dynamically by changing several fields depending on your needs via plugin settings page.
   * Go to Dashboard > Settings > Post List with Load More.
   * 'Settings' tab will let you choose Layout Style and Load More posts option.
   * 'Generate Shortcode' by changing fields depending on your requirement and copy the generated shortcode.
   * Go to Dashboard > Edit post/page > Click 'Block Inserter' from top-left corner > Search or Select 'Shortcode' block and paste the copied shortcode.

## Frequently Asked Questions

### Does this plugin support custom post type?
Yes, it does.

### Does this plugin allow me to list the posts filtered by custom taxonomy?
Yes. Absolutely! You can use custom taxonomies registered with your WordPress installation in order to filter the posts by.

### What if I wish to use shortcode in file?
You can always add snippet like `<?php echo do_shortcode('[post_list_with_load_more]'); ?>` in your code in order to render post list from your code.

## How to contribute?

Contributions or Suggestions are most welcome. I would really appreciate even minor contribution to the plugin or documentation.

### Development environment recommendations
1. Latest version of WordPress
2. Node v16
3. Composer v2+
4. WordPress coding standard

### Steps to follow
1. Create a new branch from `main`.
2. Clone the repository into your local and checkout to your branch.
3. Run ``npm start`` to start development environment.
4. Once you're done with code updates and testing, run ``npm run build`` to make assets ready to go into production environment.
5. Commit, push and publish your local branch.
6. Create a 'Pull Request' from your branch to merge your code into `main`.