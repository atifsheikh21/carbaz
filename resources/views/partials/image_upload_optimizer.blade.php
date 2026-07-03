<script>
    window.optimizeImageFilesForUpload = window.optimizeImageFilesForUpload || async function(files, options) {
        const settings = Object.assign({
            maxWidth: 1905,
            maxHeight: 1080,
            quality: 0.82,
            maxBytes: 2.5 * 1024 * 1024
        }, options || {});

        async function optimizeFile(file) {
            if (!file || !file.type || !file.type.startsWith('image/')) {
                return file;
            }

            if (file.type === 'image/gif' || file.type === 'image/svg+xml') {
                return file;
            }

            if (file.size <= settings.maxBytes) {
                return file;
            }

            return new Promise(function(resolve) {
                const image = new Image();
                const objectUrl = URL.createObjectURL(file);

                image.onload = function() {
                    URL.revokeObjectURL(objectUrl);

                    let width = image.naturalWidth || image.width;
                    let height = image.naturalHeight || image.height;
                    const scale = Math.min(settings.maxWidth / width, settings.maxHeight / height, 1);
                    width = Math.max(1, Math.round(width * scale));
                    height = Math.max(1, Math.round(height * scale));

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;

                    const context = canvas.getContext('2d');
                    if (!context) {
                        resolve(file);
                        return;
                    }

                    context.drawImage(image, 0, 0, width, height);
                    canvas.toBlob(function(blob) {
                        if (!blob) {
                            resolve(file);
                            return;
                        }

                        const optimizedName = (file.name || 'image').replace(/\.[^.]+$/, '') + '.jpg';
                        resolve(new File([blob], optimizedName, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        }));
                    }, 'image/jpeg', settings.quality);
                };

                image.onerror = function() {
                    URL.revokeObjectURL(objectUrl);
                    resolve(file);
                };

                image.src = objectUrl;
            });
        }

        const optimizedFiles = [];
        for (const file of Array.from(files || [])) {
            optimizedFiles.push(await optimizeFile(file));
        }

        return optimizedFiles;
    };
</script>
