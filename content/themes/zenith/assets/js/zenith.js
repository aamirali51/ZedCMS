/**
 * Zenith Theme — JavaScript
 * 
 * Interactive features: slider, dark mode, mobile menu, reading progress
 * 
 * @package Zenith
 * @version 1.0.0
 */

(function () {
    'use strict';

    // =============================================================================
    // Dark Mode Toggle
    // =============================================================================

    const darkToggle = document.getElementById('dark-toggle');
    if (darkToggle) {
        darkToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('zenith-dark-mode', isDark);
        });
    }

    // =============================================================================
    // Mobile Menu Toggle
    // =============================================================================

    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // =============================================================================
    // Search Overlay
    // =============================================================================

    const searchToggle = document.getElementById('search-toggle');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');

    if (searchToggle && searchOverlay) {
        searchToggle.addEventListener('click', () => {
            searchOverlay.classList.remove('hidden');
            searchOverlay.querySelector('input')?.focus();
        });

        searchClose?.addEventListener('click', () => {
            searchOverlay.classList.add('hidden');
        });

        searchOverlay.addEventListener('click', (e) => {
            if (e.target === searchOverlay) {
                searchOverlay.classList.add('hidden');
            }
        });

        // Escape key closes overlay
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !searchOverlay.classList.contains('hidden')) {
                searchOverlay.classList.add('hidden');
            }
        });
    }

    // =============================================================================
    // Featured Slider
    // =============================================================================

    const slider = document.getElementById('zenith-slider');
    if (slider) {
        const slides = slider.querySelectorAll('.zenith-slide');
        const dots = slider.querySelectorAll('.zenith-dot');
        const prevBtn = document.getElementById('slider-prev');
        const nextBtn = document.getElementById('slider-next');

        let currentIndex = 0;
        let autoplayInterval = null;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });
            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-white', i === index);
                dot.classList.toggle('w-8', i === index);
                dot.classList.toggle('bg-white/40', i !== index);
                dot.classList.toggle('w-2', i !== index);
            });
            currentIndex = index;
        }

        function nextSlide() {
            showSlide((currentIndex + 1) % slides.length);
        }

        function prevSlide() {
            showSlide((currentIndex - 1 + slides.length) % slides.length);
        }

        // Button Controls
        prevBtn?.addEventListener('click', () => {
            prevSlide();
            resetAutoplay();
        });

        nextBtn?.addEventListener('click', () => {
            nextSlide();
            resetAutoplay();
        });

        // Dot Controls
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                showSlide(i);
                resetAutoplay();
            });
        });

        // Autoplay
        function startAutoplay() {
            autoplayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            startAutoplay();
        }

        // Pause on hover
        slider.addEventListener('mouseenter', () => clearInterval(autoplayInterval));
        slider.addEventListener('mouseleave', startAutoplay);

        // Start autoplay
        if (slides.length > 1) {
            startAutoplay();
        }
    }

    // =============================================================================
    // Reading Progress Bar
    // =============================================================================

    const progressBar = document.getElementById('reading-progress');
    const articleContent = document.getElementById('article-content');

    if (progressBar && articleContent) {
        function updateProgress() {
            const windowHeight = window.innerHeight;
            const documentHeight = articleContent.offsetHeight;
            const scrollTop = window.scrollY - articleContent.offsetTop;
            const scrollPercent = Math.min(100, Math.max(0, (scrollTop / (documentHeight - windowHeight)) * 100));
            progressBar.style.width = scrollPercent + '%';
        }

        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress(); // Initial call
    }

    // =============================================================================
    // Back to Top Button
    // =============================================================================

    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                backToTop.classList.remove('opacity-0', 'invisible');
                backToTop.classList.add('opacity-100', 'visible');
            } else {
                backToTop.classList.add('opacity-0', 'invisible');
                backToTop.classList.remove('opacity-100', 'visible');
            }
        }, { passive: true });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // =============================================================================
    // Lazy Loading Images
    // =============================================================================

    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // =============================================================================
    // Smooth Scroll for Anchor Links
    // =============================================================================

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

})();
