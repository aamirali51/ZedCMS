<?php

class PenciAccessibility {
	/**
	 * Initialize the class.
	 */
	public function __construct() {

		if ( ! get_theme_mod( 'penci_accessibility_enable' ) ) {
			return;
		}
		
		// Original hooks
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_head', [ $this, 'add_skiplinks' ] );
		add_filter( 'the_content', [ $this, 'remove_tabindex' ], 999 );
		add_filter( 'body_class', [ $this, 'body_class' ] );

		// Enhanced WCAG hooks
		add_action( 'wp_head', [ $this, 'add_accessibility_meta' ] );
		add_action( 'wp_head', [ $this, 'add_focus_styles' ] );
		add_action( 'wp_footer', [ $this, 'add_accessibility_javascript' ] );
		add_filter( 'nav_menu_link_attributes', [ $this, 'add_menu_link_attributes' ], 10, 3 );
		add_filter( 'wp_nav_menu_items', [ $this, 'add_skip_links_to_menu' ], 10, 2 );
		add_filter( 'the_content', [ $this, 'improve_content_accessibility' ], 998 );
		add_filter( 'wp_list_categories', [ $this, 'add_category_aria_labels' ] );
		add_filter( 'get_search_form', [ $this, 'improve_search_form' ] );
		add_filter( 'comment_form_defaults', [ $this, 'improve_comment_form' ] );
		add_filter( 'post_thumbnail_html', [ $this, 'improve_featured_image_alt' ], 10, 5 );
		add_filter( 'gallery_style', [ $this, 'remove_inline_gallery_styles' ] );
		add_filter( 'img_caption_shortcode', [ $this, 'improve_caption_accessibility' ], 10, 3 );
		add_filter( 'dynamic_sidebar_params', [ $this, 'add_widget_aria_labels' ] );

		// Conditional original features
		if ( get_theme_mod( 'penci_accessibility_img_alt', false ) ) {
			add_filter( 'the_content', array( $this, 'fix_missing_alt_tags' ) );
			add_filter( 'widget_text', array( $this, 'fix_missing_alt_tags' ) );
			add_filter( 'get_the_excerpt', array( $this, 'fix_missing_alt_tags' ) );
		}

		if ( get_theme_mod( 'penci_accessibility_title_attr', false ) ) {
			add_filter( 'the_content', array( $this, 'add_missing_title_attributes' ) );
			add_filter( 'widget_text', array( $this, 'add_missing_title_attributes' ) );
			add_filter( 'get_the_excerpt', array( $this, 'add_missing_title_attributes' ) );
		}

		if ( get_theme_mod( 'penci_accessibility_key_prext', false ) ) {
			add_action( 'wp_footer', [ $this, 'inline_keyboard_navigation' ] );
		}

		// Enhanced features with theme mod conditions
		if ( get_theme_mod( 'penci_accessibility_enhanced_nav', true ) ) {
			add_action( 'wp_footer', [ $this, 'enhanced_keyboard_navigation' ] );
		}

		if ( get_theme_mod( 'penci_accessibility_aria_live', true ) ) {
			add_action( 'wp_footer', [ $this, 'add_live_regions' ] );
		}

		if ( get_theme_mod( 'penci_accessibility_form_labels', true ) ) {
			add_filter( 'wpcf7_form_elements', [ $this, 'improve_contact_form_labels' ] );
		}
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		if ( get_theme_mod( 'penci_accessibility_skiplinks', false ) ) {
			wp_enqueue_style( 'penci-accessibility', get_template_directory_uri() . '/css/accessibility.css', [], PENCI_SOLEDAD_VERSION );
		}
		
		// Enhanced accessibility assets
		if ( get_theme_mod( 'penci_accessibility_enhanced', true ) ) {
			wp_enqueue_script( 'penci-accessibility-js', get_template_directory_uri() . '/js/accessibility.js', ['jquery'], PENCI_SOLEDAD_VERSION, true );
			wp_localize_script( 'penci-accessibility-js', 'penciA11y', [
				'skipToContent' => __( 'Skip to main content', 'soledad' ),
				'skipToFooter' => __( 'Skip to footer', 'soledad' ),
				'menuExpanded' => __( 'Menu expanded', 'soledad' ),
				'menuCollapsed' => __( 'Menu collapsed', 'soledad' ),
				'searchLabel' => __( 'Search', 'soledad' ),
				'submitSearch' => __( 'Submit search', 'soledad' ),
			]);
		}
	}

	/**
	 * Add accessibility-related meta tags
	 */
	public function add_accessibility_meta() {
		echo '<meta name="color-scheme" content="light dark">' . "\n";
		
		// Add lang attribute to html element if not present
		if ( ! has_action( 'wp_head', 'wp_generator' ) ) {
			echo '<script>document.documentElement.setAttribute("lang", "' . esc_js( get_bloginfo('language') ) . '");</script>' . "\n";
		}
	}

	/**
	 * Add skiplinks to the head.
	 */
	public function add_skiplinks() {
		if ( get_theme_mod( 'penci_accessibility_skiplinks', false ) ) {
			$class = get_theme_mod( 'penci_accessibility_skiplinks_visible', false ) ? 'skip-link' : 'screen-reader-text skip-link';
			echo '<a href="#main" class="' . $class . '" tabindex="1">' . esc_html__( 'Skip to main content', 'soledad' ) . '</a>';
			echo '<a href="javascript:void(0)" class="skip-link" onclick="document.querySelector(\'.clear-footer\')?.scrollIntoView({ behavior: \'smooth\' });" tabindex="2">' . esc_html__( 'Skip to footer', 'soledad' ) . '</a>';
		}
	}

	/**
	 * Add ARIA attributes to navigation menu links
	 */
	public function add_menu_link_attributes( $atts, $item, $args ) {
		// Ensure we have a valid classes array
		$classes = isset( $item->classes ) && is_array( $item->classes ) ? $item->classes : [];
	
		// Add aria-current for current page
		if ( in_array( 'current-menu-item', $classes, true ) ) {
			$atts['aria-current'] = 'page';
		}
	
		// Add aria-expanded and aria-haspopup for dropdown menus
		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$atts['aria-expanded'] = 'false';
			$atts['aria-haspopup'] = 'true';
		}
	
		return $atts;
	}	

	/**
	 * Add skip links to navigation menu
	 */
	public function add_skip_links_to_menu( $items, $args ) {
		if ( isset( $args->theme_location ) && $args->theme_location === 'primary' && get_theme_mod( 'penci_accessibility_skiplinks', false ) ) {
			$skip_links = '<li class="skip-link screen-reader-text menu-item">';
			$skip_links .= '<a href="#main" tabindex="1">' . esc_html__( 'Skip to main content', 'soledad' ) . '</a>';
			$skip_links .= '</li>';
			$items = $skip_links . $items;
		}
		return $items;
	}

	/**
	 * Improve content accessibility
	 */
	public function improve_content_accessibility( $content ) {
		if ( empty( $content ) ) {
			return $content;
		}

		// Add missing alt attributes to images that don't have them
		$content = preg_replace_callback(
			'/<img(?![^>]*alt=)[^>]*>/i',
			function( $matches ) {
				return str_replace( '<img', '<img alt=""', $matches[0] );
			},
			$content
		);
		
		// Improve table accessibility
		$content = preg_replace(
			'/<table(?![^>]*role=)/i',
			'<table role="table"',
			$content
		);
		
		// Add scope to table headers if missing
		$content = preg_replace(
			'/<th(?![^>]*scope=)/i',
			'<th scope="col"',
			$content
		);

		// Add role to lists if missing
		$content = preg_replace(
			'/<ul(?![^>]*role=)/i',
			'<ul role="list"',
			$content
		);

		$content = preg_replace(
			'/<ol(?![^>]*role=)/i',
			'<ol role="list"',
			$content
		);

		return $content;
	}

	/**
	 * Add ARIA labels to category lists
	 */
	public function add_category_aria_labels( $output ) {
		return str_replace( '<ul>', '<ul role="list" aria-label="' . esc_attr__( 'Categories', 'soledad' ) . '">', $output );
	}

	/**
	 * Improve search form accessibility
	 */
	public function improve_search_form( $form ) {
		// Add aria-label to search input
		$form = str_replace(
			'type="search"',
			'type="search" aria-label="' . esc_attr__( 'Search', 'soledad' ) . '"',
			$form
		);
		
		// Add aria-label to submit button
		$form = str_replace(
			'type="submit"',
			'type="submit" aria-label="' . esc_attr__( 'Submit search', 'soledad' ) . '"',
			$form
		);
		
		return $form;
	}

	/**
	 * Improve comment form accessibility
	 */
	public function improve_comment_form( $defaults ) {
		// Add aria-required and aria-describedby to required fields
		if ( isset( $defaults['fields']['author'] ) ) {
			$defaults['fields']['author'] = str_replace(
				'name="author"',
				'name="author" aria-required="true"',
				$defaults['fields']['author']
			);
		}
		
		if ( isset( $defaults['fields']['email'] ) ) {
			$defaults['fields']['email'] = str_replace(
				'name="email"',
				'name="email" aria-required="true"',
				$defaults['fields']['email']
			);
		}
		
		if ( isset( $defaults['comment_field'] ) ) {
			$defaults['comment_field'] = str_replace(
				'name="comment"',
				'name="comment" aria-required="true"',
				$defaults['comment_field']
			);
		}
		
		return $defaults;
	}

	/**
	 * Improve featured image alt text
	 */
	public function improve_featured_image_alt( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		if ( get_theme_mod( 'penci_accessibility_img_alt', false ) ) {
			$alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
			
			if ( empty( $alt ) ) {
				$post_title = get_the_title( $post_id );
				$html = str_replace( 'alt=""', 'alt="' . esc_attr( $post_title ) . '"', $html );
			}
		}
		
		return $html;
	}

	/**
	 * Remove inline gallery styles
	 */
	public function remove_inline_gallery_styles() {
		return '';
	}

	/**
	 * Improve caption accessibility with proper figure/figcaption structure
	 */
	public function improve_caption_accessibility( $empty, $attr, $content ) {
		$id = isset( $attr['id'] ) ? 'attachment_' . $attr['id'] : '';
		$align = isset( $attr['align'] ) ? 'align' . $attr['align'] : '';
		$width = isset( $attr['width'] ) ? (int) $attr['width'] : '';
		$caption = isset( $attr['caption'] ) ? $attr['caption'] : '';
		
		if ( empty( $caption ) ) {
			return '';
		}
		
		$html = '<figure';
		if ( $id ) {
			$html .= ' id="' . esc_attr( $id ) . '"';
		}
		$html .= ' class="wp-caption ' . esc_attr( $align ) . '"';
		if ( $width ) {
			$html .= ' style="width: ' . $width . 'px"';
		}
		$html .= '>';
		
		$html .= do_shortcode( $content );
		$html .= '<figcaption class="wp-caption-text">' . $caption . '</figcaption>';
		$html .= '</figure>';
		
		return $html;
	}

	/**
	 * Add ARIA labels to widgets
	 */
	public function add_widget_aria_labels( $params ) {
		if ( isset( $params[0]['widget_name'] ) ) {
			$widget_name = $params[0]['widget_name'];
			$params[0]['before_widget'] = str_replace(
				'class="widget',
				'role="complementary" aria-label="' . esc_attr( $widget_name ) . '" class="widget',
				$params[0]['before_widget']
			);
		}
		return $params;
	}

	/**
	 * Add comprehensive focus styles and accessibility CSS
	 */
	public function add_focus_styles() {
		?>
		<style id="penci-accessibility-styles">
		/* Screen reader text utility */
		.screen-reader-text {
			border: 0;
			clip: rect(1px, 1px, 1px, 1px);
			clip-path: inset(50%);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute !important;
			width: 1px;
			word-wrap: normal !important;
		}
		
		.screen-reader-text:focus {
			background-color: #f1f1f1;
			border-radius: 3px;
			box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
			clip: auto !important;
			clip-path: none;
			color: #21759b;
			display: block;
			font-size: 14px;
			font-weight: bold;
			height: auto;
			left: 5px;
			line-height: normal;
			padding: 15px 23px 14px;
			text-decoration: none;
			top: 5px;
			width: auto;
			z-index: 100000;
		}
		
		/* Enhanced focus styles */
		<?php if ( get_theme_mod( 'penci_accessibility_focus', false ) ) : ?>
		.pc-acc-f a:focus,
		.pc-acc-f button:focus,
		.pc-acc-f input:focus,
		.pc-acc-f textarea:focus,
		.pc-acc-f select:focus,
		.pc-acc-f [tabindex]:focus {
			outline: 2px solid #005fcc !important;
			outline-offset: 2px !important;
			box-shadow: 0 0 0 2px rgba(0, 95, 204, 0.3) !important;
		}
		<?php endif; ?>
		
		/* Link underlines */
		<?php if ( get_theme_mod( 'penci_accessibility_underline', false ) ) : ?>
		.pc-acc-u a {
			text-decoration: underline !important;
		}
		<?php endif; ?>
		
		/* Disable animations */
		<?php if ( get_theme_mod( 'penci_accessibility_animate', false ) ) : ?>
		.pc-acc-animate-off *,
		.pc-acc-animate-off *::before,
		.pc-acc-animate-off *::after {
			animation-duration: 0.01ms !important;
			animation-iteration-count: 1 !important;
			transition-duration: 0.01ms !important;
			scroll-behavior: auto !important;
		}
		<?php endif; ?>
		
		/* Skip link positioning */
		.skip-link {
			position: absolute;
			left: -9999px;
			z-index: 999999;
		}
		
		.skip-link:focus,
		.skip-link:active {
			position: fixed;
			top: 10px;
			left: 10px;
			background: #000;
			color: #fff;
			padding: 8px 16px;
			text-decoration: none;
			border-radius: 3px;
		}
		
		/* High contrast mode support */
		@media (prefers-contrast: high) {
			* {
				border-color: ButtonText !important;
			}
		}
		
		/* Reduced motion support */
		@media (prefers-reduced-motion: reduce) {
			*,
			*::before,
			*::after {
				animation-duration: 0.01ms !important;
				animation-iteration-count: 1 !important;
				transition-duration: 0.01ms !important;
				scroll-behavior: auto !important;
			}
		}
		
		/* Live region for screen reader announcements */
		#penci-live-region {
			position: absolute;
			left: -10000px;
			width: 1px;
			height: 1px;
			overflow: hidden;
		}
		</style>
		<?php
	}

	/**
	 * Add comprehensive accessibility JavaScript
	 */
	public function add_accessibility_javascript() {
		if ( ! get_theme_mod( 'penci_accessibility_enhanced', true ) ) {
			return;
		}
		?>
		<script id="penci-accessibility-js">
		document.addEventListener('DOMContentLoaded', function() {
			// Keyboard navigation for dropdowns
			const menuItems = document.querySelectorAll('.menu-item-has-children > a');
			
			menuItems.forEach(function(item) {
				item.addEventListener('keydown', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						const expanded = this.getAttribute('aria-expanded') === 'true';
						this.setAttribute('aria-expanded', !expanded);
						const submenu = this.nextElementSibling;
						if (submenu && submenu.classList.contains('sub-menu')) {
							submenu.style.display = expanded ? 'none' : 'block';
						}
						// Announce to screen readers
						announceToScreenReader(expanded ? 
							'<?php echo esc_js( __( 'Menu collapsed', 'soledad' ) ); ?>' : 
							'<?php echo esc_js( __( 'Menu expanded', 'soledad' ) ); ?>'
						);
					}
				});
			});
			
			// Focus management for modals and popups
			const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
			
			function trapFocus(element) {
				const focusableContent = element.querySelectorAll(focusableElements);
				const firstFocusable = focusableContent[0];
				const lastFocusable = focusableContent[focusableContent.length - 1];
				
				element.addEventListener('keydown', function(e) {
					if (e.key === 'Tab') {
						if (e.shiftKey) {
							if (document.activeElement === firstFocusable) {
								lastFocusable.focus();
								e.preventDefault();
							}
						} else {
							if (document.activeElement === lastFocusable) {
								firstFocusable.focus();
								e.preventDefault();
							}
						}
					}
					
					// Escape key to close modal
					if (e.key === 'Escape') {
						const closeButton = element.querySelector('[data-dismiss], .close, .modal-close');
						if (closeButton) {
							closeButton.click();
						}
					}
				});
			}
			
			// Apply focus trap to modals
			document.querySelectorAll('.modal, .popup, .overlay').forEach(trapFocus);
			
			// Announce dynamic content changes to screen readers
			function announceToScreenReader(message) {
				const liveRegion = document.getElementById('penci-live-region');
				if (liveRegion) {
					liveRegion.textContent = message;
					setTimeout(function() {
						liveRegion.textContent = '';
					}, 1000);
				}
			}
			
			// Improve image loading announcements
			const images = document.querySelectorAll('img[data-src], img[loading="lazy"]');
			images.forEach(function(img) {
				img.addEventListener('load', function() {
					if (!this.alt) {
						this.alt = '<?php echo esc_js( __( 'Image loaded', 'soledad' ) ); ?>';
					}
				});
			});
			
			// Enhanced keyboard navigation for custom elements
			document.addEventListener('keydown', function(e) {
				// Handle custom dropdown toggles
				if (e.target.matches('[aria-expanded]') && (e.key === 'Enter' || e.key === ' ')) {
					e.preventDefault();
					e.target.click();
				}
				
				// Escape key handling for dropdowns
				if (e.key === 'Escape') {
					const openDropdowns = document.querySelectorAll('[aria-expanded="true"]');
					openDropdowns.forEach(function(dropdown) {
						dropdown.setAttribute('aria-expanded', 'false');
						dropdown.focus();
					});
				}
			});
			
			// Form validation announcements
			const forms = document.querySelectorAll('form');
			forms.forEach(function(form) {
				form.addEventListener('submit', function(e) {
					const requiredFields = form.querySelectorAll('[required], [aria-required="true"]');
					let hasErrors = false;
					
					requiredFields.forEach(function(field) {
						if (!field.value.trim()) {
							hasErrors = true;
							field.setAttribute('aria-invalid', 'true');
						} else {
							field.removeAttribute('aria-invalid');
						}
					});
					
					if (hasErrors) {
						announceToScreenReader('<?php echo esc_js( __( 'Please fill in all required fields', 'soledad' ) ); ?>');
					}
				});
			});
		});
		</script>
		<?php
	}

	/**
	 * Add live regions for screen reader announcements
	 */
	public function add_live_regions() {
		echo '<div id="penci-live-region" aria-live="polite" aria-atomic="true" class="screen-reader-text"></div>';
	}

	/**
	 * Enhanced keyboard navigation for post navigation
	 */
	public function enhanced_keyboard_navigation() {
		if ( is_single() ) {
			$prev_post = get_previous_post();
			$next_post = get_next_post();

			if ( $prev_post || $next_post ) {
				?>
				<script>
				jQuery(document).ready(function ($) {
					var prevUrl = <?php echo $prev_post ? '"' . esc_js( get_permalink( $prev_post->ID ) ) . '"' : 'null'; ?>;
					var nextUrl = <?php echo $next_post ? '"' . esc_js( get_permalink( $next_post->ID ) ) . '"' : 'null'; ?>;

					$(document).keydown(function (e) {
						// Ignore if user is typing in input fields or if alt/ctrl/meta keys are pressed
						if ($(e.target).is('input, textarea, select, [contenteditable]') || 
							e.altKey || e.ctrlKey || e.metaKey) {
							return;
						}

						switch (e.which) {
							case 37: // Left arrow
								if (prevUrl) {
									window.location.href = prevUrl;
								}
								break;
							case 39: // Right arrow
								if (nextUrl) {
									window.location.href = nextUrl;
								}
								break;
							case 72: // H key - go to homepage
								if (e.shiftKey) {
									window.location.href = '<?php echo esc_js( home_url( '/' ) ); ?>';
								}
								break;
						}
					});
				});
				</script>
				<?php
			}
		}
	}

	/**
	 * Improve Contact Form 7 accessibility
	 */
	public function improve_contact_form_labels( $form ) {
		// Add aria-required to required fields
		$form = str_replace(
			'[text* ',
			'[text* aria-required="true" ',
			$form
		);
		
		$form = str_replace(
			'[email* ',
			'[email* aria-required="true" ',
			$form
		);
		
		$form = str_replace(
			'[textarea* ',
			'[textarea* aria-required="true" ',
			$form
		);
		
		return $form;
	}

	// ========== ORIGINAL METHODS (Enhanced) ==========

	public function remove_tabindex( $content ) {
		if ( get_theme_mod( 'penci_accessibility_rm_tabindex', false ) ) {
			return preg_replace( '/tabindex="[^"]*"/', '', $content );
		}

		return $content;
	}

	public function body_class( $classes ) {
		if ( get_theme_mod( 'penci_accessibility_underline', false ) ) {
			$classes[] = 'pc-acc-u';
		}
		if ( get_theme_mod( 'penci_accessibility_focus', false ) ) {
			$classes[] = 'pc-acc-f';
		}
		if ( get_theme_mod( 'penci_accessibility_animate', false ) ) {
			$classes[] = 'pc-acc-animate-off';
		}
		
		// Add general accessibility class
		if ( get_theme_mod( 'penci_accessibility_enhanced', true ) ) {
			$classes[] = 'pc-acc-enhanced';
		}

		return $classes;
	}

	public function fix_missing_alt_tags( $content ) {
		if ( empty( $content ) ) {
			return $content;
		}

		// Use DOMDocument for proper HTML parsing
		$dom = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();

		$images   = $dom->getElementsByTagName( 'img' );
		$modified = false;

		foreach ( $images as $img ) {
			if ( ! $img->hasAttribute( 'alt' ) ) {
				$alt_text = $this->generate_alt_text( $img );
				$img->setAttribute( 'alt', $alt_text );
				$modified = true;
			}
		}

		if ( $modified ) {
			return $dom->saveHTML();
		}

		return $content;
	}

	public function generate_alt_text( $img ) {
		// Try to get alt text from various sources
		$alt_sources = array(
			$img->getAttribute( 'title' ),
			$img->getAttribute( 'data-caption' ),
			$this->get_filename_alt( $img->getAttribute( 'src' ) ),
			__( 'Image', 'soledad' ) // Fallback with translation
		);

		foreach ( $alt_sources as $alt ) {
			if ( ! empty( trim( $alt ) ) ) {
				return $this->clean_alt_text( $alt );
			}
		}

		return __( 'Image', 'soledad' );
	}

	public function get_filename_alt( $src ) {
		if ( empty( $src ) ) {
			return '';
		}

		$filename = basename( $src );
		$name     = pathinfo( $filename, PATHINFO_FILENAME );

		// Clean up filename to make it readable
		$name = str_replace( array( '-', '_' ), ' ', $name );
		$name = preg_replace( '/[0-9]+/', '', $name ); // Remove numbers
		$name = trim( $name );

		return ucwords( $name );
	}

	public function clean_alt_text( $alt ) {
		$alt = strip_tags( $alt );
		$alt = html_entity_decode( $alt );
		$alt = trim( $alt );
		$alt = substr( $alt, 0, 125 ); // Keep alt text reasonable length

		return $alt;
	}

	public function add_missing_title_attributes( $content ) {
		// Add title attributes to img tags
		$content = $this->add_title_to_images( $content );

		// Add title attributes to anchor tags
		$content = $this->add_title_to_links( $content );

		return $content;
	}

	public function add_title_to_images( $content ) {
		// Pattern to match img tags without title attribute
		$pattern = '/<img([^>]*?)(?<!title=)(?!\s*title\s*=)([^>]*?)>/i';

		return preg_replace_callback( $pattern, function ( $matches ) {
			$img_tag = $matches[0];

			// Extract alt text if exists
			preg_match( '/alt=["\']([^"\']*)["\']/', $img_tag, $alt_matches );
			$alt_text = isset( $alt_matches[1] ) ? $alt_matches[1] : '';

			// Extract src for filename fallback
			preg_match( '/src=["\']([^"\']*)["\']/', $img_tag, $src_matches );
			$src = isset( $src_matches[1] ) ? $src_matches[1] : '';

			// Determine title text
			$title_text = '';
			if ( ! empty( $alt_text ) ) {
				$title_text = $alt_text;
			} elseif ( ! empty( $src ) ) {
				// Use filename without extension as fallback
				$filename   = pathinfo( $src, PATHINFO_FILENAME );
				$title_text = ucwords( str_replace( [ '-', '_' ], ' ', $filename ) );
			}

			// Add title attribute if we have text
			if ( ! empty( $title_text ) ) {
				$title_text = esc_attr( $title_text );
				// Insert title attribute before the closing >
				$img_tag = str_replace( '>', ' title="' . $title_text . '">', $img_tag );
			}

			return $img_tag;
		}, $content );
	}

	public function add_title_to_links( $content ) {
		// Pattern to match anchor tags without title attribute
		$pattern = '/<a([^>]*?)(?<!title=)(?!\s*title\s*=)([^>]*?)>(.*?)<\/a>/i';

		return preg_replace_callback( $pattern, function ( $matches ) {
			$full_match      = $matches[0];
			$link_attributes = $matches[1] . $matches[2];
			$link_text       = $matches[3];

			// Extract href for external link detection
			preg_match( '/href=["\']([^"\']*)["\']/', $full_match, $href_matches );
			$href = isset( $href_matches[1] ) ? $href_matches[1] : '';

			// Determine title text
			$title_text = '';

			// Strip HTML tags from link text for title
			$clean_link_text = strip_tags( $link_text );
			$clean_link_text = trim( $clean_link_text );

			if ( ! empty( $clean_link_text ) ) {
				$title_text = $clean_link_text;
			} elseif ( ! empty( $href ) ) {
				// For empty link text, use URL as fallback
				$title_text = __( 'Link to: ', 'soledad' ) . $href;
			}

			// Add additional context for external links
			if ( ! empty( $href ) && $this->is_external_link( $href ) ) {
				$title_text .= ' ' . __( '(opens in new window)', 'soledad' );
			}

			// Add title attribute if we have text
			if ( ! empty( $title_text ) ) {
				$title_text = esc_attr( $title_text );
				$new_link   = '<a' . $link_attributes . ' title="' . $title_text . '">' . $link_text . '</a>';

				return $new_link;
			}

			return $full_match;
		}, $content );
	}

	public function is_external_link( $url ) {
		$site_url    = home_url();
		$site_domain = parse_url( $site_url, PHP_URL_HOST );
		$link_domain = parse_url( $url, PHP_URL_HOST );

		// If no domain in link (relative URL), it's internal
		if ( empty( $link_domain ) ) {
			return false;
		}

		return $site_domain !== $link_domain;
	}

	public function inline_keyboard_navigation() {
		if ( is_single() ) {
			$prev_post = get_previous_post();
			$next_post = get_next_post();

			if ( $prev_post || $next_post ) {
				?>
				<script>
				jQuery(document).ready(function ($) {
					var prevUrl = <?php echo $prev_post ? '"' . esc_js( get_permalink( $prev_post->ID ) ) . '"' : 'null'; ?>;
					var nextUrl = <?php echo $next_post ? '"' . esc_js( get_permalink( $next_post->ID ) ) . '"' : 'null'; ?>;

					$(document).keydown(function (e) {
						// Ignore if user is typing in input fields
						if ($(e.target).is('input, textarea, select')) {
							return;
						}

						switch (e.which) {
							case 37: // Left arrow
								if (prevUrl) {
									window.location.href = prevUrl;
								}
								break;
							case 39: // Right arrow
								if (nextUrl) {
									window.location.href = nextUrl;
								}
								break;
						}
					});
				});
				</script>
				<?php
			}
		}
	}

} // End of class PenciAccessibility

// Initialize the accessibility class
new PenciAccessibility();