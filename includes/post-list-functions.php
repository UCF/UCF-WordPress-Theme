<?php

/**
 * Adds custom layouts for the UCF Post List plugin.
 *
 * @since 0.5.2
 * @author Jo Dickson
 */
if ( ! function_exists( 'ucfwp_post_list_layouts' ) ) {
	function ucfwp_post_list_layouts( $layouts ) {
		$layouts['news'] = 'Vertical Feature Layout';

		if ( class_exists( 'UCF_People_PostType' ) ) {
			$layouts['people'] = 'People Layout';
		}

		return $layouts;
	}
}

add_filter( 'ucf_post_list_get_layouts', 'ucfwp_post_list_layouts' );


/**
 * Adds custom attributes for the UCF Post List plugin.
 *
 * @author Jo Dickson
 * @since 0.5.2
 */
if ( ! function_exists( 'ucfwp_post_list_sc_atts' ) ) {
	function ucfwp_post_list_sc_atts( $atts, $layout ) {
		if ( $layout === 'news' ) {
			// Create new `show_subhead` attribute to
			// toggle the post's subhead text
			$atts['show_subhead'] = false;

			// Create new `show_excerpt` attribute that toggles
			// the post's excerpt display
			$atts['show_excerpt'] = true;

			// Force a sane posts_per_row value, since these
			// lists are more horizontal
			$atts['posts_per_row'] = 1;
		}

		return $atts;
	}
}

add_filter( 'ucf_post_list_get_sc_atts', 'ucfwp_post_list_sc_atts', 10, 2 );


/**
 * Add custom people list layout for UCF Post List shortcode
 *
 * @since 0.2.2
 **/
if ( class_exists( 'UCF_People_PostType' ) ) {

	if ( ! function_exists( 'ucfwp_post_list_display_people_before' ) ) {
		function ucfwp_post_list_display_people_before( $content, $items, $atts ) {
			ob_start();
		?>
		<div class="ucf-post-list ucfwp-post-list-people">
		<?php
			return ob_get_clean();
		}
	}

	add_filter( 'ucf_post_list_display_people_before', 'ucfwp_post_list_display_people_before', 10, 3 );


	if ( ! function_exists( 'ucfwp_post_list_display_people_title' ) ) {
		function ucfwp_post_list_display_people_title( $content, $items, $atts ) {
			$formatted_title = '';
			if ( $atts['list_title'] ) {
				$formatted_title = '<h2 class="ucf-post-list-title">' . $atts['list_title'] . '</h2>';
			}
			return $formatted_title;
		}
	}

	add_filter( 'ucf_post_list_display_people_title', 'ucfwp_post_list_display_people_title', 10, 3 );


	if ( ! function_exists( 'ucfwp_post_list_display_people' ) ) {
		function ucfwp_post_list_display_people( $content, $items, $atts ) {
			if ( ! is_array( $items ) && $items !== false ) { $items = array( $items ); }

			$fa_version = get_theme_mod( 'font_awesome_version' );
			$fa_email_icon = '';
			$fa_phone_icon = '';
			switch ( $fa_version ) {
				case 'none':
					break;
				case '5':
					$fa_email_icon = 'fas fa-envelope fa-fw mr-1';
					$fa_phone_icon = 'fas fa-phone fa-fw mr-1';
					break;
				case '4':
				default:
					$fa_email_icon = 'fa fa-envelope fa-fw mr-1';
					$fa_phone_icon = 'fa fa-phone fa-fw mr-1';
					break;
			}

			ob_start();
		?>
			<?php if ( $items ): ?>
			<ul class="list-unstyled row ucf-post-list-items">
				<?php
				foreach ( $items as $item ):
					$is_content_empty = ucfwp_is_content_empty( $item->post_content );
				?>
				<li class="col-6 col-sm-4 col-md-3 col-xl-2 mt-3 mb-2 ucf-post-list-item">

					<?php if ( ! $is_content_empty ): ?>
					<a class="person-link" href="<?php echo get_permalink( $item->ID ); ?>">
					<?php endif; ?>

						<?php echo ucfwp_get_person_thumbnail( $item ); ?>

						<strong class="my-2 d-block person-name">
							<?php echo ucfwp_get_person_name( $item ); ?>
						</strong>

						<?php if ( $job_title = get_field( 'person_jobtitle', $item->ID ) ): ?>
						<div class="mb-2 font-italic person-job-title">
							<?php echo $job_title; ?>
						</div>
						<?php endif; ?>

					<?php if ( ! $is_content_empty ): ?>
					</a>
					<?php endif; ?>

					<?php
					if ( $email = get_field( 'person_email', $item->ID ) ):

					?>
					<div class="person-email">
						<a href="mailto:<?php echo $email; ?>">
							<?php if ( $fa_email_icon ): ?><span class="<?php echo $fa_email_icon; ?>" aria-hidden="true"></span><?php endif; ?>Email
							<span class="sr-only"> <?php echo $email; ?></span>
						</a>
					</div>
					<?php endif; ?>

					<?php if ( $phone = get_field( 'person_phone', $item->ID ) ): ?>
					<div class="person-phone">
						<?php if ( $fa_phone_icon ): ?><span class="<?php echo $fa_phone_icon; ?>" aria-hidden="true"></span><?php endif; ?><?php echo $phone; ?>
					</div>
					<?php endif; ?>

				</li>
				<?php endforeach; ?>
			</ul>
			<?php else: ?>
			<div class="ucf-post-list-error mb-4">No results found.</div>
			<?php endif; ?>
		<?php
			return ob_get_clean();
		}
	}

	add_filter( 'ucf_post_list_display_people', 'ucfwp_post_list_display_people', 10, 3 );


	if ( ! function_exists( 'ucfwp_post_list_display_people_after' ) ) {
		function ucfwp_post_list_display_people_after( $content, $items, $atts ) {
			ob_start();
		?>
		</div>
		<?php
			return ob_get_clean();
		}
	}

	add_filter( 'ucf_post_list_display_people_after', 'ucfwp_post_list_display_people_after', 10, 3 );

}


/**
 * Add custom "news" list layout for UCF Post List shortcode
 *
 * @since 0.5.2
 * @author Jo Dickson
 **/

if ( ! function_exists( 'ucfwp_post_list_display_news_before' ) ) {
	function ucfwp_post_list_display_news_before( $content, $posts, $atts ) {
		ob_start();
	?>
	<div class="ucf-post-list ucfwp-post-list-news" id="post-list-<?php echo $atts['list_id']; ?>">
	<?php
		return ob_get_clean();
	}
}

add_filter( 'ucf_post_list_display_news_before', 'ucfwp_post_list_display_news_before', 10, 3 );


if ( ! function_exists( 'ucfwp_post_list_display_news' ) ) {
	function ucfwp_post_list_display_news( $content, $posts, $atts ) {
		if ( $posts && ! is_array( $posts ) ) { $posts = array( $posts ); }

		$item_col = 'col-lg';
		if ( $atts['posts_per_row'] > 0 && ( 12 % $atts['posts_per_row'] ) === 0 ) {
			// Use specific column size class if posts_per_row equates
			// to a valid grid size
			$item_col .= '-' . 12 / $atts['posts_per_row'];
		}

		ob_start();
	?>

	<?php if ( $posts ): ?>

		<div class="row">

		<?php
		foreach ( $posts as $index => $item ):
			$item_title   = apply_filters( 'ucfwp_post_list_news_title', wptexturize( $item->post_title ), $item, $posts, $atts );
			$item_excerpt = $item_subhead = null;
			if ( filter_var( $atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN ) ) {
				setup_postdata( $item );
				$excerpt_length = apply_filters( 'ucfwp_post_list_news_excerpt_length', 25 );
				$item_excerpt = apply_filters( 'ucfwp_post_list_news_excerpt', ucfwp_get_excerpt( $item, $excerpt_length ), $item, $posts, $atts );
			}
			if ( filter_var( $atts['show_subhead'], FILTER_VALIDATE_BOOLEAN ) ) {
				if ( $item->post_type === 'ucf_resource_link' ) {
					$resource_sources = wp_get_post_terms( $item->ID, 'sources', array( 'fields' => 'names' ) );
					if ( ! empty( $resource_sources ) && is_array( $resource_sources ) )  {
						$item_subhead = wptexturize( $resource_sources[0] );
					}
				}
				else {
					$item_subhead = date( 'M d', strtotime( $item->post_date ) );
				}
				$item_subhead = apply_filters( 'ucfwp_post_list_news_subhead', $item_subhead, $item, $posts, $atts );
			}

			$item_link = '';
			if ( $item->post_type === 'ucf_resource_link' ) {
				$item_link = get_post_meta( $item->ID, 'ucf_resource_link_url', true );
			}
			else {
				$item_link = get_permalink( $item );
			}
			$item_link = apply_filters( 'ucfwp_post_list_news_link', $item_link, $item, $posts, $atts );

			$item_img = $item_img_srcset = null;
			if ( $atts['show_image'] ) {
				$item_img        = UCF_Post_List_Common::get_image_or_fallback( $item, 'thumbnail' );
				$item_img_srcset = UCF_Post_List_Common::get_image_srcset( $item, 'thumbnail' );
			}

			if ( $atts['posts_per_row'] > 0 && $index !== 0 && ( $index % $atts['posts_per_row'] ) === 0 ) {
				echo '</div><div class="row">';
			}
		?>

			<div class="<?php echo $item_col; ?> mb-4 ucf-post-list-item">
				<article>
					<?php if ( $item_link ) : ?>
					<a class="d-block text-secondary newsitem-link" href="<?php echo $item_link; ?>">
					<?php else: ?>
					<div class="text-default">
					<?php endif; ?>

						<div class="row">
							<?php if ( $item_img ) : ?>
							<div class="col-3 col-md-2 pr-0">
								<img src="<?php echo $item_img; ?>" srcset="<?php echo $item_img_srcset; ?>" class="ucf-post-list-thumbnail-image img-fluid" alt="">
							</div>
							<?php endif; ?>

							<div class="col">
								<h3 class="newsitem-heading"><?php echo $item_title; ?></h3>

								<?php if ( $item_excerpt ): ?>
								<div class="newsitem-excerpt"><?php echo $item_excerpt; ?></div>
								<?php endif; ?>

								<?php if ( $item_subhead ): ?>
								<div class="small text-default mt-2 newsitem-subhead"><?php echo $item_subhead; ?></div>
								<?php endif; ?>
							</div>
						</div>

					<?php if ( $item_link ): ?>
					</a>
					<?php else: ?>
					</div>
					<?php endif; ?>
				</article>
			</div>

		<?php endforeach; ?>

		</div>

	<?php else: ?>
		<div class="ucf-post-list-error">No results found.</div>
	<?php
		endif;

		return ob_get_clean();
	}
}

add_filter( 'ucf_post_list_display_news', 'ucfwp_post_list_display_news', 10, 3 );
