<?php
/**
 * Footer Related Functions
 **/

/**
 * Returns the type of footer to use for the given object.
 * The value returned will represent an equivalent template part's name.
 *
 * @author Jo Dickson
 * @since TODO
 * @param mixed $obj A queried object (e.g. WP_Post, WP_Term), or null
 * @return string The footer type name
 */
function ucfwp_get_footer_type( $obj ) {
	$footer_type = '';

	return apply_filters( 'ucfwp_get_footer_type', $footer_type, $obj );
}


/**
 * Returns markup for the site footer. Will return an empty string if all
 * footer sidebars are empty.
 *
 * @author Jo Dickson
 * @since 0.2.0
 * @return string Footer HTML markup
 **/
if ( !function_exists( 'ucfwp_get_footer_markup' ) ) {
	function ucfwp_get_footer_markup() {
		$retval = '';
		$obj    = ucfwp_get_queried_object();

		$template_part_name = ucfwp_get_footer_type( $obj );

		ob_start();
		get_template_part( ucfwp_get_template_part_slug( 'footer' ), $template_part_name );
		$retval = ob_get_clean();

		return apply_filters( 'ucfwp_get_footer_markup', $retval, $obj );
	}
}
