function initImagePreviewOverlay(previewContainerId, labelId) {
    const previewContainer = document.getElementById(previewContainerId);
    const label = document.getElementById(labelId);
    if (previewContainer && label) {
        let filterDiv = label.querySelector('.preview-filter');
        if (!filterDiv) {
            filterDiv = document.createElement('div');
            filterDiv.className = 'preview-filter absolute left-0 top-0 w-full h-full bg-black/60 pointer-events-none transition-all duration-300 opacity-0';
            label.appendChild(filterDiv);
        }
        const img = label.querySelector('img');
        const svg = label.querySelector('svg');
        const h5 = label.querySelector('h5');
        if (img) {
            img.classList.add('relative', 'z-20', 'block', 'transition-all', 'duration-300', 'blur-none', 'opacity-100');
            img.classList.remove('hidden');
        }
        if (svg) {
            svg.classList.add('absolute', 'left-1/2', 'top-1/2', '-translate-x-1/2', '-translate-y-1/2', 'z-10', 'pointer-events-none', 'transition-all', 'duration-300', 'text-white');
            svg.classList.remove('text-gray-700');
        }
        if (h5) {
            h5.classList.add('absolute', 'left-1/2', 'top-[70%]', '-translate-x-1/2', '-translate-y-1/2', 'z-10', 'pointer-events-none', 'text-white', 'transition-all', 'duration-300');
            h5.classList.remove('text-gray-700');
        }
        label.classList.add('relative');
        previewContainer.classList.add('relative');
        // On hover, bring SVG/h5/filter in front of image and apply transitions
        label.onmouseenter = function () {
            if (svg) {
                svg.classList.remove('z-10');
                svg.classList.add('z-30');
            }
            if (h5) {
                h5.classList.remove('z-10');
                h5.classList.add('z-30');
            }
            if (img) {
                img.classList.remove('z-20', 'opacity-100', 'blur-none');
                img.classList.add('z-10', 'opacity-50', 'blur-md');
            }
            if (filterDiv) {
                filterDiv.classList.remove('opacity-0');
                filterDiv.classList.add('opacity-100');
            }
        };
        label.onmouseleave = function () {
            if (svg) {
                svg.classList.remove('z-30');
                svg.classList.add('z-10');
            }
            if (h5) {
                h5.classList.remove('z-30');
                h5.classList.add('z-10');
            }
            if (img) {
                img.classList.remove('z-10', 'opacity-50', 'blur-md');
                img.classList.add('z-20', 'opacity-100', 'blur-none');
            }
            if (filterDiv) {
                filterDiv.classList.remove('opacity-100');
                filterDiv.classList.add('opacity-0');
            }
        };
    }
}

// Wait until the file input is changed
document.getElementById("gambar").onchange = function () {
    const reader = new FileReader();
    reader.onload = function () {
        // Update the preview image src
        const previewImg = document.querySelector('#gambar_preview img');
        if (previewImg) {
            previewImg.src = reader.result;
        }
        // Optionally set a hidden input for the modified image
        const hiddenInput = document.getElementById("modified_image");
        if (hiddenInput) {
            hiddenInput.value = reader.result;
        }
        // Re-init overlay and hover for new image
        initImagePreviewOverlay('gambar_preview', 'gambar_label');
    };
    reader.readAsDataURL(this.files[0]);
};

document.getElementById("gambar").onchange = function () {
    const reader = new FileReader();
    reader.onload = function () {
        // Update the preview image src
        const previewImg = document.querySelector('#gambar_preview img');
        if (previewImg) {
            previewImg.src = reader.result;
        }
        // Optionally set a hidden input for the modified image
        const hiddenInput = document.getElementById("modified_image");
        if (hiddenInput) {
            hiddenInput.value = reader.result;
        }
        // Re-init overlay and hover for new image
        initImagePreviewOverlay('gambar_preview', 'gambar_label');
    };
    reader.readAsDataURL(this.files[0]);
};
