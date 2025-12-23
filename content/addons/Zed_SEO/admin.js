/**
 * Zed SEO Pro - Admin JavaScript
 * 
 * Professional SEO analysis with live scoring, previews, and content optimization
 */

(function () {
    'use strict';

    // ==========================================================================
    // Configuration
    // ==========================================================================

    const CONFIG = {
        titleMin: 30,
        titleMax: 60,
        titleIdeal: 55,
        descMin: 120,
        descMax: 160,
        descIdeal: 155,
        keywordDensityMin: 0.5,
        keywordDensityMax: 2.5,
        keywordDensityIdeal: 1.5,
        contentMinWords: 300,
        contentIdealWords: 1000,
        internalLinksMin: 2,
        headingsMin: 2,
    };

    // ==========================================================================
    // DOM Elements
    // ==========================================================================

    let elements = {};

    function initElements() {
        elements = {
            container: document.getElementById('zed-seo-panel'),
            previewBox: document.getElementById('zed-seo-preview-box'),
            analysisBox: document.getElementById('zed-seo-analysis-box'),
            scoreDisplay: document.getElementById('zed-seo-score-display'),

            // Form fields
            titleInput: document.querySelector('input[name="meta[seo_title]"]'),
            descInput: document.querySelector('textarea[name="meta[seo_desc]"]'),
            keywordInput: document.querySelector('input[name="meta[focus_keyword]"]'),
            noindexToggle: document.querySelector('[name="meta[seo_noindex]"]'),
            schemaType: document.querySelector('[name="meta[schema_type]"]'),

            // Post fields
            postTitle: document.querySelector('input[name="title"]') || document.getElementById('post-title'),
            postSlug: document.querySelector('input[name="slug"]') || document.getElementById('post-slug'),
            postExcerpt: document.querySelector('textarea[name="excerpt"]') || document.getElementById('post-excerpt'),

            // Tab elements
            tabs: document.querySelectorAll('.zed-seo-tab'),
            tabContents: document.querySelectorAll('.zed-seo-tab-content'),
        };
    }

    // ==========================================================================
    // SEO Analysis Engine
    // ==========================================================================

    function analyzeContent() {
        const checks = [];
        let totalScore = 0;
        let maxScore = 0;

        // Get values
        const postTitle = elements.postTitle?.value || '';
        const seoTitle = elements.titleInput?.value || '';
        const title = seoTitle || postTitle || 'Untitled';

        const seoDesc = elements.descInput?.value || '';
        const excerpt = elements.postExcerpt?.value || '';
        const description = seoDesc || excerpt || '';

        const focusKeyword = elements.keywordInput?.value?.toLowerCase().trim() || '';
        const slug = elements.postSlug?.value || '';

        // Get content from editor (if available)
        const content = getEditorContent();
        const wordCount = countWords(content);
        const plainContent = stripHtml(content).toLowerCase();

        // =======================================================================
        // Check 1: SEO Title Length (15 points)
        // =======================================================================
        maxScore += 15;
        const titleLen = title.length;
        if (titleLen >= CONFIG.titleMin && titleLen <= CONFIG.titleMax) {
            checks.push({ status: 'pass', text: `Title length is good (${titleLen} characters)`, points: 15 });
            totalScore += 15;
        } else if (titleLen < CONFIG.titleMin) {
            checks.push({ status: 'warn', text: `Title is too short (${titleLen}/${CONFIG.titleMin} min characters)`, points: 5 });
            totalScore += 5;
        } else {
            checks.push({ status: 'warn', text: `Title is too long (${titleLen}/${CONFIG.titleMax} max characters)`, points: 8 });
            totalScore += 8;
        }

        // =======================================================================
        // Check 2: Meta Description Length (15 points)
        // =======================================================================
        maxScore += 15;
        const descLen = description.length;
        if (descLen >= CONFIG.descMin && descLen <= CONFIG.descMax) {
            checks.push({ status: 'pass', text: `Meta description length is optimal (${descLen} characters)`, points: 15 });
            totalScore += 15;
        } else if (descLen < CONFIG.descMin && descLen > 0) {
            checks.push({ status: 'warn', text: `Meta description is short (${descLen}/${CONFIG.descMin} min)`, points: 8 });
            totalScore += 8;
        } else if (descLen === 0) {
            checks.push({ status: 'fail', text: 'Meta description is missing', points: 0 });
        } else {
            checks.push({ status: 'warn', text: `Meta description is too long (${descLen}/${CONFIG.descMax} max)`, points: 8 });
            totalScore += 8;
        }

        // =======================================================================
        // Check 3: Focus Keyword Presence (20 points)
        // =======================================================================
        if (focusKeyword) {
            maxScore += 20;
            let keywordScore = 0;
            const keywordChecks = [];

            // In title
            if (title.toLowerCase().includes(focusKeyword)) {
                keywordChecks.push('title');
                keywordScore += 5;
            }

            // In description
            if (description.toLowerCase().includes(focusKeyword)) {
                keywordChecks.push('description');
                keywordScore += 5;
            }

            // In slug
            if (slug.toLowerCase().includes(focusKeyword.replace(/\s+/g, '-'))) {
                keywordChecks.push('URL');
                keywordScore += 5;
            }

            // In content
            if (plainContent.includes(focusKeyword)) {
                keywordChecks.push('content');
                keywordScore += 5;
            }

            totalScore += keywordScore;

            if (keywordChecks.length >= 3) {
                checks.push({ status: 'pass', text: `Focus keyword found in: ${keywordChecks.join(', ')}`, points: keywordScore });
            } else if (keywordChecks.length > 0) {
                checks.push({ status: 'warn', text: `Focus keyword found in: ${keywordChecks.join(', ')} (add to more places)`, points: keywordScore });
            } else {
                checks.push({ status: 'fail', text: 'Focus keyword not found in content', points: 0 });
            }

            // Keyword density
            maxScore += 10;
            const density = calculateKeywordDensity(plainContent, focusKeyword, wordCount);
            if (density >= CONFIG.keywordDensityMin && density <= CONFIG.keywordDensityMax) {
                checks.push({ status: 'pass', text: `Keyword density is good (${density.toFixed(1)}%)`, points: 10 });
                totalScore += 10;
            } else if (density < CONFIG.keywordDensityMin) {
                checks.push({ status: 'warn', text: `Keyword density is low (${density.toFixed(1)}%, aim for ${CONFIG.keywordDensityIdeal}%)`, points: 5 });
                totalScore += 5;
            } else {
                checks.push({ status: 'warn', text: `Keyword density is high (${density.toFixed(1)}%, may look spammy)`, points: 3 });
                totalScore += 3;
            }
        }

        // =======================================================================
        // Check 4: Content Length (15 points)
        // =======================================================================
        maxScore += 15;
        if (wordCount >= CONFIG.contentIdealWords) {
            checks.push({ status: 'pass', text: `Content length is excellent (${wordCount} words)`, points: 15 });
            totalScore += 15;
        } else if (wordCount >= CONFIG.contentMinWords) {
            checks.push({ status: 'pass', text: `Content length is good (${wordCount} words)`, points: 12 });
            totalScore += 12;
        } else if (wordCount > 100) {
            checks.push({ status: 'warn', text: `Content is short (${wordCount} words, aim for ${CONFIG.contentMinWords}+)`, points: 5 });
            totalScore += 5;
        } else {
            checks.push({ status: 'fail', text: `Content is too short (${wordCount} words)`, points: 0 });
        }

        // =======================================================================
        // Check 5: SEO-Friendly URL (10 points)
        // =======================================================================
        maxScore += 10;
        if (slug) {
            const isClean = /^[a-z0-9-]+$/.test(slug);
            const isShort = slug.length <= 60;
            const noStopWords = !/(^|-)(the|and|or|but|in|on|at|to|for|of|a|an)(-|$)/.test(slug);

            if (isClean && isShort && noStopWords) {
                checks.push({ status: 'pass', text: 'URL slug is SEO-friendly', points: 10 });
                totalScore += 10;
            } else if (isClean && isShort) {
                checks.push({ status: 'warn', text: 'URL contains stop words (consider removing)', points: 7 });
                totalScore += 7;
            } else {
                checks.push({ status: 'warn', text: 'URL could be improved (keep it short and clean)', points: 4 });
                totalScore += 4;
            }
        } else {
            checks.push({ status: 'fail', text: 'URL slug is missing', points: 0 });
        }

        // =======================================================================
        // Check 6: Headings Structure (10 points)
        // =======================================================================
        maxScore += 10;
        const headingCount = (content.match(/<h[1-6][^>]*>/gi) || []).length;
        if (headingCount >= CONFIG.headingsMin) {
            checks.push({ status: 'pass', text: `Good use of headings (${headingCount} found)`, points: 10 });
            totalScore += 10;
        } else if (headingCount > 0) {
            checks.push({ status: 'warn', text: `Add more headings to structure content (${headingCount} found)`, points: 5 });
            totalScore += 5;
        } else {
            checks.push({ status: 'fail', text: 'No headings found in content', points: 0 });
        }

        // =======================================================================
        // Check 7: Internal/External Links (5 points)
        // =======================================================================
        maxScore += 5;
        const linkCount = (content.match(/<a\s+[^>]*href/gi) || []).length;
        if (linkCount >= CONFIG.internalLinksMin) {
            checks.push({ status: 'pass', text: `Contains links (${linkCount} found)`, points: 5 });
            totalScore += 5;
        } else if (linkCount > 0) {
            checks.push({ status: 'warn', text: `Add more internal links (${linkCount} found)`, points: 3 });
            totalScore += 3;
        } else {
            checks.push({ status: 'warn', text: 'No links found in content', points: 0 });
        }

        // Calculate percentage score
        const scorePercent = maxScore > 0 ? Math.round((totalScore / maxScore) * 100) : 0;

        return {
            score: scorePercent,
            checks: checks,
            title: title,
            description: description,
            slug: slug,
            wordCount: wordCount
        };
    }

    // ==========================================================================
    // Helper Functions
    // ==========================================================================

    function getEditorContent() {
        // Try to get content from BlockNote/Tiptap editors
        if (window.zero_editor_content) {
            try {
                const blocks = window.zero_editor_content;
                return blocksToHtml(blocks);
            } catch (e) { }
        }

        // Fallback: try to get from a content textarea
        const contentEl = document.querySelector('textarea[name="content"]');
        if (contentEl) return contentEl.value;

        // Fallback: try to get from editor container
        const editorEl = document.querySelector('.ProseMirror, .bn-editor, [contenteditable="true"]');
        if (editorEl) return editorEl.innerHTML;

        return '';
    }

    function blocksToHtml(blocks) {
        if (!Array.isArray(blocks)) return '';

        return blocks.map(block => {
            const content = block.content?.map(c => c.text || '').join('') || '';
            switch (block.type) {
                case 'heading': return `<h${block.props?.level || 2}>${content}</h${block.props?.level || 2}>`;
                case 'paragraph': return `<p>${content}</p>`;
                case 'bulletListItem': return `<li>${content}</li>`;
                case 'numberedListItem': return `<li>${content}</li>`;
                default: return content;
            }
        }).join('\n');
    }

    function stripHtml(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    }

    function countWords(content) {
        const text = stripHtml(content);
        const words = text.trim().split(/\s+/).filter(w => w.length > 0);
        return words.length;
    }

    function calculateKeywordDensity(content, keyword, wordCount) {
        if (!keyword || wordCount === 0) return 0;

        const regex = new RegExp(escapeRegex(keyword), 'gi');
        const matches = content.match(regex) || [];
        const keywordWords = keyword.split(/\s+/).length;

        return (matches.length * keywordWords / wordCount) * 100;
    }

    function escapeRegex(str) {
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function truncate(str, max) {
        if (!str) return '';
        return str.length > max ? str.substring(0, max - 3) + '...' : str;
    }

    // ==========================================================================
    // Render Functions
    // ==========================================================================

    function renderGooglePreview(analysis) {
        if (!elements.previewBox) return;

        const siteName = window.ZED_SITE_NAME || 'My Website';
        const baseUrl = window.ZED_BASE_URL || 'https://example.com';
        const url = analysis.slug ? `${baseUrl}/${analysis.slug}` : baseUrl;
        const title = truncate(analysis.title, CONFIG.titleMax) || 'Page Title';
        const desc = truncate(analysis.description, CONFIG.descMax) || 'Add a meta description to see how this page will appear in search results...';

        elements.previewBox.innerHTML = `
            <div class="zed-seo-preview">
                <div class="site-breadcrumb">
                    <span class="favicon">
                        <img src="https://www.google.com/s2/favicons?domain=${baseUrl}&sz=32" alt="">
                    </span>
                    <span>${escapeHtml(siteName)}</span>
                </div>
                <div class="site-url">${escapeHtml(url)}</div>
                <a href="#" class="site-title" onclick="return false;">${escapeHtml(title)}</a>
                <div class="site-desc">${escapeHtml(desc)}</div>
            </div>
            <div class="zed-seo-counter" style="margin: 0 16px;">
                <div style="display: flex; gap: 16px;">
                    <span style="font-size: 11px; color: ${analysis.title.length > CONFIG.titleMax ? '#ef4444' : '#6b7280'}">
                        Title: ${analysis.title.length}/${CONFIG.titleMax}
                    </span>
                    <span style="font-size: 11px; color: ${analysis.description.length > CONFIG.descMax ? '#ef4444' : '#6b7280'}">
                        Description: ${analysis.description.length}/${CONFIG.descMax}
                    </span>
                </div>
            </div>
        `;
    }

    function renderAnalysisChecks(analysis) {
        if (!elements.analysisBox) return;

        const checksHtml = analysis.checks.map(check => `
            <div class="zed-seo-check">
                <div class="zed-seo-check-icon ${check.status}">
                    ${check.status === 'pass' ? '✓' : check.status === 'warn' ? '!' : '✗'}
                </div>
                <div class="zed-seo-check-text">${escapeHtml(check.text)}</div>
            </div>
        `).join('');

        elements.analysisBox.innerHTML = `
            <div class="zed-seo-analysis-title">Content Analysis</div>
            ${checksHtml}
            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #6b7280;">
                    <span>Word Count: <strong>${analysis.wordCount}</strong></span>
                    <span>Reading Time: <strong>~${Math.ceil(analysis.wordCount / 200)} min</strong></span>
                </div>
            </div>
        `;
    }

    function renderScoreDisplay(score) {
        if (!elements.scoreDisplay) return;

        let scoreClass = 'score-poor';
        let scoreLabel = 'Needs Work';

        if (score >= 80) {
            scoreClass = 'score-good';
            scoreLabel = 'Excellent';
        } else if (score >= 60) {
            scoreClass = 'score-ok';
            scoreLabel = 'Good';
        } else if (score >= 40) {
            scoreClass = 'score-ok';
            scoreLabel = 'Fair';
        }

        elements.scoreDisplay.innerHTML = `
            <div class="zed-seo-score-ring ${scoreClass}">${score}</div>
            <span>${scoreLabel}</span>
        `;
    }

    // ==========================================================================
    // Tab Navigation
    // ==========================================================================

    function initTabs() {
        elements.tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.dataset.tab;

                // Update tab buttons
                elements.tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                // Update tab content
                elements.tabContents.forEach(content => {
                    content.classList.toggle('active', content.dataset.tab === tabId);
                });
            });
        });
    }

    // ==========================================================================
    // Schema Type Selector
    // ==========================================================================

    function initSchemaSelector() {
        const options = document.querySelectorAll('.zed-seo-schema-option');

        options.forEach(option => {
            option.addEventListener('click', () => {
                options.forEach(o => o.classList.remove('selected'));
                option.classList.add('selected');

                const radio = option.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;
            });
        });
    }

    // ==========================================================================
    // Update Loop
    // ==========================================================================

    function update() {
        const analysis = analyzeContent();
        renderGooglePreview(analysis);
        renderAnalysisChecks(analysis);
        renderScoreDisplay(analysis.score);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const debouncedUpdate = debounce(update, 300);

    // ==========================================================================
    // Initialization
    // ==========================================================================

    function init() {
        initElements();

        if (!elements.container && !elements.previewBox) {
            return; // Not on editor page
        }

        initTabs();
        initSchemaSelector();

        // Bind events
        const inputs = [
            elements.titleInput,
            elements.descInput,
            elements.keywordInput,
            elements.postTitle,
            elements.postSlug,
            elements.postExcerpt
        ];

        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', debouncedUpdate);
            }
        });

        // Watch for editor content changes
        const observer = new MutationObserver(debouncedUpdate);
        const editorEl = document.querySelector('.ProseMirror, .bn-editor');
        if (editorEl) {
            observer.observe(editorEl, { childList: true, subtree: true, characterData: true });
        }

        // Also update when zero_editor_content changes
        let lastContent = null;
        setInterval(() => {
            const currentContent = JSON.stringify(window.zero_editor_content || []);
            if (currentContent !== lastContent) {
                lastContent = currentContent;
                debouncedUpdate();
            }
        }, 1000);

        // Initial render
        update();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
