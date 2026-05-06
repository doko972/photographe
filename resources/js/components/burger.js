export function initBurger() {
    const burger    = document.getElementById('burger');
    const navMobile = document.getElementById('nav-mobile');
    const header    = document.getElementById('site-header');

    if (!burger || !navMobile) return;

    const close = () => {
        burger.classList.remove('open');
        navMobile.classList.remove('open');
        burger.setAttribute('aria-expanded', 'false');
        navMobile.setAttribute('aria-hidden', 'true');
    };

    burger.addEventListener('click', () => {
        const isOpen = burger.classList.toggle('open');
        navMobile.classList.toggle('open', isOpen);
        burger.setAttribute('aria-expanded', String(isOpen));
        navMobile.setAttribute('aria-hidden', String(!isOpen));
    });

    navMobile.querySelectorAll('a').forEach(link => link.addEventListener('click', close));

    document.addEventListener('click', (e) => {
        if (header && !header.contains(e.target)) close();
    });
}
