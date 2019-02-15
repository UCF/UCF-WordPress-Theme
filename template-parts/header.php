<?php
$obj                 = get_query_var( 'ucfwp_obj', ucfwp_get_queried_object() );
$header_content_type = ucfwp_get_header_content_type( $obj );
$exclude_nav         = get_field( 'page_header_exclude_nav', $obj );
?>

<?php if ( ! $exclude_nav ) { echo ucfwp_get_nav_markup( false ); } ?>

<?php get_template_part( ucfwp_get_template_part_slug( 'header_content' ), $header_content_type ); ?>
