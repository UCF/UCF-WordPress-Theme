<?php
$obj         = ucfwp_get_queried_object();
$exclude_nav = get_field( 'page_header_exclude_nav', $obj );
// Note/TODO: The following variable will be dynamic in a future PR in order to change the header's background and text color based on if a light or dark image is selected by the user
$bg_color_class = 'bg-inverse'
?>

<div class="header-default mb-0 d-flex flex-column <?php echo $bg_color_class; ?>">
    <div class="header-media-background-wrap">
        <div class="header-media-background media-background-container">
            <?php
            // Note/TODO: This markup and functionality is temporary, upcoming PR will have functionality that allows users to select a predefined header image or upload their own in the Customizer.
            $temp_default_header_img    = UCFWP_THEME_IMG_URL . '/default-headers/dark-geometric.jpg';
            $temp_default_header_img_xs = UCFWP_THEME_IMG_URL . '/default-headers/dark-geometric-xs.jpg';
            ?>
            <picture class="media-background-picture">  
                <source srcset="<?php echo $temp_default_header_img_xs; ?>" media="(max-width: 575px)">
                <img class="media-background object-fit-cover" src="<?php echo $temp_default_header_img; ?>" alt="">
            </picture>
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
