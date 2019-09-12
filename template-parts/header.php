<?php
$obj         = ucfwp_get_queried_object();
$exclude_nav = get_field( 'page_header_exclude_nav', $obj );
?>

<div class="header-default mb-0 d-flex flex-column">
    <div class="header-media-background-wrap">
        <div class="header-media-background media-background-container">
            <?php
            // TODO: Add functionality to display a light or dark background image for this header style (and the mobile images for each) depending on what the user selects (default to light?)
            ?>
        </div>
    </div>

    <?php
    // Display the site nav
    if ( ! $exclude_nav ) { echo ucfwp_get_nav_markup(); }
    ?>

    <?php
    // Display the inner header contents
    ?>
    <div class="header-content">
        <div class="header-content-flexfix">
            <?php echo ucfwp_get_header_content_markup(); ?>
        </div>
    </div>
</div>
