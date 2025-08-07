<?php
namespace DemoTheme;

use WP_Error;

class LogoAPI
{
    public static function init()
    {
        add_action('rest_api_init', [self::class, 'register']);
    }

    public static function register()
    {
        register_rest_route('demo-theme/v1', '/logo', [
            'methods'             => 'GET',
            'callback'            => [self::class, 'get_logo'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function get_logo()
    {
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
    }
}
