<?php
/**
 * Handles post list template for public.
 *
 * @package post-list-with-load-more
 */

namespace Post_List_With_Load_More;

use Post_List_With_Load_More\Traits\Singleton;

/**
 * Front class.
 */
class Front {

	use Singleton;

	/**
	 * Constructor method.
	 */
	protected function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_fetch_posts_list', [ $this, 'fetch_posts_list' ] );
		add_action( 'wp_ajax_nopriv_fetch_posts_list', [ $this, 'fetch_posts_list' ] );
		add_shortcode( 'post_list_with_load_more', [ $this, 'render_posts_list' ] );
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
			<p><?php esc_html_e( 'No posts found.', 'post-list-with-load-more' ); ?></p>
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
				$terms = [ $terms ];
			}

            // phpcs:ignore
			$args['tax_query'] = [
				[
					'taxonomy'         => $taxonomy,
					'field'            => 'slug',
					'terms'            => $terms, // Where term_id of Term 1 is "1".
					'include_children' => false,
				],
			];
		}

		if ( ! empty( $tags ) ) {
			$tags = str_replace( ' ', '', $tags );
			if ( str_contains( $tags, ',' ) !== false ) {
				$tags = explode( ',', $tags );
			} else {
				$tags = [ $tags ];
			}

			$args['tag_slug__in'] = $tags;
		}

		$the_query = new \WP_Query( $args );
		if ( $the_query->have_posts() ) {
			$additional_classes  = '';
			$style               = get_option( 'layout_style' );
			$additional_classes .= ( '' !== $style && null !== $style ) ? ' style-' . $style : '';
			$loadmore            = get_option( 'loadmore' );
			$additional_classes .= ( '' !== $loadmore && null !== $loadmore ) ? ' more-on-' . $loadmore : '';
			$additional_classes .= ( 'list' === $style ) ? ' list-layout-sideBySide' : '';

			ob_start();
			?>
			<div class="wp-block-ramizmanked-post-list-with-load-more <?php echo esc_attr( $additional_classes ); ?>">
				<div class="custom-post-list">
					<?php
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$blog_image = get_the_post_thumbnail_url() ?: POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/images/noimage.jpg';
						?>
						<div class="custom-post">
							<div class="custom-post-image">
								<img width="345" height="245" src="<?php echo esc_url( $blog_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"/>
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
										$blog_content  = '<p>' . strip_shortcodes( wp_trim_words( $blog_content, $word_limit, '[...]' ) ) . '</p>';
										$blog_content .= '<div class="post-read-more"><a href="' . get_the_permalink() . '">' . esc_html__( 'Read More', 'post-list-with-load-more' ) . '</a></div>';
									}
									$allowed_html = [
										'p'   => [ 'class' => [] ],
										'div' => [ 'class' => [] ],
										'a'   => [
											'class' => [],
											'href'  => [],
										],
									];
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
				$new_query = new \WP_Query( $new_args );
				if ( $new_query->have_posts() && $new_query->post_count > $limit ) {
					?>
					<form class="custom-post-form" id="custom-post-form">
						<input id="post-args" type="hidden" value="<?php echo esc_attr( str_replace( '"', '\'', wp_json_encode( $args ) ) ); ?>"/>
						<button class="wp-block-button__link" data-total="<?php echo esc_attr( $new_query->post_count ); ?>" data-page="1"
								data-limit="<?php echo esc_attr( $limit ); ?>">Load More
						</button>
						<img style="display: none" id="load-more-image" height="35" width="35" src="<?php echo esc_url( POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/images/loading.svg' ); ?>" alt="Loading" />
					</form>
					<?php
				}
				?>
			</div>
			<?php
		} else {
			echo '<p>' . esc_html__( 'No posts found.', 'post-list-with-load-more' ) . '</p>';
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

				$the_query = new \WP_Query( $args );
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$blog_image   = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/images/noimage.jpg';
						$post_html   .= '
                            <div class="custom-post">
                                <div class="custom-post-image">
                                    <img width="345" height="245" src="' . esc_url( $blog_image ) . '" alt="' . esc_attr( get_the_title() ) . '" />
                                </div>
                                <div class="custom-post-con">
                                    <h4 class="custom-post-title"><a href="' . get_the_permalink() . '">' . esc_html( get_the_title() ) . '</a></h4>
                                    <div class="custom-post-content">';
						$blog_content = get_the_content();
						$word_limit   = 40;
						if ( str_word_count( $blog_content ) > $word_limit ) {
							$blog_content  = '<p>' . strip_shortcodes( wp_trim_words( $blog_content, $word_limit, '[...]' ) ) . '</p>';
							$blog_content .= '<div class="post-read-more"><a href="' . get_the_permalink() . '">' . esc_html__( 'Read More', 'post-list-with-load-more' ) . '</a></div>';
						}
						$post_html .= $blog_content;
						$post_html .= '
                                    </div>
                                </div>
                            </div>';
					}
					$next_offset = $offset + (int) $data['limit'];
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
	 * @return void
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style(
			POST_LIST_WITH_LOAD_MORE_PLUGIN_SHARED_STYLE_HANDLE,
			POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'build/style-index.css',
			[],
			POST_LIST_WITH_LOAD_MORE_VERSION
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
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
}

Front::get_instance();
