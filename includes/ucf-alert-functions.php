<?php

/**
 * Set required settings changes for UCF-Alert-Plugin
 **/
update_option( 'ucf_alert_include_css', false ); // Athena Theme rolls its own alert layout/styles
update_option( 'ucf_alert_include_js', true ); // Athena Theme uses vanilla UCF-Alert-Plugin js
update_option( 'ucf_alert_include_js_deps', false ); // Athena Theme includes js-cookie; see below

if ( !function_exists( 'ucfwp_alert_js_deps' ) ) {
	function ucfwp_alert_js_deps() {
		// js-cookie is included in script.min.js; make sure
		// UCF-Alert-Plugin can use it:
		return array( 'jquery', 'script' );
	}
}
add_filter( 'ucf_alert_script_deps', 'ucfwp_alert_js_deps', 10, 0 );


/**
 * Add custom alert layout - "Icon" layout
 **/

// Before
if ( !function_exists( 'ucfwp_alert_display_faicon_before' ) ) {
	function ucfwp_alert_display_faicon_before( $content, $args ) {
		$id = UCF_Alert_Common::get_alert_wrapper_id();
		ob_start();
	?>
		<div data-script-id="<?php echo $id; ?>" class="ucf-alert-wrapper"></div>
		<script type="text/html" id="<?php echo $id; ?>">
			<div class="alert ucf-alert ucf-alert-faicon" data-alert-id="" role="alert">
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_alert_display_faicon_before', 'ucfwp_alert_display_faicon_before', 10, 2 );

// Content
if ( !function_exists( 'ucfwp_alert_display_faicon' ) ) {
	function ucfwp_alert_display_faicon( $content, $args ) {
		ob_start();
	?>
		<div class="container">
			<div class="row no-gutters">
				<div class="col col-lg-8 offset-lg-2">
					<a class="ucf-alert-content">
						<div class="row no-gutters">
							<div class="col-auto">
								<span class="ucf-alert-icon fa" aria-hidden="true"></span>
							</div>
							<div class="col">
								<strong class="ucf-alert-title h4"></strong>
								<div class="ucf-alert-body"></div>
								<div class="ucf-alert-cta"></div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-auto">
					<button type="button" class="ucf-alert-close close" aria-label="Close alert">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_alert_display_faicon', 'ucfwp_alert_display_faicon', 10, 2 );

// After
if ( !function_exists( 'ucfwp_alert_display_faicon_after' ) ) {
	function ucfwp_alert_display_faicon_after( $content, $args ) {
		ob_start();
	?>
			</div>
		</script>
	<?php
		return ob_get_clean();
	}
}
add_filter( 'ucf_alert_display_faicon_after', 'ucfwp_alert_display_faicon_after', 10, 2 );


/**
 * Register custom UCF Alert plugin layouts
 **/
if ( !function_exists( 'ucfwp_alert_get_layouts' ) ) {
	function ucfwp_alert_get_layouts( $layouts ) {
		$layouts = array_merge(
			$layouts,
			array(
				'faicon' => 'Icon Layout'
			)
		);
		return $layouts;
	}
}
add_filter( 'ucf_alert_get_layouts', 'ucfwp_alert_get_layouts' );


/**
 * Hook into the header template to display the alert
 **/
if ( !function_exists( 'ucfwp_display_alert' ) ) {
	function ucfwp_display_alert() {
		echo UCF_Alert_Common::display_alert( 'faicon', array() );
	}
}
add_filter( 'after_body_open', 'ucfwp_display_alert', 1 );
