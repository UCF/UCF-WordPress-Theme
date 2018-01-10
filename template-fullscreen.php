<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, post
 */
?>
<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item mb-5">
	<?php the_content(); ?>
</article>

<?php get_footer(); ?>
