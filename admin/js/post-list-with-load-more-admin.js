/**
 *
 * Add jQuery dependency
 *
 * @package post-list-with-read-more
 */

(function ( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$( document ).ready(
		function () {
			$( document ).on(
				'change',
				'.shortcode-form #posttype',
				function () {
					$( '.shortcode-form #taxonomy' ).html( '<option value="" selected="selected">-- Select Category Type --</option>' );
					$( '.shortcode-form #term' ).html( '<option value="" selected="selected">-- Select Category --</option>' ).removeAttr( 'multiple' );
					const posttype = $( this ).val();
					if ( '' !== posttype && undefined !== posttype ) {
						if ( 'post' === posttype ) {
							$( '.shortcode-form .input-control.tags-control' ).css( 'display', 'block' );
						} else {
							$( '.shortcode-form .input-control.tags-control' ).css( 'display', 'none' );
							$( '#show-tags' ).empty();
						}
						$( '#show-posttype' ).html( 'post_type="' + posttype + '"' );
						$( '#show-taxonomy' ).empty();
						$( '#show-terms' ).empty();
						$.ajax(
							{
								/* jshint ignore: start */
								url: postListAjax.ajaxurl,
								/* jshint ignore: end */
								type: 'post',
								data: {
									action: 'post_list_callback',
									purpose: 'getTaxonomies',
									posttype: posttype,
									/* jshint ignore: start */
									nonce: postListAjax.nonce
									/* jshint ignore: end */
								},
								success: function ( response ) {
									if ( response.indexOf( 'notfound' ) < 0 ) {
										const parsed_html = $( '<div />' ).html( response ).text();
										$( '.shortcode-form #taxonomy' ).append( parsed_html );
									}
								}
							}
						);
					} else {
						$( '#show-posttype' ).empty();
						$( '#show-taxonomy' ).empty();
						$( '#show-terms' ).empty();
					}
				}
			);
			$( document ).on(
				'change',
				'.shortcode-form #taxonomy',
				function () {
					$( '.shortcode-form #term' ).html( '<option value="" selected="selected">-- Select Category --</option>' ).removeAttr( 'multiple' );
					const taxonomy = $( this ).val();
					if ( '' !== taxonomy && undefined !== taxonomy ) {
						$( '#show-taxonomy' ).html( 'taxonomy="' + taxonomy + '"' );
						$.ajax(
							{
								/* jshint ignore:start */
								url: postListAjax.ajaxurl,
								/* jshint ignore:end */
								type: 'post',
								data: {
									action: 'post_list_callback',
									purpose: 'getTerms',
									taxonomy: taxonomy,
									/* jshint ignore: start */
									nonce: postListAjax.nonce
									/* jshint ignore: end */
								},
								success: function ( response ) {
									if ( response.indexOf( 'notfound' ) < 0 ) {
										const parsed_html = $( '<div />' ).html( response ).text();
										$( '.shortcode-form #term' ).append( parsed_html ).attr( 'multiple', 'multiple' );
									}
								}
							}
						);
					} else {
						$( '#show-taxonomy' ).empty();
						$( '#show-terms' ).empty();
					}
				}
			);
			$( document ).on(
				'change',
				'.shortcode-form #term',
				function () {
					const terms = $( this ).val();
					if ( Array.isArray( terms ) ) {
						$( '#show-terms' ).html( 'terms="' + terms.join() + '"' );
					} else {
						$( '#show-terms' ).empty();
					}
				}
			);

			$( document ).on(
				'change',
				'.shortcode-form #tag',
				function () {
					const tags = $( this ).val();
					if ( Array.isArray( tags ) ) {
						const filtered = tags.filter(
							function ( el ) {
								return el !== '';
							}
						);
						$( '#show-tags' ).html( 'tags="' + filtered.join() + '"' );
					} else {
						$( '#show-tags' ).empty();
					}
				}
			);
			$( document ).on(
				'change',
				'.shortcode-form #limit',
				function () {
					const limit = $( this ).val();
					if ( '' !== limit && undefined !== limit ) {
						$( '#show-limit' ).html( 'limit="' + limit + '"' );
					} else {
						$( '#show-limit' ).empty();
					}
				}
			);
			$( document ).on(
				'change',
				'.shortcode-form #orderby',
				function () {
					const orderby = $( this ).val();
					if ( '' !== orderby && undefined !== orderby ) {
						$( '#show-orderby' ).html( 'orderby="' + orderby + '"' );
					} else {
						$( '#show-orderby' ).empty();
					}
				}
			);
			$( document ).on(
				'change',
				'.shortcode-form #order',
				function () {
					const order = $( this ).val();
					if ( '' !== order && undefined !== order ) {
						$( '#show-order' ).html( 'order="' + order + '"' );
					} else {
						$( '#show-order' ).empty();
					}
				}
			);
		}
	);
})( jQuery );
