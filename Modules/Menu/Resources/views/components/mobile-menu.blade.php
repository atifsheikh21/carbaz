@props(['menu'])

@php
    $menuItems = $menu->menuItems()->where('parent_id', null)->with(['children', 'translations'])->orderBy('sort_order')->get();
@endphp

<ul class="nav-links">
    @foreach($menuItems as $menuItem)
        @php
            $hasChildren = $menuItem->children && $menuItem->children->count() > 0;
        @endphp
        
        <li class="{{ $hasChildren ? 'dropdown' : '' }}">
            @if($hasChildren)
                <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}" class="{{ $menuItem->css_class }}">
                    @if($menuItem->icon)
                        <i class="{{ $menuItem->icon }}"></i>
                    @endif
                    {{ $menuItem->translated_title }}
                    <span>
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </a>
                <ul class="d-menu">
                    @foreach($menuItem->children->sortBy('sort_order') as $child)
                        @php
                            $childHasChildren = $child->children && $child->children->count() > 0;
                        @endphp
                        
                        <li class="{{ $childHasChildren ? 'dropdown' : '' }}">
                            @if($childHasChildren)
                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="{{ $child->css_class }}">
                                    @if($child->icon)
                                        <i class="{{ $child->icon }}"></i>
                                    @endif
                                    {{ $child->translated_title }}
                                    <span>
                                        <i class="fa-solid fa-angle-down"></i>
                                    </span>
                                </a>
                                <ul class="d-menu">
                                    @foreach($child->children->sortBy('sort_order') as $grandChild)
                                        <li>
                                            <a href="{{ $grandChild->url }}" target="{{ $grandChild->target }}" class="{{ $grandChild->css_class }}">
                                                @if($grandChild->icon)
                                                    <i class="{{ $grandChild->icon }}"></i>
                                                @endif
                                                {{ $grandChild->translated_title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="{{ $child->css_class }}">
                                    @if($child->icon)
                                        <i class="{{ $child->icon }}"></i>
                                    @endif
                                    {{ $child->translated_title }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <a href="{{ $menuItem->url }}" target="{{ $menuItem->target }}" class="{{ $menuItem->css_class }}">
                    @if($menuItem->icon)
                        <i class="{{ $menuItem->icon }}"></i>
                    @endif
                    {{ $menuItem->translated_title }}
                </a>
            @endif
        </li>
    @endforeach
</ul> 