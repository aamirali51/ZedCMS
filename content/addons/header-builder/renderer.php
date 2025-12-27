<?php
/**
 * Header Builder - Frontend Renderer
 * 
 * Renders the header based on saved configuration
 * 
 * @package ZedCMS\Addons\HeaderBuilder
 */

/**
 * Render the complete header from builder config
 */
function zed_render_header_builder(): string {
    $desktopConfig = zed_get_header_config('desktop');
    $mobileConfig = zed_get_header_config('mobile');
    $stickyConfig = zed_get_header_config('desktop_sticky');
    $drawerConfig = zed_get_header_config('mobile_drawer');
    
    // If no config, return empty
    if (empty($desktopConfig) && empty($mobileConfig)) {
        return '';
    }
    
    $rows = zed_header_builder_rows();
    $elementSettings = zed_get_header_element_settings();
    
    ob_start();
    ?>
    <!-- Header Builder Output -->
    <header class="hb-header bg-white dark:bg-gray-900 shadow-sm relative z-40">
        <!-- Desktop Header -->
        <div class="hb-desktop hidden lg:block">
            <?php zed_render_header_device('desktop', $desktopConfig, $rows['desktop'], $elementSettings); ?>
        </div>
        
        <!-- Mobile Header -->
        <div class="hb-mobile lg:hidden">
            <?php zed_render_header_device('mobile', $mobileConfig, $rows['mobile'], $elementSettings); ?>
        </div>
    </header>
    
    <!-- Sticky Header -->
    <?php if (!empty($stickyConfig)): ?>
    <header class="hb-sticky-header fixed top-0 left-0 right-0 bg-white dark:bg-gray-900 shadow-lg z-50 transform -translate-y-full transition-transform duration-300" id="hb-sticky">
        <div class="hidden lg:block">
            <?php zed_render_header_device('desktop_sticky', $stickyConfig, $rows['desktop_sticky'], $elementSettings); ?>
        </div>
    </header>
    <script>
    (function() {
        const sticky = document.getElementById('hb-sticky');
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const current = window.scrollY;
            if (current > 200) {
                sticky.classList.remove('-translate-y-full');
            } else {
                sticky.classList.add('-translate-y-full');
            }
            lastScroll = current;
        });
    })();
    </script>
    <?php endif; ?>
    
    <!-- Mobile Drawer -->
    <?php if (!empty($drawerConfig)): ?>
    <div id="hb-mobile-drawer" class="fixed inset-y-0 right-0 w-80 max-w-full bg-white dark:bg-gray-900 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 lg:hidden">
        <div class="flex flex-col h-full">
            <!-- Drawer Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <span class="font-semibold text-gray-900 dark:text-white">Menu</span>
                <button onclick="document.getElementById('hb-mobile-drawer').classList.add('translate-x-full')" class="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <!-- Drawer Content -->
            <div class="flex-1 overflow-y-auto p-4">
                <?php zed_render_header_device('mobile_drawer', $drawerConfig, $rows['mobile_drawer'], $elementSettings); ?>
            </div>
        </div>
    </div>
    <!-- Drawer Backdrop -->
    <div id="hb-drawer-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="document.getElementById('hb-mobile-drawer').classList.add('translate-x-full'); this.classList.add('hidden')"></div>
    <script>
    document.getElementById('hb-mobile-menu-toggle')?.addEventListener('click', () => {
        document.getElementById('hb-drawer-backdrop')?.classList.remove('hidden');
    });
    </script>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}

/**
 * Render a specific device header
 */
function zed_render_header_device(string $device, array $config, array $rows, array $elementSettings): void {
    if (empty($config)) return;
    
    foreach ($rows as $rowId => $rowConfig) {
        $rowElements = $config[$rowId] ?? [];
        $hasContent = false;
        
        // Check if row has any content
        foreach ($rowConfig['columns'] as $col) {
            if (!empty($rowElements[$col])) {
                $hasContent = true;
                break;
            }
        }
        
        if (!$hasContent) continue;
        
        // Row styles based on position
        $rowClasses = 'hb-row';
        if (in_array($rowId, ['topblock', 'top'])) {
            $rowClasses .= ' py-2 text-sm border-b border-gray-100 dark:border-gray-800';
        } elseif ($rowId === 'mid') {
            $rowClasses .= ' py-4';
        } else {
            $rowClasses .= ' py-2 border-t border-gray-100 dark:border-gray-800';
        }
        ?>
        <div class="<?= $rowClasses ?>" data-row="<?= htmlspecialchars($rowId) ?>">
            <div class="container mx-auto px-4 flex items-center">
                <?php foreach ($rowConfig['columns'] as $col): ?>
                    <?php
                    $colElements = $rowElements[$col] ?? [];
                    $alignClass = match($col) {
                        'left' => 'justify-start',
                        'right' => 'justify-end',
                        default => 'justify-center',
                    };
                    $flexClass = match($col) {
                        'center' => 'flex-none',
                        default => 'flex-1',
                    };
                    ?>
                    <div class="hb-column flex items-center gap-4 <?= $flexClass ?> <?= $alignClass ?>" data-column="<?= htmlspecialchars($col) ?>">
                        <?php foreach ($colElements as $elementId): ?>
                            <?php
                            $settings = $elementSettings[$elementId] ?? [];
                            zed_render_header_element($elementId, $settings);
                            ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Check if header builder has configuration
 */
function zed_has_header_builder(): bool {
    $config = zed_get_header_config('desktop');
    return !empty($config);
}
