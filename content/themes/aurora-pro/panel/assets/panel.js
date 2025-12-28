/**
 * Aurora Pro - Theme Panel JavaScript
 * Handles tab switching, live preview, import/export
 */

document.addEventListener('DOMContentLoaded', function () {
    initTabs();
    initColorSync();
    initRangeSync();
    initLayoutSelector();
});

/**
 * Tab Navigation
 */
function initTabs() {
    const tabs = document.querySelectorAll('.panel-tab');
    const sections = document.querySelectorAll('.panel-section');
    const activeTabInput = document.getElementById('aurora_active_tab');

    // Restore active tab from hidden field (after form submit)
    const savedTab = activeTabInput ? activeTabInput.value : 'general';

    // Activate saved tab on load
    if (savedTab && savedTab !== 'general') {
        const targetTab = document.querySelector(`.panel-tab[data-section="${savedTab}"]`);
        const targetSection = document.querySelector(`.panel-section[data-section="${savedTab}"]`);

        if (targetTab && targetSection) {
            tabs.forEach(t => t.classList.remove('active'));
            sections.forEach(s => s.classList.add('hidden'));

            targetTab.classList.add('active');
            targetSection.classList.remove('hidden');
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetSection = tab.dataset.section;

            // Save active tab to hidden field
            if (activeTabInput) {
                activeTabInput.value = targetSection;
            }

            // Update tabs
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Update sections
            sections.forEach(section => {
                if (section.dataset.section === targetSection) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });

            // Scroll to top of main content
            document.querySelector('main').scrollTop = 0;
        });
    });
}

/**
 * Sync color picker with text input
 */
function initColorSync() {
    document.querySelectorAll('.aurora-color').forEach(colorInput => {
        const textInput = colorInput.nextElementSibling;

        colorInput.addEventListener('input', (e) => {
            textInput.value = e.target.value;
        });
    });
}

/**
 * Sync range slider with display value
 */
function initRangeSync() {
    document.querySelectorAll('.aurora-range').forEach(range => {
        const display = range.nextElementSibling;

        range.addEventListener('input', (e) => {
            display.textContent = e.target.value + 'px';
        });
    });
}

/**
 * Layout selector toggle
 */
function initLayoutSelector() {
    document.querySelectorAll('.layout-option').forEach(option => {
        option.addEventListener('click', () => {
            const parent = option.parentElement;
            parent.querySelectorAll('.layout-option').forEach(o => o.classList.remove('active'));
            option.classList.add('active');
            option.querySelector('input').checked = true;
        });
    });
}

/**
 * Export settings to JSON file
 */
function exportSettings() {
    const form = document.getElementById('settings-form');
    const formData = new FormData(form);
    const settings = {};

    formData.forEach((value, key) => {
        if (key !== 'aurora_save') {
            settings[key] = value;
        }
    });

    const blob = new Blob([JSON.stringify(settings, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = 'aurora-pro-settings-' + new Date().toISOString().split('T')[0] + '.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

/**
 * Import settings from JSON file
 */
function importSettings(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        try {
            const settings = JSON.parse(e.target.result);
            const form = document.getElementById('settings-form');

            Object.entries(settings).forEach(([key, value]) => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = value === '1' || value === true;
                    } else if (field.type === 'radio') {
                        const radio = form.querySelector(`[name="${key}"][value="${value}"]`);
                        if (radio) radio.checked = true;
                    } else {
                        field.value = value;
                    }
                }
            });

            // Trigger change events for syncing
            form.querySelectorAll('.aurora-color').forEach(input => {
                input.dispatchEvent(new Event('input'));
            });
            form.querySelectorAll('.aurora-range').forEach(input => {
                input.dispatchEvent(new Event('input'));
            });

            // Update layout selector visual state
            form.querySelectorAll('.layout-option').forEach(option => {
                option.classList.remove('active');
                if (option.querySelector('input:checked')) {
                    option.classList.add('active');
                }
            });

            alert('Settings imported successfully! Click "Save Changes" to apply.');
        } catch (err) {
            alert('Error importing settings: Invalid JSON file');
        }
    };
    reader.readAsText(file);
    input.value = ''; // Reset input
}

/**
 * Show notification
 */
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'
        } text-white animate-slide-up`;

    notification.innerHTML = `
        <span class="material-symbols-outlined">${type === 'success' ? 'check_circle' : 'error'}</span>
        ${message}
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(10px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
