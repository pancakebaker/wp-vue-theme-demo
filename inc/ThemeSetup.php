<?php
namespace DemoTheme;

class ThemeSetup
{
    public static function init()
    {
        add_action('after_setup_theme', [self::class, 'setup']);
    }

    public static function setup()
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
}
