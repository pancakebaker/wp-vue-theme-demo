<?php
namespace DemoTheme;

class Menus
{
    public static function init()
    {
        // No actions required here; this class is utility-based
    }

    public static function get($location)
    {
        $locations = get_nav_menu_locations();
        $menu_id = $locations[$location] ?? 0;
        $items = $menu_id ? wp_get_nav_menu_items($menu_id) : [];
        $tree = self::build_tree($items);
        return self::to_array($tree);
    }

    protected static function build_tree(array $items, $parent_id = 0)
    {
        $branch = [];

        foreach ($items as $item) {
            if ((int)$item->menu_item_parent === $parent_id) {
                $children = self::build_tree($items, (int)$item->ID);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }

    protected static function to_array($items)
    {
        return array_map(function ($item) {
            return [
                'id'       => $item->ID,
                'title'    => $item->title,
                'url'      => $item->url,
                'parent'   => (int)$item->menu_item_parent,
                'order'    => (int)$item->menu_order,
                'children' => !empty($item->children) ? self::to_array($item->children) : [],
            ];
        }, (array)$items);
    }
}
