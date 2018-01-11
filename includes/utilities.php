<?php
/**
 * General utilities
 **/
function ucfwp_format_raw_postmeta( $postmeta ) {
	$retval = array();

	foreach( $postmeta as $key=>$val ) {
		if ( is_array( $val ) && count( $val ) === 1 ) {
			$retval[$key] = $val[0];
		} else {
			$retval[$key] = $val;
		}
	}

	return $retval;
}


/**
 * Given a WP_Term or WP_Post object, returns the relevant object ID property
 * or null.
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
 * @since 1.0.0
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
