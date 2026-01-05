<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\MenuItem;
use Modules\Menu\Entities\MenuItemTranslation;
use Illuminate\Support\Facades\DB;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Menu $menu)
    {
        $menu->load(['menuItems.children', 'menuItems.translations']);
        return view('admin.menu-items.index', compact('menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Menu $menu)
    {
        $languages = config('app.available_locales', ['en']);
        $parentItems = $menu->allMenuItems()->active()->get();
        return view('admin.menu-items.create', compact('menu', 'languages', 'parentItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'target' => 'required|string|in:_self,_blank,_parent,_top',
            'icon' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menu_items,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'translations' => 'array',
            'translations.*.locale' => 'required|string',
            'translations.*.title' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $menu) {
            $menuItem = MenuItem::create([
                'menu_id' => $menu->id,
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'url' => $request->url,
                'target' => $request->target,
                'icon' => $request->icon,
                'css_class' => $request->css_class,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Create translations
            if ($request->has('translations')) {
                foreach ($request->translations as $translation) {
                    MenuItemTranslation::create([
                        'menu_item_id' => $menuItem->id,
                        'locale' => $translation['locale'],
                        'title' => $translation['title'],
                    ]);
                }
            }
        });

        $notification = array('messege' => 'Menu item created successfully.', 'alert-type' => 'success');
        return redirect()->route('admin.menus.menu-items.index', $menu)->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $menuItem->load(['children', 'translations']);
        return view('admin.menu-items.show', compact('menu', 'menuItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $menuItem->load('translations');
        $languages = config('app.available_locales', ['en']);
        $parentItems = $menu->allMenuItems()->where('id', '!=', $menuItem->id)->active()->get();
        return view('admin.menu-items.edit', compact('menu', 'menuItem', 'languages', 'parentItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'target' => 'required|string|in:_self,_blank,_parent,_top',
            'icon' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menu_items,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'translations' => 'array',
            'translations.*.locale' => 'required|string',
            'translations.*.title' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request, $menuItem) {
            $menuItem->update([
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'url' => $request->url,
                'target' => $request->target,
                'icon' => $request->icon,
                'css_class' => $request->css_class,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Update translations
            if ($request->has('translations')) {
                // Delete existing translations
                $menuItem->translations()->delete();
                
                // Create new translations
                foreach ($request->translations as $translation) {
                    MenuItemTranslation::create([
                        'menu_item_id' => $menuItem->id,
                        'locale' => $translation['locale'],
                        'title' => $translation['title'],
                    ]);
                }
            }
        });

        $notification = array('messege' => 'Menu item updated successfully.', 'alert-type' => 'success');
        return redirect()->route('admin.menus.menu-items.index', $menu)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $menuItem->delete();
        $notification = array('messege' => 'Menu item deleted successfully.', 'alert-type' => 'success');
        return redirect()->route('admin.menus.menu-items.index', $menu)->with($notification);
    }

    /**
     * Toggle menu item active status.
     */
    public function toggleStatus(MenuItem $menuItem)
    {
        $menuItem->update(['is_active' => !$menuItem->is_active]);
        return response()->json(['success' => true, 'is_active' => $menuItem->is_active]);
    }

    /**
     * Update menu items order.
     */
    public function updateOrder(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.parent_id' => 'nullable|exists:menu_items,id',
        ]);

        foreach ($request->items as $item) {
            MenuItem::where('id', $item['id'])->update([
                'sort_order' => $item['sort_order'],
                'parent_id' => $item['parent_id'] ?? null,
            ]);
        }

        return response()->json(['success' => true]);
    }
} 