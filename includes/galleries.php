<?php
/**
 * Overrides WordPress's default [gallery] shortcode output with our own,
 * which uses the Athena Framework grid system to arrange thumbnails.
 *
 * Also adds the 'ucfwp_gallery_display' hook, which allows filtering of the
 * final generated gallery markup (not present in WP core).
 *
 * @since 0.1.0
 **/
function ucfwp_gallery_overrides( $html, $attr, $instance ) {
	global $post;

	// Set and sanitize various $attr's:
	if ( !empty( $attr['ids'] ) ) {
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	if ( isset( $attr['order'] ) && $attr['order'] === 'RAND' ) {
		$attr['orderby'] = 'none';
	}

	// Merge default attrs and user-provided attrs.
	$attr = shortcode_atts(
		array_merge(
			ucfwp_gallery_get_default_col_options(), // column_size default atts
			array(
				'order'              => 'ASC',
				'orderby'            => 'menu_order ID',
				'id'                 => $post->ID,
				'size'               => 'full',
				'include'            => '',
				'exclude'            => '',
				'thumbnail_captions' => false,  // custom
				'link'               => 'media'
			)
		),
		$attr,
		'gallery'
	);

	$attachment_args = array(
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => $attr['order'],
		'orderby'        => $attr['orderby']
	);

	if ( !empty( $attr['include'] ) ) {
		$attachments = get_posts(
			array_merge(
				$attachment_args,
				array( 'include' => $attr['include'], )
			)
		);
	}
	elseif ( !empty( $attr['exclude'] ) ) {
		$attachments = get_children(
			array_merge(
				$attachment_args,
				array(
					'post_parent' => $attr['id'],
					'exclude' => $attr['exclude'],
				)
			)
		);
	}
	else {
		$attachments = get_children(
			array_merge(
				$attachment_args,
				array( 'post_parent' => $attr['id'] )
			)
		);
	}

	// Return now if there's no attachments to work with
	if ( empty( $attachments ) ) {
		return '';
	}

	// Return a list of attachment links instead of the provided layout if
	// the current view is a feed
	if ( is_feed() ) {
		$output = '\n';
		foreach ( $attachments as $attachment ) {
			$output .= wp_get_attachment_link( $attachment->ID, 'thumbnail', true ) . '\n';
		}
		return $output;
	}

	// Finally, get + return the gallery markup
	$gallery_id = 'gallery-' . $instance;
	$retval = ucfwp_gallery_display_thumbnails( $gallery_id, $attachments, $attr );

	// Allow gallery markup to be overridden in child themes
	$retval = apply_filters( 'ucfwp_gallery_display', $retval, $gallery_id, $attachments, $attr );

	return $retval;
}

add_filter( 'post_gallery', 'ucfwp_gallery_overrides', 10, 3 );


/**
 * Returns an array of valid .col-xx class values for
 * the gallery shortcode.
 *
 * @since 0.1.0
 * @author Jo Dickson
 * @return array
 */
function ucfwp_gallery_get_valid_col_options() {
	return apply_filters( 'ucfwp_gallery_valid_col_options', array( 1, 2, 3, 4, 6, 12 ) );
}


/**
 * Returns an array of default column_size attribute values for the
 * gallery shortcode.
 *
 * @since 0.1.0
 * @author Jo Dickson
 * @return array
 */
function ucfwp_gallery_get_default_col_options() {
	return apply_filters( 'ucfwp_gallery_default_col_options', array(
		'column_size'    => 12,
		'column_size_sm' => 6,
		'column_size_md' => 6,
		'column_size_lg' => 4,
		'column_size_xl' => 3,
	) );
}


/**
 * Displays a list of attachments as a list of clickable thumbnails.
 *
 * @since 0.1.0
 * @author Jo Dickson
 * @param string $gallery_id A unique identifier for the gallery (to use as the id attribute on the gallery's parent element)
 * @param array $attachments Array of attachment post objects
 * @param array $attr Array of [gallery] shortcode attributes
 * @return string
 */
function ucfwp_gallery_display_thumbnails( $gallery_id, $attachments, $attr ) {
	if ( empty( $attachments ) || empty( $attr ) ) { return; }

	ob_start();
?>
	<div id="<?php echo $gallery_id; ?>" class="gallery gallery-thumbnails">
		<div class="row">

		<?php
		$column_size_valid_values = ucfwp_gallery_get_valid_col_options();
		$column_size_defaults = ucfwp_gallery_get_default_col_options();

		// Convert column sizes to int
		$attr['column_size'] = intval( $attr['column_size'] );
		$attr['column_size_sm'] = intval( $attr['column_size_sm'] );
		$attr['column_size_md'] = intval( $attr['column_size_md'] );
		$attr['column_size_lg'] = intval( $attr['column_size_lg'] );
		$attr['column_size_xl'] = intval( $attr['column_size_xl'] );

		// Make sure provided column sizes are valid; set sane defaults if not
		$col_xs = in_array( $attr['column_size'], $column_size_valid_values ) ? $attr['column_size'] : $column_size_defaults['column_size'];
		$col_sm = in_array( $attr['column_size_sm'], $column_size_valid_values ) ? $attr['column_size_sm'] : $column_size_defaults['column_size_sm'];
		$col_md = in_array( $attr['column_size_md'], $column_size_valid_values ) ? $attr['column_size_md'] : $column_size_defaults['column_size_md'];
		$col_lg = in_array( $attr['column_size_lg'], $column_size_valid_values ) ? $attr['column_size_lg'] : $column_size_defaults['column_size_lg'];
		$col_xl = in_array( $attr['column_size_xl'], $column_size_valid_values ) ? $attr['column_size_xl'] : $column_size_defaults['column_size_xl'];

		// Create column classes
		$col_class_xs = 'col-' . $col_xs;
		$col_class_sm = 'col-sm-' . $col_sm;
		$col_class_md = 'col-md-' . $col_md;
		$col_class_lg = 'col-lg-' . $col_lg;
		$col_class_xl = 'col-xl-' . $col_xl;
		$col_classes = implode( ' ', array( 'mb-3', $col_class_xs, $col_class_sm, $col_class_md, $col_class_lg, $col_class_xl ) );

		foreach ( $attachments as $attachment ):
			$excerpt = esc_attr( wptexturize( trim( $attachment->post_excerpt ) ) );
			$img_url_full = wp_get_attachment_image_src( $attachment->ID, 'full' );
			$img_url_full = $img_url_full ? $img_url_full[0] : '';
		?>
			<div class="<?php echo $col_classes; ?>">
				<figure class="figure">

					<?php if ( $attr['link'] !== 'none' ): ?>
					<a href="<?php echo $img_url_full; ?>">
					<?php endif; ?>

						<?php echo ucfwp_get_attachment_image( $attachment->ID, $attr['size'], false, array( 'class' => 'img-fluid' ) ); ?>

					<?php if ( $attr['link'] !== 'none' ): ?>
					</a>
					<?php endif; ?>

					<?php if ( filter_var( $attr['thumbnail_captions'], FILTER_VALIDATE_BOOLEAN ) ): ?>
					<figcaption class="figure-caption">
						<?php echo $excerpt; ?>
					</figcaption>
					<?php endif; ?>

				</figure>
			</div>
		<?php
		endforeach;
		?>

		</div>
	</div>
<?php
	return ob_get_clean();
}


/**
 * Replaces gallery settings in the media library modal with our own.
 * Based on https://wordpress.stackexchange.com/a/209923
 *
 * @since 0.1.0
 * @author Jo Dickson
 * @return void
 */
function ucfwp_gallery_custom_settings() {
	$col_options = ucfwp_gallery_get_valid_col_options();
	$col_defaults = ucfwp_gallery_get_default_col_options();
?>
<script type="text/html" id="tmpl-custom-gallery-settings">
	<h2><?php _e( 'Gallery Settings' ); ?></h2>

	<label class="setting">
		<span><?php _e('Column Size -xs'); ?></span>
		<select class="column_size" name="column_size" data-setting="column_size">
			<?php
			foreach ( $col_options as $i ) :
			?>
				<option value="<?php echo esc_attr( $i ); ?>" <#
					if ( <?php echo $i; ?> == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
				#>>
					<?php echo esc_html( $i ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>

	<label class="setting">
		<span><?php _e('Column Size -sm'); ?></span>
		<select class="column_size_sm" name="column_size_sm" data-setting="column_size_sm">
			<?php
			foreach ( $col_options as $i ) :
			?>
				<option value="<?php echo esc_attr( $i ); ?>" <#
					if ( <?php echo $i; ?> == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
				#>>
					<?php echo esc_html( $i ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>

	<label class="setting">
		<span><?php _e('Column Size -md'); ?></span>
		<select class="column_size_md" name="column_size_md" data-setting="column_size_md">
			<?php
			foreach ( $col_options as $i ) :
			?>
				<option value="<?php echo esc_attr( $i ); ?>" <#
					if ( <?php echo $i; ?> == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
				#>>
					<?php echo esc_html( $i ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>

	<label class="setting">
		<span><?php _e('Column Size -lg'); ?></span>
		<select class="column_size_lg" name="column_size_lg" data-setting="column_size_lg">
			<?php
			foreach ( $col_options as $i ) :
			?>
				<option value="<?php echo esc_attr( $i ); ?>" <#
					if ( <?php echo $i; ?> == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
				#>>
					<?php echo esc_html( $i ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>

	<label class="setting">
		<span><?php _e('Column Size -xl'); ?></span>
		<select class="column_size_xl" name="column_size_xl" data-setting="column_size_xl">
			<?php
			foreach ( $col_options as $i ) :
			?>
				<option value="<?php echo esc_attr( $i ); ?>" <#
					if ( <?php echo $i; ?> == wp.media.galleryDefaults.columns ) { #>selected="selected"<# }
				#>>
					<?php echo esc_html( $i ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>

	<label class="setting">
		<span><?php _e( 'Show Captions Under Thumbnails' ); ?></span>
		<input data-setting="thumbnail_captions" type="checkbox" <#
			if ( true == wp.media.galleryDefaults.thumbnail_captions ) { #>selected="selected"<# }
		#>>
	</label>

	<label class="setting size">
		<span><?php _e( 'Thumbnail Image Size' ); ?></span>
		<select class="size" name="size"
			data-setting="size"
			<# if ( data.userSettings ) { #>
				data-user-setting="imgsize"
			<# } #>
			>
			<?php
			/** This filter is documented in wp-admin/includes/media.php */
			$size_names = apply_filters( 'image_size_names_choose', array(
				'full'      => __( 'Full Size' ),
				'large'     => __( 'Large' ),
				'medium'    => __( 'Medium' ),
				'thumbnail' => __( 'Thumbnail' ),
			) );

			foreach ( $size_names as $size => $label ) : ?>
				<option value="<?php echo esc_attr( $size ); ?>">
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</label>

	<label class="setting">
		<span><?php _e( 'Thumbnail Links To' ); ?></span>
		<select class="link" name="link" data-setting="link">
			<option value="media" <#
				if ( 'media' == wp.media.galleryDefaults.link ) { #>selected="selected"<# }
			#>>Media</option>
			<option value="none" <#
				if ( 'none' == wp.media.galleryDefaults.link ) { #>selected="selected"<# }
			#>>None (no link)</option>
		</select>
	</label>
</script>

<script type="text/javascript">
	jQuery( document ).ready( function() {
		_.extend( wp.media.galleryDefaults, {
			column_size: <?php echo $col_defaults['column_size']; ?>,
			column_size_sm: <?php echo $col_defaults['column_size_sm']; ?>,
			column_size_md: <?php echo $col_defaults['column_size_md']; ?>,
			column_size_lg: <?php echo $col_defaults['column_size_lg']; ?>,
			column_size_xl: <?php echo $col_defaults['column_size_xl']; ?>,
			thumbnail_captions: false,
			size: 'full',
			link: 'media'
		} );

		wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend( {
			template: function( view ) {
				return wp.media.template( 'custom-gallery-settings' )( view );
			}
		} );
	} );
</script>
<?php
}

add_action( 'print_media_templates', 'ucfwp_gallery_custom_settings' );
