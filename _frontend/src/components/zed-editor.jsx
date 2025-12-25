import { useEditor, EditorContent, BubbleMenu } from '@tiptap/react'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import Placeholder from '@tiptap/extension-placeholder'
import { TextStyle, Color } from '@tiptap/extension-text-style'
import Highlight from '@tiptap/extension-highlight'
import Underline from '@tiptap/extension-underline'
import Subscript from '@tiptap/extension-subscript'
import Superscript from '@tiptap/extension-superscript'
import TextAlign from '@tiptap/extension-text-align'
import { useState, useEffect, useCallback, useRef } from 'react'
import { Extension } from '@tiptap/core'
import Suggestion from '@tiptap/suggestion'
import { ReactRenderer } from '@tiptap/react'
import tippy from 'tippy.js'

// Custom extensions
import { Callout } from '../extensions/callout'
import { YouTube } from '../extensions/youtube'
import { Button } from '../extensions/button'

// Color palette for text colors
const textColors = [
    { name: 'Default', color: null },
    { name: 'Red', color: '#ef4444' },
    { name: 'Orange', color: '#f97316' },
    { name: 'Yellow', color: '#eab308' },
    { name: 'Green', color: '#22c55e' },
    { name: 'Blue', color: '#3b82f6' },
    { name: 'Purple', color: '#8b5cf6' },
    { name: 'Pink', color: '#ec4899' },
]

// Highlight colors
const highlightColors = [
    { name: 'None', color: null },
    { name: 'Yellow', color: '#fef08a' },
    { name: 'Green', color: '#bbf7d0' },
    { name: 'Blue', color: '#bfdbfe' },
    { name: 'Pink', color: '#fbcfe8' },
    { name: 'Purple', color: '#e9d5ff' },
    { name: 'Orange', color: '#fed7aa' },
]

// Handle image upload
const handleImageUpload = (editor) => {
    const input = document.createElement('input')
    input.type = 'file'
    input.accept = 'image/*'
    input.onchange = async (e) => {
        const file = e.target.files[0]
        if (!file) return

        const formData = new FormData()
        formData.append('image', file)

        try {
            const baseUrl = window.location.pathname.split('/admin')[0]
            const uploadUrl = `${baseUrl}/admin/api/upload`

            const response = await fetch(uploadUrl, {
                method: 'POST',
                body: formData,
            })
            const data = await response.json()
            if (data.success && data.file?.url) {
                editor.chain().focus().setImage({ src: data.file.url }).run()
            }
        } catch (error) {
            console.error('Image upload failed:', error)
        }
    }
    input.click()
}

// Slash menu items
const getSlashMenuItems = (editor) => [
    { icon: 'title', label: 'Heading 1', aliases: ['h1', 'heading'], action: () => editor.chain().focus().toggleHeading({ level: 1 }).run() },
    { icon: 'format_h2', label: 'Heading 2', aliases: ['h2'], action: () => editor.chain().focus().toggleHeading({ level: 2 }).run() },
    { icon: 'format_h3', label: 'Heading 3', aliases: ['h3'], action: () => editor.chain().focus().toggleHeading({ level: 3 }).run() },
    { icon: 'format_list_bulleted', label: 'Bullet List', aliases: ['ul', 'bullet', 'list'], action: () => editor.chain().focus().toggleBulletList().run() },
    { icon: 'format_list_numbered', label: 'Numbered List', aliases: ['ol', 'numbered'], action: () => editor.chain().focus().toggleOrderedList().run() },
    { icon: 'code', label: 'Code Block', aliases: ['code', 'pre'], action: () => editor.chain().focus().toggleCodeBlock().run() },
    { icon: 'format_quote', label: 'Quote', aliases: ['quote', 'blockquote'], action: () => editor.chain().focus().toggleBlockquote().run() },
    { icon: 'horizontal_rule', label: 'Divider', aliases: ['hr', 'line', 'divider'], action: () => editor.chain().focus().setHorizontalRule().run() },
    { icon: 'info', label: 'Callout', aliases: ['callout', 'info', 'note'], action: () => editor.chain().focus().setCallout({ type: 'info' }).run() },
    { icon: 'play_circle', label: 'YouTube Video', aliases: ['youtube', 'video'], action: () => editor.chain().focus().setYouTube({}).run() },
    { icon: 'smart_button', label: 'Button', aliases: ['button', 'btn', 'cta'], action: () => editor.chain().focus().setButton({}).run() },
    { icon: 'image', label: 'Image', aliases: ['image', 'img', 'photo'], action: () => handleImageUpload(editor) },
]

// Slash Menu Component with keyboard navigation
const SlashMenuComponent = ({ items, command, selectedIndex }) => {
    const menuRef = useRef(null)

    useEffect(() => {
        if (menuRef.current) {
            const selected = menuRef.current.querySelector(`[data-index="${selectedIndex}"]`)
            if (selected) {
                selected.scrollIntoView({ block: 'nearest' })
            }
        }
    }, [selectedIndex])

    if (!items.length) {
        return (
            <div className="slash-menu">
                <div className="slash-menu-empty">No results</div>
            </div>
        )
    }

    return (
        <div className="slash-menu" ref={menuRef}>
            <div className="slash-menu-header">Insert Block</div>
            {items.map((item, index) => (
                <button
                    key={index}
                    data-index={index}
                    className={`slash-menu-item ${index === selectedIndex ? 'selected' : ''}`}
                    onClick={() => command(item)}
                >
                    <span className="material-symbols-outlined">{item.icon}</span>
                    <span>{item.label}</span>
                </button>
            ))}
        </div>
    )
}

// Create slash command extension with keyboard support
const createSlashCommandExtension = () => {
    return Extension.create({
        name: 'slashCommand',

        addOptions() {
            return {
                suggestion: {
                    char: '/',
                    allowSpaces: false,
                    startOfLine: false,
                    items: ({ query, editor }) => {
                        const items = getSlashMenuItems(editor)
                        if (!query) return items
                        const lowerQuery = query.toLowerCase()
                        return items.filter(item =>
                            item.label.toLowerCase().includes(lowerQuery) ||
                            item.aliases?.some(alias => alias.toLowerCase().includes(lowerQuery))
                        )
                    },
                    render: () => {
                        let component
                        let popup
                        let selectedIndex = 0
                        let currentItems = []
                        let commandFn = null  // Store the command function

                        return {
                            onStart: (props) => {
                                selectedIndex = 0
                                currentItems = props.items || []
                                commandFn = props.command  // Save the command function

                                component = new ReactRenderer(SlashMenuComponent, {
                                    props: {
                                        items: currentItems,
                                        selectedIndex,
                                        command: (item) => {
                                            if (commandFn) commandFn(item)
                                        }
                                    },
                                    editor: props.editor,
                                })

                                if (!props.clientRect) return

                                popup = tippy('body', {
                                    getReferenceClientRect: props.clientRect,
                                    appendTo: () => document.body,
                                    content: component.element,
                                    showOnCreate: true,
                                    interactive: true,
                                    trigger: 'manual',
                                    placement: 'bottom-start',
                                    popperOptions: {
                                        modifiers: [
                                            { name: 'flip', options: { fallbackPlacements: ['top-start'] } },
                                            { name: 'preventOverflow', options: { padding: 8 } },
                                        ],
                                    },
                                })
                            },

                            onUpdate(props) {
                                selectedIndex = 0
                                currentItems = props.items || []
                                commandFn = props.command  // Update the command function
                                component?.updateProps({
                                    items: currentItems,
                                    selectedIndex,
                                    command: (item) => {
                                        if (commandFn) commandFn(item)
                                    }
                                })
                                if (!props.clientRect) return
                                popup?.[0]?.setProps({ getReferenceClientRect: props.clientRect })
                            },

                            onKeyDown(props) {
                                const { event } = props

                                if (event.key === 'Escape') {
                                    popup?.[0]?.hide()
                                    return true
                                }

                                if (event.key === 'ArrowUp') {
                                    event.preventDefault()
                                    selectedIndex = (selectedIndex - 1 + currentItems.length) % currentItems.length
                                    component?.updateProps({
                                        items: currentItems,
                                        selectedIndex,
                                        command: (item) => { if (commandFn) commandFn(item) }
                                    })
                                    return true
                                }

                                if (event.key === 'ArrowDown') {
                                    event.preventDefault()
                                    selectedIndex = (selectedIndex + 1) % currentItems.length
                                    component?.updateProps({
                                        items: currentItems,
                                        selectedIndex,
                                        command: (item) => { if (commandFn) commandFn(item) }
                                    })
                                    return true
                                }

                                if (event.key === 'Enter') {
                                    event.preventDefault()
                                    event.stopPropagation()
                                    if (currentItems[selectedIndex] && commandFn) {
                                        commandFn(currentItems[selectedIndex])
                                    }
                                    return true
                                }

                                return false
                            },

                            onExit() {
                                popup?.[0]?.destroy()
                                component?.destroy()
                            },
                        }
                    },
                    command: ({ editor, range, props }) => {
                        // Delete the "/" trigger character and execute the action
                        editor.chain().focus().deleteRange(range).run()
                        if (props && props.action) {
                            props.action()
                        }
                    },
                },
            }
        },


        addProseMirrorPlugins() {
            return [
                Suggestion({
                    editor: this.editor,
                    ...this.options.suggestion,
                }),
            ]
        },
    })
}

/**
 * Zed TipTap Editor
 * 
 * Full-featured TipTap implementation with:
 * - Extended bubble menu (formatting toolbar on selection)
 * - Keyboard-navigable slash commands
 * - Image controls & resize
 * - Clean JSON output
 */
export const ZedEditor = ({ initialContent, onChange }) => {
    const [showColorPicker, setShowColorPicker] = useState(false)
    const [showHighlightPicker, setShowHighlightPicker] = useState(false)
    const [selectedImage, setSelectedImage] = useState(null)

    const editor = useEditor({
        extensions: [
            StarterKit.configure({
                heading: {
                    levels: [1, 2, 3, 4, 5, 6],
                },
            }),
            Image.configure({
                allowBase64: true,
                HTMLAttributes: {
                    class: 'editor-image',
                },
            }),
            Link.configure({
                openOnClick: false,
                HTMLAttributes: {
                    class: 'editor-link',
                },
            }),
            Placeholder.configure({
                placeholder: 'Start writing, or type "/" for commands...',
            }),
            TextStyle,
            Color,
            Highlight.configure({ multicolor: true }),
            Underline,
            Subscript,
            Superscript,
            TextAlign.configure({
                types: ['heading', 'paragraph'],
            }),
            // Custom cards
            Callout,
            YouTube,
            Button,
            // Slash command with keyboard support
            createSlashCommandExtension(),
        ],
        content: initialContent || '',
        onUpdate: ({ editor }) => {
            window.zed_editor_content = editor.getJSON()
            if (onChange) onChange(editor.getJSON())
        },
        editorProps: {
            attributes: {
                class: 'zed-editor-content',
            },
            handleClick: (view, pos, event) => {
                // Image selection handling
                const target = event.target
                if (target.tagName === 'IMG') {
                    setSelectedImage(target)
                } else {
                    setSelectedImage(null)
                }
                return false
            },
        },
    })

    // Expose editor globally
    useEffect(() => {
        if (editor) {
            window.editor = editor
            window.zed_editor_content = editor.getJSON()
        }
    }, [editor])

    // Close pickers when clicking outside
    useEffect(() => {
        const handleClickOutside = () => {
            setShowColorPicker(false)
            setShowHighlightPicker(false)
        }
        document.addEventListener('click', handleClickOutside)
        return () => document.removeEventListener('click', handleClickOutside)
    }, [])

    if (!editor) return null

    // Toolbar button component
    const ToolbarButton = ({ onClick, isActive, icon, title, children }) => (
        <button
            type="button"
            onClick={(e) => {
                e.stopPropagation()
                onClick()
            }}
            className={`toolbar-btn ${isActive ? 'active' : ''}`}
            title={title}
        >
            {icon && <span className="material-symbols-outlined">{icon}</span>}
            {children}
        </button>
    )

    // Color picker dropdown
    const ColorPickerDropdown = ({ colors, onSelect, activeColor, label }) => (
        <div className="color-picker-dropdown" onClick={(e) => e.stopPropagation()}>
            <div className="color-picker-label">{label}</div>
            <div className="color-picker-grid">
                {colors.map((c, i) => (
                    <button
                        key={i}
                        className={`color-swatch ${activeColor === c.color ? 'active' : ''}`}
                        style={{ backgroundColor: c.color || '#e2e8f0' }}
                        onClick={() => onSelect(c.color)}
                        title={c.name}
                    >
                        {!c.color && <span className="material-symbols-outlined" style={{ fontSize: '12px' }}>format_clear</span>}
                    </button>
                ))}
            </div>
        </div>
    )

    // Add link
    const addLink = () => {
        const url = window.prompt('Enter URL:')
        if (url) {
            editor.chain().focus().setLink({ href: url }).run()
        }
    }

    // Image controls
    const ImageControls = () => {
        if (!selectedImage) return null

        const handleResize = (size) => {
            if (selectedImage) {
                selectedImage.style.width = size
                selectedImage.style.height = 'auto'
            }
        }

        const handleAlign = (align) => {
            if (selectedImage) {
                selectedImage.style.display = 'block'
                switch (align) {
                    case 'left':
                        selectedImage.style.marginLeft = '0'
                        selectedImage.style.marginRight = 'auto'
                        break
                    case 'center':
                        selectedImage.style.marginLeft = 'auto'
                        selectedImage.style.marginRight = 'auto'
                        break
                    case 'right':
                        selectedImage.style.marginLeft = 'auto'
                        selectedImage.style.marginRight = '0'
                        break
                }
            }
        }

        return (
            <div className="image-controls">
                <div className="image-controls-group">
                    <span className="image-controls-label">Size:</span>
                    <button onClick={() => handleResize('25%')} className="image-control-btn">25%</button>
                    <button onClick={() => handleResize('50%')} className="image-control-btn">50%</button>
                    <button onClick={() => handleResize('75%')} className="image-control-btn">75%</button>
                    <button onClick={() => handleResize('100%')} className="image-control-btn">100%</button>
                </div>
                <div className="image-controls-divider" />
                <div className="image-controls-group">
                    <span className="image-controls-label">Align:</span>
                    <button onClick={() => handleAlign('left')} className="image-control-btn">
                        <span className="material-symbols-outlined">format_align_left</span>
                    </button>
                    <button onClick={() => handleAlign('center')} className="image-control-btn">
                        <span className="material-symbols-outlined">format_align_center</span>
                    </button>
                    <button onClick={() => handleAlign('right')} className="image-control-btn">
                        <span className="material-symbols-outlined">format_align_right</span>
                    </button>
                </div>
            </div>
        )
    }

    return (
        <div className="zed-editor">
            {/* Bubble Menu - appears on text selection */}
            {editor && (
                <BubbleMenu
                    editor={editor}
                    tippyOptions={{
                        duration: 100,
                        maxWidth: 'none',
                        appendTo: () => document.body,
                        popperOptions: {
                            modifiers: [
                                { name: 'preventOverflow', enabled: false },
                                { name: 'flip', enabled: true },
                            ],
                        },
                    }}
                    className="bubble-menu"
                >
                    {/* Basic Formatting */}
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleBold().run()}
                        isActive={editor.isActive('bold')}
                        icon="format_bold"
                        title="Bold (Ctrl+B)"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleItalic().run()}
                        isActive={editor.isActive('italic')}
                        icon="format_italic"
                        title="Italic (Ctrl+I)"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleUnderline().run()}
                        isActive={editor.isActive('underline')}
                        icon="format_underlined"
                        title="Underline (Ctrl+U)"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleStrike().run()}
                        isActive={editor.isActive('strike')}
                        icon="strikethrough_s"
                        title="Strikethrough"
                    />

                    <div className="toolbar-divider" />

                    {/* Text Color */}
                    <div className="toolbar-dropdown-container">
                        <ToolbarButton
                            onClick={() => {
                                setShowColorPicker(!showColorPicker)
                                setShowHighlightPicker(false)
                            }}
                            isActive={showColorPicker}
                            title="Text Color"
                        >
                            <span className="material-symbols-outlined">format_color_text</span>
                            <span className="color-indicator" style={{ backgroundColor: editor.getAttributes('textStyle').color || '#1e293b' }} />
                        </ToolbarButton>
                        {showColorPicker && (
                            <ColorPickerDropdown
                                colors={textColors}
                                activeColor={editor.getAttributes('textStyle').color}
                                onSelect={(color) => {
                                    if (color) {
                                        editor.chain().focus().setColor(color).run()
                                    } else {
                                        editor.chain().focus().unsetColor().run()
                                    }
                                    setShowColorPicker(false)
                                }}
                                label="Text Color"
                            />
                        )}
                    </div>

                    {/* Highlight */}
                    <div className="toolbar-dropdown-container">
                        <ToolbarButton
                            onClick={() => {
                                setShowHighlightPicker(!showHighlightPicker)
                                setShowColorPicker(false)
                            }}
                            isActive={showHighlightPicker || editor.isActive('highlight')}
                            title="Highlight"
                        >
                            <span className="material-symbols-outlined">highlight</span>
                        </ToolbarButton>
                        {showHighlightPicker && (
                            <ColorPickerDropdown
                                colors={highlightColors}
                                activeColor={editor.getAttributes('highlight').color}
                                onSelect={(color) => {
                                    if (color) {
                                        editor.chain().focus().toggleHighlight({ color }).run()
                                    } else {
                                        editor.chain().focus().unsetHighlight().run()
                                    }
                                    setShowHighlightPicker(false)
                                }}
                                label="Highlight"
                            />
                        )}
                    </div>

                    <div className="toolbar-divider" />

                    {/* Subscript / Superscript */}
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleSubscript().run()}
                        isActive={editor.isActive('subscript')}
                        icon="subscript"
                        title="Subscript"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleSuperscript().run()}
                        isActive={editor.isActive('superscript')}
                        icon="superscript"
                        title="Superscript"
                    />

                    <div className="toolbar-divider" />

                    {/* Text Alignment */}
                    <ToolbarButton
                        onClick={() => editor.chain().focus().setTextAlign('left').run()}
                        isActive={editor.isActive({ textAlign: 'left' })}
                        icon="format_align_left"
                        title="Align Left"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().setTextAlign('center').run()}
                        isActive={editor.isActive({ textAlign: 'center' })}
                        icon="format_align_center"
                        title="Align Center"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().setTextAlign('right').run()}
                        isActive={editor.isActive({ textAlign: 'right' })}
                        icon="format_align_right"
                        title="Align Right"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().setTextAlign('justify').run()}
                        isActive={editor.isActive({ textAlign: 'justify' })}
                        icon="format_align_justify"
                        title="Justify"
                    />

                    <div className="toolbar-divider" />

                    {/* Code & Link */}
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleCode().run()}
                        isActive={editor.isActive('code')}
                        icon="code"
                        title="Inline Code"
                    />
                    <ToolbarButton
                        onClick={addLink}
                        isActive={editor.isActive('link')}
                        icon="link"
                        title="Add Link"
                    />
                    {editor.isActive('link') && (
                        <ToolbarButton
                            onClick={() => editor.chain().focus().unsetLink().run()}
                            icon="link_off"
                            title="Remove Link"
                        />
                    )}

                    <div className="toolbar-divider" />

                    {/* Clear Formatting */}
                    <ToolbarButton
                        onClick={() => editor.chain().focus().unsetAllMarks().clearNodes().run()}
                        icon="format_clear"
                        title="Clear Formatting"
                    />
                </BubbleMenu>
            )}

            {/* Image Controls - shown when image is selected */}
            <ImageControls />

            {/* Editor Content */}
            <EditorContent editor={editor} />
        </div>
    )
}
