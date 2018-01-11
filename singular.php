<?php get_header(); the_post(); ?>

<article class="<?php echo $post->post_status; ?> post-list-item mt-4 mt-sm-5 mb-5">
	<div class="container">
		<?php the_content(); ?>
	</div>
</article>

<?php get_footer(); ?>
