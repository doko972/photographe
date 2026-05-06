import { showToast } from './toast.js';

export function initUpload() {
    const zone      = document.getElementById('upload-zone');
    const fileInput = document.getElementById('file-input');
    const preview   = document.getElementById('upload-preview');
    const previewImg = document.getElementById('preview-img');
    const removeBtn  = document.getElementById('preview-remove');

    if (!zone || !fileInput) return;

    zone.addEventListener('dragover',  (e) => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', ()  => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) handleFile(file);
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        if (file.size > 10 * 1024 * 1024) {
            showToast('⚠️ La photo dépasse 10 Mo.');
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            preview.classList.add('visible');
            zone.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', () => {
            previewImg.src = '';
            fileInput.value = '';
            preview.classList.remove('visible');
            zone.style.display = '';
        });
    }
}
