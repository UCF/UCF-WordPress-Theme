<?php
// Theme foundation
include_once 'includes/utilities.php';
include_once 'includes/config.php';
include_once 'includes/meta.php';
include_once 'includes/navwalker.php';
include_once 'includes/galleries.php';
include_once 'includes/media-backgrounds.php';
include_once 'includes/header-functions.php';
include_once 'includes/footer-functions.php';

// Plugin extras/overrides
if ( class_exists( 'UCF_Section_Common' ) ) {
	include_once 'includes/section-functions.php';
}

if ( class_exists( 'UCF_Alert_Common' ) ) {
	include_once 'includes/ucf-alert-functions.php';
}

if ( class_exists( 'UCF_Pegasus_List_Common' ) ) {
	include_once 'includes/pegasus-list-functions.php';
}

if ( class_exists( 'UCF_Events_Common' ) ) {
	include_once 'includes/events-functions.php';
}

if ( class_exists( 'UCF_Acad_Cal_Common' ) ) {
	include_once 'includes/acad-cal-functions.php';
}
