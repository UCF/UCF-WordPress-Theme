<?php get_header(); the_post(); ?>
<?php echo do_shortcode( '[ucf-spotlight slug="' . $post->post_name . '"]' ); ?>
<?php get_footer(); ?>
