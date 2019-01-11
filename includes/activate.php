<?php
/**
 * All post-theme activation steps should be registered in this file.
 */


/**
 * Prints an error message in the WordPress admin notifying the user
 * that ACF PRO is a required plugin for this theme.
 *
 * @since 0.2.7
 * @author Jo Dickson
 */
function ucfwp_activation_error_acf() {
	ob_start();
?>
	<div class="notice notice-error">
		<p><strong>Theme not activated:</strong> The UCF WordPress Theme requires the <a href="https://www.advancedcustomfields.com/pro/" target="_blank">Advanced Custom Fields PRO</a> plugin.  Please install and activate ACF PRO and try again.</p>
	</div>
<?php
	echo ob_get_clean();
}


/**
 * Performs verification checks immediately upon theme activation
 * to ensure required dependencies are installed. Will revert to the
 * previously-active theme if a requirement isn't met.
 *
 * @since 0.2.7
 * @author Jo Dickson
 * @param string $oldtheme_name The name of the theme that was active prior to switching to this theme
 * @param object $oldtheme WP_Theme instance of the old theme.
 */
function ucfwp_activation_checks( $oldtheme_name, $oldtheme ) {
	$do_revert = false;

	// Require ACF PRO
	if ( ! class_exists( 'acf_pro' ) ) {
		$do_revert = true;
		add_action( 'admin_notices', 'ucfwp_activation_error_acf' );
	}

	// Switch back to previous theme if a requirement is missing
	if ( $do_revert ) {
		switch_theme( $oldtheme->stylesheet );
	}

	return false;
}

add_action( 'after_switch_theme', 'ucfwp_activation_checks', 10, 2 );
