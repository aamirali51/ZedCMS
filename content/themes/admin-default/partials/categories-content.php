<?php
/**
 * Categories Manager Partial
 */
use Core\Router;
?>
<div class="grid grid-cols-12 gap-6">
    <!-- List -->
    <div class="col-span-12 md:col-span-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-gray-400">category</span>
                    All Categories
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Slug</th>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="text-gray-400 mb-2">
                                    <span class="material-symbols-outlined text-4xl">inbox</span>
                                </div>
                                <p class="text-gray-500">No categories found.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($cat['name']); ?></td>
                                <td class="px-6 py-4 text-gray-500 font-mono text-xs"><?php echo htmlspecialchars($cat['slug']); ?></td>
                                <td class="px-6 py-4 text-gray-400 text-xs"><?php echo $cat['id']; ?></td>
                                <td class="px-6 py-4 text-right">
                                    <?php if ($cat['id'] != 1): // Lock Uncategorized ?>
                                    <a href="<?php echo Router::url('/admin/categories/delete?id=' . $cat['id']); ?>" 
                                       onclick="return confirm('Are you sure?');"
                                       class="text-red-600 hover:text-red-900 font-medium text-xs opacity-0 group-hover:opacity-100 transition-opacity">Delete</a>
                                    <?php else: ?>
                                    <span class="text-gray-300 text-xs italic">Locked</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add New -->
    <div class="col-span-12 md:col-span-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-900">Add New Category</h3>
            </div>
            <form action="<?php echo Router::url('/admin/categories/create'); ?>" method="POST" class="p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Name</label>
                    <input type="text" name="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required placeholder="e.g. Technology">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Slug</label>
                    <input type="text" name="slug" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-white" placeholder="e.g. technology">
                    <p class="text-xs text-gray-400 mt-1">Leave empty to generate automatically.</p>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Add Category
                </button>
            </form>
        </div>
    </div>
</div>
