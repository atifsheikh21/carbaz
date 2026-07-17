@extends('layout')
@section('title')
    <title>{{ __('translate.Ask a Question') }} — Part Help Forum</title>
@endsection

@section('body-content')
<main>
    <section class="forum-shell">
        <div class="container">

            {{-- Topbar --}}
            <div class="forum-topbar" id="top">
                <a href="{{ route('car-part-requests.index') }}" class="forum-logo">Part Help Forum</a>
                <form action="{{ route('car-part-requests.index') }}" method="GET" class="forum-search">
                    <input type="text" name="q" placeholder="Search questions, parts, models...">
                </form>
                <div class="forum-avatar">@if(auth('web')->user()?->image)<img src="{{ getImageOrPlaceholder(auth('web')->user()->image,'40x40') }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr(auth('web')->user()?->name ?? 'G', 0, 1)) }}@endif</div>
            </div>

            {{-- Breadcrumb --}}
            <nav class="forum-breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('car-part-requests.index') }}">Part Help Forum</a>
                <span>/</span>
                <span>Ask a Question</span>
            </nav>

            {{-- Composer grid --}}
            <div class="forum-create-grid">
                <div class="forum-create-main">
                    <div class="forum-composer-card">
                        <div class="forum-composer-head">
                            <div class="forum-composer-avatar">@if(auth('web')->user()?->image)<img src="{{ getImageOrPlaceholder(auth('web')->user()->image,'48x48') }}" alt="" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">@else{{ strtoupper(substr(auth('web')->user()?->name ?? 'G', 0, 1)) }}@endif</div>
                            <div>
                                <strong>{{ auth('web')->user()?->name }}</strong>
                                <small>Asking publicly on Part Help Forum</small>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('car-part-requests.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf

                            {{-- Title --}}
                            <div class="forum-field">
                                <label class="forum-label">Question / Title <span>*</span></label>
                                <input type="text" name="title" class="forum-input @error('title') is-invalid @enderror"
                                    placeholder="e.g. Where can I find a Honda Civic 2020 radiator?"
                                    value="{{ old('title') }}">
                                @error('title')<div class="forum-error">{{ $message }}</div>@enderror
                            </div>

                            {{-- Category --}}
                            <div class="forum-field">
                                <label class="forum-label">Category <span>*</span></label>
                                <select name="category" class="forum-input @error('category') is-invalid @enderror">
                                    <option value="">Select category</option>
                                    @foreach(($categories ?? []) as $category)
                                        <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                                @error('category')<div class="forum-error">{{ $message }}</div>@enderror
                            </div>

                            {{-- Description --}}
                            <div class="forum-field">
                                <label class="forum-label">Describe what you need <span>*</span></label>
                                <div class="forum-editor-toolbar">
                                    <button type="button" title="Bold"><b>B</b></button>
                                    <button type="button" title="Italic"><i>I</i></button>
                                    <button type="button" title="Link">Link</button>
                                    <button type="button" title="Code">Code</button>
                                </div>
                                <textarea name="part_description" class="forum-textarea @error('part_description') is-invalid @enderror"
                                    rows="7" placeholder="Provide as much detail as possible — year, condition, location, urgency...">{{ old('part_description') }}</textarea>
                                @error('part_description')<div class="forum-error">{{ $message }}</div>@enderror
                            </div>

                            {{-- Car details row --}}
                            <div class="forum-field">
                                <label class="forum-label">Car Details <small>(optional — helps helpers find the right part)</small></label>
                                <div class="forum-car-row">
                                    <div>
                                        <input type="text" name="car_make" class="forum-input" placeholder="Make e.g. Honda" value="{{ old('car_make') }}">
                                    </div>
                                    <div>
                                        <input type="text" name="car_model" class="forum-input" placeholder="Model e.g. Civic" value="{{ old('car_model') }}">
                                    </div>
                                    <div>
                                        <input type="text" name="car_year" class="forum-input" placeholder="Year e.g. 2020" value="{{ old('car_year') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Additional notes --}}
                            <div class="forum-field">
                                <label class="forum-label">Additional Notes <small>(optional)</small></label>
                                <textarea name="additional_notes" class="forum-textarea" rows="3"
                                    placeholder="Budget, preferred condition, delivery preference...">{{ old('additional_notes') }}</textarea>
                            </div>

                            {{-- Image upload --}}
                            <div class="forum-field">
                                <label class="forum-label">Attach an Image <small>(optional, max 4MB)</small></label>
                                <label class="forum-upload-zone" id="upload-zone">
                                    <input type="file" name="image" accept="image/*" id="forum-image-input" style="display:none">
                                    <span id="upload-label">📎 Click to attach an image</span>
                                </label>
                                @error('image')<div class="forum-error">{{ $message }}</div>@enderror
                            </div>

                            {{-- Footer actions --}}
                            <div class="forum-composer-footer">
                                <a href="{{ route('car-part-requests.index') }}" class="forum-btn-cancel">Cancel</a>
                                <button type="submit" class="forum-btn-submit">Post Question</button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- Sidebar tips --}}
                <aside class="forum-create-side">
                    <div class="forum-widget">
                        <h3>Tips for a great question</h3>
                        <ul class="forum-tips">
                            <li>Be specific about the part you need</li>
                            <li>Include car make, model and year</li>
                            <li>Mention the condition you need (new/used)</li>
                            <li>Add a photo if you have one</li>
                            <li>Check if a similar question already exists</li>
                        </ul>
                    </div>
                    <div class="forum-widget">
                        <h3>Similar recent requests</h3>
                        @php
                            $recentRequests = \App\Models\CarPartRequest::latest()->limit(5)->get();
                        @endphp
                        @forelse($recentRequests as $recent)
                            <a href="{{ route('car-part-requests.show', $recent->id) }}">{{ $recent->title }}</a>
                        @empty
                            <p>No recent requests yet.</p>
                        @endforelse
                    </div>
                </aside>
            </div>

        </div>
    </section>
</main>
@endsection

@push('style_section')
<style>
    .forum-shell{background:#F9FAFB;padding:24px 0 80px;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;font-size:15px;color:#111827}
    .forum-topbar{position:sticky;top:0;z-index:20;display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);margin-bottom:16px}
    .forum-logo{font-weight:800;color:#111827;text-decoration:none;white-space:nowrap}
    .forum-search{flex:1}.forum-search input{width:100%;min-height:40px;border:1px solid #E5E7EB;border-radius:999px;padding:0 16px;background:#F9FAFB;font-size:14px}
    .forum-avatar{width:40px;height:40px;border-radius:50%;background:#fde8e8;color:#b60304;display:inline-flex;align-items:center;justify-content:center;font-weight:800;flex:0 0 auto}
    .forum-breadcrumb{display:flex;align-items:center;gap:8px;font-size:13px;color:#6B7280;margin-bottom:20px}
    .forum-breadcrumb a{color:#b60304;text-decoration:none}.forum-breadcrumb a:hover{text-decoration:underline}
    .forum-create-grid{display:grid;grid-template-columns:minmax(0,1fr) 280px;gap:22px;align-items:start}
    .forum-create-side{position:sticky;top:92px}
    .forum-composer-card{background:#fff;border:1px solid #E5E7EB;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:28px}
    .forum-composer-head{display:flex;align-items:center;gap:14px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid #F3F4F6}
    .forum-composer-avatar{width:48px;height:48px;border-radius:50%;background:#fde8e8;color:#b60304;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;flex:0 0 auto}
    .forum-composer-head strong{display:block;font-weight:700;font-size:15px}
    .forum-composer-head small{color:#6B7280;font-size:13px}
    .forum-field{margin-bottom:20px}
    .forum-label{display:block;font-weight:600;font-size:14px;color:#374151;margin-bottom:8px}
    .forum-label span{color:#DC2626}
    .forum-label small{font-weight:400;color:#9CA3AF;margin-left:4px}
    .forum-input{width:100%;min-height:44px;border:1px solid #E5E7EB;border-radius:8px;padding:0 14px;font-size:15px;color:#111827;background:#fff;transition:border-color .15s}
    .forum-input:focus{outline:none;border-color:#b60304;box-shadow:0 0 0 3px rgba(182,3,4,.1)}
    .forum-input.is-invalid{border-color:#DC2626}
    .forum-editor-toolbar{display:flex;gap:6px;margin-bottom:6px}
    .forum-editor-toolbar button{min-height:32px;padding:0 12px;border:1px solid #E5E7EB;border-radius:6px;background:#F9FAFB;font-size:13px;cursor:pointer;color:#374151}
    .forum-editor-toolbar button:hover{background:#fff1f1;border-color:#b60304;color:#b60304}
    .forum-textarea{width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:12px 14px;font-size:15px;color:#111827;background:#fff;resize:vertical;transition:border-color .15s;font-family:inherit}
    .forum-textarea:focus{outline:none;border-color:#b60304;box-shadow:0 0 0 3px rgba(182,3,4,.1)}
    .forum-textarea.is-invalid{border-color:#DC2626}
    .forum-car-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px}
    .forum-upload-zone{display:flex;align-items:center;justify-content:center;min-height:80px;border:2px dashed #D1D5DB;border-radius:8px;cursor:pointer;color:#6B7280;font-size:14px;background:#F9FAFB;transition:border-color .15s}
    .forum-upload-zone:hover{border-color:#b60304;color:#b60304;background:#fff1f1}
    .forum-error{color:#DC2626;font-size:13px;margin-top:5px}
    .forum-composer-footer{display:flex;justify-content:flex-end;align-items:center;gap:12px;margin-top:28px;padding-top:20px;border-top:1px solid #F3F4F6}
    .forum-btn-cancel{min-height:44px;padding:0 20px;display:inline-flex;align-items:center;border:1px solid #E5E7EB;border-radius:8px;background:#fff;color:#374151;font-weight:600;text-decoration:none;font-size:15px}
    .forum-btn-cancel:hover{background:#F9FAFB;color:#111827}
    .forum-btn-submit{min-height:44px;padding:0 28px;display:inline-flex;align-items:center;border:0;border-radius:8px;background:#b60304;color:#fff;font-weight:700;font-size:15px;cursor:pointer;transition:background .15s}
    .forum-btn-submit:hover{background:#8b0202}
    .forum-widget{background:#fff;border:1px solid #E5E7EB;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.08);padding:18px;margin-bottom:18px}
    .forum-widget h3{font-size:15px;font-weight:800;margin:0 0 14px;color:#111827}
    .forum-widget a{min-height:40px;display:flex;align-items:center;color:#374151;text-decoration:none;border-radius:6px;padding:0 8px;font-size:14px}
    .forum-widget a:hover{background:#fff1f1;color:#b60304}
    .forum-tips{margin:0;padding-left:18px;color:#4B5563;font-size:14px;line-height:2}
    @media(max-width:900px){.forum-create-grid{grid-template-columns:1fr}.forum-create-side{position:static}}
    @media(max-width:600px){.forum-car-row{grid-template-columns:1fr}.forum-topbar{flex-wrap:wrap}.forum-search{order:3;flex:0 0 100%}}
</style>
@endpush

@push('js_section')
<script>
    document.getElementById('forum-image-input').addEventListener('change', function () {
        const label = document.getElementById('upload-label');
        label.textContent = this.files[0] ? '📎 ' + this.files[0].name : '📎 Click to attach an image';
    });
    document.getElementById('upload-zone').addEventListener('click', function () {
        document.getElementById('forum-image-input').click();
    });
</script>
@endpush
