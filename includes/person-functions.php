<?php

/**
 * Displays a person's thumbnail image.
 *
 * @author Jo Dickson
 * @since 0.2.2
 * @param $post object | Person post object
 * @param $css_classes str | Additional classes to add to the thumbnail wrapper
 * @return Mixed | thumbnail HTML or void
 **/
if ( ! function_exists( 'ucfwp_get_person_thumbnail' ) ) {
	function ucfwp_get_person_thumbnail( $post, $css_classes='' ) {
		if ( ! $post->post_type == 'person' ) { return; }
		$thumbnail = get_the_post_thumbnail_url( $post ) ?: ucfwp_get_theme_mod_or_default( 'person_thumbnail' );
		// Account for attachment ID being returned by get_theme_mod_or_default():
		if ( is_numeric( $thumbnail ) ) {
			$thumbnail = wp_get_attachment_url( $thumbnail );
		}
		ob_start();
		if ( $thumbnail ):
	?>
		<div class="media-background-container person-photo mx-auto <?php echo $css_classes; ?>">
			<img src="<?php echo $thumbnail; ?>" alt="" class="media-background object-fit-cover">
		</div>
	<?php
		endif;
		return ob_get_clean();
	}
}


/**
 * Returns a person's name with title prefix and suffix applied.
 *
 * @author Jo Dickson
 * @since 0.2.2
 * @param $post object | Person post object
 * @return Mixed | person's formatted name or void
 **/
if ( ! function_exists( 'ucfwp_get_person_name' ) ) {
	function ucfwp_get_person_name( $post ) {
		if ( ! $post->post_type == 'person' ) { return; }
		$prefix = get_field( 'person_title_prefix', $post->ID ) ?: '';
		$suffix = get_field( 'person_title_suffix', $post->ID ) ?: '';
		if ( $prefix ) {
			$prefix = trim( $prefix ) . ' ';
		}
		if ( $suffix && substr( $suffix, 0, 1 ) !== ',' ) {
			$suffix = ' ' . trim( $suffix );
		}
		return wptexturize( $prefix . $post->post_title . $suffix );
	}
}
