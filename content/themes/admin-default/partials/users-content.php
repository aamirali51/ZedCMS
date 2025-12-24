<?php
/**
 * User Management Module
 * 
 * Features:
 * - User table with Gravatar avatars
 * - Add/Edit modal with role selection
 * - Password generator
 * - AJAX operations (no page reload)
 * - Self-deletion protection
 */

use Core\Router;
use Core\Auth;

$base_url = Router::getBasePath();
$currentUserId = Auth::id();

// Helper function for Gravatar
function getGravatar(string $email, int $size = 40): string {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mp";
}

// Role badge colors
function getRoleBadge(string $role): array {
    return match($role) {
        'admin', 'administrator' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'shield_person'],
        'editor' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'edit_note'],
        'author' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'draw'],
        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'person'],
    };
}
?>

<style>
    /* User Management Styles */
    .user-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .user-table th {
        background: #f8fafc;
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .user-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .user-table tbody tr {
        transition: background-color 0.15s;
    }
    
    .user-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .action-btn {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    /* Modal Styles */
    .user-modal {
        border: none;
        border-radius: 16px;
        padding: 0;
        max-width: 480px;
        width: 90%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .user-modal::backdrop {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #f8fafc;
        border-radius: 0 0 16px 16px;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.15s;
        background: white;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }
    
    .generate-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        padding: 4px 8px;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.15s;
    }
    
    .generate-btn:hover {
        background: #e5e7eb;
        color: #1f2937;
    }
    
    /* Toast */
    .toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .toast.success { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .toast.error { background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); }
    
    /* Self indicator */
    .self-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        background: #dbeafe;
        color: #1d4ed8;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
    }
</style>

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">group</span>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">User Management</h3>
                    <p class="text-sm text-gray-500"><?= count($users ?? []) ?> user<?= count($users ?? []) !== 1 ? 's' : '' ?> total</p>
                </div>
            </div>
            <button id="addUserBtn" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-all shadow-sm hover:shadow-md">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Add User
            </button>
        </div>
    </div>
    
    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <?php if (empty($users)): ?>
        <div class="py-20 text-center">
            <span class="material-symbols-outlined text-[64px] text-gray-300 mb-4">group_off</span>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No users found</h3>
            <p class="text-gray-500 text-sm">Create your first user to get started.</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Last Login</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php foreach ($users as $user): ?>
                    <tr data-user-id="<?= $user['id'] ?>">
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="<?= getGravatar($user['email']) ?>" alt="" class="user-avatar">
                                <div>
                                    <div class="font-semibold text-gray-900">
                                        <?= htmlspecialchars($user['email']) ?>
                                        <?php if ((int)$user['id'] === $currentUserId): ?>
                                        <span class="self-badge">
                                            <span class="material-symbols-outlined text-[12px]">person</span>
                                            You
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-xs text-gray-500">ID: <?= $user['id'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php $badge = getRoleBadge($user['role']); ?>
                            <span class="role-badge <?= $badge['bg'] ?> <?= $badge['text'] ?>">
                                <span class="material-symbols-outlined text-[14px]"><?= $badge['icon'] ?></span>
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($user['last_login'])): ?>
                            <span class="text-sm text-gray-600"><?= date('M j, Y g:i A', strtotime($user['last_login'])) ?></span>
                            <?php else: ?>
                            <span class="text-sm text-gray-400">Never</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600"><?= date('M j, Y', strtotime($user['created_at'])) ?></span>
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button class="action-btn bg-gray-100 hover:bg-gray-200 text-gray-700 edit-user-btn"
                                        data-id="<?= $user['id'] ?>"
                                        data-email="<?= htmlspecialchars($user['email']) ?>"
                                        data-role="<?= htmlspecialchars($user['role']) ?>">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                    Edit
                                </button>
                                <?php if ((int)$user['id'] !== $currentUserId): ?>
                                <button class="action-btn bg-red-50 hover:bg-red-100 text-red-600 delete-user-btn"
                                        data-id="<?= $user['id'] ?>"
                                        data-email="<?= htmlspecialchars($user['email']) ?>">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- User Modal (Native HTML5 Dialog) -->
<dialog id="userModal" class="user-modal">
    <form id="userForm" method="dialog">
        <div class="modal-header">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900">Add New User</h3>
            <button type="button" id="closeModalBtn" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-gray-500">close</span>
            </button>
        </div>
        
        <div class="modal-body space-y-5">
            <input type="hidden" id="userId" name="id" value="">
            
            <!-- Email -->
            <div>
                <label for="userEmail" class="form-label">Email Address <span class="text-red-500">*</span></label>
                <input type="email" id="userEmail" name="email" class="form-input" placeholder="user@example.com" required>
            </div>
            
            <!-- Password -->
            <div>
                <label for="userPassword" class="form-label">
                    Password <span id="passwordRequired" class="text-red-500">*</span>
                    <span id="passwordOptional" class="text-gray-400 font-normal hidden">(leave blank to keep current)</span>
                </label>
                <div class="relative">
                    <input type="password" id="userPassword" name="password" class="form-input pr-24" placeholder="••••••••" minlength="6">
                    <button type="button" id="generatePasswordBtn" class="generate-btn">
                        <span class="material-symbols-outlined text-[14px]">casino</span> Generate
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
            </div>
            
            <!-- Role -->
            <div>
                <label for="userRole" class="form-label">Role</label>
                <select id="userRole" name="role" class="form-input form-select">
                    <option value="subscriber">Subscriber</option>
                    <option value="author">Author</option>
                    <option value="editor">Editor</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                Cancel
            </button>
            <button type="submit" id="saveUserBtn" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span id="saveButtonText">Create User</span>
            </button>
        </div>
    </form>
</dialog>

<!-- Toast -->
<div id="toast" class="toast">
    <span id="toastIcon" class="material-symbols-outlined">check_circle</span>
    <span id="toastMessage">Success!</span>
</div>

<script>
(function() {
    'use strict';
    
    const API_SAVE = '<?= Router::url('/admin/api/save-user') ?>';
    const API_DELETE = '<?= Router::url('/admin/api/delete-user') ?>';
    
    // Elements
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const addBtn = document.getElementById('addUserBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const generateBtn = document.getElementById('generatePasswordBtn');
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    
    // Form elements
    const modalTitle = document.getElementById('modalTitle');
    const userId = document.getElementById('userId');
    const userEmail = document.getElementById('userEmail');
    const userPassword = document.getElementById('userPassword');
    const userRole = document.getElementById('userRole');
    const passwordRequired = document.getElementById('passwordRequired');
    const passwordOptional = document.getElementById('passwordOptional');
    const saveButtonText = document.getElementById('saveButtonText');
    
    // ========== TOAST ==========
    let toastTimeout;
    function showToast(message, type = 'success') {
        clearTimeout(toastTimeout);
        toast.className = 'toast ' + type;
        toastIcon.textContent = type === 'success' ? 'check_circle' : 'error';
        toastMessage.textContent = message;
        toast.classList.add('show');
        toastTimeout = setTimeout(() => toast.classList.remove('show'), 4000);
    }
    
    // ========== MODAL ==========
    function openModal(isEdit = false, data = {}) {
        // Reset form
        form.reset();
        userId.value = '';
        
        if (isEdit && data.id) {
            modalTitle.textContent = 'Edit User';
            saveButtonText.textContent = 'Update User';
            userId.value = data.id;
            userEmail.value = data.email || '';
            userRole.value = data.role || 'subscriber';
            
            // Password is optional for edit
            userPassword.required = false;
            passwordRequired.classList.add('hidden');
            passwordOptional.classList.remove('hidden');
            userPassword.placeholder = 'Leave blank to keep current';
        } else {
            modalTitle.textContent = 'Add New User';
            saveButtonText.textContent = 'Create User';
            
            // Password is required for new user
            userPassword.required = true;
            passwordRequired.classList.remove('hidden');
            passwordOptional.classList.add('hidden');
            userPassword.placeholder = '••••••••';
        }
        
        modal.showModal();
        userEmail.focus();
    }
    
    function closeModal() {
        modal.close();
    }
    
    // ========== PASSWORD GENERATOR ==========
    function generatePassword() {
        const chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        userPassword.value = password;
        userPassword.type = 'text'; // Show the generated password
        
        // Copy to clipboard
        navigator.clipboard.writeText(password).then(() => {
            showToast('Password generated and copied!');
        }).catch(() => {
            showToast('Password generated!');
        });
    }
    
    // ========== SAVE USER ==========
    async function saveUser(e) {
        e.preventDefault();
        
        const data = {
            id: userId.value || null,
            email: userEmail.value.trim(),
            password: userPassword.value,
            role: userRole.value,
            nonce: window.ZED_NONCE || ''
        };
        
        // Validate
        if (!data.email) {
            showToast('Please enter an email address.', 'error');
            return;
        }
        
        if (!data.id && !data.password) {
            showToast('Password is required for new users.', 'error');
            return;
        }
        
        try {
            const response = await fetch(API_SAVE, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message || 'User saved successfully!');
                closeModal();
                // Reload page to refresh table
                setTimeout(() => location.reload(), 500);
            } else {
                showToast(result.error || 'Failed to save user.', 'error');
            }
        } catch (err) {
            showToast('An error occurred. Please try again.', 'error');
        }
    }
    
    // ========== DELETE USER ==========
    async function deleteUser(id, email) {
        if (!confirm(`Are you sure you want to delete "${email}"?\n\nThis action cannot be undone.`)) {
            return;
        }
        
        try {
            const response = await fetch(API_DELETE, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, nonce: window.ZED_NONCE || '' })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast(result.message || 'User deleted successfully!');
                // Remove row from table
                const row = document.querySelector(`tr[data-user-id="${id}"]`);
                if (row) {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        row.remove();
                        // Reload if no users left
                        if (document.querySelectorAll('#userTableBody tr').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            } else {
                showToast(result.error || 'Failed to delete user.', 'error');
            }
        } catch (err) {
            showToast('An error occurred. Please try again.', 'error');
        }
    }
    
    // ========== EVENT LISTENERS ==========
    
    // Add user button
    addBtn?.addEventListener('click', () => openModal(false));
    
    // Close modal buttons
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    
    // Close on backdrop click
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    
    // Close on Escape
    modal?.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
    
    // Generate password
    generateBtn?.addEventListener('click', generatePassword);
    
    // Form submit
    form?.addEventListener('submit', saveUser);
    
    // Edit buttons (delegation)
    document.addEventListener('click', (e) => {
        const editBtn = e.target.closest('.edit-user-btn');
        if (editBtn) {
            openModal(true, {
                id: editBtn.dataset.id,
                email: editBtn.dataset.email,
                role: editBtn.dataset.role
            });
        }
        
        const deleteBtn = e.target.closest('.delete-user-btn');
        if (deleteBtn) {
            deleteUser(deleteBtn.dataset.id, deleteBtn.dataset.email);
        }
    });
    
})();
</script>
