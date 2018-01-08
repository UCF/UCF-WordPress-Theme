<?php

/**
 * Add custom Pegasus List layout - Featured Issue layout
 **/

// Before
if ( !function_exists( 'ucfwp_pegasus_list_display_featured_before' ) ) {
	function ucfwp_pegasus_list_display_featured_before( $content, $items, $args ) {
		ob_start();
	?>
		<div class="ucf-pegasus-list ucf-pegasus-list-featured">
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_pegasus_list_display_featured_before', 'ucfwp_pegasus_list_display_featured_before', 10, 3 );

// Content
if ( !function_exists( 'ucfwp_pegasus_list_display_featured_content' ) ) {
	function ucfwp_pegasus_list_display_featured_content( $content, $items, $args ) {
		$first       = array_shift( $items );
		$issue_url   = $first->link;
		$issue_title = $first->title->rendered;
		$cover_story = $first->_embedded->issue_cover_story[0];
		$cover_story_url = $cover_story->link;
		$cover_story_title = $cover_story->title->rendered;
		$cover_story_subtitle = $cover_story->story_subtitle;
		$cover_story_description = $cover_story->story_description;
		$cover_story_blurb = null;
		$thumbnail_id = $first->featured_media;
		$thumbnail = null;
		$thumbnail_url = null;

		if ( $thumbnail_id !== 0 ) {
			$thumbnail = $first->_embedded->{"wp:featuredmedia"}[0];
			$thumbnail_url = $thumbnail->media_details->sizes->full->source_url;
		}
		if ( $cover_story_description ) {
			$cover_story_blurb = $cover_story_description;
		} else if ( $cover_story_subtitle ) {
			$cover_story_blurb = $cover_story_subtitle;
		}

		ob_start();
	?>
		<!-- Featured Issue -->
		<div class="row mb-4 mb-md-5">
			<div class="col-sm-4">
				<a class="h2 mb-2" href="<?php echo $issue_url; ?>" target="_blank">
					<img class="w-100" src="<?php echo $thumbnail_url; ?>" alt="<?php echo $issue_title; ?>">
				</a>
			</div>
			<div class="col-sm-8 mt-3 mt-sm-0">
				<a class="h1 text-secondary" href="<?php echo $issue_url; ?>" target="_blank">
					<?php echo $issue_title; ?>
				</a>
				<p class="mt-2 mt-md-4 mb-2 text-muted text-uppercase">Featured Story</p>
				<a class="h3 font-slab-serif text-secondary" href="<?php echo $cover_story_url; ?>" target="_blank">
					<?php echo $cover_story_title; ?>
				</a>
				<p class="mt-3 mb-4"><?php echo $cover_story_blurb; ?></p>
				<a class="btn btn-primary" href="<?php echo $issue_url; ?>" target="_blank">
					Read More<span class="sr-only"> from <?php echo $issue_title; ?></span>
				</a>
			</div>
		</div>

		<hr class="hidden-lg-up my-4 my-md-5">

		<div class="row">
		<?php foreach( $items as $item ) :
			$issue_url   = $item->link;
			$issue_title = $item->title->rendered;
			$cover_story = $item->_embedded->issue_cover_story[0];
			$cover_story_url = $cover_story->link;
			$cover_story_title = $cover_story->title->rendered;
			$cover_story_subtitle = $cover_story->story_subtitle;
			$cover_story_description = $cover_story->story_description;
			$cover_story_blurb = null;
			$thumbnail_id = $item->featured_media;
			$thumbnail = null;
			$thumbnail_url = null;

			if ( $thumbnail_id !== 0 ) {
				$thumbnail = $item->_embedded->{"wp:featuredmedia"}[0];
				$thumbnail_url = $thumbnail->media_details->sizes->full->source_url;
			}
			if ( $cover_story_description ) {
				$cover_story_blurb = $cover_story_description;
			} else if ( $cover_story_subtitle ) {
				$cover_story_blurb = $cover_story_subtitle;
			}
		?>
			<div class="col-lg-3 mb-4">
				<div class="row">
					<div class="col-3 col-lg-12 pr-0 pr-sm-3">
						<a class="text-secondary" href="<?php echo $issue_url; ?>" target="_blank">
							<img class="w-100 mb-3" src="<?php echo $thumbnail_url; ?>" alt="<?php echo $issue_title; ?>">
						</a>
					</div>
					<div class="col-9 col-lg-12">
						<a class="h3 text-secondary" href="<?php echo $issue_url; ?>" target="_blank">
							<?php echo $issue_title; ?>
						</a>
						<p class="mt-2 mt-md-3 mb-1 small text-muted text-uppercase">Featured Story</p>
						<a class="h5 font-slab-serif text-secondary mb-3" href="<?php echo $cover_story_url; ?>" target="_blank">
							<?php echo $cover_story_title; ?>
						</a>
						<p class="my-2"><?php echo $cover_story_blurb; ?></p>
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		</div>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_pegasus_list_display_featured_content', 'ucfwp_pegasus_list_display_featured_content', 10, 3 );

// After
if ( !function_exists( 'ucfwp_pegasus_list_display_featured_after' ) ) {
	function ucfwp_pegasus_list_display_featured_after( $content, $items, $args ) {
		ob_start();
	?>
		</div>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_pegasus_list_display_featured_after', 'ucfwp_pegasus_list_display_featured_after', 10, 3 );


/**
 * Register custom plugin layouts
 **/
if ( !function_exists( 'ucfwp_pegasus_add_layout' ) ) {
	function ucfwp_pegasus_add_layout( $layouts ) {
		if ( ! isset( $layouts['featured'] ) ) {
			$layouts['featured'] = 'Featured Issue Layout';
		}

		return $layouts;
	}
}
add_filter( 'ucf_pegasus_list_get_layouts', 'ucfwp_pegasus_add_layout', 10, 1 );
