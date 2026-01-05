
<div class="next-prev-btn">
    <ul>
        @if ($paginator->onFirstPage())
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" class="two">{{ __('translate.Prev') }}</a></li>
        @endif

        @foreach ($elements as $element)
            @if (!is_array($element))
                <li><a href="javascript:;">...</a></li>
            @else
                @if (count($element) < 2)
                @else
                    @foreach ($element as $key => $el)
                        @if ($key == $paginator->currentPage())
                            <li ><a class="active" href="javascript::void()">{{ $key }}</a></li>
                        @else
                            <li><a href="{{ $el }}">{{ $key }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" class="two">{{ __('translate.Next') }}</a></li>
        @endif

    </ul>
</div>
