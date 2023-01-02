<?php

// @codingStandardsIgnoreStart
$limit        = $attributes['postsPerPage'] ?? 6;
$order        = $attributes['order'] ?? 'desc';
$orderby      = $attributes['orderBy'] ?? 'date';
$post_type    = $attributes['postType'] ?? '';
$taxonomy     = $attributes['taxonomy'] ?? '';
$terms        = $attributes['term'] ?? '';
$layout       = $attributes['layout'] ?? 'grid';
$more_posts   = $attributes['morePosts'] ?? 'click';
$grid_columns = $attributes['gridColumns'] ?? '3';
$list_layout  = $attributes['listLayout'] ?? 'sideBySide';
// @codingStandardsIgnoreEnd

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
			'terms'            => $terms,
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
	$additional_classes .= " style-$layout";
	$additional_classes .= " more-on-$more_posts";
	$additional_classes .= 'list' === $layout ? " list-layout-$list_layout" : '';
	$styles              = 'grid' === $layout ? "grid-template-columns: repeat($grid_columns, minmax(0, 1fr))" : '';
	ob_start();
	?>
	<div <?php echo get_block_wrapper_attributes( [ 'class' => $additional_classes ] ); ?>>
		<div class="custom-post-list" style="<?php echo $styles; ?>">
			<?php
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$blog_image = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : POST_LIST_WITH_LOAD_MORE_PLUGIN_URL . 'assets/images/noimage.jpg';
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
			wp_reset_postdata();
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
		wp_reset_postdata();
		?>
	</div>
	<?php
} else {
	echo '<p>' . esc_html__( 'No posts found.', 'post-list-with-load-more' ) . '</p>';
}

echo ob_get_clean();
