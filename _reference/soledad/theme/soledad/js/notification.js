(function($) {
    'use strict';

    /**
     * Push Notification Handler for OneSignal integration
     * Handles subscription, unsubscription, and category-based notifications
     */
    const PushNotificationManager = {
        
        /**
         * Initialize the push notification system
         */
        init() {
            this.bindEvents();
            this.initializeExistingNotifications();
        },

        /**
         * Bind event listeners to notification elements
         */
        bindEvents() {
            $(document).on('click', '.penci_push_notification .button', (event) => {
                event.preventDefault();
                this.handleButtonClick($(event.currentTarget));
            });
        },

        /**
         * Initialize existing notification containers
         */
        initializeExistingNotifications() {
            $('.penci_push_notification').each((index, element) => {
                const $container = $(element);
                this.setupNotificationContainer($container);
            });
        },

        /**
         * Setup individual notification container
         * @param {jQuery} $container - The notification container element
         */
        setupNotificationContainer($container) {
            try {
                $container.addClass('loading');
                
                // Check if OneSignal is available
                if (!window.OneSignal) {
                    console.error('OneSignal is not loaded');
                    this.showError($container, 'Push notifications not available');
                    return;
                }

                // Use OneSignal.push to safely interact with the API
                OneSignal.push(() => {
                    console.log('OneSignal User API available:', {
                        User: typeof OneSignal.User,
                        addTag: OneSignal.User ? typeof OneSignal.User.addTag : 'undefined',
                        addTags: OneSignal.User ? typeof OneSignal.User.addTags : 'undefined',
                        removeTag: OneSignal.User ? typeof OneSignal.User.removeTag : 'undefined',
                        removeTags: OneSignal.User ? typeof OneSignal.User.removeTags : 'undefined',
                        getTags: OneSignal.User ? typeof OneSignal.User.getTags : 'undefined'
                    });

                    // Check if user is subscribed using the User API
                    if (OneSignal.User && typeof OneSignal.User.getTags === 'function') {
                        const tags = OneSignal.User.getTags();
                        console.log('User tags:', tags);
                        
                        // Check if user has any subscription (has tags or is subscribed)
                        if (Object.keys(tags || {}).length > 0) {
                            this.handleExistingSubscription($container, tags);
                        } else {
                            this.setButtonState($container, 'register');
                        }
                    } else {
                        console.error('OneSignal User API not available');
                        this.showError($container, 'OneSignal User API not ready');
                    }
                    
                    $container.removeClass('loading');
                });

            } catch (error) {
                console.error('Error setting up notification container:', error);
                this.showError($container, 'Error initializing notifications');
                $container.removeClass('loading');
            }
        },

        /**
         * Handle existing subscription state
         * @param {jQuery} $container - The notification container
         * @param {Object} tags - User's current tags
         */
        handleExistingSubscription($container, tags = null) {
            OneSignal.push(() => {
                // Get tags if not provided
                const userTags = tags || OneSignal.User.getTags();
                const notificationType = $container.find('.button').attr('data-type');

                if (Object.keys(userTags || {}).length > 0) {
                    if (notificationType === 'category') {
                        const requiredCategories = this.getRequiredCategories($container);
                        const hasAllCategories = requiredCategories.every(category => 
                            userTags && userTags.hasOwnProperty(category)
                        );

                        this.setButtonState($container, hasAllCategories ? 'unsubscribe' : 'subscribe');
                    } else {
                        // General notifications - check if 'all' tag exists
                        const hasGeneralSubscription = userTags && userTags.hasOwnProperty('all');
                        this.setButtonState($container, hasGeneralSubscription ? 'unsubscribe' : 'subscribe');
                    }
                } else {
                    // No tags found, show subscribe option
                    this.setButtonState($container, notificationType === 'category' ? 'subscribe' : 'subscribe');
                }
            });
        },

        /**
         * Handle button click events
         * @param {jQuery} $button - The clicked button
         */
        async handleButtonClick($button) {
            const $container = $button.closest('.penci_push_notification');
            const action = $button.attr('data-action');
            const type = $button.attr('data-type');

            if ($container.hasClass('processing')) {
                return; // Prevent multiple clicks
            }

            $container.addClass('processing');

            try {
                if (type === 'general') {
                    await this.handleGeneralNotification($container, action);
                } else if (type === 'category') {
                    await this.handleCategoryNotification($container, action);
                }
            } catch (error) {
                console.error('Error handling button click:', error);
                this.showError($container, 'Operation failed. Please try again.');
            } finally {
                setTimeout(() => {
                    $container.removeClass('processing');
                }, 600);
            }
        },

        /**
         * Handle general notification actions
         * @param {jQuery} $container - The notification container
         * @param {string} action - The action to perform
         */
        async handleGeneralNotification($container, action) {
            switch (action) {
                case 'register':
                    await this.registerForNotifications($container);
                    await this.subscribeToGeneral($container);
                    break;
                case 'subscribe':
                    await this.subscribeToGeneral($container);
                    break;
                case 'unsubscribe':
                    await this.unsubscribeFromAll($container);
                    break;
            }
        },

        /**
         * Handle category-specific notification actions
         * @param {jQuery} $container - The notification container
         * @param {string} action - The action to perform
         */
        async handleCategoryNotification($container, action) {
            const categories = this.getRequiredCategories($container);

            switch (action) {
                case 'register':
                    await this.registerForNotifications($container);
                    await this.subscribeToCategories($container, categories);
                    break;
                case 'subscribe':
                    await this.subscribeToCategories($container, categories);
                    break;
                case 'unsubscribe':
                    await this.unsubscribeFromCategories($container, categories);
                    break;
            }
        },

        /**
         * Register for push notifications
         * @param {jQuery} $container - The notification container
         */
        registerForNotifications($container) {
            return new Promise((resolve, reject) => {
                OneSignal.push(() => {
                    // Use the newer Slidedown or Native prompt
                    if (typeof OneSignal.showSlidedownPrompt === 'function') {
                        OneSignal.showSlidedownPrompt();
                    } else if (typeof OneSignal.showNativePrompt === 'function') {
                        OneSignal.showNativePrompt();
                    } else {
                        // Fallback to older method
                        //OneSignal.registerForPushNotifications();
                    }
                    
                    // Resolve after a short delay to allow registration to process
                    setTimeout(() => {
                        resolve();
                    }, 1000);
                });
            });
        },

        /**
         * Subscribe to general notifications
         * @param {jQuery} $container - The notification container
         */
        subscribeToGeneral($container) {
            return new Promise((resolve, reject) => {
                OneSignal.push(() => {
                    try {
                        // Use the new User API to add tag
                        OneSignal.User.addTag('all', 'all');
                        console.log('General subscription tag added');
                        this.setButtonState($container, 'unsubscribe');
                        resolve();
                    } catch (error) {
                        console.error('Error adding general tag:', error);
                        reject(error);
                    }
                });
            });
        },

        /**
         * Subscribe to category notifications
         * @param {jQuery} $container - The notification container
         * @param {Array} categories - Categories to subscribe to
         */
        subscribeToCategories($container, categories) {
            return new Promise((resolve, reject) => {
                OneSignal.push(() => {
                    try {
                        // Remove general subscription first
                        OneSignal.User.removeTag('all');
                        console.log('Removed general tag');
                        
                        // Add category tags using the new User API
                        const categoryTags = {};
                        categories.forEach(category => {
                            categoryTags[category] = category;
                        });
                        
                        OneSignal.User.addTags(categoryTags);
                        console.log('Category tags added:', categoryTags);
                        this.setButtonState($container, 'unsubscribe');
                        resolve();
                    } catch (error) {
                        console.error('Error adding category tags:', error);
                        reject(error);
                    }
                });
            });
        },

        /**
         * Unsubscribe from all notifications
         * @param {jQuery} $container - The notification container
         */
        unsubscribeFromAll($container) {
            return new Promise((resolve, reject) => {
                OneSignal.push(() => {
                    try {
                        const userTags = OneSignal.User.getTags();
                        if (userTags && Object.keys(userTags).length > 0) {
                            const tagKeys = Object.keys(userTags);
                            OneSignal.User.removeTags(tagKeys);
                            console.log('Removed all tags:', tagKeys);
                        }
                        this.setButtonState($container, 'subscribe');
                        resolve();
                    } catch (error) {
                        console.error('Error removing all tags:', error);
                        reject(error);
                    }
                });
            });
        },

        /**
         * Unsubscribe from specific categories
         * @param {jQuery} $container - The notification container
         * @param {Array} categories - Categories to unsubscribe from
         */
        unsubscribeFromCategories($container, categories) {
            return new Promise((resolve, reject) => {
                OneSignal.push(() => {
                    try {
                        OneSignal.User.removeTags(categories);
                        console.log('Removed category tags:', categories);
                        this.setButtonState($container, 'subscribe');
                        resolve();
                    } catch (error) {
                        console.error('Error removing category tags:', error);
                        reject(error);
                    }
                });
            });
        },

        /**
         * Get required categories from container
         * @param {jQuery} $container - The notification container
         * @returns {Array} Array of category names
         */
        getRequiredCategories($container) {
            const categoryString = $container.find('input[name="post-category"]').val();
            return categoryString ? categoryString.split(',').map(cat => cat.trim()) : [];
        },

        /**
         * Set button state and appearance
         * @param {jQuery} $container - The notification container
         * @param {string} state - The state to set (register, subscribe, unsubscribe)
         */
        setButtonState($container, state) {
            const $button = $container.find('.button');
            const processingText = $container.find('input[name="button-processing"]').val() || 'Processing...';
            const subscribeText = $container.find('input[name="button-subscribe"]').val() || 'Subscribe';
            const unsubscribeText = $container.find('input[name="button-unsubscribe"]').val() || 'Unsubscribe';

            // Show processing state first
            $button.html(`<i class="fa fa-refresh fa-spin"></i> ${processingText}`);

            setTimeout(() => {
                switch (state) {
                    case 'subscribe':
                    case 'register':
                        $button.attr('data-action', state);
                        $button.html(`<i class="fa fa-bell"></i> ${subscribeText}`);
                        break;
                    case 'unsubscribe':
                        $button.attr('data-action', 'unsubscribe');
                        $button.html(`<i class="fa fa-bell"></i> ${unsubscribeText}`);
                        break;
                }
            }, 500);
        },

        /**
         * Show error message
         * @param {jQuery} $container - The notification container
         * @param {string} message - Error message to display
         */
        showError($container, message) {
            const $button = $container.find('.button');
            $button.html(`<i class="fa fa-exclamation-triangle"></i> ${message}`);
            $button.addClass('error');
            
            setTimeout(() => {
                $button.removeClass('error');
                this.setButtonState($container, 'subscribe');
            }, 3000);
        }
    };

    // Initialize when document is ready
    $(document).ready(() => {
        PushNotificationManager.init();
    });

    // Export for global access if needed
    window.PENCI = window.PENCI || {};
    window.PENCI.PushNotificationManager = PushNotificationManager;

})(jQuery);