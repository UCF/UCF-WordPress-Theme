<?php
global $wp_customize;
$image          = (bool) get_query_var( 'ucfwp_image_behind_nav', false );
$customizing    = isset( $wp_customize );
$feed_url       = get_theme_mod( 'mainsite_nav_url' ) ?: UCFWP_MAINSITE_NAV_URL;
$transient_name = 'ucfwp_mainsite_nav_json';
$result         = get_transient( $transient_name );

if ( empty( $result ) || $customizing ) {
	// Try fetching the theme mod value or default
	$result = ucfwp_fetch_json( $feed_url );

	// If the theme mod value failed and it's not what we set as our
	// default, try again using the default
	if ( !$result && $feed_url !== UCFWP_MAINSITE_NAV_URL ) {
		$result = ucfwp_fetch_json( UCFWP_MAINSITE_NAV_URL );
	}

	if ( ! $customizing ) {
		set_transient( $transient_name, $result, (60 * 60 * 24) );
	}
}

if ( $result ):
	$menu = $result;
?>
	<nav class="navbar navbar-toggleable-md navbar-mainsite py-2<?php echo $image ? ' py-sm-4 navbar-inverse header-gradient' : ' navbar-inverse bg-inverse-t-3 py-lg-4'; ?>" role="navigation" aria-label="Site navigation">
		<div class="container">
			<button class="navbar-toggler ml-auto collapsed" type="button" data-toggle="collapse" data-target="#header-menu" aria-controls="header-menu" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-text">Navigation</span>
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="header-menu">
				<ul id="menu-header-menu" class="nav navbar-nav nav-fill">
					<?php foreach ( $menu->items as $item ): ?>
					<li class="menu-item nav-item">
						<a href="<?php echo $item->url; ?>" target="<?php echo $item->target; ?>" class="nav-link">
							<?php echo $item->title; ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</nav>
<?php endif; ?>
