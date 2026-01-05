<?php

namespace Modules\Menu\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\MenuItem;
use Modules\Menu\Entities\MenuItemTranslation;
use Illuminate\Support\Facades\DB;
use Modules\Language\Entities\Language;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Menu $menu)
    {
        $mainMenu = $menu;
        $menu = MenuItem::where('menu_id', $menu->id)->with('translations')->get();
        return view('menu::admin.menu-items.index', compact('menu','mainMenu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Menu $menu)
    {
        $frontendRoutes = [
            ['name' => 'Home', 'route' => '/'],
            ['name' => 'About Us', 'route' => '/about-us'],
            ['name' => 'Contact Us', 'route' => '/contact-us'],
            ['name' => 'Terms & Conditions', 'route' => '/terms-conditions'],
            ['name' => 'Privacy Policy', 'route' => '/privacy-policy'],
            ['name' => 'FAQ', 'route' => '/faq'],
            ['name' => 'Compare', 'route' => '/compare'],
            ['name' => 'Blogs', 'route' => '/blogs'],
            ['name' => 'Listings', 'route' => '/listings'],
            ['name' => 'Dealers', 'route' => '/dealers'],
            ['name' => 'Join as Dealer', 'route' => '/join-as-dealer'],
            ['name' => 'Pricing Plan', 'route' => '/pricing-plan'],
            // Dynamic routes that require parameters are excluded since they need specific values
        ];

        $languages = config('app.available_locales', ['en']);
        $parentItems = $menu->allMenuItems()->active()->get();
        return view('menu::admin.menu-items.create', compact('menu', 'languages', 'parentItems','frontendRoutes'));
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
        ]);

        DB::transaction(function () use ($request, $menu) {
            $menuItem = MenuItem::create([
                'menu_id' => $menu->id,
                'parent_id' => $request->parent_id,
                'title' => $request->title,
                'url' => $request->url === 'custom' ? $request->custom_url : $request->url,
                'target' => $request->target,
                'icon' => $request->icon,
                'css_class' => $request->css_class,
                'is_active' => $request->boolean('is_active', true),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Create translations
            $languages = Language::all();
            foreach($languages as $language){
                $menuItem_translation = new MenuItemTranslation();
                $menuItem_translation->menu_item_id = $menuItem->id;
                $menuItem_translation->locale =  $language->lang_code;
                $menuItem_translation->title = $request->title;
                $menuItem_translation->save();
            }
        });

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $notification= trans('translate.Menu Item Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.menus.menu-items.index', $menu)->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $menuItem->load(['children', 'translations']);
        return view('menu::admin.menu-items.show', compact('menu', 'menuItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $frontendRoutes = [
            ['name' => 'Home', 'route' => '/'],
            ['name' => 'About Us', 'route' => '/about-us'],
            ['name' => 'Contact Us', 'route' => '/contact-us'],
            ['name' => 'Terms & Conditions', 'route' => '/terms-conditions'],
            ['name' => 'Privacy Policy', 'route' => '/privacy-policy'],
            ['name' => 'FAQ', 'route' => '/faq'],
            ['name' => 'Compare', 'route' => '/compare'],
            ['name' => 'Blogs', 'route' => '/blogs'],
            ['name' => 'Listings', 'route' => '/listings'],
            ['name' => 'Dealers', 'route' => '/dealers'],
            ['name' => 'Join as Dealer', 'route' => '/join-as-dealer'],
            ['name' => 'Pricing Plan', 'route' => '/pricing-plan'],
            // Dynamic routes that require parameters are excluded since they need specific values
        ];

        $menuItem = MenuItem::find($request->menu_id);
        $menu = $menuItem->menu;
        $menuItem->load('translations');
        $menu_translate = MenuItemTranslation::where('menu_item_id', $request->menu_id)->where('locale', $request->lang_code)->first();
        $parentItems = $menu->allMenuItems()->where('id', '!=', $menuItem->id)->active()->get();
        return view('menu::admin.menu-items.edit', compact('menu', 'menuItem', 'parentItems','menu_translate','frontendRoutes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $menu = $menuItem->menu;

        DB::transaction(function () use ($request, $menuItem) {
            // Update menu item fields (only for admin language)
            if (admin_lang() == $request->lang_code) {
                $menuItem->update([
                    'parent_id' => $request->parent_id ?: null,
                    'url' => $request->url === 'custom' ? $request->custom_url : $request->url,
                    'target' => $request->target,
                    'icon' => $request->icon,
                    'css_class' => $request->css_class,
                    'is_active' => $request->boolean('is_active', true),
                    'sort_order' => $request->sort_order ?? 0,
                ]);
            }

            // Update translation
            $menuItem_translate = MenuItemTranslation::where('id', $request->translate_id)
                                                   ->where('menu_item_id', $menuItem->id)
                                                   ->where('locale', $request->lang_code)
                                                   ->first();

            if ($menuItem_translate) {
                $menuItem_translate->update([
                    'title' => $request->title,
                ]);
            }
        });

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $notification = trans('translate.Menu Item Updated Successfully');
        $notification = array('messege' => $notification, 'alert-type' => 'success');
        return redirect()->route('admin.menus.menu-items.index', $menu)->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $menuItem->delete();

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $notification= trans('translate.Menu Item Deleted Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.menus.menu-items.index', $menu)->with($notification);
    }

    /**
     * Toggle menu item active status.
     */
    public function toggleStatus(MenuItem $menuItem)
    {
        $menuItem->update(['is_active' => !$menuItem->is_active]);

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

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

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return response()->json(['success' => true]);
    }
}
