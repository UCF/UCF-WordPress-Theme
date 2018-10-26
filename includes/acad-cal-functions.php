<?php

/**
 * Academic Calendar Custom Layout - Modern
 **/

// Before
if ( !function_exists( 'ucfwp_academic_calendar_modern_before' ) ) {
	function ucfwp_academic_calendar_modern_before( $content, $items, $args ) {
		return '<div class="academic-calendar-container">';
	}
}
add_filter( 'ucf_acad_cal_display_modern_before', 'ucfwp_academic_calendar_modern_before', 10, 3 );

// Title
if ( !function_exists( 'ucfwp_academic_calendar_modern_title' ) ) {
	function ucfwp_academic_calendar_modern_title( $content, $items, $args ) {
		ob_start();
		if ( isset( $args['title'] ) ) :
	?>
		<h2 class="mt-2 mb-4 mb-md-5 text-inverse"><span class="fa fa-calendar text-primary align-top" aria-hidden="true"></span> <?php echo $title; ?></h2>
	<?php
		endif;
		return ob_get_clean();
	}
}
add_filter( 'ucf_acad_cal_display_modern_title', 'ucfwp_academic_calendar_modern_title', 10, 3 );

// Content
if ( !function_exists( 'ucfwp_academic_calendar_modern_content' ) ) {
	function ucfwp_academic_calendar_modern_content( $content, $items, $args, $fallback_message ) {
		ob_start();
	?>
		<div class="row pt-2 pt-md-0">
			<div class="col-lg-4 mb-4 mb-lg-0">
				<h3 class="h5 mb-3"><span class="badge badge-inverse">Up Next</span></h3>
				<?php
				if ( !empty( $items ) ) :
					$first_item = array_shift( $items );
				?>
				<div class="academic-calendar-item">
					<a href="<?php echo $first_item->directUrl; ?>" target="_blank">
						<span class="text-inverse title h4 mb-3 d-block"><?php echo $first_item->summary; ?></span>
						<?php echo ucfwp_academic_calendar_format_date( $first_item->dtstart, $first_item->dtend ); ?>
						<?php if ( ! empty( $first_item->description ) ) : ?>
							<p class="text-inverse"><?php echo $first_item->description; ?></p>
						<?php endif; ?>
					</a>
				</div>
				<?php else: ?>
				<div class="ucf-academic-calendar-error"><?php echo $fallback_message; ?></div>
				<?php endif; ?>
			</div>
			<div class="col-lg-7 offset-lg-1">
				<h3 class="h5 mb-3"><span class="badge badge-inverse">Looking Ahead</span></h3>
				<?php if ( !empty( $items ) ) : ?>
				<div class="academic-calendar-columns">
					<?php foreach ( $items as $item ) : ?>
					<div class="academic-calendar-item">
						<a href="<?php echo $item->directUrl; ?>" target="_blank">
							<?php echo ucfwp_academic_calendar_format_date( $item->dtstart, $item->dtend ); ?>
							<span class="text-inverse title"><?php echo $item->summary; ?></span>
						</a>
					</div>
					<?php endforeach; ?>
				</div>
				<?php else: ?>
				<div class="ucf-academic-calendar-error"><?php echo $fallback_message; ?></div>
				<?php endif; ?>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_acad_cal_display_modern', 'ucfwp_academic_calendar_modern_content', 10, 4 );

// Custom date formatting
if ( !function_exists( 'ucfwp_academic_calendar_format_date' ) ) {
	function ucfwp_academic_calendar_format_date( $start_date, $end_date ) {
		$start_date = strtotime( $start_date );
		$end_date = strtotime( $end_date );

		ob_start();
	?>
		<div class="time text-primary">
		<time datetime="<?php echo date( 'Y-m-d', $start_date ); ?>"><?php echo date( 'F j', $start_date ); ?></time>
	<?php
		if ( $end_date ) :
			if ( date( 'F',  $start_date ) === date( 'F', $end_date ) ) :
		?>
			- <time datetime="<?php echo date( 'Y-m-d', $end_date ); ?>"><?php echo date( 'j', $end_date ); ?></time>
		<?php else: ?>
			- <time datetime="<?php echo date( 'Y-m-d', $end_date ); ?>"><?php echo date( 'F j', $end_date ); ?></time>
		<?php endif;
		endif;

		?>
		</div>
		<?php
		return ob_get_clean();
	}
}

// After
if ( !function_exists( 'ucfwp_academic_calendar_modern_after' ) ) {
	function ucfwp_academic_calendar_modern_after( $content, $items, $args ) {
		return '</div>';
	}
}
add_filter( 'ucf_acad_cal_display_modern_after', 'ucfwp_academic_calendar_modern_after', 10, 3 );


/**
 * Register custom Academic Calendar plugin layouts
 **/
if ( !function_exists( 'ucfwp_academic_calendar_add_layout' ) ) {
	function ucfwp_academic_calendar_add_layout( $layouts ) {
		if ( ! isset( $layouts['modern'] ) ) {
			$layouts['modern'] = 'Modern Layout';
		}

		return $layouts;
	}
}
add_filter( 'ucf_acad_cal_get_layouts', 'ucfwp_academic_calendar_add_layout', 10, 1 );
