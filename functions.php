<?php
if (!defined('ABSPATH')) exit;

// load theme classes
require_once __DIR__ . '/inc/ThemeSetup.php';
require_once __DIR__ . '/inc/Assets.php';
require_once __DIR__ . '/inc/Menus.php';
require_once __DIR__ . '/inc/LogoAPI.php';

use DemoTheme\ThemeSetup;
use DemoTheme\Assets;
use DemoTheme\Menus;
use DemoTheme\LogoAPI;

// Initialize modules
ThemeSetup::init();
Assets::init();
LogoAPI::init();

// Contact Form 7 fix
add_filter('wpcf7_load_js', '__return_false');
add_action('wp_footer', function () {
    if (has_shortcode(get_the_content(), 'contact-form-7') && function_exists('wpcf7_enqueue_scripts')) {
        wpcf7_enqueue_scripts();

        global $wp_scripts;
        if (isset($wp_scripts->registered['contact-form-7'])) {
            $script = $wp_scripts->registered['contact-form-7'];
            if (!empty($script->extra['after'])) {
                foreach ($script->extra['after'] as $inline_js) {
                    echo "<script>{$inline_js}</script>";
                }
            }
        }
    }
}, 5);
