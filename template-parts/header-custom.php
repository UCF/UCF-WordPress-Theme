<?php
$obj         = ucfwp_get_queried_object();
$exclude_nav = get_field( 'page_header_exclude_nav', $obj );
?>

<?php if ( ! $exclude_nav ) { echo ucfwp_get_nav_markup( false ); } ?>

<?php echo ucfwp_get_header_content_markup(); ?>
