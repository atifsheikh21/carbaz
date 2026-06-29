@php
    /**
     * Expected vars:
     * - $images: array of image paths/urls
     * - $idPrefix: unique string per page instance (e.g. 'car', 'carpart')
     * - $__imgUrl: callable that maps a path to a full URL (optional; falls back to asset)
     * - $wishlistType: 'car' or 'car_part' (optional)
     * - $wishlistItemId: the item ID for wishlist (optional)
     * - $isInWishlist: whether item is in wishlist (optional)
     */
    $images = is_array($images ?? null) ? $images : [];
    $idPrefix = (string) ($idPrefix ?? 'dg');
    $wishlistType = $wishlistType ?? null;
    $wishlistItemId = $wishlistItemId ?? null;
    $isInWishlist = $isInWishlist ?? false;

    $__imgUrlLocal = $__imgUrl ?? function ($path) {
        $path = trim((string) $path);
        if ($path === '') return '';
        if (preg_match('~^https?://~i', $path)) return $path;
        if (env('FILESYSTEM_DISK') === 's3') {
            try {
                return \Illuminate\Support\Facades\Storage::disk('s3')->url($path);
            } catch (\Throwable $e) {
            }
        }
        return asset($path);
    };

    $mainId = $idPrefix.'_gallery_main';
    $thumbsId = $idPrefix.'_gallery_thumbs';
    $lbId = $idPrefix.'_lightbox';
    $lbMainId = $idPrefix.'_lightbox_main';
    $lbThumbsId = $idPrefix.'_lightbox_thumbs';
    $lbCounterId = $idPrefix.'_lightbox_counter';
@endphp

<div class="cd-gallery" data-cd-gallery="{{ $idPrefix }}">
    <div class="swiper cd-gallery__main" id="{{ $mainId }}">
        <div class="swiper-wrapper">
            @if(count($images) > 0)
                @foreach ($images as $img)
                    <div class="swiper-slide" role="button" tabindex="0" data-cd-open-lightbox data-cd-index="{{ $loop->index }}">
                        <div class="cd-gallery__main-img">
                            <img src="{{ $__imgUrlLocal($img) }}" alt="img" loading="lazy">
                        </div>
                    </div>
                @endforeach
            @else
                <div class="swiper-slide">
                    <div class="cd-gallery__main-img" style="display:flex;align-items:center;justify-content:center;background:#e8e8e8;height:100%;width:100%;color:#999;font-size:15px;font-weight:600;letter-spacing:0.5px;">Image Coming Soon</div>
                </div>
            @endif
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <button type="button" class="ad-share-btn js-ad-share-btn" data-share-url="{{ url()->current() }}" data-share-title="{{ trim($__env->yieldContent('title')) ?: config('app.name') }}" aria-label="Share ad">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11A2.99 2.99 0 1 0 15 5c0 .24.04.47.09.7L8.04 9.81a3 3 0 1 0 0 4.38l7.12 4.18c-.05.2-.08.41-.08.63a2.92 2.92 0 1 0 2.92-2.92Z"/>
            </svg>
            <span>Share</span>
        </button>

        @if($wishlistType && $wishlistItemId)
            @guest('web')
                <a href="javascript:;" class="ad-wishlist-btn before_auth_wishlist" title="{{ __('translate.Add to Favourite') }}" aria-label="wishlist">
                    <svg width="20" height="18" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke="#e74c3c" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                </a>
            @else
                @if($wishlistType === 'car_part')
                    <a href="{{ route('user.add-car-part-to-wishlist', $wishlistItemId) }}"
                       class="ad-wishlist-btn {{ $isInWishlist ? 'active' : '' }}"
                       title="{{ $isInWishlist ? __('translate.Remove from Favourite') : __('translate.Add to Favourite') }}"
                       aria-label="wishlist">
                        <svg width="20" height="18" viewBox="0 0 18 16" fill="{{ $isInWishlist ? '#e74c3c' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke="#e74c3c" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @elseif($wishlistType === 'car')
                    <a href="{{ route('user.add-to-wishlist', $wishlistItemId) }}"
                       class="ad-wishlist-btn {{ $isInWishlist ? 'active' : '' }}"
                       title="{{ $isInWishlist ? __('translate.Remove from Favourite') : __('translate.Add to Favourite') }}"
                       aria-label="wishlist">
                        <svg width="20" height="18" viewBox="0 0 18 16" fill="{{ $isInWishlist ? '#e74c3c' : 'none' }}" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.61204 2.324L9 2.96329L8.38796 2.324C6.69786 0.558667 3.95767 0.558666 2.26757 2.324C0.577476 4.08933 0.577475 6.95151 2.26757 8.71684L7.77592 14.4704C8.45196 15.1765 9.54804 15.1765 10.2241 14.4704L15.7324 8.71684C17.4225 6.95151 17.4225 4.08934 15.7324 2.324C14.0423 0.558667 11.3021 0.558666 9.61204 2.324Z" stroke="#e74c3c" stroke-width="1.5" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @endif
            @endguest
        @endif
    </div>

    <div class="swiper cd-gallery__thumbs" id="{{ $thumbsId }}">
        <div class="swiper-wrapper">
            @foreach ($images as $img)
                <div class="swiper-slide" role="button" tabindex="0" data-cd-open-lightbox data-cd-index="{{ $loop->index }}">
                    <div class="cd-gallery__thumb-img">
                        <img src="{{ getImageOrPlaceholder($img, '216x148') }}" alt="thumb" loading="lazy">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="cd-lightbox" id="{{ $lbId }}" aria-hidden="true">
    <div class="cd-lightbox__backdrop" data-cd-close></div>
    <div class="cd-lightbox__dialog" role="dialog" aria-modal="true" aria-label="Gallery">
        <div class="cd-lightbox__topbar">
            <div class="cd-lightbox__counter" id="{{ $lbCounterId }}">1 / 1</div>
            <button class="cd-lightbox__close" type="button" aria-label="Close" data-cd-close>×</button>
        </div>
        <div class="cd-lightbox__main">
            <div class="swiper cd-lightbox__swiper" id="{{ $lbMainId }}">
                <div class="swiper-wrapper">
                    @foreach ($images as $img)
                        <div class="swiper-slide" data-cd-type="image">
                            <img src="{{ getImageOrPlaceholder($img, '1905x1080') }}" alt="img" loading="eager" decoding="async" fetchpriority="high">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        <div class="cd-lightbox__thumbs">
            <div class="swiper" id="{{ $lbThumbsId }}">
                <div class="swiper-wrapper">
                    @foreach ($images as $img)
                        <div class="swiper-slide">
                            <img src="{{ getImageOrPlaceholder($img, '216x148') }}" alt="thumb">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('style_section')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
        .cd-gallery{width:100%;max-width:100%;min-width:0;}
        .cd-gallery__main{width:100%;height:360px;border-radius:16px;border:4px solid #2f2f2f;background:#f4f4f4;overflow:hidden;box-sizing:border-box;}
        .cd-gallery__main{position:relative;}
        .cd-gallery__main .swiper-slide{height:100%;}
        .cd-gallery__main-img{height:100%;width:100%;display:flex;align-items:center;justify-content:center;}
        .cd-gallery__main-img img{width:100%;height:100%;object-fit:cover;display:block;background:transparent;border:0;border-radius:0;}
        .cd-gallery__thumbs{margin-top:18px;}
        .cd-gallery__thumbs .swiper-slide{opacity:.75;cursor:pointer;}
        [data-cd-open-lightbox]{cursor:pointer;-webkit-tap-highlight-color:transparent;touch-action:manipulation;}
        .cd-gallery__thumbs .swiper-slide-thumb-active{opacity:1;}
        .cd-gallery__thumb-img img{width:100%;height:80px;object-fit:cover;border-radius:10px;background:#f4f4f4;}

        .cd-gallery .swiper-button-prev,
        .cd-gallery .swiper-button-next{color:#fff;}
        .cd-gallery .swiper-button-prev:after,
        .cd-gallery .swiper-button-next:after{font-size:22px;font-weight:900;}
        .ad-share-btn{
                position: absolute;
    right: 10px;
    bottom: 18px;
    z-index: 10;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    height: 40px;
    padding: 0 18px;
    border: 0;
    border-radius: 6px;
    background: #ffffff00;
    color: #ffffff;
    font-size: 14px;
    font-weight: 500;
    line-height: 1;
    box-shadow: 0 6px 18px rgba(0, 0, 0, .14);
        }
        .ad-share-btn:hover{
            background: #ffffff00;
            color: #000;
        }
        .ad-share-btn.is-copied{
            background: #fff;
        }
        .ad-share-btn svg{
            width: 18px;
            height: 18px;
            fill: #ffffff;
            flex: 0 0 auto;
        }

        .ad-wishlist-btn{
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            color: #e74c3c;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .ad-wishlist-btn:hover{
            background: #fff;
            transform: scale(1.05);
        }
        .ad-wishlist-btn svg{
            width: 20px;
            height: 18px;
            flex: 0 0 auto;
        }
        .ad-wishlist-btn.active svg{
            fill: #e74c3c;
        }

        .cd-lightbox{position:fixed !important;top:0 !important;right:0 !important;bottom:0 !important;left:0 !important;z-index:99999;overflow:hidden;visibility:hidden;opacity:0;pointer-events:none;transition:opacity .2s ease;}
        .cd-lightbox.is-open{visibility:visible;opacity:1;pointer-events:auto;}
        .cd-lightbox__backdrop{position:absolute;inset:0;background:rgba(0,0,0,.86);backdrop-filter:blur(6px);z-index:0;}
        .cd-lightbox__dialog{position:absolute;inset:0;z-index:1;display:flex;flex-direction:column;}
        .cd-lightbox__topbar{flex:0 0 auto;display:flex;align-items:center;gap:12px;padding:12px 16px;color:#fff;}
        .cd-lightbox__counter{margin-right:auto;font-size:14px;font-weight:700;letter-spacing:.04em;}
        .cd-lightbox__close{width:40px;height:40px;border:0;border-radius:12px;background:rgba(255,255,255,.12);color:#fff;font-size:24px;line-height:1;cursor:pointer;}
        .cd-lightbox__main{flex:1;min-height:0;overflow:hidden;padding:0 16px;}
        .cd-lightbox__main{min-height:min(70vh,640px);}
        .cd-lightbox__swiper{width:100%;max-width:1100px;height:100%;margin:0 auto;}
        .cd-lightbox__swiper{min-height:min(70vh,640px);}
        .cd-lightbox__swiper .swiper-wrapper{height:100%;}
        .cd-lightbox__swiper .swiper-slide{display:flex;align-items:center;justify-content:center;overflow:hidden;height:100%;}
        .cd-lightbox__swiper .swiper-slide img{display:block;width:100%;height:100%;object-fit:contain;-webkit-transform:none;transform:none;will-change:auto;-webkit-backface-visibility:hidden;backface-visibility:hidden;}
        .cd-lightbox__thumbs{flex:0 0 auto;padding:12px 16px 18px;}
        .cd-lightbox__thumbs .swiper{width:min(1100px,100%);margin:0 auto;}
        .cd-lightbox__thumbs .swiper-slide{opacity:.55;cursor:pointer;}
        .cd-lightbox__thumbs .swiper-slide-thumb-active{opacity:1;}
        .cd-lightbox__thumbs img{width:100%;height:72px;object-fit:cover;border-radius:10px;border:2px solid rgba(255,255,255,.18);background:#111;}
        .cd-lightbox .swiper-button-next,
        .cd-lightbox .swiper-button-prev{color:#fff;}
        .cd-lightbox .swiper-button-next::after,
        .cd-lightbox .swiper-button-prev::after{font-size:22px;font-weight:900;}

        /* iOS WebKit: disable backdrop blur and relax overflow to avoid compositing blank */
        @supports (-webkit-touch-callout: none){
            .cd-lightbox__backdrop{backdrop-filter:none;}
            .cd-lightbox{overflow:visible;}
            .cd-lightbox__main{overflow:visible;}
            .cd-lightbox__swiper .swiper-slide{overflow:visible;}
        }

        @media (max-width: 991.98px){
            .cd-gallery__main{height:auto;}
        }
        @media (max-width: 767.98px){
            .cd-lightbox__main{padding:0 10px;min-height:min(60vh,420px);}
            .cd-lightbox__swiper{min-height:min(60vh,420px);}
            .cd-lightbox__thumbs img{height:56px;}
        }
    </style>
@endpush

@push('js_section')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        (function(){
            if (window.__adShareHandlerBound) return;
            window.__adShareHandlerBound = true;
            document.addEventListener('click', function(e){
                var btn = e.target && e.target.closest ? e.target.closest('.js-ad-share-btn') : null;
                if (!btn) return;
                e.preventDefault();
                e.stopPropagation();
                var shareUrl = btn.getAttribute('data-share-url') || window.location.href;
                var shareTitle = btn.getAttribute('data-share-title') || document.title || 'Ad';
                function copied(){
                    var oldText = btn.textContent;
                    btn.classList.add('is-copied');
                    btn.textContent = 'Copied';
                    window.setTimeout(function(){
                        btn.classList.remove('is-copied');
                        btn.textContent = oldText || 'Share';
                    }, 1500);
                }
                if (navigator.share) {
                    navigator.share({ title: shareTitle, url: shareUrl }).catch(function(){});
                    return;
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(shareUrl).then(copied).catch(function(){ window.prompt('Copy this link', shareUrl); });
                    return;
                }
                window.prompt('Copy this link', shareUrl);
            }, true);
        })();

        (function(){
            const galleryEl = document.querySelector('[data-cd-gallery="{{ $idPrefix }}"]');
            if (!galleryEl || typeof Swiper === 'undefined') return;

            const mainSel = '#{{ $mainId }}';
            const thumbsSel = '#{{ $thumbsId }}';

            function swiperOpenLightbox(swiper, event){
                var slide = event && event.target ? event.target.closest('[data-cd-open-lightbox]') : null;
                if (!slide) return;
                var idx = parseInt(slide.getAttribute('data-cd-index') || '0', 10);
                openAt(Number.isFinite(idx) ? idx : 0);
            }

            const thumbs = new Swiper(thumbsSel, {
                slidesPerView: 4,
                spaceBetween: 12,
                watchSlidesProgress: true,
                breakpoints: {
                    0: { slidesPerView: 3 },
                    768: { slidesPerView: 4 },
                    992: { slidesPerView: 5 }
                },
                on: { click: swiperOpenLightbox }
            });

            const main = new Swiper(mainSel, {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                navigation: {
                    nextEl: mainSel + ' .swiper-button-next',
                    prevEl: mainSel + ' .swiper-button-prev'
                },
                thumbs: { swiper: thumbs },
                on: { click: swiperOpenLightbox }
            });

            // Lightbox
            const modal = document.getElementById('{{ $lbId }}');
            if (!modal) return;

            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            const counterEl = document.getElementById('{{ $lbCounterId }}');
            const closeEls = modal.querySelectorAll('[data-cd-close]');
            const openEls = galleryEl.querySelectorAll('[data-cd-open-lightbox]');

            let lbThumbs = null;
            let lbMain = null;
            let lastFocusedEl = null;

            function setBodyLock(locked){
                try {
                    var isIOS = /iP(ad|hone|od)/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
                    if (isIOS) return;
                    document.body.style.overflow = locked ? 'hidden' : '';
                } catch(e){}
            }

            function updateCounter(){
                if (!lbMain || !counterEl) return;
                counterEl.textContent = (lbMain.realIndex + 1) + ' / ' + lbMain.slides.length;
            }

            const __isIOS = /iP(ad|hone|od)/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            function repaintActiveImage(){
                if (!__isIOS || !lbMain) return;
                try {
                    const slide = lbMain.slides[lbMain.activeIndex];
                    if (!slide) return;
                    const img = slide.querySelector('img');
                    if (!img) return;
                    const d = img.style.display;
                    img.style.display = 'none';
                    void img.offsetHeight;
                    img.style.display = d || '';
                } catch(e){}
            }

            function calcMainHeight(){
                var topbar = modal.querySelector('.cd-lightbox__topbar');
                var thumbs = modal.querySelector('.cd-lightbox__thumbs');
                var mainEl = modal.querySelector('.cd-lightbox__main');
                var swiperEl = mainEl ? mainEl.querySelector('.cd-lightbox__swiper') : null;
                if (!mainEl) return 0;
                var vh = window.innerHeight || document.documentElement.clientHeight;
                var topH = topbar ? topbar.offsetHeight : 0;
                var thumbH = thumbs ? thumbs.offsetHeight : 0;
                var available = vh - topH - thumbH;
                if (available < 120) available = 120;
                mainEl.style.height = available + 'px';
                if (swiperEl) swiperEl.style.height = available + 'px';
                // Force synchronous reflow so Swiper sees the real dimensions
                void mainEl.offsetHeight;
                return available;
            }

            function initOrUpdateSwipers(index){
                if (!lbThumbs) {
                    lbThumbs = new Swiper('#{{ $lbThumbsId }}', {
                        slidesPerView: 5,
                        spaceBetween: 10,
                        watchSlidesProgress: true,
                        observer: true,
                        observeParents: true,
                        breakpoints: {
                            0: { slidesPerView: 4 },
                            768: { slidesPerView: 6 }
                        }
                    });
                }

                if (!lbMain) {
                    lbMain = new Swiper('#{{ $lbMainId }}', {
                        initialSlide: index || 0,
                        loop: false,
                        keyboard: { enabled: true },
                        observer: true,
                        observeParents: true,
                        navigation: {
                            nextEl: '#{{ $lbMainId }} .swiper-button-next',
                            prevEl: '#{{ $lbMainId }} .swiper-button-prev'
                        },
                        thumbs: { swiper: lbThumbs },
                        on: {
                            slideChange: function(){ updateCounter(); repaintActiveImage(); },
                            afterInit: function(){ updateCounter(); repaintActiveImage(); }
                        }
                    });
                } else {
                    lbMain.slideTo(index || 0, 0);
                    repaintActiveImage();
                }
                updateCounter();
            }

            function openAt(index){
                lastFocusedEl = document.activeElement;
                if (lastFocusedEl && typeof lastFocusedEl.blur === 'function') lastFocusedEl.blur();

                // Recalc height (handles orientation/viewport changes)
                calcMainHeight();

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                setBodyLock(true);

                // Make sure Swipers exist (pre-init may have failed on some pages)
                initOrUpdateSwipers(index);

                // After modal becomes visible, recalc and update Swipers
                requestAnimationFrame(function(){
                    calcMainHeight();
                    try {
                        if (lbThumbs) lbThumbs.update();
                        if (lbMain) {
                            lbMain.update();
                            lbMain.slideTo(index || 0, 0);
                        }
                        updateCounter();
                        try {
                            if (__isIOS && lbMain) {
                                const slide = lbMain.slides[lbMain.activeIndex];
                                const img = slide ? slide.querySelector('img') : null;
                                if (img && img.decode) {
                                    img.decode().catch(function(){}).finally(repaintActiveImage);
                                } else {
                                    repaintActiveImage();
                                }
                            } else {
                                repaintActiveImage();
                            }
                        } catch(e){}
                    } catch(e){}

                    setTimeout(function(){
                        calcMainHeight();
                        try {
                            if (lbThumbs) lbThumbs.update();
                            if (lbMain) {
                                lbMain.update();
                                lbMain.slideTo(index || 0, 0);
                            }
                            try {
                                if (__isIOS && lbMain) {
                                    const slide = lbMain.slides[lbMain.activeIndex];
                                    const img = slide ? slide.querySelector('img') : null;
                                    if (img && img.decode) {
                                        img.decode().catch(function(){}).finally(repaintActiveImage);
                                    } else {
                                        repaintActiveImage();
                                    }
                                } else {
                                    repaintActiveImage();
                                }
                            } catch(e){}
                        } catch(e){}
                    }, 150);
                });
            }

            // Move modal to body and pre-init Swipers as soon as possible
            // The lightbox is visibility:hidden (not display:none) so it has dimensions
            function preInit(){
                if (modal.parentElement !== document.body) document.body.appendChild(modal);
                calcMainHeight();
                initOrUpdateSwipers(0);
                // Re-run after a delay to ensure correct dimensions
                setTimeout(function(){
                    calcMainHeight();
                    try {
                        if (lbThumbs) lbThumbs.update();
                        if (lbMain) lbMain.update();
                    } catch(e){}
                }, 200);
            }
            if (document.readyState === 'complete') {
                preInit();
            } else {
                window.addEventListener('load', preInit);
            }

            window.addEventListener('resize', function(){
                if (!modal.classList.contains('is-open')) return;
                calcMainHeight();
                try {
                    if (lbThumbs) lbThumbs.update();
                    if (lbMain) lbMain.update();
                } catch(e){}
            });

            function close(){
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                setBodyLock(false);
                // zoom removed for iOS compatibility

                // Swiper main/thumbs sometimes need a refresh after scrollbars/body lock changes
                window.setTimeout(function(){
                    try {
                        thumbs.update();
                        main.update();
                    } catch (e) {}
                }, 0);

                if (lastFocusedEl && document.contains(lastFocusedEl) && typeof lastFocusedEl.focus === 'function') {
                    lastFocusedEl.focus();
                }
            }

            // Expose so mobile sections outside the gallery container can open the lightbox
            galleryEl.__cdOpenLightbox = openAt;

            // Fallback click handler for any non-Swiper areas
            galleryEl.addEventListener('click', function(e){
                const t = e.target && (e.target.closest ? e.target.closest('[data-cd-open-lightbox]') : null);
                if (!t || !galleryEl.contains(t)) return;
                e.preventDefault();
                const idx = parseInt(t.getAttribute('data-cd-index') || '0', 10);
                openAt(Number.isFinite(idx) ? idx : 0);
            });

            galleryEl.addEventListener('keydown', function(e){
                if (e.key !== 'Enter' && e.key !== ' ') return;
                const t = e.target && (e.target.closest ? e.target.closest('[data-cd-open-lightbox]') : null);
                if (!t || !galleryEl.contains(t)) return;
                e.preventDefault();
                const idx = parseInt(t.getAttribute('data-cd-index') || '0', 10);
                openAt(Number.isFinite(idx) ? idx : 0);
            });

            closeEls.forEach((el) => el.addEventListener('click', close));
            document.addEventListener('keydown', (e) => {
                if (!modal.classList.contains('is-open')) return;
                if (e.key === 'Escape') close();
            });
        })();
    </script>
@endpush
