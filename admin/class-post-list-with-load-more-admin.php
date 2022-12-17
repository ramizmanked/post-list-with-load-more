<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ramizmanked.com
 * @since      1.0.0
 *
 * @package    Post_List_With_Load_More
 * @subpackage Post_List_With_Load_More/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_List_With_Load_More
 * @subpackage Post_List_With_Load_More/admin
 * @author     Ramiz Manked <ramiz.manked@gmail.com>
 */
class Post_List_With_Load_More_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private string $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
		add_action( 'admin_init', array( $this, 'setup_layout_section' ) );
		add_action( 'admin_init', array( $this, 'setup_shortcode_section' ) );
		add_action( 'admin_init', array( $this, 'setup_fields' ) );
		add_action( 'wp_ajax_post_list_callback', array( $this, 'post_list_callback' ) );
		add_action( 'wp_ajax_nopriv_post_list_callback', array( $this, 'post_list_callback' ) );
	}

	/**
	 * Create plugin settings page
	 *
	 * @since 1.0.0
	 */
	public function create_plugin_settings_page(): void {
		// Add the menu item and page.
		$page_title = 'Post List with Load More Settings Page';
		$menu_title = 'Post List with Load More';
		$capability = 'manage_options';
		$slug       = 'post_list_with_load_more_settings';
		$callback   = array( $this, 'plugin_settings_page_content' );
		add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
	}

	/**
	 * Renders sections in plugin settings page
	 *
	 * @since 1.0.0
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
                   class="nav-tab <?php echo 'shortcode' === $active_tab ? 'nav-tab-active' : ''; ?>">Generate
                    Shortcode</a>
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
	 * Setup layout tab
	 *
	 * @since 1.0.0
	 */
	public function setup_layout_section(): void {
		add_settings_section( 'layout_section', '', array( $this, 'layout_section_callback' ), 'layout_section' );
	}

	/**
	 * Callback function for setup layout tab
	 *
	 * @since 1.0.0
	 */
	public function layout_section_callback(): void {
		?>
        <p>Post list will be displayed to the end user depending on the settings saved below.</p>
		<?php
	}

	/**
	 * Setiup shortcode tab
	 *
	 * @since 1.0.0
	 */
	public function setup_shortcode_section(): void {
		add_settings_section( 'shortcode_section', '', array(
			$this,
			'shortcode_section_callback'
		), 'shortcode_section' );
	}

	/**
	 * Callback function for setup shortcode tab
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
					$args       = array(
						'public' => true,
					);
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
	 * Setup tab fields
	 *
	 * @since 1.0.0
	 */
	public function setup_fields(): void {
		$fields = array(
			array(
				'uid'         => 'layout_style',
				'label'       => 'Layout Style',
				'section'     => 'layout_section',
				'type'        => 'radio',
				'options'     => array(
					'list' => 'List',
					'grid' => 'Grid',
				),
				'placeholder' => '',
				'default'     => 'list',
			),
			array(
				'uid'         => 'loadmore',
				'label'       => 'Load More Posts',
				'section'     => 'layout_section',
				'type'        => 'radio',
				'options'     => array(
					'scroll' => 'On Scroll',
					'click'  => 'On Button Click',
				),
				'placeholder' => '',
				'default'     => 'click',
			),
		);
		foreach ( $fields as $field ) {
			add_settings_field( $field['uid'], $field['label'], array(
				$this,
				'field_callback'
			), 'layout_section', $field['section'], $field );
			register_setting( 'layout_section', $field['uid'] );
		}
	}

	/**
	 * Callback function for settings tab fields
	 *
	 * @param array $arguments List of arguments.
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
						printf( '<label for="%1$s"><input type="radio" id="%1$s" name="%2$s" value="%1$s" %4$s /> %3$s</label>', esc_attr( $key ), esc_attr( $arguments['uid'] ), esc_attr( $label ), checked( $key, $value, false ) );
					}
				}
				break;
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_List_With_Load_More_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Post_List_With_Load_More_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-list-with-load-more-admin.css', [], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_List_With_Load_More_Loader as all the hooks are defined
		 * in that particular class.
		 *
		 * The Post_List_With_Load_More_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/post-list-with-load-more-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'postListAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax_nonce' ),
			)
		);
	}

	/**
	 * Insist user to login if there are not logged in
	 *
	 * @since   1.0.0
	 */
	public function post_list_must_login(): void {
		echo 'You must log in to continue.';
		die();
	}

	/**
	 * Ajax callback for post list
	 *
	 * @since   1.0.0
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
					$terms       = get_terms( $taxonomy, array( 'hide_empty' => false ) );
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
