<?php

/**
 * Events - classic layout overrides
 **/

// Content
if ( !function_exists( 'ucfwp_events_display_classic' ) ) {
	function ucfwp_events_display_classic( $content, $items, $args, $display_type ) {
		if ( $items && ! is_array( $items ) ) { $items = array( $items ); }
		ob_start();
	?>
		<div class="ucf-events-list">

		<?php if ( $items ): ?>
			<?php
			foreach( $items as $event ) :
				$starts = new DateTime( $event->starts );
				$ends   = new DateTime( $event->ends );
			?>
			<div class="ucf-event ucf-event-row">
				<div class="ucf-event-col ucf-event-when">
					<time class="ucf-event-start-datetime" datetime="<?php echo $starts->format( 'c' ); ?>">
						<span class="ucf-event-start-month"><?php echo $starts->format( 'M' ); ?></span>
						<span class="ucf-event-start-date"><?php echo $starts->format( 'j' ); ?></span>
					</time>
				</div>
				<div class="ucf-event-col ucf-event-content">
					<a class="ucf-event-link" href="<?php echo $event->url; ?>">
						<span class="ucf-event-title"><?php echo $event->title; ?></span>
						<span class="ucf-event-time">
							<span class="ucf-event-start-time"><?php echo $starts->format( 'g:i a' ); ?></span>
							-
							<span class="ucf-event-end-time"><?php echo $ends->format( 'g:i a' ); ?></span>
						</span>
					</a>
				</div>
			</div>
			<?php endforeach; ?>

		<?php else: ?>
			<span class="ucf-events-error">No events found.</span>
		<?php endif; ?>

		</div>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_events_display_classic', 'ucfwp_events_display_classic', 11, 4 );
