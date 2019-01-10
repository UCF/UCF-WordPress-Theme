<?php
/**
 * General utilities
 **/

/**
 * Given a WP_Term or WP_Post object, returns the relevant object ID property
 * or null.
 *
 * @since 0.0.0
 * @author Jo Dickson
 * @param object $obj WP_Post or WP_Term object
 * @return mixed Post or Term object ID integer, or null on failure
 **/
function ucfwp_get_object_id( $obj ) {
	$obj_id = null;

	if ( $obj instanceof WP_Post ) {
		$obj_id = $obj->ID;
	}
	else if ( $obj instanceof WP_Term ) {
		$obj_id = $obj->term_id;
	}

	return $obj_id;
}


/**
 * Given a WP_Term or WP_Post object, returns the relevant $post_id argument
 * for ACF field retrieval/modification functions (e.g. get_field()) or null.
 *
 * @see https://www.advancedcustomfields.com/resources/get_field/ ACF get_field() docs (scroll to "Get a value from different objects")
 * @since 0.0.0
 * @author Jo Dickson
 * @param object $obj WP_Post or WP_Term object
 * @return mixed ACF $post_id argument for the Post or Term, or null on failure
 **/
function ucfwp_get_object_field_id( $obj ) {
	$field_id = null;

	if ( $obj instanceof WP_Post ) {
		$field_id = $obj->ID;
	}
	else if ( $obj instanceof WP_Term ) {
		$field_id = $obj->taxonomy . '_' . $obj->term_id;
	}

	return $field_id;
}


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
