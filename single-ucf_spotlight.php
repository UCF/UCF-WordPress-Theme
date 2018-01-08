<?php get_header(); the_post(); ?>

<?php $layout = get_post_meta( $post->ID, 'ucf_spotlight_layout', true ); ?>

<?php if ( $layout === 'vertical' || $layout === 'square' ): ?>
<div class="container">
<?php endif; ?>

<?php echo do_shortcode( '[ucf-spotlight slug="' . $post->post_name . '"]' ); ?>

<?php if ( $layout === 'vertical' || $layout === 'square' ): ?>
</div>
<?php endif; ?>

<?php get_footer(); ?>
