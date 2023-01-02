/**
 * Plugin script for backend, mostly handles shortcode generator via plugin settings.
 *
 * @param {Object} $ jQuery object.
 */
(function ($) {
	'use strict';

	$(document).ready(function () {
		$(document).on('change', '.shortcode-form #posttype', function () {
			$('.shortcode-form #taxonomy').html(
				'<option value="" selected="selected">-- Select Category Type --</option>'
			);
			$('.shortcode-form #term')
				.html(
					'<option value="" selected="selected">-- Select Category --</option>'
				)
				.removeAttr('multiple');

			const posttype = $(this).val();
			if ('' !== posttype && undefined !== posttype) {
				if ('post' === posttype) {
					$('.shortcode-form .input-control.tags-control').css(
						'display',
						'block'
					);
				} else {
					$('.shortcode-form .input-control.tags-control').css(
						'display',
						'none'
					);
					$('#show-tags').empty();
				}

				$('#show-posttype').html('post_type="' + posttype + '"');
				$('#show-taxonomy').empty();
				$('#show-terms').empty();

				$.ajax({
					// eslint-disable-next-line no-undef
					url: postListAjax.ajaxurl,
					type: 'post',
					data: {
						action: 'post_list_callback',
						purpose: 'getTaxonomies', // eslint-disable-next-line no-undef
						nonce: postListAjax.nonce,
						posttype,
					},
					success(response) {
						if (response.indexOf('notfound') < 0) {
							const parsedHTML = $('<div />')
								.html(response)
								.text();
							$('.shortcode-form #taxonomy').append(parsedHTML);
						}
					},
				});
			} else {
				$('#show-posttype').empty();
				$('#show-taxonomy').empty();
				$('#show-terms').empty();
			}
		});
		$(document).on('change', '.shortcode-form #taxonomy', function () {
			$('.shortcode-form #term')
				.html(
					'<option value="" selected="selected">-- Select Category --</option>'
				)
				.removeAttr('multiple');
			const taxonomy = $(this).val();
			if ('' !== taxonomy && undefined !== taxonomy) {
				$('#show-taxonomy').html('taxonomy="' + taxonomy + '"');
				$.ajax({
					// eslint-disable-next-line no-undef
					url: postListAjax.ajaxurl,
					type: 'post',
					data: {
						action: 'post_list_callback',
						purpose: 'getTerms',
						taxonomy, // eslint-disable-next-line no-undef
						nonce: postListAjax.nonce,
					},
					success(response) {
						if (response.indexOf('notfound') < 0) {
							const parsedHTML = $('<div />')
								.html(response)
								.text();
							$('.shortcode-form #term')
								.append(parsedHTML)
								.attr('multiple', 'multiple');
						}
					},
				});
			} else {
				$('#show-taxonomy').empty();
				$('#show-terms').empty();
			}
		});
		$(document).on('change', '.shortcode-form #term', function () {
			const terms = $(this).val();
			if (Array.isArray(terms)) {
				$('#show-terms').html('terms="' + terms.join() + '"');
			} else {
				$('#show-terms').empty();
			}
		});

		$(document).on('change', '.shortcode-form #tag', function () {
			const tags = $(this).val();
			if (Array.isArray(tags)) {
				const filtered = tags.filter(function (el) {
					return el !== '';
				});
				$('#show-tags').html('tags="' + filtered.join() + '"');
			} else {
				$('#show-tags').empty();
			}
		});
		$(document).on('change', '.shortcode-form #limit', function () {
			const limit = $(this).val();
			if ('' !== limit && undefined !== limit) {
				$('#show-limit').html('limit="' + limit + '"');
			} else {
				$('#show-limit').empty();
			}
		});
		$(document).on('change', '.shortcode-form #orderby', function () {
			const orderby = $(this).val();
			if ('' !== orderby && undefined !== orderby) {
				$('#show-orderby').html('orderby="' + orderby + '"');
			} else {
				$('#show-orderby').empty();
			}
		});
		$(document).on('change', '.shortcode-form #order', function () {
			const order = $(this).val();
			if ('' !== order && undefined !== order) {
				$('#show-order').html('order="' + order + '"');
			} else {
				$('#show-order').empty();
			}
		});
	});
	// eslint-disable-next-line no-undef
})(jQuery);
