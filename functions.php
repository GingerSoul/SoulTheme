<?php

namespace GingerSoul\GingerTheme;

/**
 * @package GingerSoul
 * @subpackage GingerTheme
 * @since 2.0
 */

use FLThemeBuilderLayoutData, FLThemeBuilderLayoutRenderer;

add_action('after_setup_theme', __NAMESPACE__ . '\\after_setup_theme');

function after_setup_theme()
{
    load_theme_textdomain('gingertheme');
    add_theme_support('title-tag');
    add_theme_support( 'menus' );
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style',
    ]);
    add_theme_support('fl-theme-builder-headers');
    add_theme_support('fl-theme-builder-footers');
    add_theme_support('fl-theme-builder-parts');
	add_theme_support( 'post-thumbnails' );
}


/**
 * Enqueue scripts and styles.
 */
 
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\soultheme_scripts'); 
 
function soultheme_scripts() {

    $parenthandle = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();
    wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
        array(),  // if the parent theme code has a dependency, copy it to here
        $theme->parent()->get('Version')
    );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );




}




add_action('wp', __NAMESPACE__ . '\\header_footer_render');

function header_footer_render() {
    add_action('soultheme_do_header', get_theme_builder_callback('header') ?:
        function() { get_template_part('template-parts/default-header'); }
    );
    add_action('soultheme_do_footer', get_theme_builder_callback('footer') ?:
        function() { get_template_part('template-parts/default-footer'); }
    );
}

function get_theme_builder_callback($section)
{
    if (!class_exists('FLThemeBuilderLayoutData')) {
        return false;
    }
    $method_name = "get_current_page_{$section}_ids";
    return FLThemeBuilderLayoutData::$method_name() ?
        [FLThemeBuilderLayoutRenderer::class, "render_$section"] : false;
}

add_filter( 'fl_theme_builder_part_hooks', __NAMESPACE__ . '\\soultheme_register_part_hooks' );

function soultheme_register_part_hooks() {
  return array(
    array(
      'label' => 'Header',
      'hooks' => array(
        'soultheme_before_header' => 'Before Header',
        'soultheme_after_header'  => 'After Header',
      )
    ),
    array(
      'label' => 'Content',
      'hooks' => array(
        'soultheme_before_content' => 'Before Content',
        'soultheme_after_content'  => 'After Content',
        'soultheme_after_navigation'  => 'After Navigation',
      )
    ),
    array(
      'label' => 'Footer',
      'hooks' => array(
        'soultheme_before_footer' => 'Before Footer',
        'soultheme_after_footer'  => 'After Footer',
        'soultheme_end_body'  => 'End Body',
      )
    ),
  );
}





























function list_menu($atts, $content = null) {
    extract(shortcode_atts(array(  
        'menu'            => '', 
        'container'       => '', 
        'container_class' => '', 
        'container_id'    => '', 
        'menu_class'      => '', 
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'depth'           => 0,
        'walker'          => '',
        'theme_location'  => ''), 
        $atts));
  
    return wp_nav_menu( array( 
        'menu'            => $menu, 
        'container'       => $container, 
        'container_class' => $container_class, 
        'container_id'    => $container_id, 
        'menu_class'      => $menu_class, 
        'menu_id'         => $menu_id,
        'echo'            => false,
        'fallback_cb'     => $fallback_cb,
        'before'          => $before,
        'after'           => $after,
        'link_before'     => $link_before,
        'link_after'      => $link_after,
        'depth'           => $depth,
        'walker'          => $walker,
        'theme_location'  => $theme_location));
}
add_shortcode("listmenu", "list_menu");
