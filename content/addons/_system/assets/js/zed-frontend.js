/**
 * Zed CMS Frontend Library
 * 
 * Lightweight AJAX utilities for theme developers.
 * 
 * Features:
 * - AJAX wrapper with loading states
 * - Infinite scroll component
 * - Load more button component
 * - Live search with debounce
 * - Filter/sort without page reload
 * 
 * @package Zed CMS
 * @version 3.2.0
 */

(function (window, document) {
    'use strict';

    // =========================================================================
    // ZED NAMESPACE
    // =========================================================================

    const Zed = window.Zed || {};

    // Base URL (set by PHP or detect from current location)
    Zed.baseUrl = window.ZED_BASE_URL || '';
    Zed.apiUrl = Zed.baseUrl + '/api';

    // =========================================================================
    // AJAX UTILITIES
    // =========================================================================

    /**
     * Make an AJAX request
     * 
     * @param {string} url - Request URL
     * @param {Object} options - Request options
     * @returns {Promise}
     * 
     * @example
     * Zed.ajax('/api?action=get_posts', {
     *     method: 'GET',
     *     onSuccess: (data) => console.log(data),
     *     onError: (error) => console.error(error),
     * });
     */
    Zed.ajax = function (url, options = {}) {
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: null,
            onStart: null,
            onSuccess: null,
            onError: null,
            onComplete: null,
        };

        const config = { ...defaults, ...options };

        // Trigger start callback
        if (typeof config.onStart === 'function') {
            config.onStart();
        }

        // Build fetch options
        const fetchOptions = {
            method: config.method,
            headers: config.headers,
        };

        if (config.body && config.method !== 'GET') {
            fetchOptions.body = typeof config.body === 'string'
                ? config.body
                : JSON.stringify(config.body);
        }

        return fetch(url, fetchOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (typeof config.onSuccess === 'function') {
                    config.onSuccess(data);
                }
                return data;
            })
            .catch(error => {
                if (typeof config.onError === 'function') {
                    config.onError(error);
                }
                throw error;
            })
            .finally(() => {
                if (typeof config.onComplete === 'function') {
                    config.onComplete();
                }
            });
    };

    /**
     * GET request shorthand
     */
    Zed.get = function (url, options = {}) {
        return Zed.ajax(url, { ...options, method: 'GET' });
    };

    /**
     * POST request shorthand
     */
    Zed.post = function (url, data, options = {}) {
        return Zed.ajax(url, { ...options, method: 'POST', body: data });
    };

    // =========================================================================
    // DEBOUNCE / THROTTLE
    // =========================================================================

    /**
     * Debounce a function
     * 
     * @param {Function} func - Function to debounce
     * @param {number} wait - Milliseconds to wait
     * @returns {Function}
     */
    Zed.debounce = function (func, wait = 300) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    };

    /**
     * Throttle a function
     * 
     * @param {Function} func - Function to throttle
     * @param {number} limit - Milliseconds between calls
     * @returns {Function}
     */
    Zed.throttle = function (func, limit = 100) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    };

    // =========================================================================
    // INFINITE SCROLL
    // =========================================================================

    /**
     * Initialize infinite scroll on a container
     * 
     * @param {Object} options - Configuration
     * 
     * @example
     * Zed.infiniteScroll({
     *     container: '.posts-grid',
     *     item: '.post-card',
     *     url: '/api?action=get_posts',
     *     page: 1,
     *     perPage: 10,
     *     threshold: 200,
     *     onLoad: (items, page) => {},
     *     onEnd: () => {},
     *     render: (item) => `<div class="post-card">...</div>`,
     * });
     */
    Zed.infiniteScroll = function (options) {
        const defaults = {
            container: '.infinite-scroll-container',
            item: '.infinite-scroll-item',
            url: Zed.apiUrl + '?action=get_posts',
            page: 1,
            perPage: 10,
            threshold: 200,
            loading: false,
            hasMore: true,
            extraParams: {},
            onLoad: null,
            onEnd: null,
            render: null,
            loadingHtml: '<div class="loading-spinner"><span class="spinner"></span> Loading...</div>',
        };

        const config = { ...defaults, ...options };
        const container = document.querySelector(config.container);

        if (!container) {
            console.warn('Infinite scroll: Container not found:', config.container);
            return null;
        }

        // Create loading indicator
        const loadingEl = document.createElement('div');
        loadingEl.className = 'infinite-scroll-loading';
        loadingEl.innerHTML = config.loadingHtml;
        loadingEl.style.display = 'none';
        container.parentNode.insertBefore(loadingEl, container.nextSibling);

        // Scroll handler
        const checkScroll = Zed.throttle(function () {
            if (config.loading || !config.hasMore) return;

            const containerRect = container.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            if (containerRect.bottom - windowHeight < config.threshold) {
                loadMore();
            }
        }, 100);

        // Load more items
        function loadMore() {
            if (config.loading || !config.hasMore) return;

            config.loading = true;
            loadingEl.style.display = 'block';

            const params = new URLSearchParams({
                page: config.page + 1,
                per_page: config.perPage,
                ...config.extraParams,
            });

            const url = config.url + (config.url.includes('?') ? '&' : '?') + params.toString();

            Zed.get(url)
                .then(data => {
                    const items = data.items || data.posts || data.data || [];

                    if (items.length === 0) {
                        config.hasMore = false;
                        if (typeof config.onEnd === 'function') {
                            config.onEnd();
                        }
                        return;
                    }

                    config.page++;

                    // Render items
                    if (typeof config.render === 'function') {
                        items.forEach(item => {
                            const html = config.render(item);
                            container.insertAdjacentHTML('beforeend', html);
                        });
                    }

                    // Callback
                    if (typeof config.onLoad === 'function') {
                        config.onLoad(items, config.page);
                    }

                    // Check if no more items
                    if (items.length < config.perPage) {
                        config.hasMore = false;
                        if (typeof config.onEnd === 'function') {
                            config.onEnd();
                        }
                    }
                })
                .catch(error => {
                    console.error('Infinite scroll error:', error);
                })
                .finally(() => {
                    config.loading = false;
                    loadingEl.style.display = 'none';
                });
        }

        // Attach scroll listener
        window.addEventListener('scroll', checkScroll);

        // Return control object
        return {
            destroy: () => window.removeEventListener('scroll', checkScroll),
            loadMore: loadMore,
            reset: (newPage = 1) => {
                config.page = newPage;
                config.hasMore = true;
                container.innerHTML = '';
            },
        };
    };

    // =========================================================================
    // LOAD MORE BUTTON
    // =========================================================================

    /**
     * Initialize load more button
     * 
     * @param {Object} options - Configuration
     * 
     * @example
     * Zed.loadMore({
     *     button: '.load-more-btn',
     *     container: '.posts-grid',
     *     url: '/api?action=get_posts',
     *     page: 1,
     *     perPage: 10,
     *     render: (item) => `<div class="post-card">...</div>`,
     * });
     */
    Zed.loadMore = function (options) {
        const defaults = {
            button: '.load-more-btn',
            container: '.load-more-container',
            url: Zed.apiUrl + '?action=get_posts',
            page: 1,
            perPage: 10,
            extraParams: {},
            loading: false,
            hasMore: true,
            render: null,
            onLoad: null,
            onEnd: null,
            buttonText: 'Load More',
            loadingText: 'Loading...',
        };

        const config = { ...defaults, ...options };
        const button = document.querySelector(config.button);
        const container = document.querySelector(config.container);

        if (!button || !container) {
            console.warn('Load more: Button or container not found');
            return null;
        }

        const originalText = button.textContent;

        button.addEventListener('click', function (e) {
            e.preventDefault();

            if (config.loading || !config.hasMore) return;

            config.loading = true;
            button.textContent = config.loadingText;
            button.disabled = true;

            const params = new URLSearchParams({
                page: config.page + 1,
                per_page: config.perPage,
                ...config.extraParams,
            });

            const url = config.url + (config.url.includes('?') ? '&' : '?') + params.toString();

            Zed.get(url)
                .then(data => {
                    const items = data.items || data.posts || data.data || [];

                    if (items.length === 0) {
                        config.hasMore = false;
                        button.style.display = 'none';
                        if (typeof config.onEnd === 'function') {
                            config.onEnd();
                        }
                        return;
                    }

                    config.page++;

                    // Render items
                    if (typeof config.render === 'function') {
                        items.forEach(item => {
                            const html = config.render(item);
                            container.insertAdjacentHTML('beforeend', html);
                        });
                    }

                    // Callback
                    if (typeof config.onLoad === 'function') {
                        config.onLoad(items, config.page);
                    }

                    // Check if no more items
                    if (items.length < config.perPage) {
                        config.hasMore = false;
                        button.style.display = 'none';
                        if (typeof config.onEnd === 'function') {
                            config.onEnd();
                        }
                    }
                })
                .catch(error => {
                    console.error('Load more error:', error);
                })
                .finally(() => {
                    config.loading = false;
                    button.textContent = config.buttonText || originalText;
                    button.disabled = false;
                });
        });

        return {
            reset: (newPage = 1) => {
                config.page = newPage;
                config.hasMore = true;
                button.style.display = '';
                container.innerHTML = '';
            },
        };
    };

    // =========================================================================
    // LIVE SEARCH
    // =========================================================================

    /**
     * Initialize live search
     * 
     * @param {Object} options - Configuration
     * 
     * @example
     * Zed.liveSearch({
     *     input: '#search-input',
     *     results: '#search-results',
     *     url: '/api?action=search',
     *     minChars: 2,
     *     debounce: 300,
     *     render: (item) => `<a href="${item.url}">${item.title}</a>`,
     * });
     */
    Zed.liveSearch = function (options) {
        const defaults = {
            input: '.live-search-input',
            results: '.live-search-results',
            url: Zed.apiUrl + '?action=search',
            minChars: 2,
            debounceMs: 300,
            render: null,
            onResults: null,
            onEmpty: null,
            onClear: null,
            extraParams: {},
            loadingClass: 'searching',
            noResultsHtml: '<div class="no-results">No results found</div>',
        };

        const config = { ...defaults, ...options };
        const input = document.querySelector(config.input);
        const results = document.querySelector(config.results);

        if (!input || !results) {
            console.warn('Live search: Input or results container not found');
            return null;
        }

        let lastQuery = '';
        let abortController = null;

        const doSearch = Zed.debounce(function (query) {
            // Abort previous request
            if (abortController) {
                abortController.abort();
            }

            if (query.length < config.minChars) {
                results.innerHTML = '';
                results.style.display = 'none';
                if (typeof config.onClear === 'function') {
                    config.onClear();
                }
                return;
            }

            if (query === lastQuery) return;
            lastQuery = query;

            abortController = new AbortController();
            input.classList.add(config.loadingClass);

            const params = new URLSearchParams({
                q: query,
                ...config.extraParams,
            });

            const url = config.url + (config.url.includes('?') ? '&' : '?') + params.toString();

            fetch(url, { signal: abortController.signal })
                .then(response => response.json())
                .then(data => {
                    const items = data.results || data.items || data.posts || data.data || [];

                    if (items.length === 0) {
                        results.innerHTML = config.noResultsHtml;
                        results.style.display = 'block';
                        if (typeof config.onEmpty === 'function') {
                            config.onEmpty(query);
                        }
                        return;
                    }

                    // Render results
                    if (typeof config.render === 'function') {
                        results.innerHTML = items.map(config.render).join('');
                    }

                    results.style.display = 'block';

                    // Callback
                    if (typeof config.onResults === 'function') {
                        config.onResults(items, query);
                    }
                })
                .catch(error => {
                    if (error.name !== 'AbortError') {
                        console.error('Live search error:', error);
                    }
                })
                .finally(() => {
                    input.classList.remove(config.loadingClass);
                });
        }, config.debounceMs);

        // Input handler
        input.addEventListener('input', function () {
            doSearch(this.value.trim());
        });

        // Close on click outside
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.style.display = 'none';
            }
        });

        // Show results on focus if has content
        input.addEventListener('focus', function () {
            if (results.innerHTML && this.value.length >= config.minChars) {
                results.style.display = 'block';
            }
        });

        return {
            clear: () => {
                input.value = '';
                results.innerHTML = '';
                results.style.display = 'none';
                lastQuery = '';
            },
            search: (query) => {
                input.value = query;
                doSearch(query);
            },
        };
    };

    // =========================================================================
    // AJAX FILTER
    // =========================================================================

    /**
     * Initialize AJAX filter/sort
     * 
     * @param {Object} options - Configuration
     * 
     * @example
     * Zed.ajaxFilter({
     *     form: '#filter-form',
     *     container: '.posts-grid',
     *     url: '/api?action=get_posts',
     *     updateUrl: true,
     *     render: (item) => `<div class="post-card">...</div>`,
     * });
     */
    Zed.ajaxFilter = function (options) {
        const defaults = {
            form: '.ajax-filter-form',
            container: '.ajax-filter-container',
            url: Zed.apiUrl + '?action=get_posts',
            updateUrl: true,
            pushState: true,
            onStart: null,
            onLoad: null,
            onError: null,
            render: null,
            loadingClass: 'loading',
        };

        const config = { ...defaults, ...options };
        const form = document.querySelector(config.form);
        const container = document.querySelector(config.container);

        if (!form || !container) {
            console.warn('AJAX filter: Form or container not found');
            return null;
        }

        function doFilter() {
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            container.classList.add(config.loadingClass);

            if (typeof config.onStart === 'function') {
                config.onStart();
            }

            const url = config.url + (config.url.includes('?') ? '&' : '?') + params.toString();

            Zed.get(url)
                .then(data => {
                    const items = data.items || data.posts || data.data || [];

                    // Render items
                    if (typeof config.render === 'function') {
                        container.innerHTML = items.map(config.render).join('');
                    }

                    // Update browser URL
                    if (config.updateUrl && config.pushState) {
                        const newUrl = window.location.pathname + '?' + params.toString();
                        history.pushState(null, '', newUrl);
                    }

                    // Callback
                    if (typeof config.onLoad === 'function') {
                        config.onLoad(items, params);
                    }
                })
                .catch(error => {
                    console.error('AJAX filter error:', error);
                    if (typeof config.onError === 'function') {
                        config.onError(error);
                    }
                })
                .finally(() => {
                    container.classList.remove(config.loadingClass);
                });
        }

        // Form submit handler
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            doFilter();
        });

        // Auto-submit on change (for selects, radios, checkboxes)
        form.querySelectorAll('select, input[type="radio"], input[type="checkbox"]').forEach(el => {
            el.addEventListener('change', doFilter);
        });

        // Handle browser back/forward
        if (config.updateUrl) {
            window.addEventListener('popstate', function () {
                // Parse URL params and update form
                const params = new URLSearchParams(window.location.search);
                params.forEach((value, key) => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox' || input.type === 'radio') {
                            input.checked = value === input.value;
                        } else {
                            input.value = value;
                        }
                    }
                });
                doFilter();
            });
        }

        return {
            filter: doFilter,
            reset: () => {
                form.reset();
                doFilter();
            },
        };
    };

    // =========================================================================
    // UTILITY HELPERS
    // =========================================================================

    /**
     * Escape HTML entities
     */
    Zed.escapeHtml = function (str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    };

    /**
     * Format date
     */
    Zed.formatDate = function (dateStr, format = 'short') {
        const date = new Date(dateStr);
        const options = format === 'long'
            ? { year: 'numeric', month: 'long', day: 'numeric' }
            : { year: 'numeric', month: 'short', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    };

    /**
     * Truncate text
     */
    Zed.truncate = function (str, length = 100, suffix = '...') {
        if (str.length <= length) return str;
        return str.substring(0, length).trim() + suffix;
    };

    // =========================================================================
    // CSS CLASSES
    // =========================================================================

    // Inject minimal CSS for loading states
    const style = document.createElement('style');
    style.textContent = `
        .loading { opacity: 0.6; pointer-events: none; }
        .searching { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cpath fill='%236366f1' d='M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z'%3E%3CanimateTransform attributeName='transform' type='rotate' from='0 12 12' to='360 12 12' dur='1s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; }
        .infinite-scroll-loading { text-align: center; padding: 20px; color: #6b7280; }
        .spinner { display: inline-block; width: 20px; height: 20px; border: 2px solid #e5e7eb; border-top-color: #6366f1; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .live-search-results { position: absolute; z-index: 50; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-height: 300px; overflow-y: auto; }
        .live-search-results a { display: block; padding: 10px 15px; color: #374151; text-decoration: none; border-bottom: 1px solid #f3f4f6; }
        .live-search-results a:hover { background: #f9fafb; }
        .live-search-results .no-results { padding: 15px; color: #9ca3af; text-align: center; }
    `;
    document.head.appendChild(style);

    // =========================================================================
    // EXPORT
    // =========================================================================

    window.Zed = Zed;

})(window, document);
