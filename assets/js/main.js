// =====================================================================
// main.js — interaksi bersama semua halaman publik TJKT
// =====================================================================

// ---- toggle menu mobile ----
const navToggle = document.querySelector('.nav-toggle');
const navLinks = document.querySelector('.nav-links');

if (navToggle && navLinks) {
    navToggle.addEventListener('click', function () {
        const isOpen = navLinks.classList.contains('open');
        if (isOpen) {
            navLinks.classList.remove('open');
            navToggle.classList.remove('open');
        } else {
            navLinks.classList.add('open');
            navToggle.classList.add('open');
        }
    });
}

// ---- filter galeri (hanya jalan kalau elemen filter ada di halaman) ----
const filterButtons = document.querySelectorAll('.tab-btn');
const galleryItems = document.querySelectorAll('.gallery-item');

if (filterButtons.length && galleryItems.length) {
    filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterButtons.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');

            const target = btn.dataset.filter;

            galleryItems.forEach(function (item) {
                if (target === 'semua' || item.dataset.kategori === target) {
                    item.classList.remove('is-hidden');
                } else {
                    item.classList.add('is-hidden');
                }
            });
        });
    });
}

// ---- lightbox foto galeri ----
const lightbox = document.querySelector('.lightbox');
const lightboxImg = document.querySelector('.lightbox img');
const lightboxClose = document.querySelector('.lightbox-close');
const photoItems = document.querySelectorAll('.gallery-item[data-full]');

if (lightbox && lightboxImg) {
    photoItems.forEach(function (item) {
        item.addEventListener('click', function () {
            lightboxImg.src = item.dataset.full;
            lightbox.classList.add('open');
        });
    });

    if (lightboxClose) {
        lightboxClose.addEventListener('click', function () {
            lightbox.classList.remove('open');
        });
    }

    lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox) {
            lightbox.classList.remove('open');
        }
    });
}

// ---- tandai link navbar aktif sesuai halaman saat ini ----
const currentPage = window.location.pathname.split('/').pop() || 'index.html';
document.querySelectorAll('.nav-links a').forEach(function (link) {
    const href = link.getAttribute('href').split('/').pop();
    if (href === currentPage) {
        link.classList.add('active');
    }
});
