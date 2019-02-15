<?php
$obj     = get_query_var( 'ucfwp_obj', ucfwp_get_queried_object() );
$content = get_field( 'page_header_content', $obj ) ?: '';
?>

<div class="header-content-inner">
	<?php echo $content; ?>
</div>
