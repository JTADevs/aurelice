const MAX_EDGE = 2000;
const QUALITY = 0.85;

function canvasToBlob(canvas, type) {
    return new Promise((resolve) => canvas.toBlob(resolve, type, QUALITY));
}

/**
 * Scale a photo down to MAX_EDGE px on the longer side and re-encode it as WebP
 * (JPEG where the browser cannot encode WebP), so large camera photos upload fast.
 * Runs only in the browser, from a file input handler. Returns the original file when conversion fails.
 */
export async function prepareImageForUpload(file) {
    try {
        const bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });
        const scale = Math.min(1, MAX_EDGE / Math.max(bitmap.width, bitmap.height));
        const canvas = document.createElement('canvas');

        canvas.width = Math.round(bitmap.width * scale);
        canvas.height = Math.round(bitmap.height * scale);
        canvas.getContext('2d').drawImage(bitmap, 0, 0, canvas.width, canvas.height);
        bitmap.close();

        let blob = await canvasToBlob(canvas, 'image/webp');

        if (!blob || blob.type !== 'image/webp') {
            blob = await canvasToBlob(canvas, 'image/jpeg');
        }

        if (!blob) {
            return file;
        }

        const extension = blob.type === 'image/webp' ? 'webp' : 'jpg';
        const baseName = file.name.replace(/\.[^.]+$/, '');

        return new File([blob], `${baseName}.${extension}`, { type: blob.type });
    } catch {
        return file;
    }
}
