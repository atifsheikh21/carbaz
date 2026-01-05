<?php

namespace App\Helpers;

use Modules\Menu\Services\MenuService;

class MenuHelper
{
    /**
     * Check if menu exists for a location
     */
    public static function hasMenu(string $location): bool
    {
        $menuService = app(MenuService::class);
        $menu = $menuService->getMenuByLocation($location);
        return $menu !== null;
    }

    /**
     * Render menu with mobile navigation structure
     */
    public static function renderMenu(string $location, array $options = []): string
    {
        $menuService = app(MenuService::class);
        
        // Set default options to match mobile navigation structure
        $defaultOptions = [
            'ul_class' => 'nav-links',
            'li_class' => '',
            'a_class' => '',
            'submenu_class' => 'd-menu',
            'active_class' => 'active',
        ];

        $options = array_merge($defaultOptions, $options);
        
        return $menuService->renderMenu($location, $options);
    }

    /**
     * Render mobile menu specifically
     */
    public static function renderMobileMenu(string $location): string
    {
        return self::renderMenu($location, [
            'ul_class' => 'nav-links',
            'li_class' => '',
            'a_class' => '',
            'submenu_class' => 'd-menu',
            'active_class' => 'active',
        ]);
    }

    /**
     * Get menu items for admin
     */
    public static function getMenuItems(string $location): array
    {
        $menuService = app(MenuService::class);
        $menu = $menuService->getMenuByLocation($location);
        
        if (!$menu) {
            return [];
        }

        return $menuService->getMenuItemsForAdmin($menu);
    }
} 