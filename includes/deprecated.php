<?php
/**
 * The functions in this file are included for the sake of backward
 * compatibility within the current major version of the theme.
 * They will be removed in the next major release.
 *
 * Do not use the functions below in new code.
 */


/**
 * Given a queried object, returns the relevant object ID property
 * or null.
 *
 * @since 0.0.0
 * @deprecated 0.4.0
 * @author Jo Dickson
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
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
 * @deprecated 0.4.0 Pass the entire queried object to get_field() instead
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
 * Returns markup for page header title + subtitles within headers that use a
 * media background.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @deprecated 0.4.0 Use ucfwp_get_header_content_markup()
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the page title + subtitle
 **/
if ( !function_exists( 'ucfwp_get_header_content_title_subtitle' ) ) {
	function ucfwp_get_header_content_title_subtitle( $obj ) {
		set_query_var( 'ucfwp_obj', $obj );

		ob_start();
		get_template_part( ucfwp_get_template_part_slug( 'header_content' ), 'title_subtitle' );
		return ob_get_clean();
	}
}


/**
 * Returns markup for page header custom content.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @deprecated 0.4.0 Use ucfwp_get_header_content_markup()
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the custom page header contents
 **/
if ( !function_exists( 'ucfwp_get_header_content_custom' ) ) {
	function ucfwp_get_header_content_custom( $obj ) {
		set_query_var( 'ucfwp_obj', $obj );

		ob_start();
		get_template_part( ucfwp_get_template_part_slug( 'header_content' ), 'custom' );
		return ob_get_clean();
	}
}


/**
 * Returns the markup for page headers with media backgrounds.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @deprecated 0.4.0 Use ucfwp_get_header_markup()
 * @param object $obj A WP_Post or WP_Term object
 * @param array $videos Deprecated
 * @param array $images Deprecated
 * @return string HTML for the page header
 **/
if ( !function_exists( 'ucfwp_get_header_media_markup' ) ) {
	function ucfwp_get_header_media_markup( $obj, $videos, $images ) {
		set_query_var( 'ucfwp_obj', $obj );

		ob_start();
		get_template_part( ucfwp_get_template_part_slug( 'header' ), 'media' );
		return ob_get_clean();
	}
}


/**
 * Returns the default markup for page headers without a media background.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @deprecated 0.4.0 Use ucfwp_get_header_markup()
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the page header
 **/
if ( !function_exists( 'ucfwp_get_header_default_markup' ) ) {
	function ucfwp_get_header_default_markup( $obj ) {
		set_query_var( 'ucfwp_obj', $obj );

		ob_start();
		get_template_part( ucfwp_get_template_part_slug( 'header' ) );
		return ob_get_clean();
	}
}


/**
 * Returns inner navbar markup for ucf.edu's primary site navigation.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @deprecated 0.4.0 Use ucfwp_get_nav_markup()
 * @return string HTML markup
 */
if ( !function_exists( 'ucfwp_get_mainsite_menu' ) ) {
	function ucfwp_get_mainsite_menu( $image=true ) {
		set_query_var( 'ucfwp_image_behind_nav', $image );

		ob_start();
		get_template_part( ucfwp_get_template_part_slug( 'header' ), 'mainsite' );
		return ob_get_clean();
	}
}
