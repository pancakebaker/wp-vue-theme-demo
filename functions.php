<?php

/**
 * Demo Theme functions and definitions
 *
 * @package Demo
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('after_setup_theme', 'demo_theme_setup');
add_action('wp_enqueue_scripts', 'demo_theme_enqueue_assets');
add_action('wp_head', 'demo_theme_load_tailwind_cdn', 1);
add_action('rest_api_init', 'demo_theme_register_logo_endpoint');

/**
 * Theme setup: add theme support, register menus, etc.
 */
function demo_theme_setup()
{
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    add_theme_support('post-thumbnails');

    register_nav_menus([
        'primary' => __('Primary Menu', 'demo'),
        'footer'  => __('Footer Menu', 'demo'),
    ]);
}

/**
 * Load Tailwind CSS via CDN
 */
function demo_theme_load_tailwind_cdn()
{
    echo '<script src="https://cdn.tailwindcss.com"></script>';
}

/**
 * Build a nested tree from flat menu items
 */
function demo_theme_build_menu_tree(array $items, $parent_id = 0)
{
    $branch = [];

    foreach ($items as $item) {
        if ((int)$item->menu_item_parent === $parent_id) {
            $children = demo_theme_build_menu_tree($items, (int)$item->ID);
            if ($children) {
                $item->children = $children;
            }
            $branch[] = $item;
        }
    }

    return $branch;
}

/**
 * Convert menu objects to array
 */
function demo_theme_menu_to_array($items)
{
    return array_map(function ($item) {
        return [
            'id'       => $item->ID,
            'title'    => $item->title,
            'url'      => $item->url,
            'parent'   => (int)$item->menu_item_parent,
            'order'    => (int)$item->menu_order,
            'children' => !empty($item->children) ? demo_theme_menu_to_array($item->children) : []
        ];
    }, (array)$items);
}

/**
 * Enqueue theme assets and pass data to Vue
 */
function demo_theme_enqueue_assets()
{
    $theme_dir  = get_stylesheet_directory();
    $theme_uri  = get_stylesheet_directory_uri();
    $manifest_path = $theme_dir . '/dist/manifest.json';

    if (!file_exists($manifest_path)) {
        error_log('Demo Theme: manifest.json not found at ' . $manifest_path);
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry    = $manifest['src/main.ts'] ?? null;

    if (!$entry) {
        error_log('Demo Theme: Entry "src/main.ts" not found in manifest.');
        return;
    }

    // Enqueue CSS
    if (!empty($entry['css'])) {
        foreach ($entry['css'] as $css_file) {
            wp_enqueue_style(
                'demo-theme-style',
                $theme_uri . '/dist/' . $css_file,
                [],
                filemtime($theme_dir . '/dist/' . $css_file)
            );
        }
    }

    // Enqueue JS
    wp_enqueue_script(
        'demo-theme-script',
        $theme_uri . '/dist/' . $entry['file'],
        [],
        filemtime($theme_dir . '/dist/' . $entry['file']),
        true
    );

    // Prepare menu data
    $locations = get_nav_menu_locations();
    $primary_id = $locations['primary'] ?? 0;
    $footer_id  = $locations['footer'] ?? 0;

    $primary_items = $primary_id ? wp_get_nav_menu_items($primary_id) : [];
    $footer_items  = $footer_id ? wp_get_nav_menu_items($footer_id) : [];

    $primary_tree = demo_theme_build_menu_tree($primary_items);
    $footer_tree  = demo_theme_build_menu_tree($footer_items);

    wp_localize_script('demo-theme-script', 'demoThemeData', [
        'primaryMenu' => demo_theme_menu_to_array($primary_tree),
        'footerMenu'  => demo_theme_menu_to_array($footer_tree),
    ]);
}

add_filter('wpcf7_load_js', '__return_false');

add_action('wp_footer', function () {
    if (has_shortcode(get_the_content(), 'contact-form-7')) {
        if (function_exists('wpcf7_enqueue_scripts')) {
            // Ensures dependencies are loaded so we can capture them
            wpcf7_enqueue_scripts();
        }

        global $wp_scripts;
        foreach ($wp_scripts->registered as $handle => $script) {
            if ($handle === 'contact-form-7') {
                if (!empty($script->extra['after'])) {
                    foreach ($script->extra['after'] as $inline_js) {
                        echo "<script>{$inline_js}</script>";
                    }
                }
            }
        }
    }
}, 5); // Run this before wp_footer scripts

function demo_get_vite_asset_uri($entry = 'src/main.ts') {
    $manifest_path = get_stylesheet_directory() . '/dist/manifest.json';

    if (!file_exists($manifest_path)) {
        return '';
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (!isset($manifest[$entry])) {
        return '';
    }

    return get_stylesheet_directory_uri() . '/dist/' . $manifest[$entry]['file'];
}

/**
 * Register REST API endpoint for custom logo
 */
function demo_theme_register_logo_endpoint()
{
    register_rest_route('demo-theme/v1', '/logo', [
        'methods'  => 'GET',
        'callback' => function () {
            if (!function_exists('get_theme_mod')) {
                return new WP_Error('missing_function', 'get_theme_mod does not exist');
            }

            $custom_logo_id = get_theme_mod('custom_logo');
            if (!$custom_logo_id) {
                return new WP_Error('no_logo', 'No logo found', ['status' => 404]);
            }

            $url = wp_get_attachment_image_url($custom_logo_id, 'full');
            if (!$url || !file_exists(get_attached_file($custom_logo_id))) {
                return new WP_Error('invalid_logo', 'Logo file missing');
            }

            return [
                'url' => $url,
                'alt' => get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true),
            ];
        },
        'permission_callback' => '__return_true',
    ]);
}
