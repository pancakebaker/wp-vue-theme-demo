<?php
namespace DemoTheme;

class Assets
{
    public static function init()
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue']);
        add_action('wp_head', [self::class, 'load_tailwind_cdn'], 1);
    }

    public static function load_tailwind_cdn()
    {
        echo '<script src="https://cdn.tailwindcss.com"></script>';
    }

    public static function enqueue()
    {
        $theme_dir  = get_stylesheet_directory();
        $theme_uri  = get_stylesheet_directory_uri();
        $manifest_path = $theme_dir . '/dist/manifest.json';

        if (!file_exists($manifest_path)) {
            error_log('Demo Theme: manifest.json not found.');
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

        // Menu data
        wp_localize_script('demo-theme-script', 'demoThemeData', [
            'primaryMenu' => Menus::get('primary'),
            'footerMenu'  => Menus::get('footer'),
        ]);
    }

    public static function get_vite_asset_uri($entry = 'src/main.ts')
    {
        $manifest_path = get_stylesheet_directory() . '/dist/manifest.json';

        if (!file_exists($manifest_path)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        return isset($manifest[$entry])
            ? get_stylesheet_directory_uri() . '/dist/' . $manifest[$entry]['file']
            : '';
    }
}
