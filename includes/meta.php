<?php
/**
 * Includes functions that handle registration/enqueuing of meta tags, styles,
 * and scripts in the document head and footer.
 **/

/**
 * Enqueue front-end css and js.
 **/
function ucfwp_enqueue_frontend_assets() {
	$theme         = wp_get_theme( 'UCF-WordPress-Theme' );
	$theme_version = ( $theme instanceof WP_Theme ) ? $theme->get( 'Version' ) : false;
	$style_deps    = array();

	// Register Font Awesome stylesheet
	$fa_version = get_theme_mod( 'font_awesome_version' );
	switch ( $fa_version ) {
		case 'none':
			break;
		case '6':
			wp_enqueue_style( 'font-awesome-6', UCFWP_THEME_CSS_URL . '/font-awesome-6.min.css', null, $theme_version );
			$style_deps[] = 'font-awesome-6';
			break;
		case '5':
			wp_enqueue_style( 'font-awesome-5', UCFWP_THEME_CSS_URL . '/font-awesome-5.min.css', null, $theme_version );
			$style_deps[] = 'font-awesome-5';
			break;
		case '4':
		default:
			wp_enqueue_style( 'font-awesome-4', UCFWP_THEME_CSS_URL . '/font-awesome-4.min.css', null, $theme_version );
			$style_deps[] = 'font-awesome-4';
			break;
	}

	// Register main theme stylesheet
	wp_enqueue_style( 'style', UCFWP_THEME_CSS_URL . '/style.min.css', $style_deps, $theme_version );

	// Register UCF Header
	wp_enqueue_script( 'ucf-header', '//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=1', null, null, true );

	// Register main theme scripts
	wp_enqueue_script( 'tether', 'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js', null, null, true );
	wp_enqueue_script( 'script', UCFWP_THEME_JS_URL . '/script.min.js', array( 'jquery', 'tether' ), $theme_version, true );

	// Add localized script variables to the document
	$site_url = parse_url( get_site_url() );
	wp_localize_script( 'script', 'UCFWP', array(
		'domain' => $site_url['host'],
		'mediaBgVideoViewportMin' => intval( apply_filters( 'ucfwp_media_background_video_viewport_min_width', 576 ) )
	) );
}

add_action( 'wp_enqueue_scripts', 'ucfwp_enqueue_frontend_assets' );


/**
 * De-register and re-register a newer version of jquery early in the
 * document head.
 *
 * @author Jo Dickson
 * @since 0.2.5
 */
function ucfwp_enqueue_jquery() {
	// Deregister jquery and re-register newer version in the document head.
	wp_deregister_script( 'jquery' );
	$jquery_enqueue_location = ( get_theme_mod( 'jquery_enqueue_location' ) === 'top' ) ? false : true;
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', null, null, $jquery_enqueue_location );
	wp_enqueue_script( 'jquery' );
}

add_action( 'wp_enqueue_scripts', 'ucfwp_enqueue_jquery', 1 );


/**
 * Meta tags to insert into the document head.
 **/
function ucfwp_add_meta_tags() {
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
$gw_verify = get_theme_mod( 'gw_verify' );
if ( $gw_verify ):
?>
<meta name="google-site-verification" content="<?php echo htmlentities( $gw_verify ); ?>">
<?php endif; ?>
<?php
	$fallback_fonts = (array) apply_filters( 'ucfwp_preload_athena_fallback_fonts', array(
		UCFWP_THEME_FONT_URL . '/ucf-sans-serif-alt/ucfsansserifalt-medium-webfont.woff2',
		UCFWP_THEME_FONT_URL . '/ucf-sans-serif-alt/ucfsansserifalt-bold-webfont.woff2',
	) );
	foreach ( $fallback_fonts as $fb_font ) :
?>
<link rel="preload" href="<?php echo $fb_font; ?>" as="font" type="font/woff2" crossorigin>
<?php endforeach; ?>

<?php
// Preload Font Awesome
$fa_fonts   = array();
$fa_version = get_theme_mod( 'font_awesome_version' );
switch ( $fa_version ) {
	case 'none':
		break;
	case '6':
		$fa_6_url = ucfwp_get_font_awesome_6_font_url();
		if ( $fa_6_url ) {
			$fa_fonts[] = $fa_6_url . '/fa-thin-100.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-light-300.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-regular-400.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-solid-900.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-sharp-thin-100.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-sharp-light-300.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-sharp-regular-400.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-sharp-solid-900.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-brands-400.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-duotone-900.woff2';
			$fa_fonts[] = $fa_6_url . '/fa-v4compatibility.woff2';
		}
		break;
	case '5':
		$fa_5_url = ucfwp_get_font_awesome_5_font_url();
		if ( $fa_5_url ) {
			$fa_fonts[] = $fa_5_url . '/fa-regular-400.woff2';
			$fa_fonts[] = $fa_5_url . '/fa-solid-900.woff2';
			$fa_fonts[] = $fa_5_url . '/fa-brands-400.woff2';
		}
		break;
	case '4':
	default:
		$fa_fonts[] = UCFWP_THEME_FONT_URL . '/font-awesome-4/fontawesome-webfont.woff2';
		break;
}
$fa_fonts = (array) apply_filters( 'ucfwp_preload_font_awesome_fonts', $fa_fonts, $fa_version );

foreach ( $fa_fonts as $fa_font ) :
?>
<link rel="preload" href="<?php echo $fa_font; ?>" as="font" type="font/woff2" crossorigin>
<?php
endforeach;

}

add_action( 'wp_head', 'ucfwp_add_meta_tags', 1 );


/**
 * Removed unneeded meta tags generated by WordPress.
 * Some of these may already be handled by security plugins.
 **/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
add_filter( 'emoji_svg_url', '__return_false' );


/**
 * Replaces the ucf-header bar ID with the correct
 * ID needed for the script to work correctly.
 *
 * @author Jim Barnes
 * @since v0.9.3
 *
 * @param string $tag The script tag being filtered
 * @param string $handle The handle of the header script
 * @param string $src The source of the script
 */
function ucfhb_script_handle( $tag, $handle, $src ) {
	if ( false !== strpos( $src, 'universityheader.ucf.edu' ) ) {
		$tag = str_replace( "{$handle}-js", 'ucfhb-script', $tag );
	}

	return $tag;
}

if ( version_compare( $wp_version, '6.3.0', '>=' ) ) {
	add_filter( 'script_loader_tag', 'ucfhb_script_handle', 10, 3 );
}

/**
 * Adds ID attribute to UCF Header script.
 **/
function ucfwp_add_id_to_ucfhb( $url ) {
	if (
		( false !== strpos($url, 'bar/js/university-header.js' ) )
		|| ( false !== strpos( $url, 'bar/js/university-header-full.js' ) )
	) {
      remove_filter( 'clean_url', 'ucfwp_add_id_to_ucfhb', 10, 3 );
      return "$url' id='ucfhb-script";
    }
    return $url;
}

if ( version_compare( $wp_version, '6.3.0', '<' ) ) {
	add_filter( 'clean_url', 'ucfwp_add_id_to_ucfhb', 10, 1 );
}

/**
 * Prints Chartbeat tracking code in the footer if a UID and Domain are set in
 * the customizer.
 **/
function ucfwp_add_chartbeat() {
	$uid = get_theme_mod( 'chartbeat_uid' );
	$domain = get_theme_mod( 'chartbeat_domain' );

	if ( $uid && $domain ) {
?>
<script type="text/javascript">
    var _sf_async_config = _sf_async_config || {};
    /** CONFIGURATION START **/
    _sf_async_config.uid = '<?php echo $uid; ?>'
    _sf_async_config.domain = '<?php echo $domain; ?>';
    /** CONFIGURATION END **/
    (function() {
        function loadChartbeat() {
            var e = document.createElement('script');
            e.setAttribute('language', 'javascript');
            e.setAttribute('type', 'text/javascript');
            e.setAttribute('src', '//static.chartbeat.com/js/chartbeat.js');
            document.body.appendChild(e);
        }
        var oldonload = window.onload;
        window.onload = (typeof window.onload != 'function') ?
            loadChartbeat : function() {
                oldonload();
                loadChartbeat();
            };
    })();
</script>
<?php
	}
}

add_action( 'wp_footer', 'ucfwp_add_chartbeat' );


/**
 * Adds Google Analytics script to the document head.  Note that, if a Google
 * Tag Manager ID is provided in the customizer, this hook will have no effect.
 **/
function ucfwp_add_google_analytics() {
	$ga_account  = get_theme_mod( 'ga_account' );
	$ga4_account = get_theme_mod( 'ga4_account' );
	$gtm_id      = get_theme_mod( 'gtm_id' );
	if ( $ga4_account && !$gtm_id ) {
		ucfwp_add_ga4_analytics( $ga4_account );
	} else if ( $ga_account && !$gtm_id ) {
		ucfwp_add_classic_analytics( $ga_account );
	}
}

add_action( 'wp_head', 'ucfwp_add_google_analytics' );

/**
 * Adds the classic (UA) analytics snippet into the head.
 *
 * @author Jim Barnes
 * @since v0.8.0
 * @param  string $ga_account The Google Analytics property
 * @return void
 */
function ucfwp_add_classic_analytics( $ga_account ) {
?>
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', '<?php echo $ga_account; ?>', 'auto');
		ga('send', 'pageview');
	</script>
<?php
}

/**
 * Adds the GA4 code snippet.
 *
 * @author Jim Barnes
 * @since v0.8.0
 * @param  string $ga_account The Google Analytics version 4 property
 * @return void
 */
function ucfwp_add_ga4_analytics( $ga_account ) {
?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ga_account; ?>"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '<?php echo $ga_account; ?>');
	</script>
<?php
}


/**
 * Prints the Google Tag Manager data layer snippet in the document head if a
 * GTM ID is set in the customizer.
 **/
function ucfwp_google_tag_manager_dl() {
	$gtm_id = get_theme_mod( 'gtm_id' );
	if ( $gtm_id ) :
?>
<script>
	dataLayer = [];
</script>
<?php
	endif;
}

add_action( 'wp_head', 'ucfwp_google_tag_manager_dl', 2 );


/**
 * Prints the Google Tag Manager script tag in the document head if a GTM ID is
 * set in the customizer.
 **/
function ucfwp_google_tag_manager() {
	$gtm_id = get_theme_mod( 'gtm_id' );
	if ( $gtm_id ) :
?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
<?php
	endif;
}

add_action( 'wp_head', 'ucfwp_google_tag_manager', 3 );


/**
 * Prints the Google Tag Manager noscript snippet using the GTM ID set in the customizer.
 **/
function ucfwp_google_tag_manager_noscript() {
	$gtm_id = get_theme_mod( 'gtm_id' );
	if ( $gtm_id ) :
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
	endif;
}

add_action( 'wp_body_open', 'ucfwp_google_tag_manager_noscript', 0 );


/**
 * Sets a default favicon if a site icon is not already set.
 */
function ucfwp_add_favicon_default() {
	if ( !has_site_icon() ):
?>
<link rel="shortcut icon" href="<?php echo UCFWP_THEME_URL . '/favicon.ico'; ?>" />
<?php
	endif;
}

add_action( 'wp_head', 'ucfwp_add_favicon_default' );


/**
 * Appends additional URLs to WP's list of domains to generate
 * <link rel="dns-prefetch"> tags for.
 *
 * @since 0.7.1
 * @author Jo Dickson
 * @return array
 */
function ucfwp_add_dns_prefetch_domains( $urls, $relation_type ) {
	$new_urls = get_theme_mod( 'dns_prefetch_domains' );
	if ( $new_urls && $relation_type === 'dns-prefetch' ) {
		$new_urls = array_unique( array_filter( array_map( 'trim', explode( ',', $new_urls ) ) ) );
		$urls = array_merge( $urls, $new_urls );
	}
	return $urls;
}

add_filter( 'wp_resource_hints', 'ucfwp_add_dns_prefetch_domains', 10, 2 );


/**
 * Convenience method that returns the static directory
 * where Font Awesome v5.x font files are stored in the theme.
 *
 * @since 0.7.1
 * @author Jo Dickson
 * @return mixed URL dir string, or null if FA version not found
 */
function ucfwp_get_font_awesome_5_font_url() {
	$fa_5_version = ucfwp_get_theme_package_version( '@fortawesome/fontawesome-free' );
	if ( ! $fa_5_version ) return null;

	return UCFWP_THEME_FONT_URL . '/font-awesome-5/' . $fa_5_version;
}

function ucfwp_get_font_awesome_6_font_url() {
	$fa_6_version = ucfwp_get_theme_package_version( '@fortawesome/fontawesome-pro' );
	if ( ! $fa_6_version ) return null;

	return UCFWP_THEME_FONT_URL . '/font-awesome-6/' . $fa_6_version;
}
