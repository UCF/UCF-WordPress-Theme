<?php
/**
 * Handle all theme configuration here
 **/

define( 'UCFWP_THEME_URL', get_template_directory_uri() );
define( 'UCFWP_THEME_STATIC_URL', UCFWP_THEME_URL . '/static' );
define( 'UCFWP_THEME_CSS_URL', UCFWP_THEME_STATIC_URL . '/css' );
define( 'UCFWP_THEME_JS_URL', UCFWP_THEME_STATIC_URL . '/js' );
define( 'UCFWP_THEME_IMG_URL', UCFWP_THEME_STATIC_URL . '/img' );
define( 'UCFWP_THEME_FONT_URL', UCFWP_THEME_STATIC_URL . '/fonts' );
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
		UCFWP_THEME_CUSTOMIZER_PREFIX . 'icons',
		array(
			'title' => 'Icons'
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

	$wp_customize->add_section(
		UCFWP_THEME_CUSTOMIZER_PREFIX . 'performance',
		array(
			'title' => 'Performance'
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

	// Icons
	$wp_customize->add_setting(
		'font_awesome_version',
		array(
			'default' => '4'
		)
	);

	$wp_customize->add_control(
		'font_awesome_version',
		array(
			'type'        => 'select',
			'label'       => 'Font Awesome Version',
			'description' => 'What version of <a href="https://fontawesome.com/">Font Awesome</a> to load throughout the site.
								By default, this theme includes Font Awesome version 4.<br><br>
								If you wish to load Font Awesome yourself using other means (e.g. CDN), or want to load it
								via a third-party plugin, you should set this to "None".<br><br>
								Once set, you should avoid changing this value to avoid breaking pages that use a different
								version of Font Awesome icons.  This theme does not include v4 upgrade shims when v5 is in use.',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'icons',
			'choices'     => array(
				'4' => 'Version 4 (4.7.0)',
				'5' => 'Version 5',
				'none' => 'None (do not load Font Awesome)'
			)
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

	// Performance Settings
	$wp_customize->add_setting(
		'jquery_enqueue_location',
		array(
        	'default' => 'bottom'
		)
	);

	$wp_customize->add_control(
		'jquery_enqueue_location',
		array(
			'type'        => 'radio',
			'label'       => 'jQuery Enqueue Location',
			'choices'     => array(
			   'top'      => 'Top',
			   'bottom'   => 'Bottom'
			),
			'description' => 'Loading jQuery at the bottom of the page will improve page performance by eliminating it as a render-blocking resource.',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'performance'
		)
	);

	$wp_customize->add_setting(
		'dns_prefetch_domains',
	);

	$wp_customize->add_control(
		'dns_prefetch_domains',
		array(
			'type'        => 'textarea',
			'label'       => 'Additional Required Origins for DNS Prefetching',
			'description' => 'Specify a comma-separated list of domains to third-party origins that should be prefetched using <code>&lt;link rel="dns-prefetch"&gt;</code> that WordPress doesn\'t already handle out-of-the-box.',
			'section'     => UCFWP_THEME_CUSTOMIZER_PREFIX . 'performance'
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
 * Allow special tags in post bodies that would get stripped
 * otherwise for most users.
 * Modifies $allowedposttags defined in wp-includes/kses.php
 **/
function ucfwp_kses_allowed_html( $tags, $context ) {
	$global_attrs = array(
		'aria-describedby' => true,
		'aria-details'     => true,
		'aria-label'       => true,
		'aria-labelledby'  => true,
		'aria-hidden'      => true,
		'class'            => true,
		'data-*'           => true,
		'hidden'           => array( 'valueless', 'y' ),
		'id'               => true,
		'role'             => true,
		'style'            => true,
		'title'            => true,
	);

	//
	// Forms
	//

	$tags['input'] = array_merge(
		$global_attrs,
		array(
			'name'  => true,
			'type'  => true,
			'value' => true
		)
	);

	$tags['select'] = array_merge(
		$global_attrs,
		array(
			'name' => true
		)
	);

	$tags['option'] = array_merge(
		$global_attrs,
		array(
			'name'  => true,
			'value' => true
		)
	);

	//
	// Embedded content
	//

	$tags['iframe'] = array_merge(
		$global_attrs,
		array(
			'allowfullscreen' => true,
			'frameborder'     => true,
			'height'          => true,
			'name'            => true,
			'src'             => true,
			'type'            => true,
			'value'           => true,
			'width'           => true
		)
	);

	$tags['object'] = array_merge(
		$global_attrs,
		array(
			'height' => true,
			'width'  => true
		)
	);

	$tags['param'] = array_merge(
		$global_attrs,
		array(
			'name'  => true,
			'value' => true
		)
	);

	$tags['embed'] = array_merge(
		$global_attrs,
		array(
			'allowfullscreen'   => true,
			'allowscriptaccess' => true,
			'height'            => true,
			'src'               => true,
			'type'              => true,
			'width'             => true
		)
	);

	$tags['picture'] = $global_attrs;

	$tags['source'] = array_merge(
		$global_attrs,
		array(
			'media'  => true,
			'sizes'  => true,
			'src'    => true,
			'srcset' => true,
			'type'   => true
		)
	);

	//
	// Extensions of other, already-whitelisted elements
	//

	// Some of these attrs won't be valid on the elements
	// they're assigned to, but that's intentional for
	// backward compatibility:
	$div_a_btn_attrs = array(
		'width'  => true,
		'height' => true,

		'align'          => true,
		'autocomplete'   => true,
		'autofocus'      => true,
		'dir'            => true,
		'disabled'       => true,
		'form'           => true,
		'formaction'     => true,
		'formenctype'    => true,
		'formmethod'     => true,
		'formonvalidate' => true,
		'formtarget'     => true,
		'href'           => true,
		'name'           => true,
		'rel'            => true,
		'rev'            => true,
		'target'         => true,
		'type'           => true,
		'value'          => true,
	);

	$tags['div'] = array_merge(
		$tags['div'] ?? array(),
		$global_attrs,
		$div_a_btn_attrs
	);

	$tags['a'] = array_merge(
		$tags['a'] ?? array(),
		$global_attrs,
		$div_a_btn_attrs
	);

	$tags['button'] = array_merge(
		$tags['button'] ?? array(),
		$global_attrs,
		$div_a_btn_attrs
	);

	return $tags;
}

add_filter( 'wp_kses_allowed_html', 'ucfwp_kses_allowed_html', 10, 2 );


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
 * Modifies attachment links to point directly to individual files instead of
 * single attachment views.
 *
 * Takes effect only when the `ucfwp_kill_unused_templates` hook is registered,
 * and/or if the `ucfwp_enable_attachment_link_rewrites` hook has been passed a
 * custom value.
 *
 * @since 0.6.0
 * @author Jo Dickson
 * @param string $link Existing URL to attachment page
 * @param int $post_id Attachment post ID
 * @return string Modified attachment URL
 */
function ucfwp_modify_attachment_links( $link, $post_id ) {
	$do_rewrites = has_action( 'template_redirect', 'ucfwp_kill_unused_templates' ) !== false ? true : false;
	// Let child themes/plugins override this behavior:
	if ( has_filter( 'ucfwp_enable_attachment_link_rewrites' ) !== false ) {
		$do_rewrites = filter_var( apply_filters( 'ucfwp_enable_attachment_link_rewrites', $do_rewrites ), FILTER_VALIDATE_BOOLEAN );
	}

	if ( $do_rewrites ) {
		$attachment_url = wp_get_attachment_url( $post_id );
		if ( $attachment_url ) {
			$link = $attachment_url;
		}
	}

	return $link;
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
