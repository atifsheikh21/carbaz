<script>
    window.optimizeImageFilesForUpload = window.optimizeImageFilesForUpload || async function(files, options) {
        const settings = Object.assign({
            maxWidth: 1600,
            maxHeight: 1200,
            quality: 0.72,
            maxBytes: 900 * 1024
        }, options || {});

        async function optimizeFile(file) {
            if (!file || !file.type || !file.type.startsWith('image/')) {
                return file;
            }

            if (file.type === 'image/gif' || file.type === 'image/svg+xml') {
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
                    function buildBlob(targetCanvas, quality, attemptsLeft) {
                        targetCanvas.toBlob(function(blob) {
                            if (!blob) {
                                resolve(file);
                                return;
                            }

                            if (blob.size <= settings.maxBytes || attemptsLeft <= 0) {
                                const optimizedName = (file.name || 'image').replace(/\.[^.]+$/, '') + '.jpg';
                                resolve(new File([blob], optimizedName, {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                }));
                                return;
                            }

                            const smallerCanvas = document.createElement('canvas');
                            smallerCanvas.width = Math.max(1, Math.round(targetCanvas.width * 0.85));
                            smallerCanvas.height = Math.max(1, Math.round(targetCanvas.height * 0.85));

                            const smallerContext = smallerCanvas.getContext('2d');
                            if (!smallerContext) {
                                resolve(file);
                                return;
                            }

                            smallerContext.drawImage(targetCanvas, 0, 0, smallerCanvas.width, smallerCanvas.height);
                            buildBlob(smallerCanvas, Math.max(0.58, quality - 0.06), attemptsLeft - 1);
                        }, 'image/jpeg', quality);
                    }

                    buildBlob(canvas, settings.quality, 4);
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
