@include('partials.image_upload_optimizer')
<script>
    /*
     * Shared profile-picture upload (sidebar pencil + Edit Profile page).
     * Phone photos are often 3-5MB, above the 2MB server limit, so the file is
     * compressed in the browser first. On failure the server's message is shown
     * and the previews go back to the saved image instead of a fake new one.
     */
    window.uploadUserAvatar = window.uploadUserAvatar || async function(input) {
        const file = input && input.files ? input.files[0] : null;
        if (!file) {
            return;
        }

        if ("{{ env('APP_MODE') }}" === 'DEMO') {
            toastr.error('This Is Demo Version. You Can Not Change Anything');
            input.value = '';
            return;
        }

        const previews = ['preview-user-avatar', 'preview-user-avatar-edit-page']
            .map(function(id) { return document.getElementById(id); })
            .filter(Boolean);
        const previousSrc = previews.map(function(img) { return img.src; });

        const localUrl = URL.createObjectURL(file);
        previews.forEach(function(img) { img.src = localUrl; });

        let uploadFile = file;
        try {
            const optimized = await window.optimizeImageFilesForUpload([file]);
            if (optimized && optimized[0]) {
                uploadFile = optimized[0];
            }
        } catch (e) {
            uploadFile = file;
        }

        const formData = new FormData();
        formData.append('_token', "{{ csrf_token() }}");
        formData.append('image', uploadFile, uploadFile.name || 'avatar.jpg');

        $.ajax({
            type: 'POST',
            url: "{{ route('user.upload-user-avatar') }}",
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                toastr.success(response.message);
                if (response.image_url) {
                    const newUrl = response.image_url + (response.image_url.indexOf('?') >= 0 ? '&' : '?') + 'v=' + Date.now();
                    previews.forEach(function(img) { img.src = newUrl; });
                }
            },
            error: function(xhr) {
                previews.forEach(function(img, i) { img.src = previousSrc[i]; });

                const json = xhr && xhr.responseJSON ? xhr.responseJSON : {};
                let msg = (json.errors && json.errors.image && json.errors.image[0]) || json.message;
                if (xhr && xhr.status === 413) {
                    msg = "{{ __('Image is too large. Please choose a smaller image.') }}";
                }
                toastr.error(msg || "{{ __('Image could not be uploaded. Please try again.') }}");
            },
            complete: function() {
                URL.revokeObjectURL(localUrl);
                input.value = '';
            }
        });
    };
</script>
