<?php
/**
 * Widgets Admin Page Template
 * 
 * Drag-and-drop widget management interface.
 * 
 * @package Zed CMS Admin
 * @since 3.2.0
 */

$base_url = \Core\Router::getBasePath();

// Get registered sidebars and widgets
$sidebars = zed_get_sidebars();
$widgets = zed_get_widgets();
$sidebar_widgets = zed_get_all_sidebar_widgets();

// Handle save action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_widgets'])) {
    $data = json_decode($_POST['widget_data'] ?? '{}', true);
    if (is_array($data)) {
        zed_save_all_sidebar_widgets($data);
        zed_add_notice('Widget settings saved.', 'success');
    }
}
?>

<div class="admin-header">
    <div class="admin-header-title">
        <h1>Widgets</h1>
        <p class="subtitle">Add widgets to your sidebars</p>
    </div>
    <div class="admin-header-actions">
        <button type="button" id="save-widgets" class="btn btn-primary">
            <span class="material-symbols-outlined">save</span>
            Save Changes
        </button>
    </div>
</div>

<?php if (empty($sidebars)): ?>
<div class="empty-state-card">
    <span class="material-symbols-outlined">widgets</span>
    <h3>No Sidebars Registered</h3>
    <p>Your theme needs to register sidebars before you can add widgets.</p>
    <pre class="code-example">zed_register_sidebar('main-sidebar', [
    'name' => 'Main Sidebar',
    'description' => 'Appears on blog pages',
]);</pre>
</div>
<?php else: ?>

<div class="widgets-container">
    <!-- Available Widgets -->
    <div class="widgets-available">
        <h3>Available Widgets</h3>
        <p class="help-text">Drag widgets to a sidebar on the right.</p>
        
        <div class="widget-list" id="available-widgets">
            <?php foreach ($widgets as $id => $widget): ?>
            <div class="widget-item" data-widget-type="<?= $id ?>" draggable="true">
                <div class="widget-handle">
                    <span class="material-symbols-outlined"><?= $widget['icon'] ?? 'widgets' ?></span>
                </div>
                <div class="widget-info">
                    <strong><?= htmlspecialchars($widget['name']) ?></strong>
                    <small><?= htmlspecialchars($widget['description']) ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Sidebars -->
    <div class="sidebars-list">
        <?php foreach ($sidebars as $id => $sidebar): ?>
        <div class="sidebar-card" data-sidebar-id="<?= $id ?>">
            <div class="sidebar-header">
                <h4><?= htmlspecialchars($sidebar['name']) ?></h4>
                <?php if (!empty($sidebar['description'])): ?>
                <small><?= htmlspecialchars($sidebar['description']) ?></small>
                <?php endif; ?>
            </div>
            
            <div class="sidebar-widgets" data-sidebar="<?= $id ?>">
                <?php 
                $assigned = $sidebar_widgets[$id] ?? [];
                if (empty($assigned)):
                ?>
                <div class="empty-sidebar">Drop widgets here</div>
                <?php else: ?>
                    <?php foreach ($assigned as $index => $widget_instance): 
                        $type = $widget_instance['type'] ?? '';
                        $widget_config = $widgets[$type] ?? null;
                        if (!$widget_config) continue;
                        $instance = $widget_instance['instance'] ?? [];
                    ?>
                    <div class="widget-instance" data-widget-type="<?= $type ?>" data-index="<?= $index ?>">
                        <div class="widget-instance-header">
                            <span class="material-symbols-outlined drag-handle">drag_indicator</span>
                            <span class="widget-name"><?= htmlspecialchars($instance['title'] ?? $widget_config['name']) ?></span>
                            <button type="button" class="widget-toggle">
                                <span class="material-symbols-outlined">expand_more</span>
                            </button>
                            <button type="button" class="widget-remove" title="Remove">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <div class="widget-instance-body" style="display: none;">
                            <?php foreach ($widget_config['fields'] as $field_id => $field): 
                                $value = $instance[$field_id] ?? $field['default'] ?? '';
                            ?>
                            <div class="widget-field">
                                <label><?= htmlspecialchars($field['label']) ?></label>
                                <?php if ($field['type'] === 'text' || $field['type'] === 'url'): ?>
                                    <input type="<?= $field['type'] ?>" name="<?= $field_id ?>" value="<?= htmlspecialchars($value) ?>">
                                <?php elseif ($field['type'] === 'number'): ?>
                                    <input type="number" name="<?= $field_id ?>" value="<?= htmlspecialchars($value) ?>" min="<?= $field['min'] ?? 1 ?>" max="<?= $field['max'] ?? 100 ?>">
                                <?php elseif ($field['type'] === 'textarea'): ?>
                                    <textarea name="<?= $field_id ?>" rows="<?= $field['rows'] ?? 4 ?>"><?= htmlspecialchars($value) ?></textarea>
                                <?php elseif ($field['type'] === 'checkbox'): ?>
                                    <input type="checkbox" name="<?= $field_id ?>" value="1" <?= $value ? 'checked' : '' ?>>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Hidden form for saving -->
<form id="widgets-form" method="post">
    <input type="hidden" name="save_widgets" value="1">
    <input type="hidden" name="widget_data" id="widget-data">
</form>

<script>
const widgetsConfig = <?= json_encode($widgets) ?>;

// Toggle widget settings
document.querySelectorAll('.widget-toggle').forEach(btn => {
    btn.addEventListener('click', function() {
        const body = this.closest('.widget-instance').querySelector('.widget-instance-body');
        const icon = this.querySelector('.material-symbols-outlined');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.textContent = 'expand_less';
        } else {
            body.style.display = 'none';
            icon.textContent = 'expand_more';
        }
    });
});

// Remove widget
document.querySelectorAll('.widget-remove').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.widget-instance').remove();
        updateEmptyState();
    });
});

// Drag and drop
let draggedWidget = null;

document.querySelectorAll('.widget-item').forEach(widget => {
    widget.addEventListener('dragstart', function(e) {
        draggedWidget = {type: this.dataset.widgetType, isNew: true};
        this.classList.add('dragging');
    });
    
    widget.addEventListener('dragend', function() {
        this.classList.remove('dragging');
    });
});

document.querySelectorAll('.sidebar-widgets').forEach(sidebar => {
    sidebar.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    
    sidebar.addEventListener('dragleave', function() {
        this.classList.remove('drag-over');
    });
    
    sidebar.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        if (draggedWidget && draggedWidget.isNew) {
            const type = draggedWidget.type;
            const config = widgetsConfig[type];
            if (!config) return;
            
            // Remove empty state
            const empty = this.querySelector('.empty-sidebar');
            if (empty) empty.remove();
            
            // Create widget instance
            const widget = createWidgetInstance(type, config);
            this.appendChild(widget);
        }
    });
});

function createWidgetInstance(type, config) {
    const div = document.createElement('div');
    div.className = 'widget-instance';
    div.dataset.widgetType = type;
    
    let fieldsHtml = '';
    for (const [id, field] of Object.entries(config.fields || {})) {
        const value = field.default || '';
        let input = '';
        if (field.type === 'text' || field.type === 'url') {
            input = `<input type="${field.type}" name="${id}" value="${escapeHtml(value)}">`;
        } else if (field.type === 'number') {
            input = `<input type="number" name="${id}" value="${value}" min="${field.min || 1}" max="${field.max || 100}">`;
        } else if (field.type === 'textarea') {
            input = `<textarea name="${id}" rows="${field.rows || 4}">${escapeHtml(value)}</textarea>`;
        } else if (field.type === 'checkbox') {
            input = `<input type="checkbox" name="${id}" value="1" ${value ? 'checked' : ''}>`;
        }
        fieldsHtml += `<div class="widget-field"><label>${escapeHtml(field.label)}</label>${input}</div>`;
    }
    
    div.innerHTML = `
        <div class="widget-instance-header">
            <span class="material-symbols-outlined drag-handle">drag_indicator</span>
            <span class="widget-name">${escapeHtml(config.name)}</span>
            <button type="button" class="widget-toggle"><span class="material-symbols-outlined">expand_more</span></button>
            <button type="button" class="widget-remove" title="Remove"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="widget-instance-body">${fieldsHtml}</div>
    `;
    
    // Bind events
    div.querySelector('.widget-toggle').addEventListener('click', function() {
        const body = div.querySelector('.widget-instance-body');
        const icon = this.querySelector('.material-symbols-outlined');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.textContent = 'expand_less';
        } else {
            body.style.display = 'none';
            icon.textContent = 'expand_more';
        }
    });
    
    div.querySelector('.widget-remove').addEventListener('click', function() {
        div.remove();
        updateEmptyState();
    });
    
    return div;
}

function updateEmptyState() {
    document.querySelectorAll('.sidebar-widgets').forEach(sidebar => {
        if (!sidebar.querySelector('.widget-instance')) {
            if (!sidebar.querySelector('.empty-sidebar')) {
                sidebar.innerHTML = '<div class="empty-sidebar">Drop widgets here</div>';
            }
        }
    });
}

function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[s]));
}

// Save widgets
document.getElementById('save-widgets').addEventListener('click', function() {
    const data = {};
    
    document.querySelectorAll('.sidebar-card').forEach(card => {
        const sidebarId = card.dataset.sidebarId;
        data[sidebarId] = [];
        
        card.querySelectorAll('.widget-instance').forEach(widget => {
            const type = widget.dataset.widgetType;
            const instance = {};
            
            widget.querySelectorAll('.widget-field input, .widget-field textarea').forEach(input => {
                if (input.type === 'checkbox') {
                    instance[input.name] = input.checked;
                } else {
                    instance[input.name] = input.value;
                }
            });
            
            data[sidebarId].push({type, instance});
        });
    });
    
    document.getElementById('widget-data').value = JSON.stringify(data);
    document.getElementById('widgets-form').submit();
});
</script>

<style>
.widgets-container {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    align-items: start;
}

.widgets-available {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid #e5e7eb;
    position: sticky;
    top: 1rem;
}

.dark .widgets-available {
    background: #1e293b;
    border-color: #334155;
}

.widgets-available h3 {
    margin: 0 0 0.25rem;
    font-size: 1rem;
}

.widgets-available .help-text {
    color: #6b7280;
    font-size: 0.8rem;
    margin: 0 0 1rem;
}

.widget-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.widget-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: grab;
    transition: all 0.15s;
}

.dark .widget-item {
    background: #0f172a;
    border-color: #334155;
}

.widget-item:hover {
    border-color: #6366f1;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
}

.widget-item.dragging {
    opacity: 0.5;
}

.widget-handle {
    color: #9ca3af;
}

.widget-info {
    flex: 1;
    min-width: 0;
}

.widget-info strong {
    display: block;
    font-size: 0.875rem;
}

.widget-info small {
    color: #6b7280;
    font-size: 0.75rem;
}

.sidebars-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.sidebar-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.dark .sidebar-card {
    background: #1e293b;
    border-color: #334155;
}

.sidebar-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.dark .sidebar-header {
    background: #0f172a;
    border-color: #334155;
}

.sidebar-header h4 {
    margin: 0;
    font-size: 1rem;
}

.sidebar-header small {
    color: #6b7280;
    font-size: 0.8rem;
}

.sidebar-widgets {
    min-height: 100px;
    padding: 1rem;
}

.sidebar-widgets.drag-over {
    background: rgba(99, 102, 241, 0.05);
}

.empty-sidebar {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
    border: 2px dashed #e5e7eb;
    border-radius: 8px;
}

.widget-instance {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.dark .widget-instance {
    background: #0f172a;
    border-color: #334155;
}

.widget-instance-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    cursor: pointer;
}

.drag-handle {
    color: #9ca3af;
    cursor: grab;
}

.widget-name {
    flex: 1;
    font-weight: 500;
    font-size: 0.875rem;
}

.widget-toggle, .widget-remove {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
}

.widget-toggle:hover, .widget-remove:hover {
    color: #374151;
    background: rgba(0,0,0,0.05);
}

.widget-remove:hover {
    color: #ef4444;
}

.widget-instance-body {
    padding: 0 0.75rem 0.75rem;
    border-top: 1px solid #e5e7eb;
}

.dark .widget-instance-body {
    border-color: #334155;
}

.widget-field {
    margin-top: 0.75rem;
}

.widget-field label {
    display: block;
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.widget-field input[type="text"],
.widget-field input[type="url"],
.widget-field input[type="number"],
.widget-field textarea {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.875rem;
}

.dark .widget-field input,
.dark .widget-field textarea {
    background: #1e293b;
    border-color: #334155;
    color: #e2e8f0;
}

.empty-state-card {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.dark .empty-state-card {
    background: #1e293b;
    border-color: #334155;
}

.empty-state-card .material-symbols-outlined {
    font-size: 64px;
    color: #d1d5db;
}

.empty-state-card h3 {
    margin: 1rem 0 0.5rem;
}

.empty-state-card p {
    color: #6b7280;
    margin-bottom: 1.5rem;
}

.code-example {
    text-align: left;
    background: #1e293b;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 8px;
    font-size: 0.8rem;
    overflow-x: auto;
    display: inline-block;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.admin-header-title h1 {
    margin: 0;
}

.admin-header-title .subtitle {
    color: #6b7280;
    margin: 0.25rem 0 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #6366f1;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s;
}

.btn-primary:hover {
    background: #4f46e5;
}

.btn-primary .material-symbols-outlined {
    font-size: 18px;
}
</style>

<?php endif; ?>
