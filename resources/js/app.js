import './bootstrap.js';

import { initHeaderScroll } from './components/header-scroll.js';
import { initBurger }       from './components/burger.js';
import { initCarousel }     from './components/carousel.js';
import { initUpload }       from './components/upload.js';
import { initGallery }      from './components/gallery.js';
import { initLightbox }     from './components/lightbox.js';
import { initVote }         from './components/vote.js';
import { showToast }        from './components/toast.js';

document.addEventListener('DOMContentLoaded', () => {
    initHeaderScroll();
    initBurger();
    initCarousel();
    initUpload();
    initGallery();
    initLightbox();
    initVote();

    // Afficher les flash messages Laravel comme toast
    const flashSuccess = document.querySelector('[data-flash-success]');
    if (flashSuccess) showToast('✅ ' + flashSuccess.dataset.flashSuccess);

    const flashError = document.querySelector('[data-flash-error]');
    if (flashError) showToast('❌ ' + flashError.dataset.flashError);
});
