<?php
/**
 * Footer Related Functions
 **/

/**
 * Returns markup for the site footer. Will return an empty string if all
 * footer sidebars are empty.
 *
 * @author Jo Dickson
 * @since 1.0.0
 * @return string Footer HTML markup
 **/
if ( !function_exists( 'ucfwp_get_footer_markup' ) ) {
	function ucfwp_get_footer_markup() {
		ob_start();

		if (
			is_active_sidebar( 'footer-col-1' )
			|| is_active_sidebar( 'footer-col-2' )
			|| is_active_sidebar( 'footer-col-3' )
			|| is_active_sidebar( 'footer-col-4' )
		):
	?>
		<footer class="site-footer bg-inverse pt-4 py-md-5">
			<div class="container mt-4">
				<div class="row">

				<?php if ( is_active_sidebar( 'footer-col-1' ) ): ?>
					<section class="col-lg mb-5 mb-lg-4">
						<?php dynamic_sidebar( 'footer-col-1' ); ?>
					</section>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-col-2' ) ): ?>
					<section class="col-lg mb-5 mb-lg-4">
						<?php dynamic_sidebar( 'footer-col-2' ); ?>
					</section>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-col-3' ) ): ?>
					<section class="col-lg mb-5 mb-lg-4">
						<?php dynamic_sidebar( 'footer-col-3' ); ?>
					</section>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'footer-col-4' ) ): ?>
					<section class="col-lg mb-5 mb-lg-4">
						<?php dynamic_sidebar( 'footer-col-4' ); ?>
					</section>
				<?php endif; ?>

			</div>
		</footer>
	<?php
		endif;

		return ob_get_clean();
	}
}
