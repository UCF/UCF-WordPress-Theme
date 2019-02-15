<?php
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
			<section class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-1' ); ?>
			</section>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-col-2' ) ): ?>
			<section class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-2' ); ?>
			</section>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-col-3' ) ): ?>
			<section class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-3' ); ?>
			</section>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-col-4' ) ): ?>
			<section class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-4' ); ?>
			</section>
		<?php endif; ?>

		</div>
	</div>
</footer>
<?php endif; ?>
