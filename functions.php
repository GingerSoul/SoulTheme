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
    if (!class_exists('FLThemeBuilderLayoutData')) {
        return;
    }
    add_action('soultheme_do_header', !empty(FLThemeBuilderLayoutData::get_current_page_header_ids()) ?
        [FLThemeBuilderLayoutRenderer::class, 'render_header'] :
        function() { get_template_part('template-parts/default-header'); }
    );

    add_action('soultheme_do_footer', !empty(FLThemeBuilderLayoutData::get_current_page_footer_ids()) ?
        [FLThemeBuilderLayoutRenderer::class, 'render_footer'] :
        function() { get_template_part('template-parts/default-footer'); }
    );
}