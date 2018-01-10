<?php

/**
 * Section markup override
 **/

// Before
if ( !function_exists( 'ucfwp_section_markup_before' ) ) {
	function ucfwp_section_markup_before( $content, $section, $class, $title, $section_id ) {
		// Retrieve background image sizes
		$bg_image_sm_id = get_field( 'section_background_image', $section->ID );    // -sm+
		$bg_image_xs_id = get_field( 'section_background_image_xs', $section->ID ); // -xs only
		$bg_images = ucfwp_get_media_background_picture_srcs( $bg_image_xs_id, $bg_image_sm_id, 'bg-img' );

		// Retrieve color classes/custom definitions
		$bg_color = get_field( 'section_background_color', $section->ID );
		$bg_color_custom = get_field( 'section_background_color_custom', $section->ID );

		$text_color = get_field( 'section_text_color', $section->ID );
		$text_color_custom = get_field( 'section_text_color_custom', $section->ID );

		// Define classes for the section
		$section_classes = '';
		if ( $class ) {
			$section_classes = $class;
		}

		if ( isset( $bg_images['fallback'] ) ) {
			$section_classes .= ' media-background-container';
		}
		if ( $bg_color && !empty( $bg_color ) && $bg_color !== 'custom' ) {
			$section_classes .= ' ' . $bg_color;
		}
		if ( $text_color && !empty( $text_color ) && $text_color !== 'custom' ) {
			$section_classes .= ' ' . $text_color;
		}

		// Define custom style attribute values for the section
		$style_attrs = '';
		if ( $bg_color === 'custom' && $bg_color_custom ) {
			$style_attrs .= 'background-color: '. $bg_color_custom .'; ';
		}
		if ( $text_color === 'custom' && $text_color_custom ) {
			$style_attrs .= 'color: '. $text_color_custom .'; ';
		}

		$title = ! empty( $title ) ? ' data-section-link-title="' . $title . '" role="region" aria-label="' . $title . '"' : '';

		$section_id = ! empty( $section_id ) ? 'id="' . $section_id . '" ' : '';

		ob_start();
	?>
		<section <?php echo $section_id; ?>class="<?php echo $section_classes; ?>" style="<?php echo $style_attrs; ?>"<?php echo $title; ?>>
		<?php echo ucfwp_get_media_background_picture( $bg_images ); ?>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_section_display_before', 'ucfwp_section_markup_before', 10, 5 );

