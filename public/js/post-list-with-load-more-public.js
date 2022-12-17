// Add jQuery dependency
(function ($) {
	'use strict';

	$(document).ready(function () {
		$(document).on('click', '#custom-post-form button', function (e) {
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
		});
		let debounce = true;
		const button = $('#custom-post-form.more_on_scroll button');
		if (button.length) {
			$(window).scroll(function () {
				if (button.isInViewport() && debounce) {
					$(button).trigger('click');
					debounce = false;
					setTimeout(function () {
						debounce = true;
					}, 2000);
				}
			});
		}
	});
	$.fn.isInViewport = function () {
		const elementTop = $(this).offset().top;
		const elementBottom = elementTop + $(this).outerHeight();

		const viewportTop = $(window).scrollTop();
		const viewportBottom = viewportTop + $(window).height();

		return elementBottom > viewportTop && elementTop < viewportBottom;
	};
	// eslint-disable-next-line no-undef
})(jQuery);
