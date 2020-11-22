<?php

namespace GingerSoul\GingerTheme;

/**
 * @package GingerSoul
 * @subpackage GingerTheme
 * @since 2.0
 */

use FLThemeBuilderLayoutData;

add_action('after_setup_theme', __NAMESPACE__ . '\\after_setup_theme');

function after_setup_theme()
{
    load_theme_textdomain('gingertheme');
    add_theme_support('title-tag');
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style',
    ]);
    add_theme_support('fl-theme-builder-headers');
    add_theme_support('fl-theme-builder-footers');
    add_theme_support('fl-theme-builder-parts');
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
