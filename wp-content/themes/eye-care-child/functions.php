<?php
/**
 * Theme functions and definitions.
 */
function eyecare_child_enqueue_styles() {
wp_enqueue_style( 'eye-care-child-style',
get_stylesheet_directory_uri() . '/style.css',
array(),
wp_get_theme()->get('Version')
);
}

add_action( 'wp_enqueue_scripts', 'eyecare_child_enqueue_styles', 11 );