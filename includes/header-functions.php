<?php
/**
 * Header Related Functions
 **/

/**
 * Gets the header image for pages and taxonomy terms that have page header
 * images enabled.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return array A set of Attachment IDs, one sized for use on -sm+ screens, and another for -xs
 **/
function ucfwp_get_header_images( $obj ) {
	$obj_id = ucfwp_get_object_id( $obj );
	$field_id = ucfwp_get_object_field_id( $obj );

	$retval = array(
		'header_image'    => '',
		'header_image_xs' => ''
	);

	$retval = (array) apply_filters( 'ucfwp_get_header_images_before', $retval, $obj );
	// Exit early if this filter added a 'header_image' value
	if ( isset( $retval['header_image'] ) && $retval['header_image'] ) {
		return $retval;
	}

	if ( $obj_header_image = get_field( 'page_header_image', $field_id ) ) {
		$retval['header_image'] = $obj_header_image;
	}
	if ( $obj_header_image_xs = get_field( 'page_header_image_xs', $field_id ) ) {
		$retval['header_image_xs'] = $obj_header_image_xs;
	}

	$retval = (array) apply_filters( 'ucfwp_get_header_images_after', $retval, $obj );

	if ( isset( $retval['header_image'] ) && $retval['header_image'] ) {
		return $retval;
	}
	return false;
}


/**
 * Gets the header video sources for pages and taxonomy terms that have page
 * header videos enabled.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return array A set of Attachment IDs corresponding to available video filetypes
 **/
function ucfwp_get_header_videos( $obj ) {
	$obj_id = ucfwp_get_object_id( $obj );
	$field_id = ucfwp_get_object_field_id( $obj );

	$retval = array(
		'webm' => '',
		'mp4'  => ''
	);

	$retval = (array) apply_filters( 'ucfwp_get_header_videos_before', $retval, $obj );
	$retval = array_filter( $retval );
	// Exit early if a 'mp4' value was provided early
	if ( isset( $retval['mp4'] ) && $retval['mp4'] ) {
		return $retval;
	}

	if ( $obj_header_video_mp4 = get_field( 'page_header_mp4', $field_id ) ) {
		$retval['mp4'] = $obj_header_video_mp4;
	}
	if ( $obj_header_video_webm = get_field( 'page_header_webm', $field_id ) ) {
		$retval['webm'] = $obj_header_video_webm;
	}

	$retval = (array) apply_filters( 'ucfwp_get_header_videos_after', $retval, $obj );
	$retval = array_filter( $retval );

	// MP4 must be available to display video successfully cross-browser
	if ( isset( $retval['mp4'] ) && $retval['mp4'] ) {
		return $retval;
	}
	return false;
}


/**
 * Returns texturized title text for use in the page header.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string Header title text
 **/
 function ucfwp_get_header_title( $obj ) {
	$field_id = ucfwp_get_object_field_id( $obj );
	$title = '';

	// Exit early if the title has been overridden early
	$title = (string) apply_filters( 'ucfwp_get_header_title_before', $title, $obj );
	if ( !empty( $title ) ) {
		return wptexturize( $title );
	}

	if ( ! $obj ) {
		// We intentionally don't add a fallback title for 404s;
		// this allows us to add a custom h1 to the default 404 template.
		if ( ! is_404() ) {
			$title = get_bloginfo( 'name', 'display' );
		}
	}
	else {
		// Checks listed below are copied directly from WP core
		// (see wp_get_document_title()).
		// NOTE: We still include support for templates that are disabled in
		// ucfwp_kill_unused_templates() in case a child theme re-enables
		// one of those templates.

		if ( is_search() ) {
			$title = sprintf( __( 'Search Results for &#8220;%s&#8221;' ), get_search_query() );
		} elseif ( is_front_page() ) {
			$title = get_bloginfo( 'name', 'display' );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} elseif ( is_home() || is_singular() ) {
			$title = single_post_title( '', false );
		} elseif ( is_category() || is_tag() ) {
			$title = single_term_title( '', false );
		} elseif ( is_author() && $author = get_queried_object() ) {
			$title = $author->display_name;
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format' ) );
		} elseif ( is_month() ) {
			$title = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
		} elseif ( is_day() ) {
			$title = get_the_date();
		}
	}

	// Apply custom header title override, if available
	if ( $custom_header_title = get_field( 'page_header_title', $field_id ) ) {
		$title = do_shortcode( $custom_header_title );
	}

	$title = (string) apply_filters( 'ucfwp_get_header_title_after', $title, $obj );

	return wptexturize( $title );
}


/**
 * Returns texturized subtitle text for use in the page header.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string Header subtitle text
 **/
function ucfwp_get_header_subtitle( $obj ) {
	$field_id = ucfwp_get_object_field_id( $obj );
	$subtitle = '';

	$subtitle = (string) apply_filters( 'ucfwp_get_header_subtitle_before', $subtitle, $obj );
	// Exit early if subtitle has been modified early
	if ( !empty( $subtitle ) ) {
		return wptexturize( $subtitle );
	}

	$subtitle = do_shortcode( get_field( 'page_header_subtitle', $field_id ) );

	$subtitle = (string) apply_filters( 'ucfwp_get_header_subtitle_after', $subtitle, $obj );

	return wptexturize( $subtitle );
}


/**
 * Returns whether the page title or subtitle was designated as the page's h1.
 * Defaults to 'title' if the option isn't set.
 * Will force return a different value if the user screwed up (e.g. specified
 * "subtitle" but didn't provide a subtitle value).
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string Option value for the designated page header h1
 **/
if ( !function_exists( 'ucfwp_get_header_h1_option' ) ) {
	function ucfwp_get_header_h1_option( $obj ) {
		$field_id = ucfwp_get_object_field_id( $obj );
		$subtitle = get_field( 'page_header_subtitle', $field_id ) ?: '';
		$h1       = get_field( 'page_header_h1', $field_id ) ?: 'title';

		if ( $h1 === 'subtitle' && trim( $subtitle ) === '' ) {
			$h1 = 'title';
		}

		return $h1;
	}
}


/**
 * Returns inner navbar markup for ucf.edu's primary site navigation.
 *
 * @since 0.0.0
 * @author Jo Dickson
 * @return string HTML markup
 */
if ( !function_exists( 'ucfwp_get_mainsite_menu' ) ) {
	function ucfwp_get_mainsite_menu( $image=true ) {
		global $wp_customize;
		$customizing    = isset( $wp_customize );
		$feed_url       = get_theme_mod( 'mainsite_nav_url' ) ?: UCFWP_MAINSITE_NAV_URL;
		$transient_name = 'ucfwp_mainsite_nav_json';
		$result         = get_transient( $transient_name );

		if ( empty( $result ) || $customizing ) {
			// Try fetching the theme mod value or default
			$result = ucfwp_fetch_json( $feed_url );

			// If the theme mod value failed and it's not what we set as our
			// default, try again using the default
			if ( !$result && $feed_url !== UCFWP_MAINSITE_NAV_URL ) {
				$result = ucfwp_fetch_json( UCFWP_MAINSITE_NAV_URL );
			}

			if ( ! $customizing ) {
				set_transient( $transient_name, $result, (60 * 60 * 24) );
			}
		}

		if ( !$result ) { return ''; }
		$menu = $result;

		ob_start();
	?>
	<nav class="navbar navbar-toggleable-md navbar-mainsite py-2<?php echo $image ? ' py-sm-4 navbar-inverse header-gradient' : ' navbar-inverse bg-inverse-t-3 py-lg-4'; ?>" role="navigation">
		<div class="container">
			<button class="navbar-toggler ml-auto collapsed" type="button" data-toggle="collapse" data-target="#header-menu" aria-controls="header-menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-text">Navigation</span>
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="header-menu">
				<ul id="menu-header-menu" class="nav navbar-nav nav-fill">
					<?php foreach ( $menu->items as $item ): ?>
					<li class="menu-item nav-item">
						<a href="<?php echo $item->url; ?>" target="<?php echo $item->target; ?>" class="nav-link">
							<?php echo $item->title; ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</nav>
	<?php
		return ob_get_clean();
	}
}


/**
 * Returns HTML markup for the primary site navigation.  Falls back to the
 * ucf.edu primary navigation if a header menu is not set.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param bool $image Whether or not a media background is present in the page header.
 * @return string Nav HTML
 **/
if ( !function_exists( 'ucfwp_get_nav_markup' ) ) {
	function ucfwp_get_nav_markup( $image=true ) {
		$title_elem = ( is_home() || is_front_page() ) ? 'h1' : 'span';

		ob_start();

		if ( has_nav_menu( 'header-menu' ) ) {
	?>
		<nav class="navbar navbar-toggleable-md navbar-custom<?php echo $image ? ' py-2 py-sm-4 navbar-inverse header-gradient' : ' navbar-inverse bg-inverse-t-3'; ?>" role="navigation">
			<div class="container d-flex flex-row flex-nowrap justify-content-between">
				<<?php echo $title_elem; ?> class="mb-0">
					<a class="navbar-brand mr-lg-5" href="<?php echo get_home_url(); ?>"><?php echo bloginfo( 'name' ); ?></a>
				</<?php echo $title_elem; ?>>
				<button class="navbar-toggler ml-auto align-self-start collapsed" type="button" data-toggle="collapse" data-target="#header-menu" aria-controls="header-menu" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-text">Navigation</span>
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php
				$container_class = 'collapse navbar-collapse';
				if ( !$image ) {
					$container_class = $container_class . ' align-self-lg-stretch';
				}
				wp_nav_menu( array(
					'container'       => 'div',
					'container_class' => $container_class,
					'container_id'    => 'header-menu',
					'depth'           => 2,
					'fallback_cb'     => 'bs4Navwalker::fallback',
					'menu_class'      => 'nav navbar-nav ml-md-auto',
					'theme_location'  => 'header-menu',
					'walker'          => new bs4Navwalker()
				) );
				?>
			</div>
		</nav>
	<?php
		}
		else {
			echo ucfwp_get_mainsite_menu( $image );
		}

		return ob_get_clean();
	}
}


/**
 * Returns markup for page header title + subtitles within headers that use a
 * media background.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the page title + subtitle
 **/
if ( !function_exists( 'ucfwp_get_header_content_title_subtitle' ) ) {
	function ucfwp_get_header_content_title_subtitle( $obj ) {
		$title         = ucfwp_get_header_title( $obj );
		$subtitle      = ucfwp_get_header_subtitle( $obj );
		$h1            = ucfwp_get_header_h1_option( $obj );
		$h1_elem       = ( is_home() || is_front_page() ) ? 'h2' : 'h1'; // name is misleading but we need to override this elem on the homepage
		$title_elem    = ( $h1 === 'title' ) ? $h1_elem : 'span';
		$subtitle_elem = ( $h1 === 'subtitle' ) ? $h1_elem : 'span';

		ob_start();

		if ( $title ):
	?>
		<div class="header-content-inner align-self-start pt-4 pt-sm-0 align-self-sm-center">
			<div class="container">
				<div class="d-inline-block bg-primary-t-1">
					<<?php echo $title_elem; ?> class="header-title"><?php echo $title; ?></<?php echo $title_elem; ?>>
				</div>
				<?php if ( $subtitle ) : ?>
				<div class="clearfix"></div>
				<div class="d-inline-block bg-inverse">
					<<?php echo $subtitle_elem; ?> class="header-subtitle"><?php echo $subtitle; ?></<?php echo $subtitle_elem; ?>>
				</div>
				<?php endif; ?>
			</div>
		</div>
	<?php
		endif;

		return ob_get_clean();
	}
}


/**
 * Returns markup for page header custom content.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the custom page header contents
 **/
if ( !function_exists( 'ucfwp_get_header_content_custom' ) ) {
	function ucfwp_get_header_content_custom( $obj ) {
		$field_id = ucfwp_get_object_field_id( $obj );
		$content = get_field( 'page_header_content', $field_id );

		ob_start();
	?>
		<div class="header-content-inner">
	<?php
		if ( $content ) {
			echo $content;
		}
	?>
		</div>
	<?php
		return ob_get_clean();
	}
}


/**
 * Returns an array of src's for a page header's media background
 * <picture> <source>s, by breakpoint.  Will return a unique set of src's
 * depending on the page's header height.
 *
 * @author Jo Dickson
 * @since 0.2.1
 * @param string $header_height Name of the header's height
 * @param array $images Assoc. array of image size names and attachment IDs (expects a return value from ucfwp_get_header_images())
 * @return array Assoc. array of breakpoint names and image URLs (see ucfwp_get_media_background_picture_srcs())
 */
if ( ! function_exists( 'ucfwp_get_header_media_picture_srcs' ) ) {
	function ucfwp_get_header_media_picture_srcs( $header_height, $images ) {
		$bg_image_srcs = array();

		switch ( $header_height ) {
			case 'header-media-fullscreen':
				$bg_image_srcs = ucfwp_get_media_background_picture_srcs( null, $images['header_image'], 'bg-img' );
				$bg_image_src_xs = ucfwp_get_media_background_picture_srcs( $images['header_image_xs'], null, 'header-img' );

				if ( isset( $bg_image_src_xs['xs'] ) ) {
					$bg_image_srcs['xs'] = $bg_image_src_xs['xs'];
				}

				break;
			default:
				$bg_image_srcs = ucfwp_get_media_background_picture_srcs( $images['header_image_xs'], $images['header_image'], 'header-img' );
				break;
		}

		return $bg_image_srcs;
	}
}


/**
 * Returns the markup for page headers with media backgrounds.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @param array $videos Assoc. array of video Attachment IDs for use in page header media background
 * @param array $images Assoc. array of image Attachment IDs for use in page header media background
 * @return string HTML for the page header
 **/
if ( !function_exists( 'ucfwp_get_header_media_markup' ) ) {
	function ucfwp_get_header_media_markup( $obj, $videos, $images ) {
		$field_id   = ucfwp_get_object_field_id( $obj );
		$videos     = $videos ?: ucfwp_get_header_videos( $obj );
		$images     = $images ?: ucfwp_get_header_images( $obj );
		$video_loop = get_field( 'page_header_video_loop', $field_id );
		$header_content_type = get_field( 'page_header_content_type', $field_id );
		$header_height       = get_field( 'page_header_height', $field_id );
		$exclude_nav         = get_field( 'page_header_exclude_nav', $field_id );

		ob_start();
	?>
		<div class="header-media <?php echo $header_height; ?> mb-0 d-flex flex-column">
			<div class="header-media-background-wrap">
				<div class="header-media-background media-background-container">
					<?php
					// Display the media background (video + picture)

					if ( $videos ) {
						echo ucfwp_get_media_background_video( $videos, $video_loop );
					}
					if ( $images ) {
						$bg_image_srcs = ucfwp_get_header_media_picture_srcs( $header_height, $images );
						echo ucfwp_get_media_background_picture( $bg_image_srcs );
					}
					?>
				</div>
			</div>

			<?php
			// Display the site nav
			if ( !$exclude_nav ) { echo ucfwp_get_nav_markup(); }
			?>

			<?php
			// Display the inner header contents
			?>
			<div class="header-content">
				<div class="header-content-flexfix">
					<?php
					if ( $header_content_type === 'custom' ) {
						echo ucfwp_get_header_content_custom( $obj );
					}
					else {
						echo ucfwp_get_header_content_title_subtitle( $obj );
					}
					?>
				</div>
			</div>

			<?php
			// Print a spacer div for headers with background videos (to make
			// control buttons accessible), and for headers showing a standard
			// title/subtitle to push them up a bit
			if ( $videos || $header_content_type === 'title_subtitle' ):
			?>
			<div class="header-media-controlfix"></div>
			<?php endif; ?>
		</div>
	<?php
		return ob_get_clean();
	}
}


/**
 * Returns the default markup for page headers without a media background.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @param object $obj A WP_Post or WP_Term object
 * @return string HTML for the page header
 **/
if ( !function_exists( 'ucfwp_get_header_default_markup' ) ) {
	function ucfwp_get_header_default_markup( $obj ) {
		$title               = ucfwp_get_header_title( $obj );
		$subtitle            = ucfwp_get_header_subtitle( $obj );
		$field_id            = ucfwp_get_object_field_id( $obj );
		$header_content_type = get_field( 'page_header_content_type', $field_id );
		$exclude_nav         = get_field( 'page_header_exclude_nav', $field_id );
		$h1                  = ucfwp_get_header_h1_option( $obj );
		$h1_elem             = ( is_home() || is_front_page() ) ? 'h2' : 'h1'; // name is misleading but we need to override this elem on the homepage
		$title_elem          = ( $h1 === 'title' ) ? $h1_elem : 'span';
		$subtitle_elem       = ( $h1 === 'subtitle' ) ? $h1_elem : 'p';

		$title_classes = 'h1 d-block mt-3 mt-sm-4 mt-md-5 mb-2 mb-md-3';
		$subtitle_classes = 'lead mb-2 mb-md-3';

		ob_start();
	?>
		<?php if ( !$exclude_nav ) { echo ucfwp_get_nav_markup( false ); } ?>

		<?php
		if ( $header_content_type === 'custom' ):
			echo ucfwp_get_header_content_custom( $obj );
		elseif ( $title ):
		?>
		<div class="container">
			<<?php echo $title_elem; ?> class="<?php echo $title_classes; ?>">
				<?php echo $title; ?>
			</<?php echo $title_elem; ?>>

			<?php if ( $subtitle ): ?>
				<<?php echo $subtitle_elem; ?> class="<?php echo $subtitle_classes; ?>">
					<?php echo $subtitle; ?>
				</<?php echo $subtitle_elem; ?>>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	<?php
		return ob_get_clean();
	}
}


/**
 * Returns header markup for the current post or term.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @return string HTML for the page header
 **/
if ( !function_exists( 'ucfwp_get_header_markup' ) ) {
	function ucfwp_get_header_markup() {
		$obj = get_queried_object();

		if ( !$obj && is_404() ) {
			$page = get_page_by_title( '404' );
			if ( $page && $page->post_status === 'publish' ) {
				$obj = $page;
			}
		}

		$videos = ucfwp_get_header_videos( $obj );
		$images = ucfwp_get_header_images( $obj );

		if ( $videos || $images ) {
			echo ucfwp_get_header_media_markup( $obj, $videos, $images );
		}
		else {
			echo ucfwp_get_header_default_markup( $obj );
		}
	}
}


/**
 * Returns subnavigation markup for the current post or term.
 *
 * @author Jo Dickson
 * @since 0.0.0
 * @return string HTML for the page header
 **/
if ( !function_exists( 'ucfwp_get_subnav_markup' ) ) {
	function ucfwp_get_subnav_markup() {
		$obj = get_queried_object();

		if ( !$obj && is_404() ) {
			$page = get_page_by_title( '404' );
			if ( $page && $page->post_status === 'publish' ) {
				$obj = $page;
			}
		}

		$field_id       = ucfwp_get_object_field_id( $obj );
		$include_subnav = get_field( 'page_header_include_subnav', $field_id );

		if ( class_exists( 'Section_Menus_Common' ) && $include_subnav ) {
			echo do_shortcode( '[section-menu]' );
		}
	}
}
