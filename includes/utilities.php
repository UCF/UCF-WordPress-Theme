<?php
/**
 * General utilities
 **/


/**
 * Utility function that returns an image url by its thumbnail size.
 *
 * @since 0.0.0
 * @author Jo Dickson
 * @param int $id Attachment ID
 * @param string $size Image size name
 * @return string Attachment image URL
 **/
function ucfwp_get_attachment_src_by_size( $id, $size ) {
	$attachment = wp_get_attachment_image_src( $id, $size, false );
	if ( is_array( $attachment ) ) {
		return $attachment[0];
	}
	return $attachment;
}


/**
 * Shim for `wp_get_attachment_image()` that forces the maximum
 * width in the image's generated srcset to not exceed the requested
 * image dimensions.
 *
 * @since 0.5.0
 * @author Jo Dickson
 * @param int $attachment_id Image attachment ID.
 * @param mixed $size String or array representing an image size
 * @param bool $icon (Optional) Whether the image should be treated as an icon
 * @param mixed $attr String or array of attributes for the image markup
 * @return string HTML img element or empty string on failure.
 */
function ucfwp_get_attachment_image( $attachment_id, $size='thumbnail', $icon=false, $attr='' ) {
	$use_filter = true;
	$max_srcset_width = is_array( $size ) ? intval( $size[0] ) : intval( get_option( $size . '_size_w' ) );

	if (
		! $max_srcset_width
		|| ! $size
		|| $size === 'full'
	) {
		$use_filter = false;
	}

	$custom_filter = function( $width ) use ( $max_srcset_width ) {
		return $max_srcset_width;
	};

	if ( $use_filter ) {
		add_filter( 'max_srcset_image_width', $custom_filter );
	}

	$image = wp_get_attachment_image( $attachment_id, $size, $icon, $attr );

	if ( $use_filter ) {
		remove_filter( 'max_srcset_image_width', $custom_filter );
	}

	return $image;
}


/**
 * Returns the post excerpt with an optional custom character count.
 * Handles necessary postdata setup.
 *
 * @since 0.5.2
 * @author Jo Dickson
 * @param object $post A WP_Post object
 * @param int $length Specify a custom length for the excerpt.
 * @return string Sanitized post excerpt
 */
function ucfwp_get_excerpt( $post, $length=0 ) {
	if ( ! ( $post instanceof WP_Post ) ) return '';

	if ( $length === 0 ) {
		$length = apply_filters( 'excerpt_length', $length );
	}

	// Allow custom excerpt modification short-circuit
	$excerpt = apply_filters( 'ucfwp_get_excerpt_before', '', $post, $length );
	if ( $excerpt ) {
		return $excerpt;
	}

	setup_postdata( $post );

	$custom_filter = function( $l ) use ( $length ) {
		return $length;
	};

	add_filter( 'excerpt_length', $custom_filter, 999 );
	$excerpt = wp_strip_all_tags( get_the_excerpt( $post ) );
	remove_filter( 'excerpt_length', $custom_filter, 999 );

	return apply_filters( 'ucfwp_get_excerpt', $excerpt, $post, $length );
}


/**
 * Returns a JSON object from the provided URL.  Detects undesirable status
 * codes and returns false if the response doesn't look valid.
 *
 * @since 0.0.0
 * @author Jo Dickson
 * @param string $url URL that points to a JSON object/feed
 * @return mixed JSON-decoded object or false on failure
 */
function ucfwp_fetch_json( $url ) {
	$response      = wp_remote_get( $url, array( 'timeout' => 15 ) );
	$response_code = wp_remote_retrieve_response_code( $response );
	$result        = false;

	if ( is_array( $response ) && is_int( $response_code ) && $response_code < 400 ) {
		$result = json_decode( wp_remote_retrieve_body( $response ) );
	}

	return $result;
}


/**
 * Returns a theme mod's default value from a constant.
 *
 * @since 0.2.2
 * @param string $theme_mod The name of the theme mod
 * @param string $defaults Serialized array of theme mod names + default values
 * @return mixed Theme mod default value, or false if a default is not set
 **/
function ucfwp_get_theme_mod_default( $theme_mod, $defaults=UCFWP_THEME_CUSTOMIZER_DEFAULTS ) {
	$defaults = unserialize( $defaults );
	if ( $defaults && isset( $defaults[$theme_mod] ) ) {
		return $defaults[$theme_mod];
	}
	return false;
}


/**
 * Returns a theme mod value or the default set in
 * $defaults if the theme mod value hasn't been set yet.
 *
 * @since 0.2.2
 * @param string $theme_mod The name of the theme mod
 * @param string $defaults Serialized array of theme mod names + default values
 * @return mixed Theme mod value or its default
 **/
function ucfwp_get_theme_mod_or_default( $theme_mod, $defaults=UCFWP_THEME_CUSTOMIZER_DEFAULTS ) {
	$default = ucfwp_get_theme_mod_default( $theme_mod, $defaults );
	return get_theme_mod( $theme_mod, $default );
}


/**
 * Check if the content is empty
 *
 * @since 0.2.2
 **/
function ucfwp_is_content_empty($str) {
	return trim( str_replace( '&nbsp;', '', strip_tags( $str ) ) ) === '';
}


/**
 * Shim that provides backward compatibility for header markup functions
 * while still utilizing get_template_part() whenever possible.
 *
 * Ideally in a next major release, this function will be replaceable
 * with get_template_part().
 *
 * @since 0.4.0
 * @author Jo Dickson
 * @internal
 * @param string $template_part_slug The template part slug to fetch
 * @param string $template_part_name The template part name to fetch
 */
function _ucfwp_get_template_part( $template_part_slug, $template_part_name ) {
	$shim_retval = '';
	$obj = ucfwp_get_queried_object();

	$videos = ucfwp_get_header_videos( $obj );
	$images = ucfwp_get_header_images( $obj );

	switch ( $template_part_slug ) {
		case ucfwp_get_template_part_slug( 'header' ):
			switch ( $template_part_name ) {
				case '':
					$shim_retval = ucfwp_get_header_default_markup( $obj );
					break;
				case 'media':
					$shim_retval = ucfwp_get_header_media_markup( $obj, $videos, $images );
					break;
				default:
					break;
			}
		case ucfwp_get_template_part_slug( 'header_content' ):
			switch ( $template_part_name ) {
				case 'title_subtitle':
					$shim_retval = ucfwp_get_header_content_title_subtitle( $obj );
					break;
				case 'custom':
					$shim_retval = ucfwp_get_header_content_custom( $obj );
					break;
				default:
					break;
			}
		default:
			break;
	}

	if ( $shim_retval ) {
		echo $shim_retval;
	}
	else {
		get_template_part( $template_part_slug, $template_part_name );
	}
}


/**
 * Returns a template part slug suitable for use as the
 * $slug param in get_template_part().
 *
 * @author Jo Dickson
 * @since 0.4.0
 * @param string $subpath An optional subdirectory within the template parts directory
 * @return string The desired template part slug (for this theme and child themes)
 */
if ( ! function_exists( 'ucfwp_get_template_part_slug' ) ) {
	function ucfwp_get_template_part_slug( $subpath='' ) {
		if ( $subpath ) {
			$subpath = '/' . $subpath;
		}
		return UCFWP_THEME_TEMPLATE_PARTS_PATH . $subpath;
	}
}


/**
 * Wrapper for get_queried_object() with opinionated overrides for this theme.
 * Sets a `ucfwp_obj` query var on the global $wp_query object for fast
 * reference in subsequent requests for the queried object.
 *
 * @see https://codex.wordpress.org/Function_Reference/get_queried_object
 *
 * @since 0.4.0
 * @author Jo Dickson
 * @return mixed The queried object, or null if no valid object was queried
 */
function ucfwp_get_queried_object() {
	// If ucfwp_obj is already a set query param, return it.
	// Note that a set value may still be null, but valid.
	//
	// We reference $wp_query here directly because we have no
	// other means of determining the difference between an
	// unset value and a set, but empty/null, value.
	global $wp_query;
	if ( $wp_query && array_key_exists( 'ucfwp_obj', $wp_query->query_vars ) ) {
		return $wp_query->query_vars['ucfwp_obj'];
	}

	$obj = get_queried_object();

	if ( !$obj && is_404() ) {
		$page = get_page_by_title( '404' );
		if ( $page && $page->post_status === 'publish' ) {
			$obj = $page;
		}
	}

	// Store as a query var on $wp_query for reference in
	// subsequent queried object requests:
	set_query_var( 'ucfwp_obj', $obj );

	return $obj;
}
