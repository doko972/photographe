import { showToast } from './toast.js';

export function initGallery() {
    initLikes();
    initSortButtons();
}

function initLikes() {
    document.querySelectorAll('.heart-btn[data-photo-id]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();

            const photoId = btn.dataset.photoId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            try {
                const res = await fetch(`/photos/${photoId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                if (res.status === 401) {
                    showToast('🔒 Connectez-vous pour aimer une photo.');
                    return;
                }

                const data = await res.json();
                const icon  = btn.querySelector('.heart-icon');
                const count = btn.querySelector('.heart-count');

                btn.classList.toggle('liked', data.liked);
                icon.textContent = data.liked ? '❤️' : '🤍';
                count.textContent = data.count;

                if (data.liked) {
                    btn.classList.add('pop');
                    btn.addEventListener('animationend', () => btn.classList.remove('pop'), { once: true });
                }
            } catch {
                showToast('❌ Une erreur est survenue.');
            }
        });
    });
}

function initSortButtons() {
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', btn.dataset.sort);
            window.location.href = url.toString();
        });
    });
}
