/* ============================================================
   SCRIPT.JS — Florent & Aurélie 40 ans Viking
   - Menu burger
   - Carousel hero
   - Header scroll
   - Upload preview (poster.html)
   - Galerie favoris localStorage (galerie.html)
   - Lightbox (galerie.html)
   - Toast notifications
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  /* ── DÉTECTION DE LA PAGE COURANTE ──────────────────────── */
  const page = document.body.dataset.page;

  /* ── 1. HEADER — SCROLL SHADOW ───────────────────────────── */
  const siteHeader = document.getElementById('site-header');
  if (siteHeader) {
    const onScroll = () => {
      siteHeader.classList.toggle('scrolled', window.scrollY > 20);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ── 2. MENU BURGER ──────────────────────────────────────── */
  const burger    = document.getElementById('burger');
  const navMobile = document.getElementById('nav-mobile');

  if (burger && navMobile) {

    burger.addEventListener('click', () => {
      const isOpen = burger.classList.toggle('open');
      navMobile.classList.toggle('open', isOpen);
      burger.setAttribute('aria-expanded', isOpen);
      navMobile.setAttribute('aria-hidden', !isOpen);
    });

    /* Fermer le menu mobile quand on clique sur un lien */
    navMobile.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        burger.classList.remove('open');
        navMobile.classList.remove('open');
        burger.setAttribute('aria-expanded', 'false');
        navMobile.setAttribute('aria-hidden', 'true');
      });
    });

    /* Fermer si on clique en dehors */
    document.addEventListener('click', (e) => {
      if (!siteHeader.contains(e.target)) {
        burger.classList.remove('open');
        navMobile.classList.remove('open');
        burger.setAttribute('aria-expanded', 'false');
        navMobile.setAttribute('aria-hidden', 'true');
      }
    });
  }

  /* ── 3. CAROUSEL HERO ────────────────────────────────────── */
  const track     = document.getElementById('carousel-track');
  const prevBtn   = document.getElementById('carousel-prev');
  const nextBtn   = document.getElementById('carousel-next');
  const dots      = document.querySelectorAll('.dot');

  if (track && prevBtn && nextBtn) {
    let current      = 0;
    const total      = document.querySelectorAll('.carousel-slide').length;
    let autoInterval = null;

    const goTo = (index) => {
      current = (index + total) % total;
      track.style.transform = `translateX(-${current * 100}%)`;
      dots.forEach((d, i) => {
        d.classList.toggle('active', i === current);
        d.setAttribute('aria-pressed', i === current);
      });
    };

    prevBtn.addEventListener('click', () => { goTo(current - 1); resetAuto(); });
    nextBtn.addEventListener('click', () => { goTo(current + 1); resetAuto(); });

    dots.forEach(dot => {
      dot.addEventListener('click', () => {
        goTo(parseInt(dot.dataset.index));
        resetAuto();
      });
    });

    /* Auto-play toutes les 4 secondes */
    const startAuto  = () => { autoInterval = setInterval(() => goTo(current + 1), 4000); };
    const resetAuto  = () => { clearInterval(autoInterval); startAuto(); };

    startAuto();

    /* Pause au survol */
    const carouselEl = document.getElementById('carousel');
    if (carouselEl) {
      carouselEl.addEventListener('mouseenter', () => clearInterval(autoInterval));
      carouselEl.addEventListener('mouseleave', startAuto);
    }

    /* Swipe tactile */
    let touchStartX = 0;
    track.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend', (e) => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 40) {
        diff > 0 ? goTo(current + 1) : goTo(current - 1);
        resetAuto();
      }
    });
  }

  /* ── 4. UPLOAD — PAGE POSTER.HTML ───────────────────────── */
  const uploadZone    = document.getElementById('upload-zone');
  const fileInput     = document.getElementById('file-input');
  const uploadPreview = document.getElementById('upload-preview');
  const previewImg    = document.getElementById('preview-img');
  const removeBtn     = document.getElementById('preview-remove');
  const uploadForm    = document.getElementById('upload-form');
  const submitBtn     = document.getElementById('submit-btn');

  if (uploadZone && fileInput) {

    /* Drag & drop visuel */
    uploadZone.addEventListener('dragover',  (e) => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
    uploadZone.addEventListener('dragleave', ()  => uploadZone.classList.remove('drag-over'));
    uploadZone.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadZone.classList.remove('drag-over');
      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) handleFile(file);
    });

    fileInput.addEventListener('change', () => {
      const file = fileInput.files[0];
      if (file) handleFile(file);
    });

    function handleFile(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        uploadPreview.classList.add('visible');
        uploadZone.style.display = 'none';
      };
      reader.readAsDataURL(file);
    }

    if (removeBtn) {
      removeBtn.addEventListener('click', () => {
        previewImg.src = '';
        fileInput.value = '';
        uploadPreview.classList.remove('visible');
        uploadZone.style.display = '';
      });
    }

    /* Soumission du formulaire (maquette → localStorage) */
    if (uploadForm) {
      uploadForm.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!previewImg.src || previewImg.src === window.location.href) {
          showToast('⚔️ Veuillez sélectionner une photo !', 'warning');
          return;
        }

        const name     = document.getElementById('input-name').value.trim()     || 'Anonyme';
        const pseudo   = document.getElementById('input-pseudo').value.trim()   || 'Viking mystérieux';
        const caption  = document.getElementById('input-caption').value.trim()  || '';

        /* Récupérer les photos existantes */
        const photos = JSON.parse(localStorage.getItem('viking_photos') || '[]');

        const newPhoto = {
          id:       Date.now(),
          src:      previewImg.src,
          name,
          pseudo,
          caption,
          likes:    0,
          date:     new Date().toLocaleDateString('fr-FR')
        };

        photos.push(newPhoto);
        localStorage.setItem('viking_photos', JSON.stringify(photos));

        showToast('🛡️ Photo postée avec succès ! Valhalla t\'attend !');
        setTimeout(() => { window.location.href = 'galerie.html'; }, 2000);
      });
    }
  }

  /* ── 5. GALERIE — PAGE GALERIE.HTML ─────────────────────── */
  const galleryGrid = document.getElementById('gallery-grid');

  if (galleryGrid) {
    /* Photos de démo si localStorage vide */
    let photos = JSON.parse(localStorage.getItem('viking_photos') || '[]');

    if (photos.length === 0) {
      photos = generateDemoPhotos();
      localStorage.setItem('viking_photos', JSON.stringify(photos));
    }

    /* Likes sauvegardés séparément pour ne pas écraser les données */
    let likes = JSON.parse(localStorage.getItem('viking_likes') || '{}');

    let currentSort = 'recent';

    renderGallery(photos, likes);
    updateCount(photos.length);

    /* Tri */
    document.querySelectorAll('.sort-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentSort = btn.dataset.sort;
        renderGallery(getSorted(photos, currentSort), likes);
      });
    });

    function getSorted(arr, sort) {
      const copy = [...arr];
      if (sort === 'popular') copy.sort((a, b) => (likes[b.id] || a.likes || 0) - (likes[a.id] || b.likes || 0));
      else copy.sort((a, b) => b.id - a.id); // recent
      return copy;
    }

    function renderGallery(arr, likesMap) {
      galleryGrid.innerHTML = '';
      const sorted = getSorted(arr, currentSort);

      sorted.forEach((photo, index) => {
        const likeCount  = likesMap[photo.id] !== undefined ? likesMap[photo.id] : photo.likes;
        const isLiked    = localStorage.getItem(`liked_${photo.id}`) === '1';
        const rankLabel  = index < 3 ? ['🥇','🥈','🥉'][index] : null;

        const card = document.createElement('div');
        card.className = 'gallery-card';
        card.innerHTML = `
          <div class="gallery-card-img">
            ${photo.src
              ? `<img src="${photo.src}" alt="Photo de ${photo.pseudo}" loading="lazy" style="width:100%;height:100%;object-fit:cover;">`
              : `<div class="placeholder-img">
                   <span class="placeholder-rune">${photo.rune || 'ᚠ'}</span>
                   <span class="placeholder-label">${photo.pseudo}</span>
                 </div>`
            }
            ${rankLabel ? `<div class="gallery-rank">${rankLabel}</div>` : ''}
            <button class="gallery-dl-btn" data-id="${photo.id}" aria-label="Télécharger">⬇ Télécharger</button>
          </div>
          <div class="gallery-card-body">
            <div class="gallery-card-info">
              <h3>${photo.pseudo}</h3>
              <p>${photo.name} · ${photo.date}</p>
            </div>
            <button class="heart-btn ${isLiked ? 'liked' : ''}" data-id="${photo.id}" aria-label="${isLiked ? 'Retirer le favori' : 'Ajouter aux favoris'}">
              <span class="heart-icon">${isLiked ? '❤️' : '🤍'}</span>
              <span class="heart-count">${likeCount}</span>
            </button>
          </div>
        `;

        /* Clic sur la photo → lightbox */
        card.querySelector('.gallery-card-img').addEventListener('click', (e) => {
          if (e.target.classList.contains('gallery-dl-btn')) return;
          openLightbox(photo);
        });

        /* Bouton cœur */
        card.querySelector('.heart-btn').addEventListener('click', (e) => {
          e.stopPropagation();
          toggleLike(photo.id, card.querySelector('.heart-btn'));
        });

        /* Bouton télécharger */
        card.querySelector('.gallery-dl-btn').addEventListener('click', (e) => {
          e.stopPropagation();
          downloadPhoto(photo);
        });

        galleryGrid.appendChild(card);
      });
    }

    function toggleLike(id, btn) {
      const isLiked   = localStorage.getItem(`liked_${id}`) === '1';
      const countEl   = btn.querySelector('.heart-count');
      const heartIcon = btn.querySelector('.heart-icon');
      let   count     = parseInt(countEl.textContent, 10);

      if (isLiked) {
        count--;
        localStorage.removeItem(`liked_${id}`);
        btn.classList.remove('liked');
        heartIcon.textContent = '🤍';
      } else {
        count++;
        localStorage.setItem(`liked_${id}`, '1');
        btn.classList.add('liked');
        heartIcon.textContent = '❤️';
        /* Animation pop */
        btn.classList.add('pop');
        btn.addEventListener('animationend', () => btn.classList.remove('pop'), { once: true });
      }

      countEl.textContent = count;
      likes[id] = count;
      localStorage.setItem('viking_likes', JSON.stringify(likes));
    }

    function downloadPhoto(photo) {
      if (!photo.src || photo.src.startsWith('placeholder')) {
        showToast('📸 Photo de démonstration — téléchargement non disponible.');
        return;
      }
      const a = document.createElement('a');
      a.href     = photo.src;
      a.download = `viking_${photo.pseudo}_${photo.id}.jpg`;
      a.click();
    }

    function updateCount(n) {
      const el = document.getElementById('gallery-count');
      if (el) el.textContent = `${n} photo${n > 1 ? 's' : ''}`;
    }
  }

  /* ── 6. LIGHTBOX ─────────────────────────────────────────── */
  const lightbox      = document.getElementById('lightbox');
  const lightboxClose = document.getElementById('lightbox-close');
  const lightboxBody  = document.getElementById('lightbox-body');

  function openLightbox(photo) {
    if (!lightbox || !lightboxBody) return;

    lightboxBody.innerHTML = photo.src
      ? `<img src="${photo.src}" alt="${photo.pseudo}" style="max-width:85vw;max-height:80vh;border-radius:8px;display:block;">`
      : `<div class="placeholder-img" style="width:min(80vw,500px);height:min(60vh,380px);">
           <span class="placeholder-rune">${photo.rune || 'ᚠ'}</span>
           <span class="placeholder-label">${photo.pseudo}</span>
         </div>`;

    lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
  if (lightbox)      lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeLightbox(); });

  /* ── 7. TOAST ────────────────────────────────────────────── */
  function showToast(message) {
    let toast = document.querySelector('.toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'toast';
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
  }
  /* Exposer showToast globalement pour usage inline si besoin */
  window.showToast = showToast;

  /* ── 8. PHOTOS DE DÉMO ───────────────────────────────────── */
  function generateDemoPhotos() {
    const runes   = ['ᚠ','ᚢ','ᚦ','ᚨ','ᚱ','ᚲ','ᚷ','ᚹ','ᚺ','ᚾ'];
    const pseudos = [
      { pseudo: 'Björn le Valeureux', name: 'Thomas D.'  },
      { pseudo: 'Freya des Brumes',   name: 'Sophie L.'  },
      { pseudo: 'Harald au Tonnerre', name: 'Kevin M.'   },
      { pseudo: 'Sigrid la Farouche', name: 'Marie C.'   },
      { pseudo: 'Ragnar du Nord',     name: 'Nicolas B.' },
      { pseudo: 'Astrid aux Flammes', name: 'Julie R.'   },
      { pseudo: "Leif l'Explorateur", name: 'Antoine P.' },
      { pseudo: 'Ingrid la Sage',     name: 'Camille F.' },
    ];

    return pseudos.map((p, i) => ({
      id:      1000 + i,
      src:     null,
      rune:    runes[i % runes.length],
      name:    p.name,
      pseudo:  p.pseudo,
      caption: 'Photo de démonstration',
      likes:   Math.floor(Math.random() * 30) + 1,
      date:    new Date(Date.now() - i * 3600000).toLocaleDateString('fr-FR')
    }));
  }

});