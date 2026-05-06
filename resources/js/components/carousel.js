export function initCarousel() {
    const track   = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots    = document.querySelectorAll('.dot');

    if (!track || !prevBtn || !nextBtn) return;

    const total = document.querySelectorAll('.carousel-slide').length;
    let current = 0;
    let interval = null;

    const goTo = (index) => {
        current = (index + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === current);
            d.setAttribute('aria-pressed', String(i === current));
        });
    };

    const start = () => { interval = setInterval(() => goTo(current + 1), 4000); };
    const reset = () => { clearInterval(interval); start(); };

    prevBtn.addEventListener('click', () => { goTo(current - 1); reset(); });
    nextBtn.addEventListener('click', () => { goTo(current + 1); reset(); });
    dots.forEach(d => d.addEventListener('click', () => { goTo(+d.dataset.index); reset(); }));

    const carousel = document.getElementById('carousel');
    if (carousel) {
        carousel.addEventListener('mouseenter', () => clearInterval(interval));
        carousel.addEventListener('mouseleave', start);
    }

    let touchStartX = 0;
    track.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend', (e) => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) { diff > 0 ? goTo(current + 1) : goTo(current - 1); reset(); }
    });

    start();
}
