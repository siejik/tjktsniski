// =====================================================================
// main.js — interaksi bersama semua halaman publik TJKT
// Native vanilla JS — tanpa framework/library. Dipakai bareng Tailwind CDN.
// =====================================================================

document.addEventListener('DOMContentLoaded', function () {

    // -----------------------------------------------------------------
    // 1. Navbar: toggle menu mobile + tandai link aktif
    // -----------------------------------------------------------------
    const navToggle = document.getElementById('nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconMenu = document.getElementById('icon-menu');
    const iconClose = document.getElementById('icon-close');

    if (navToggle && mobileMenu) {
        navToggle.addEventListener('click', function () {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            navToggle.setAttribute('aria-expanded', String(!isOpen));
            if (iconMenu && iconClose) {
                iconMenu.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');
            }
        });

        // tutup menu mobile begitu salah satu link ditekan
        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
                navToggle.setAttribute('aria-expanded', 'false');
                if (iconMenu && iconClose) {
                    iconMenu.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });
        });
    }

    // Catatan: highlight link navbar aktif sekarang ditentukan di server
    // lewat includes/header.php (fungsi nav_class / nav_class_mobile),
    // jadi tidak perlu lagi dideteksi lewat JS di sini.

    // -----------------------------------------------------------------
    // 2. Hero slideshow (foto background bergeser di halaman beranda)
    // -----------------------------------------------------------------
    const heroSlides = document.querySelectorAll('.hero-slide');
    if (heroSlides.length > 1) {
        let heroIndex = 0;
        setInterval(function () {
            heroSlides[heroIndex].classList.remove('opacity-100');
            heroSlides[heroIndex].classList.add('opacity-0');

            heroIndex = (heroIndex + 1) % heroSlides.length;

            heroSlides[heroIndex].classList.remove('opacity-0');
            heroSlides[heroIndex].classList.add('opacity-100');
        }, 5000);
    }

    // -----------------------------------------------------------------
    // 3. Filter galeri (hanya jalan kalau elemen filter ada di halaman)
    // -----------------------------------------------------------------
    const filterButtons = document.querySelectorAll('.tab-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    if (filterButtons.length && galleryItems.length) {
        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterButtons.forEach(function (b) {
                    b.classList.remove('bg-brand-primary', 'text-white');
                    b.classList.add('bg-white/5', 'text-slate-300');
                });
                btn.classList.add('bg-brand-primary', 'text-white');
                btn.classList.remove('bg-white/5', 'text-slate-300');

                const target = btn.dataset.filter;

                galleryItems.forEach(function (item) {
                    if (target === 'semua' || item.dataset.kategori === target) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
    }

    // -----------------------------------------------------------------
    // 4. Lightbox foto galeri
    // -----------------------------------------------------------------
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.getElementById('lightbox-close');
    const photoItems = document.querySelectorAll('.gallery-item[data-full]');

    if (lightbox && lightboxImg) {
        photoItems.forEach(function (item) {
            item.addEventListener('click', function () {
                lightboxImg.src = item.dataset.full;
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            });
        });

        function closeLightbox() {
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        if (lightboxClose) {
            lightboxClose.addEventListener('click', closeLightbox);
        }

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLightbox();
        });
    }

});
