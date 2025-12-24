<?php
/**
 * Test Addon for Admin Menu Registration API
 * 
 * This addon demonstrates all features of the Admin Menu Registration API:
 * - Top-level menu registration
 * - Submenu registration
 * - Custom capability registration
 * - Automatic route handling
 * - Permission checks
 */

// DEBUG: Log that this file is being loaded
error_log('[TEST_MENU_API] File loaded at ' . date('Y-m-d H:i:s'));

// Register custom capabilities
zed_register_capabilities([
    'manage_test_addon' => 'Manage Test Addon',
    'view_test_logs' => 'View Test Logs',
]);

error_log('[TEST_MENU_API] Capabilities registered');

// Register top-level menu
$result = zed_register_admin_menu([
    'id' => 'test_addon',
    'title' => 'Test Addon',
    'icon' => 'science',  // Material Symbols icon
    'capability' => 'manage_options',  // Use standard capability so admins can see it
    'position' => 55,  // Between system menus and settings
    'badge' => '3',  // Optional badge
    'callback' => function($vars) {
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Test Addon Dashboard</h1>
                <p class="text-gray-600 mb-4">
                    This page demonstrates the Admin Menu Registration API. This addon was registered without modifying any core files!
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-blue-600 text-3xl">check_circle</span>
                            <div>
                                <div class="font-semibold text-blue-900">Menu Registered</div>
                                <div class="text-sm text-blue-700">Via zed_register_admin_menu()</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600 text-3xl">route</span>
                            <div>
                                <div class="font-semibold text-green-900">Auto-Routed</div>
                                <div class="text-sm text-green-700">No core file modifications</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-purple-600 text-3xl">security</span>
                            <div>
                                <div class="font-semibold text-purple-900">Permission Checked</div>
                                <div class="text-sm text-purple-700">Capability: manage_test_addon</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">API Features Demonstrated</h2>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">check</span>
                        <span><strong>Top-level menu:</strong> "Test Addon" in sidebar</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">check</span>
                        <span><strong>Submenus:</strong> "Logs" and "Settings" pages</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">check</span>
                        <span><strong>Custom capabilities:</strong> manage_test_addon, view_test_logs</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">check</span>
                        <span><strong>Badge support:</strong> Red badge showing "3"</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">check</span>
                        <span><strong>Auto-routing:</strong> /admin/test_addon works automatically</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-green-600 mt-0.5">check</span>
                        <span><strong>Admin layout:</strong> Wrapped in standard admin theme</span>
                    </li>
                </ul>
            </div>
        </div>
        <?php
    }
]);

error_log('[TEST_MENU_API] Menu registered, result: ' . ($result ? 'true' : 'false'));
global $ZED_ADMIN_MENUS;
error_log('[TEST_MENU_API] Total menus in registry: ' . count($ZED_ADMIN_MENUS));

// Register submenus
zed_register_admin_submenu('test_addon', [
    'id' => 'test_addon_logs',
    'title' => 'Logs',
    'capability' => 'view_test_logs',
    'position' => 10,
    'callback' => function($vars) {
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Test Addon Logs</h1>
                <p class="text-gray-600 mb-6">This is a submenu page registered via zed_register_admin_submenu()</p>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="font-mono text-sm space-y-1">
                        <div class="text-gray-600">[2025-01-24 12:00:00] INFO: Addon initialized</div>
                        <div class="text-gray-600">[2025-01-24 12:00:01] INFO: Menu registered successfully</div>
                        <div class="text-green-600">[2025-01-24 12:00:02] SUCCESS: All features working!</div>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        <div class="text-sm text-blue-900">
                            <strong>Note:</strong> This page has a different capability requirement (view_test_logs) than the main page.
                            This demonstrates granular permission control.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
]);

zed_register_admin_submenu('test_addon', [
    'id' => 'test_addon_settings',
    'title' => 'Settings',
    'capability' => 'manage_test_addon',
    'position' => 20,
    'callback' => function($vars) {
        ?>
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Test Addon Settings</h1>
                <p class="text-gray-600 mb-6">Another submenu demonstrating the API</p>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sample Setting</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter value...">
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Enable feature</span>
                        </label>
                    </div>
                    
                    <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Save Settings
                    </button>
                </form>
            </div>
        </div>
        <?php
    }
]);
