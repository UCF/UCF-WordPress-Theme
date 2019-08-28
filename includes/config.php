<?php
/**
 * Handle all theme configuration here
 **/

define( 'UCFWP_THEME_URL', get_template_directory_uri() );
define( 'UCFWP_THEME_STATIC_URL', UCFWP_THEME_URL . '/static' );
define( 'UCFWP_THEME_CSS_URL', UCFWP_THEME_STATIC_URL . '/css' );
define( 'UCFWP_THEME_JS_URL', UCFWP_THEME_STATIC_URL . '/js' );
define( 'UCFWP_THEME_IMG_URL', UCFWP_THEME_STATIC_URL . '/img' );
define( 'UCFWP_THEME_TEMPLATE_PARTS_PATH', 'template-parts' );
define( 'UCFWP_THEME_CUSTOMIZER_PREFIX', 'ucfwp_' );
define( 'UCFWP_MAINSITE_NAV_URL', 'https://www.ucf.edu/wp-json/ucf-rest-menus/v1/menus/23' );
define( 'UCFWP_THEME_CUSTOMIZER_DEFAULTS', serialize( array(
	'person_thumbnail' => UCFWP_THEME_STATIC_URL . '/img/person-no-photo.png'
) ) );


/**
 * Initialization functions to be fired early when WordPress loads the theme.
 */
function ucfwp_init() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	add_theme_support( 'title-tag' );

	add_image_size( 'header-img', 575, 575, true );
	add_image_size( 'header-img-sm', 767, 500, true );
	add_image_size( 'header-img-md', 991, 500, true );
	add_image_size( 'header-img-lg', 1199, 500, true );
	add_image_size( 'header-img-xl', 1600, 500, true );
	add_image_size( 'bg-img', 575, 2000, true );
	add_image_size( 'bg-img-sm', 767, 2000, true );
	add_image_size( 'bg-img-md', 991, 2000, true );
	add_image_size( 'bg-img-lg', 1199, 2000, true );
	add_image_size( 'bg-img-xl', 1600, 2000, true );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );

	register_sidebar( array(
		'name'          => __( 'Footer - Column 1' ),
		'id'            => 'footer-col-1',
		'description'   => 'First column in the site footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="h6 heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer - Column 2' ),
		'id'            => 'footer-col-2',
		'description'   => 'Second column in the site footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="h6 heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer - Column 3' ),
		'id'            => 'footer-col-3',
		'description'   => 'Third column in the site footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="h6 heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer - Column 4' ),
		'id'            => 'footer-col-4',
		'description'   => 'Last column in the site footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget mb-5 %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="h6 heading-underline letter-spacing-3">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'after_setup_theme', 'ucfwp_init' );


/**
 * Defines sections used in the WordPress Customizer.
 */
function ucfwp_define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		UCFWP_THEME_CUSTOMIZER_PREFIX . 'nav_settings',
		array(
			'title' => 'Navigation Settings',
			'panel' => 'nav_menus'
		)
	);

	$wp_customize->add_section(
		UCFWP_THEME_CUSTOMIZER_PREFIX . 'webfonts',
		array(
			'title' => 'Web Fonts'
		)
	);

	$wp_customize->add_section(
		UCFWP_THEME_CUSTOMIZER_PREFIX . 'analytics',
		array(
			'title' => 'Analytics'
		)
	);
}

add_action( 'customize_register', 'ucfwp_define_customizer_sections' );


/**
 * Defines settings and controls used in the WordPress Customizer.
 */
function ucfwp_define_customizer_fields( $wp_customize ) {
	// Menus
	$wp_customize->add_setting(
		'mainsite_nav_url',
		array(
			'default' => UCFWP_MAINSITE_NAV_URL
		)
	);

	$wp_customize->add_control(
		'mainsite_nav_url',
		array(
			'type'        => 'text',
			'label'       => 'ucf.edu Primary Navigation JSON',
			'description' => 'URL that points to a JSON feed of ucf.edu\'s primary navigation data.',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'nav_settings'
		)
	);

	// Web Fonts
	$wp_customize->add_setting(
		'cloud_typography_key'
	);

	$wp_customize->add_control(
		'cloud_typography_key',
		array(
			'type'        => 'text',
			'label'       => 'Cloud.Typography CSS Key URL',
			'description' => 'The CSS Key provided by Cloud.Typography for this project.  <strong>Only include the value in the "href" portion of the link
								tag provided; e.g. "//cloud.typography.com/000000/000000/css/fonts.css".</strong><br><br>NOTE: Make sure the Cloud.Typography
								project has been configured to deliver fonts to this site\'s domain.<br>
								See the <a target="_blank" href="http://www.typography.com/cloud/user-guide/managing-domains">Cloud.Typography docs on managing domains</a> for more info.',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'webfonts'
		)
	);

	// Analytics
	$wp_customize->add_setting(
		'gw_verify'
	);

	$wp_customize->add_control(
		'gw_verify',
		array(
			'type'        => 'text',
			'label'       => 'Google WebMaster Verification',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	$wp_customize->add_setting(
		'gtm_id'
	);

	$wp_customize->add_control(
		'gtm_id',
		array(
			'type'        => 'text',
			'label'       => 'Google Tag Manager Container ID',
			'description' => 'Example: <em>GTM-XXXX</em>.<br>Takes precedence over a Google Analytics Account value below if both are provided (assumes Google Analytics is included as a tag in your Google Tag Manager container).',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	$wp_customize->add_setting(
		'ga_account'
	);

	$wp_customize->add_control(
		'ga_account',
		array(
			'type'        => 'text',
			'label'       => 'Google Analytics Account',
			'description' => 'Example: <em>UA-9876543-21</em>.<br>Leave blank for development, or if you\'ve provided a Google Tag Manager Container ID and include Google Analytics via Google Tag Manager.',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	$wp_customize->add_setting(
		'chartbeat_uid'
	);

	$wp_customize->add_control(
		'chartbeat_uid',
		array(
			'type'        => 'text',
			'label'       => 'Chartbeat UID',
			'description' => 'Example: <em>1842</em>',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);

	$wp_customize->add_setting(
		'chartbeat_domain'
	);

	$wp_customize->add_control(
		'chartbeat_domain',
		array(
			'type'        => 'text',
			'label'       => 'Chartbeat Domain',
			'description' => 'Example: <em>some.domain.com</em>',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);
}

add_action( 'customize_register', 'ucfwp_define_customizer_fields' );


/**
 * Allow extra file types to be uploaded to the media library.
 **/
function ucfwp_custom_mimes( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['json'] = 'application/json';

	return $mimes;
}

add_filter( 'upload_mimes', 'ucfwp_custom_mimes' );


/**
 * Enable TinyMCE formatting options in the Athena Shortcodes plugin.
 **/
if ( function_exists( 'athena_sc_tinymce_init' ) ) {
	add_filter( 'athena_sc_enable_tinymce_formatting', '__return_true' );
}


/**
 * Allow special tags in post bodies that would get stripped otherwise for most users.
 * Modifies $allowedposttags defined in wp-includes/kses.php
 *
 * http://wordpress.org/support/topic/div-ids-being-stripped-out
 * http://wpquicktips.wordpress.com/2010/03/12/how-to-change-the-allowed-html-tags-for-wordpress/
 **/
$allowedposttags['input'] = array(
	'type' => array(),
	'value' => array(),
	'id' => array(),
	'name' => array(),
	'class' => array()
);
$allowedposttags['select'] = array(
	'id' => array(),
	'name' => array()
);
$allowedposttags['option'] = array(
	'id' => array(),
	'name' => array(),
	'value' => array()
);
$allowedposttags['iframe'] = array(
	'type' => array(),
	'value' => array(),
	'id' => array(),
	'name' => array(),
	'class' => array(),
	'src' => array(),
	'height' => array(),
	'width' => array(),
	'allowfullscreen' => array(),
	'frameborder' => array()
);
$allowedposttags['object'] = array(
	'height' => array(),
	'width' => array()
);

$allowedposttags['param'] = array(
	'name' => array(),
	'value' => array()
);

$allowedposttags['embed'] = array(
	'src' => array(),
	'type' => array(),
	'allowfullscreen' => array(),
	'allowscriptaccess' => array(),
	'height' => array(),
	'width' => array()
);
// Most of these attributes aren't actually valid for some of
// the tags they're assigned to, but whatever:
$allowedposttags['div'] =
$allowedposttags['a'] =
$allowedposttags['button'] = array(
	'id' => array(),
	'class' => array(),
	'style' => array(),
	'width' => array(),
	'height' => array(),

	'align' => array(),
	'aria-hidden' => array(),
	'aria-labelledby' => array(),
	'autofocus' => array(),
	'dir' => array(),
	'disabled' => array(),
	'form' => array(),
	'formaction' => array(),
	'formenctype' => array(),
	'formmethod' => array(),
	'formonvalidate' => array(),
	'formtarget' => array(),
	'hidden' => array(),
	'href' => array(),
	'name' => array(),
	'rel' => array(),
	'rev' => array(),
	'role' => array(),
	'target' => array(),
	'type' => array(),
	'title' => array(),
	'value' => array(),

	// Bootstrap JS stuff:
	'data-dismiss' => array(),
	'data-toggle' => array(),
	'data-target' => array(),
	'data-backdrop' => array(),
	'data-spy' => array(),
	'data-offset' => array(),
	'data-animation' => array(),
	'data-html' => array(),
	'data-placement' => array(),
	'data-selector' => array(),
	'data-title' => array(),
	'data-trigger' => array(),
	'data-delay' => array(),
	'data-content' => array(),
	'data-offset' => array(),
	'data-offset-top' => array(),
	'data-loading-text' => array(),
	'data-complete-text' => array(),
	'autocomplete' => array(),
	'data-parent' => array(),
);


/**
 * Remove paragraph tag from excerpts
 **/
remove_filter( 'the_excerpt', 'wpautop' );


/**
 * Kill attachment pages, author pages, daily archive pages, search, and feeds.
 *
 * http://betterwp.net/wordpress-tips/disable-some-wordpress-pages/
 **/
function ucfwp_kill_unused_templates() {
	global $wp_query, $post;

	if ( is_author() || is_attachment() || is_date() || is_search() || is_feed() || is_comment_feed() ) {
		wp_redirect( home_url() );
		exit();
	}
}

add_action( 'template_redirect', 'ucfwp_kill_unused_templates' );


/**
 *
 */
function ucfwp_modify_attachment_links( $link, $post_id ) {
	$do_rewrites = has_action( 'template_redirect', 'ucfwp_kill_unused_templates' ) !== false ? true : false;
	// Let child themes/plugins override this behavior:
	if ( has_filter( 'ucfwp_enable_attachment_link_rewrites' ) !== false ) {
		$do_rewrites = filter_var( apply_filters( 'ucfwp_enable_attachment_link_rewrites', $do_rewrites ), FILTER_VALIDATE_BOOLEAN );
	}

	if ( $do_rewrites ) {
		return wp_get_attachment_url( $post_id );
	}
}

add_filter( 'attachment_link', 'ucfwp_modify_attachment_links', 20, 2 );


/**
 * Disable widgets that aren't supported by this theme.
 */
function ucfwp_kill_unused_widgets() {
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Media_Gallery' );
}

add_action( 'widgets_init', 'ucfwp_kill_unused_widgets' );


/**
 * An opinionated set of overrides for this theme that disables comments,
 * trackbacks, and pingbacks sitewide, and hides references to comments in the
 * WordPress admin to reduce clutter.
 *
 * @since 0.3.0
 * @author Jo Dickson
 */
function ucfwp_kill_comments() {
	// Remove the X-Pingback HTTP header, if present.
	add_filter( 'wp_headers', function( $headers ) {
		if ( isset( $headers['X-Pingback'] ) ) {
			unset( $headers['X-Pingback'] );
		}
		return $headers;
	} );

	// Remove native post type support for comments and trackbacks on all
	// public-facing post types.
	// NOTE: If an existing post already has comments posted to it, they'll
	// still be viewable in the Comments metabox when editing the post.
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	foreach ( $post_types as $pt ) {
		if ( post_type_supports( $pt, 'comments' ) ) {
			remove_post_type_support( $pt, 'comments' );
		}
		if ( post_type_supports( $pt, 'trackbacks' ) ) {
			remove_post_type_support( $pt, 'trackbacks' );
		}
	}

	// Disable comments and pingbacks on new posts (these are the primary
	// default discussion settings under Settings > Discussion)
	add_filter( 'option_default_pingback_flag', '__return_zero' );
	add_filter( 'option_default_ping_status', '__return_zero' );
	add_filter( 'option_default_comment_status', '__return_zero' );

	// Close ability to add new comments and pingbacks on existing posts.
	add_filter( 'comments_open', '__return_false' );
	add_filter( 'pings_open', '__return_false' );

	// Remove admin bar link for comments
	add_action( 'wp_before_admin_bar_render', function() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	} );

	// Remove Comments and Settings > Discussion links from the admin menu.
	// NOTE: Both of these admin views are still accessible if requested
	// directly.
	add_action( 'admin_menu', function() {
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	} );

	// Remove the recent comments box from the admin dashboard.
	add_action( 'wp_dashboard_setup', function() {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	} );

	// Hide comment count and other inline references to comments in the
	// admin dashboard and user profile view.
	$admin_css = '';
	ob_start();
?>
	<style>
		#dashboard_right_now .comment-count,
		#dashboard_right_now .comment-mod-count,
		#latest-comments,
		#welcome-panel .welcome-comments,
		.user-comment-shortcuts-wrap {
			display: none !important;
		}
	</style>
<?php
	$admin_css = ob_get_clean();
	add_action( 'admin_print_styles-index.php', function() use ( $admin_css ) {
		echo $admin_css;
	} );
	add_action( 'admin_print_styles-profile.php', function() use ( $admin_css ) {
		echo $admin_css;
	} );
}

add_action( 'init', 'ucfwp_kill_comments' );


/**
 * Modifies the string printed at the end of excerpts.
 *
 * @since 0.5.2
 * @author Jo Dickson
 */
function ucfwp_excerpt_more( $more ) {
	return '&hellip;';
}

add_filter( 'excerpt_more', 'ucfwp_excerpt_more' );
