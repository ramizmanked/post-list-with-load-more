<?php
/**
 * Handles settings page for admin.
 *
 * @package post-list-with-load-more
 */

namespace Post_List_With_Load_More\Admin;

use Post_List_With_Load_More\Traits\Singleton;

/**
 * Settings class.
 */
class Settings {

	use Singleton;

	/**
	 * Constructor method.
	 */
	protected function __construct() {
		add_action( 'admin_menu', [ $this, 'create_plugin_settings_page' ] );
		add_action( 'admin_init', [ $this, 'setup_layout_section' ] );
		add_action( 'admin_init', [ $this, 'setup_shortcode_section' ] );
		add_action( 'admin_init', [ $this, 'setup_fields' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_post_list_callback', [ $this, 'post_list_callback' ] );
		add_action( 'wp_ajax_nopriv_post_list_callback', [ $this, 'post_list_callback' ] );
	}

	/**
	 * Create plugin settings page.
	 *
	 * @return void
	 */
	public function create_plugin_settings_page(): void {
		// Add the menu item and page.
		add_submenu_page(
			'options-general.php',
			'Post List with Load More Settings Page',
			'Post List with Load More',
			'manage_options',
			'post_list_with_load_more_settings',
			[ $this, 'plugin_settings_page_content' ]
		);
	}

	/**
	 * Renders sections in plugin settings page.
	 *
	 * @return void
	 */
	public function plugin_settings_page_content(): void { ?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">
			<h2>Post List with Load More Settings</h2>
			<?php
			$tab_action_nonce = wp_create_nonce( 'tab_action_nonce' );
			$active_tab       = 'layout';
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ?? '' ) ), 'tab_action_nonce' ) ) {
				if ( isset( $_GET['tab'] ) ) {
					$active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
				}
			}
			?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=post_list_with_load_more_settings&tab=layout&_wpnonce=<?php echo esc_attr( $tab_action_nonce ); ?>"
					class="nav-tab <?php echo 'layout' === $active_tab ? 'nav-tab-active' : ''; ?>">Settings</a>
				<a href="?page=post_list_with_load_more_settings&tab=shortcode&_wpnonce=<?php echo esc_attr( $tab_action_nonce ); ?>"
					class="nav-tab <?php echo 'shortcode' === $active_tab ? 'nav-tab-active' : ''; ?>">Generate Shortcode</a>
			</h2>

			<form method="post" action="options.php" id="post-list-settings">
				<?php

				if ( 'shortcode' === $active_tab ) {
					settings_fields( 'shortcode_section' );
					do_settings_sections( 'shortcode_section' );
				} else {
					settings_fields( 'layout_section' );
					do_settings_sections( 'layout_section' );
					submit_button();
				}
				?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Setup layout tab.
	 *
	 * @return void
	 */
	public function setup_layout_section(): void {
		add_settings_section(
			'layout_section',
			'',
			[ $this, 'layout_section_callback' ],
			'layout_section'
		);
	}

	/**
	 * Callback function for setup layout tab.
	 *
	 * @return void
	 */
	public function layout_section_callback(): void {
		?>
		<p>Post list will be displayed to the end user depending on the settings saved below.</p>
		<?php
	}

	/**
	 * Setup shortcode tab.
	 *
	 * @return void
	 */
	public function setup_shortcode_section(): void {
		add_settings_section(
			'shortcode_section',
			'',
			[ $this, 'shortcode_section_callback' ],
			'shortcode_section' 
		);
	}

	/**
	 * Callback function for setup shortcode tab.
	 *
	 * @return void
	 */
	public function shortcode_section_callback(): void {
		?>
		<p>Select the field(s) based on your requirement and get shortcode ready.</p>
		<div class="shortcode-form">
			<div class="input-control">
				<label for="posttype">Post Type:</label>
				<select name="posttype" id="posttype">
					<option value="" selected="selected">-- Select Post Type --</option>
					<?php
					$args       = [ 'public' => true ];
					$post_types = get_post_types( $args, 'object' );
					unset( $post_types['attachment'] );
					ksort( $post_types );
					foreach ( $post_types as $post_type ) {
						echo sprintf( '<option value="%s">%s</option>', esc_attr( $post_type->name ), esc_attr( $post_type->label ) );
					}
					?>
				</select>
			</div>
			<div class="input-control">
				<label for="taxonomy">Category Type:</label>
				<select name="taxonomy" id="taxonomy">
					<option value="" selected="selected">-- Select Category Type --</option>
				</select>
			</div>
			<div class="input-control">
				<label for="term">Category:</label>
				<select name="term" id="term">
					<option value="" selected="selected">-- Select Category --</option>
				</select>
				(Hold down the Ctrl (windows) / Command (Mac) button and click to select multiple options.)
			</div>
			<?php
			$tags = get_tags();
			if ( is_array( $tags ) && count( $tags ) > 0 ) {
				?>
				<div class="input-control tags-control">
					<label for="tag">Tag(s):</label>
					<select name="tag" id="tag" multiple="multiple">
						<option value="" selected="selected">-- Select Tag(s) --</option>
						<?php
						foreach ( $tags as $tag ) {
							echo sprintf( '<option value="%s">%s</option>', esc_attr( $tag->slug ), esc_attr( $tag->name ) );
						}
						?>
					</select>
					(Hold down the Ctrl (windows) / Command (Mac) button and click to select multiple options.)
				</div>
				<?php
			}
			?>
			<div class="input-control">
				<label for="limit">Post Per Page:</label>
				<input type="number" name="limit" id="limit" value="" min="1"/>
				<span class="field-help">(Default: 6)</span>
			</div>
			<div class="input-control">
				<label for="orderby">Order By:</label>
				<select name="orderby" id="orderby">
					<option value="" selected="selected">-- Select Order By --</option>
					<option value="author">Author</option>
					<option value="date">Date</option>
					<option value="title">Title</option>
				</select>
				<span class="field-help">(Default: Date)</span>
			</div>
			<div class="input-control">
				<label for="order">Order:</label>
				<select name="order" id="order">
					<option value="" selected="selected">-- Select Order --</option>
					<option value="ASC">Ascending</option>
					<option value="DESC">Descending</option>
				</select>
				<span class="field-help">(Default: Descending)</span>
			</div>
		</div>
		<div class="shortcode-display">
			<div class="shortcode-info"><strong>Use Shortcode: </strong>
				[post_list_with_load_more
				<span id="show-posttype"></span>
				<span id="show-taxonomy"></span>
				<span id="show-terms"></span>
				<span id="show-tags"></span>
				<span id="show-limit"></span>
				<span id="show-orderby"></span>
				<span id="show-order"></span>]
			</div>
		</div>
		<?php
	}

	/**
	 * Setup tab fields.
	 *
	 * @return void
	 */
	public function setup_fields(): void {
		$fields = [
			[
				'uid'         => 'layout_style',
				'label'       => 'Layout Style',
				'section'     => 'layout_section',
				'type'        => 'radio',
				'options'     => [
					'list' => 'List',
					'grid' => 'Grid',
				],
				'placeholder' => '',
				'default'     => 'list',
			],
			[
				'uid'         => 'loadmore',
				'label'       => 'Load More Posts',
				'section'     => 'layout_section',
				'type'        => 'radio',
				'options'     => [
					'scroll' => 'On Scroll',
					'click'  => 'On Button Click',
				],
				'placeholder' => '',
				'default'     => 'click',
			],
		];
		foreach ( $fields as $field ) {
			add_settings_field(
				$field['uid'],
				$field['label'],
				[ $this, 'field_callback' ],
				'layout_section',
				$field['section'],
				$field 
			);
			register_setting( 'layout_section', $field['uid'] );
		}
	}

	/**
	 * Callback function for settings tab fields.
	 *
	 * @param array $arguments List of arguments.
	 * @return void
	 */
	public function field_callback( array $arguments ): void {
		$value = get_option( $arguments['uid'] ); // Get the current value, if there is one.
		if ( ! $value ) {                   // If no value exists.
			$value = $arguments['default']; // Set to our default.
		}

		// Check which type of field we want.
		switch ( $arguments['type'] ) {
			case 'text': // If it is a text field.
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', esc_attr( $arguments['uid'] ), esc_attr( $arguments['type'] ), esc_attr( $arguments['placeholder'] ), esc_attr( $value ) );
				break;
			case 'textarea': // If it is a textarea.
				printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', esc_attr( $arguments['uid'] ), esc_attr( $arguments['placeholder'] ), esc_attr( $value ) );
				break;
			case 'select': // If it is a select dropdown.
				if ( ! empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
					$options_markup = '';
					foreach ( $arguments['options'] as $key => $label ) {
						$options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
					}
					printf( '<select name="%1$s" id="%1$s">%2$s</select>', esc_attr( $arguments['uid'] ), esc_attr( $options_markup ) );
				}
				break;
			case 'radio': // If it is a select dropdown.
				if ( ! empty( $arguments['options'] ) && is_array( $arguments['options'] ) ) {
					foreach ( $arguments['options'] as $key => $label ) {
						printf( '<label for="%1$s"><input type="radio" id="%1$s" name="%2$s" value="%1$s" %3$s /> %4$s</label>', esc_attr( $key ), esc_attr( $arguments['uid'] ), checked( $key, $value, false ), esc_attr( $label ) );
					}
				}
				break;
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return void
	 */
	public function enqueue_styles(): void {
		// Refines settings page at backend.
		wp_enqueue_style(
			POST_LIST_WITH_LOAD_MORE_PLUGIN_ADMIN_STYLE_HANDLE,
			POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/css/editor.css',
			[],
			POST_LIST_WITH_LOAD_MORE_VERSION
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		// Handles shortcode builder section from plugin settings page at backend.
		wp_enqueue_script(
			POST_LIST_WITH_LOAD_MORE_PLUGIN_ADMIN_SCRIPT_HANDLE,
			POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/js/editor.js',
			[ 'jquery' ],
			POST_LIST_WITH_LOAD_MORE_VERSION
		);
		wp_localize_script(
			POST_LIST_WITH_LOAD_MORE_PLUGIN_ADMIN_SCRIPT_HANDLE,
			'postListAjax',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax_nonce' ),
			]
		);

		// Handles load more functionality.
		wp_enqueue_script(
			POST_LIST_WITH_LOAD_MORE_PLUGIN_SHARED_SCRIPT_HANDLE,
			POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/js/script.js',
			[ 'jquery' ],
			POST_LIST_WITH_LOAD_MORE_VERSION
		);
		wp_localize_script(
			POST_LIST_WITH_LOAD_MORE_PLUGIN_SHARED_SCRIPT_HANDLE,
			'myAjax',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax_nonce' ),
			]
		);
	}

	/**
	 * Ajax callback for post list.
	 *
	 * @return void
	 */
	public function post_list_callback(): void {
		if ( wp_verify_nonce( sanitize_text_field( ! empty( $_POST['nonce'] ) ? wp_unslash( $_POST['nonce'] ) : '' ), 'ajax_nonce' ) ) {
			$purpose = ( isset( $_POST['purpose'] ) ) ? sanitize_text_field( wp_unslash( $_POST['purpose'] ) ) : '';
			if ( 'getTaxonomies' === $purpose ) {
				$posttype = ( isset( $_POST['posttype'] ) ) ? sanitize_text_field( wp_unslash( $_POST['posttype'] ) ) : '';
				if ( '' !== $posttype ) {
					$option_html = '';
					$taxonomies  = get_object_taxonomies( $posttype, 'object' );
					if ( count( $taxonomies ) > 0 ) {
						foreach ( $taxonomies as $taxonomy ) {
							$option_html .= sprintf( '<option value="%s">%s</option>', $taxonomy->name, $taxonomy->label );
						}
					}
					echo esc_html( $option_html );
					exit;
				}
			}
			if ( 'getTerms' === $purpose ) {
				$taxonomy = ( isset( $_POST['taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : '';
				if ( '' !== $taxonomy ) {
					$option_html = '';
					$terms       = get_terms( $taxonomy, [ 'hide_empty' => false ] );
					if ( count( $terms ) > 0 ) {
						foreach ( $terms as $term ) {
							$option_html .= sprintf( '<option value="%s">%s</option>', $term->slug, $term->name );
						}
					}
					echo esc_html( $option_html );
					exit;
				}
			}
		}
		exit;
	}
}
