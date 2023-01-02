(function ($) {
	'use strict';

	let debounce = true;
	let buttonElem;

	$(document).ready(function () {
		buttonElem = $(
			'.wp-block-ramizmanked-post-list-with-load-more.more-on-scroll #custom-post-form button'
		);

		$(document).on(
			'click',
			'.wp-block-ramizmanked-post-list-with-load-more #custom-post-form button',
			function (e) {
				e.preventDefault();
				$('#load-more-image').css('display', 'block');
				$(this).css('display', 'none');
				const page = $(this).attr('data-page');
				const limit = $(this).attr('data-limit');
				const total = $(this).attr('data-total');
				const args = $('#post-args').val();
				$.ajax({
					// eslint-disable-next-line no-undef
					url: myAjax.ajaxurl,
					type: 'post',
					data: {
						action: 'fetch_posts_list',
						page,
						limit,
						total,
						args, // eslint-disable-next-line no-undef
						nonce: myAjax.nonce,
					},
					success(response) {
						$('.custom-post-list').append(response);
						if (response.indexOf('remove-view-more') > -1) {
							$('#custom-post-form').remove();
							$('#load-more-image').css('display', 'none');
						} else {
							$('#load-more-image').css('display', 'none');
							$('#custom-post-form button')
								.css('display', 'block')
								.attr('data-page', parseInt(page) + 1);
						}
					},
				});
			}
		);

		$(window).trigger('scroll');
	});

	window.onscroll = function () {
		if (buttonElem.length) {
			if (buttonElem.isInViewport() && debounce) {
				$(buttonElem).trigger('click');
				debounce = false;
				setTimeout(function () {
					debounce = true;
				}, 500);
			}
		}
	};

	$.fn.isInViewport = function () {
		const bounding = $(this).get(0).getBoundingClientRect();

		return (
			bounding.top >= 0 &&
			bounding.left >= 0 &&
			bounding.right <=
				(window.innerWidth || document.documentElement.clientWidth) &&
			bounding.bottom <=
				(window.innerHeight || document.documentElement.clientHeight)
		);
	};
	// eslint-disable-next-line no-undef
})(jQuery);
