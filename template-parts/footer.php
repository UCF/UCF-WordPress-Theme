<?php
if (
	is_active_sidebar( 'footer-col-1' )
	|| is_active_sidebar( 'footer-col-2' )
	|| is_active_sidebar( 'footer-col-3' )
	|| is_active_sidebar( 'footer-col-4' )
):
?>
<footer class="site-footer bg-inverse pt-4 py-md-5" aria-label="Site footer">
	<div class="container mt-4">
		<div class="row">

		<?php if ( is_active_sidebar( 'footer-col-1' ) ): ?>
			<div class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-1' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-col-2' ) ): ?>
			<div class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-2' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-col-3' ) ): ?>
			<div class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-3' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'footer-col-4' ) ): ?>
			<div class="col-12 col-lg">
				<?php dynamic_sidebar( 'footer-col-4' ); ?>
			</div>
		<?php endif; ?>

		</div>
	</div>
</footer>
<?php endif; ?>
