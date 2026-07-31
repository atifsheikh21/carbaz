@extends('layout')
@section('title')
    <title>Edit: {{ $requestModel->title }}</title>
@endsection

@section('body-content')
<main>
    <section class="forum-shell">
        <div class="container">
            <div class="forum-topbar">
                <a href="{{ route('car-part-requests.index') }}" class="forum-logo">Part Help Forum</a>
                <div style="flex:1"></div>
                <a href="{{ route('car-part-requests.show', $requestModel->id) }}" class="forum-ask" style="background:#6b7280;">Cancel</a>
            </div>

            <div style="max-width:760px;margin:0 auto;">
                <article class="forum-question-card" style="padding:32px;">
                    <h1 style="font-size:22px;margin-bottom:24px;">Edit Post</h1>
                    <form method="POST" action="{{ route('car-part-requests.update', $requestModel->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:14px;margin-bottom:18px;color:#b91c1c;font-size:14px;">
                                <ul style="margin:0;padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div style="margin-bottom:16px;">
                            <label style="display:block;font-weight:700;margin-bottom:6px;">Title</label>
                            <input type="text" name="title" value="{{ old('title', $requestModel->title) }}" class="forum-offer-input" style="min-height:44px;" required>
                        </div>

                        <div style="margin-bottom:16px;">
                            <label style="display:block;font-weight:700;margin-bottom:6px;">Category</label>
                            <select name="category" class="forum-offer-input" style="min-height:44px;" required>
                                <option value="">Select category</option>
                                @foreach(['Engine', 'Electrical', 'Body', 'Radiator', 'Suspension', 'Transmission', 'Interior', 'Exterior', 'Wheels', 'Other'] as $category)
                                    <option value="{{ $category }}" {{ old('category', $requestModel->category) === $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:16px;">
                            <label style="display:block;font-weight:700;margin-bottom:6px;">Description</label>
                            <textarea name="part_description" class="forum-rich-editor" rows="5" required>{{ old('part_description', $requestModel->part_description) }}</textarea>
                        </div>

                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:16px;">
                            <div>
                                <label style="display:block;font-weight:700;margin-bottom:6px;">Car Make</label>
                                <input type="text" name="car_make" value="{{ old('car_make', $requestModel->car_make) }}" class="forum-offer-input">
                            </div>
                            <div>
                                <label style="display:block;font-weight:700;margin-bottom:6px;">Car Model</label>
                                <input type="text" name="car_model" value="{{ old('car_model', $requestModel->car_model) }}" class="forum-offer-input">
                            </div>
                            <div>
                                <label style="display:block;font-weight:700;margin-bottom:6px;">Car Year</label>
                                <input type="text" name="car_year" value="{{ old('car_year', $requestModel->car_year) }}" class="forum-offer-input">
                            </div>
                        </div>

                        <div style="margin-bottom:24px;">
                            <label style="display:block;font-weight:700;margin-bottom:6px;">Additional Notes</label>
                            <textarea name="additional_notes" class="forum-rich-editor" rows="3">{{ old('additional_notes', $requestModel->additional_notes) }}</textarea>
                        </div>

                        <div style="margin-bottom:24px;">
                            <label style="display:block;font-weight:700;margin-bottom:6px;">Image</label>
                            @if($requestModel->image)
                                <div class="forum-edit-image">
                                    <img src="{{ getImageOrPlaceholder($requestModel->image, '480x270') }}" alt="{{ $requestModel->title }}">
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*" class="forum-offer-input">
                            @error('image')
                                <div style="color:#DC2626;font-size:13px;margin-top:5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="forum-ask">Save Changes</button>
                    </form>
                </article>
            </div>
        </div>
    </section>
</main>
@endsection

@push('style_section')
<style>
    .forum-shell{background:#F9FAFB;padding:24px 0 80px;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;font-size:15px;color:#111827}
    .forum-topbar{position:sticky;top:0;z-index:20;display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);margin-bottom:24px}
    .forum-logo{font-weight:800;color:#111827;text-decoration:none;white-space:nowrap}
    .forum-ask{min-height:44px;display:inline-flex;align-items:center;justify-content:center;padding:0 18px;border:0;border-radius:8px;background:#b60304;color:#fff;font-weight:700;text-decoration:none;cursor:pointer}
    .forum-question-card{background:#fff;border:1px solid #E5E7EB;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.08)}
    .forum-rich-editor,.forum-offer-input{width:100%;border:1px solid #E5E7EB;border-radius:8px;padding:12px 14px;background:#fff;box-sizing:border-box;font-size:15px;font-family:inherit}
    .forum-edit-image{margin:0 0 10px;border:1px solid #E5E7EB;border-radius:8px;overflow:hidden;background:#F3F4F6;max-width:420px}
    .forum-edit-image img{display:block;width:100%;height:auto;object-fit:contain;background:#fff}
</style>
@endpush
