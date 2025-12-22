/**
 * Zed Contact Form — Frontend JavaScript
 * 
 * Handles form submission via AJAX to the DX API endpoint.
 */

(function () {
    'use strict';

    // Find the contact form
    const form = document.getElementById('zed-contact-form');
    if (!form) return;

    const submitBtn = document.getElementById('zc-submit');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    const feedback = document.getElementById('zc-feedback');

    // Handle form submission
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        // Disable button and show loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        feedback.classList.add('hidden');

        // Collect form data
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            // Send to AJAX endpoint
            const response = await fetch('/api/ajax/zed_contact_submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            // Show feedback
            feedback.textContent = result.message;
            feedback.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');

            if (result.success) {
                feedback.classList.add('bg-green-100', 'text-green-800');
                form.reset();

                // Auto-hide success message after 8 seconds
                setTimeout(() => {
                    feedback.classList.add('hidden');
                }, 8000);
            } else {
                feedback.classList.add('bg-red-100', 'text-red-800');
            }

        } catch (error) {
            console.error('Contact form error:', error);
            feedback.textContent = 'Network error. Please check your connection and try again.';
            feedback.classList.remove('hidden', 'bg-green-100', 'text-green-800');
            feedback.classList.add('bg-red-100', 'text-red-800');
        }

        // Re-enable button
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    });

    // Real-time email validation
    const emailInput = document.getElementById('zc-email');
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

})();
