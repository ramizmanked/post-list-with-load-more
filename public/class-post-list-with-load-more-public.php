<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ramizmanked.com
 * @since      1.0.0
 *
 * @package    Post_List_With_Load_More
 * @subpackage Post_List_With_Load_More/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Post_List_With_Load_More
 * @subpackage Post_List_With_Load_More/public
 * @author     Ramiz Manked <ramiz.manked@gmail.com>
 */
class Post_List_With_Load_More_Public {

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
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		add_shortcode( 'post_list_with_load_more', array( $this, 'render_posts_list' ) );
		add_action( 'wp_ajax_fetch_posts_list', array( $this, 'fetch_posts_list' ) );
		add_action( 'wp_ajax_nopriv_fetch_posts_list', array( $this, 'fetch_posts_list' ) );
	}

	/**
	 * Renders post list
	 *
	 * @param array|string $atts Array of attributes.
	 *
	 * @return false|string
	 */
	public function render_posts_list( array|string $atts ): bool|string {

		$limit     = $atts['limit'] ?? 6;
		$order     = $atts['order'] ?? 'DESC';
		$orderby   = $atts['orderby'] ?? 'date';
		$post_type = $atts['post_type'] ?? '';
		$taxonomy  = $atts['taxonomy'] ?? '';
		$terms     = $atts['terms'] ?? '';
		$tags      = $atts['tags'] ?? [];

		if ( empty( $post_type ) ) {
			ob_start();
			?>
            <p>No posts found.</p>
			<?php
			return ob_get_clean();
		}

		$args = [
			'post_type'      => $post_type,
			'posts_per_page' => $limit,
			'order'          => $order,
			'orderby'        => $orderby,
		];


		if ( ! empty( $taxonomy ) && ! empty( $terms ) ) {
			if ( str_contains( $terms, ',' ) !== false ) {
				$terms = explode( ',', $terms );
			} else {
				$terms = array( $terms );
			}

			$args['tax_query'] = [
				[
					'taxonomy'         => $taxonomy,
					'field'            => 'slug',
					'terms'            => $terms, // Where term_id of Term 1 is "1".
					'include_children' => false,
				]
			];
		}

		if ( ! empty( $tags ) ) {
			$tags = str_replace( ' ', '', $tags );
			if ( str_contains( $tags, ',' ) !== false ) {
				$tags = explode( ',', $tags );
			} else {
				$tags = array( $tags );
			}

			$args['tag_slug__in'] = $tags;
		}

		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			$additional_classes = '';
			$style              = get_option( 'layout_style' );
			$additional_classes .= ( '' !== $style && null !== $style ) ? ' style_' . $style : '';
			$loadmore           = get_option( 'loadmore' );
			$additional_classes .= ( '' !== $loadmore && null !== $loadmore ) ? ' more_on_' . $loadmore : '';

			ob_start();
			?>
            <div class="custom-posts-wrapper">
                <div class="custom-post-list <?php echo esc_attr( $additional_classes ); ?>">
					<?php
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$blog_image = get_the_post_thumbnail_url() ?? plugin_dir_url( __FILE__ ) . 'images/noimage.jpg';
						?>
                        <div class="custom-post">
                            <div class="custom-post-image">
                                <img width="345" height="245" src="<?php echo esc_url( $blog_image ); ?>"
                                     alt="<?php echo esc_attr( get_the_title() ); ?>"/>
                            </div>
                            <div class="custom-post-con">
                                <h4 class="custom-post-title">
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                                </h4>
                                <div class="custom-post-content">
									<?php
									$blog_content = get_the_content();
									$word_limit   = 40;
									if ( str_word_count( $blog_content ) > $word_limit ) {
										$blog_content = '<p>' . strip_shortcodes( wp_trim_words( $blog_content, $word_limit, '[...]' ) ) . '</p>';
										$blog_content .= '<div class="post-read-more"><a href="' . get_the_permalink() . '">Read More</a></div>';
									}
									$allowed_html = array(
										'p'   => array(
											'class' => array(),
										),
										'div' => array(
											'class' => array(),
										),
										'a'   => array(
											'class' => array(),
											'href'  => array(),
										),
									);
									echo wp_kses( $blog_content, $allowed_html );
									?>
                                </div>
                            </div>
                        </div>
						<?php
					}
					?>
                </div>
				<?php
				$new_args  = array_merge( $args, [ 'posts_per_page' => - 1 ] );
				$new_query = new WP_Query( $new_args );
				if ( $new_query->have_posts() && $new_query->post_count > $limit ) {
					?>
                    <form class="custom-post-form <?php echo esc_attr( $additional_classes ); ?>" id="custom-post-form">
                        <input id="post-args" type="hidden"
                               value="<?php echo esc_attr( str_replace( '"', '\'', wp_json_encode( $args ) ) ); ?>"/>
                        <button data-total="<?php echo esc_attr( $new_query->post_count ); ?>" data-page="1"
                                data-limit="<?php echo esc_attr( $limit ); ?>">Load More
                        </button>
                        <img style="display: none" id="load-more-image" height="35" width="35"
                             src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/loading.svg' ); ?>"
                             alt="Loading"/>
                    </form>
					<?php
				}
				?>
            </div>
			<?php
		} else {
			echo '<p>No posts found.</p>';
		}
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Fetches more posts on scroll or button click
	 *
	 * @return void
	 */
	public function fetch_posts_list(): void {
		if ( wp_verify_nonce( sanitize_text_field( ! empty( $_POST['nonce'] ) ? wp_unslash( $_POST['nonce'] ) : '' ), 'ajax_nonce' ) ) {
			$data      = $_POST;
			$post_html = null;
			if ( ! empty( $data['args'] ) ) {
				$offset         = (int) sanitize_text_field( $data['page'] ) * (int) sanitize_text_field( $data['limit'] );
				$args           = str_replace( "\'", '"', sanitize_text_field( $data['args'] ) );
				$args           = json_decode( $args, true );
				$args['offset'] = $offset;

				$the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) {
	                while ( $the_query->have_posts() ) {
		                $the_query->the_post();
		                $blog_image   = get_the_post_thumbnail_url() ?? plugin_dir_url( __FILE__ ) . 'images/noimage.jpg';
		                $post_html    .= '
                            <div class="custom-post">
                                <div class="custom-post-image">
                                    <img width="345" height="245" src="' . $blog_image . '" alt="' . esc_attr( get_the_title() ) . '" />
                                </div>
                                <div class="custom-post-con">
                                    <h4 class="custom-post-title"><a href="' . get_the_permalink() . '">' . esc_html( get_the_title() ) . '</a></h4>
                                    <div class="custom-post-content">';
		                $blog_content = get_the_content();
		                $word_limit   = 40;
		                if ( str_word_count( $blog_content ) > $word_limit ) {
			                $blog_content = '<p>' . strip_shortcodes( wp_trim_words( $blog_content, $word_limit, '[...]' ) ) . '</p>';
			                $blog_content .= '<div class="post-read-more"><a href="' . get_the_permalink() . '">Load More</a></div>';
		                }
		                $post_html .= $blog_content;
		                $post_html .= '
                                    </div>
                                </div>
                            </div>';
	                }
                    $next_offset =  $offset + (int) $data['limit'];
	                if ( $next_offset >= $data['total'] ) {
		                echo '<span style="display: none">remove-view-more</span>';
	                }
                }
			}
			wp_reset_postdata();
			echo wp_kses_post( $post_html );
			exit;
		}
		exit;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/post-list-with-load-more-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/post-list-with-load-more-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'myAjax',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ajax_nonce' ),
			)
		);
	}

}
