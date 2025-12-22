/**
 * Aurora Theme JavaScript
 * 
 * @package Aurora
 */

(function () {
    'use strict';

    // Mobile menu toggle
    const initMobileMenu = () => {
        const toggle = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');

        if (toggle && menu) {
            toggle.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    };

    // Smooth scroll for anchor links
    const initSmoothScroll = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    };

    // Lazy loading images
    const initLazyLoad = () => {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                observer.observe(img);
            });
        }
    };

    // Header scroll effect
    const initHeaderScroll = () => {
        const header = document.querySelector('header');
        if (!header) return;

        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }

            lastScroll = currentScroll;
        });
    };

    // Copy code blocks
    const initCodeCopy = () => {
        document.querySelectorAll('pre').forEach(pre => {
            const button = document.createElement('button');
            button.className = 'copy-btn absolute top-2 right-2 px-2 py-1 text-xs bg-slate-700 text-slate-300 rounded hover:bg-slate-600';
            button.textContent = 'Copy';

            button.addEventListener('click', async () => {
                const code = pre.querySelector('code');
                if (code) {
                    await navigator.clipboard.writeText(code.textContent);
                    button.textContent = 'Copied!';
                    setTimeout(() => {
                        button.textContent = 'Copy';
                    }, 2000);
                }
            });

            pre.style.position = 'relative';
            pre.appendChild(button);
        });
    };

    // Initialize all
    document.addEventListener('DOMContentLoaded', () => {
        initMobileMenu();
        initSmoothScroll();
        initLazyLoad();
        initHeaderScroll();
        initCodeCopy();

        console.log('🌌 Aurora Framework initialized');
    });

})();
