<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

/**
 * Removes the BB Theme Customizer panels
 **/
add_action('customize_register', 'remove_bb_theme_customizer_panels', 11, 1);
function remove_bb_theme_customizer_panels($customizer) {
    //$customizer->remove_panel('fl-general');
    //$customizer->remove_panel('fl-header');
    //$customizer->remove_panel('fl-content');
    //$customizer->remove_panel('fl-footer');
    //$customizer->remove_panel('fl-code');
    //$customizer->remove_panel('fl-settings');
}

/**
 * Dequeues the Customizer styles used by the BB Theme
 **/
add_action('wp_print_styles', 'dequeue_bb_theme_customizer_styles', 100);
function dequeue_bb_theme_customizer_styles() {
    if(wp_style_is( 'fl-automator-skin', 'enqueued')){
        wp_dequeue_style('fl-automator-skin');
    }
}
