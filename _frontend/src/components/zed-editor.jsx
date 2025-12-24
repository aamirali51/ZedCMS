import { useEditor, EditorContent, BubbleMenu, FloatingMenu } from '@tiptap/react'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import Placeholder from '@tiptap/extension-placeholder'
import { useState, useEffect, useCallback } from 'react'

// Custom extensions
import { Callout } from '../extensions/callout'
import { YouTube } from '../extensions/youtube'
import { Button } from '../extensions/button'

/**
 * Zed TipTap Editor
 * 
 * Clean TipTap implementation without Mantine/BlockNote dependencies.
 * Features:
 * - Bubble menu (formatting toolbar on selection)
 * - Floating menu (slash commands)
 * - Image upload support
 * - Clean JSON output
 */
export const ZedEditor = ({ initialContent, onChange }) => {
    const [isSlashMenuOpen, setSlashMenuOpen] = useState(false)

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
            // Custom cards
            Callout,
            YouTube,
            Button,
        ],
        content: initialContent || '',
        onUpdate: ({ editor }) => {
            // Expose content for saving
            window.zed_editor_content = editor.getJSON()
            if (onChange) onChange(editor.getJSON())
        },
        editorProps: {
            attributes: {
                class: 'zed-editor-content',
            },
            handleKeyDown: (view, event) => {
                if (event.key === '/') {
                    setSlashMenuOpen(true)
                }
                if (event.key === 'Escape') {
                    setSlashMenuOpen(false)
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

    if (!editor) return null

    // Toolbar button component
    const ToolbarButton = ({ onClick, isActive, icon, title }) => (
        <button
            type="button"
            onClick={onClick}
            className={`toolbar-btn ${isActive ? 'active' : ''}`}
            title={title}
        >
            <span className="material-symbols-outlined">{icon}</span>
        </button>
    )

    // Slash command items - organized by category
    const slashCommands = [
        // Text blocks
        { icon: 'title', label: 'Heading 1', action: () => editor.chain().focus().toggleHeading({ level: 1 }).run() },
        { icon: 'format_h2', label: 'Heading 2', action: () => editor.chain().focus().toggleHeading({ level: 2 }).run() },
        { icon: 'format_h3', label: 'Heading 3', action: () => editor.chain().focus().toggleHeading({ level: 3 }).run() },
        // Lists
        { icon: 'format_list_bulleted', label: 'Bullet List', action: () => editor.chain().focus().toggleBulletList().run() },
        { icon: 'format_list_numbered', label: 'Numbered List', action: () => editor.chain().focus().toggleOrderedList().run() },
        // Content blocks
        { icon: 'code', label: 'Code Block', action: () => editor.chain().focus().toggleCodeBlock().run() },
        { icon: 'format_quote', label: 'Quote', action: () => editor.chain().focus().toggleBlockquote().run() },
        { icon: 'horizontal_rule', label: 'Divider', action: () => editor.chain().focus().setHorizontalRule().run() },
        // Cards
        { icon: 'info', label: 'Callout', action: () => editor.chain().focus().setCallout({ type: 'info' }).run() },
        { icon: 'play_circle', label: 'YouTube Video', action: () => editor.chain().focus().setYouTube({}).run() },
        { icon: 'smart_button', label: 'Button', action: () => editor.chain().focus().setButton({}).run() },
        // Media
        { icon: 'image', label: 'Image', action: () => handleImageUpload() },
    ]

    // Handle image upload
    const handleImageUpload = () => {
        const input = document.createElement('input')
        input.type = 'file'
        input.accept = 'image/*'
        input.onchange = async (e) => {
            const file = e.target.files[0]
            if (!file) return

            const formData = new FormData()
            formData.append('image', file)

            try {
                // Get base URL from current location (handles /ZedCMS/ subfolder)
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

    // Add link
    const addLink = () => {
        const url = window.prompt('Enter URL:')
        if (url) {
            editor.chain().focus().setLink({ href: url }).run()
        }
    }

    return (
        <div className="zed-editor">
            {/* Bubble Menu - appears on text selection */}
            {editor && (
                <BubbleMenu
                    editor={editor}
                    tippyOptions={{ duration: 100 }}
                    className="bubble-menu"
                >
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleBold().run()}
                        isActive={editor.isActive('bold')}
                        icon="format_bold"
                        title="Bold"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleItalic().run()}
                        isActive={editor.isActive('italic')}
                        icon="format_italic"
                        title="Italic"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleStrike().run()}
                        isActive={editor.isActive('strike')}
                        icon="strikethrough_s"
                        title="Strikethrough"
                    />
                    <ToolbarButton
                        onClick={() => editor.chain().focus().toggleCode().run()}
                        isActive={editor.isActive('code')}
                        icon="code"
                        title="Inline Code"
                    />
                    <div className="toolbar-divider" />
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
                </BubbleMenu>
            )}

            {/* Floating Menu - slash commands */}
            {editor && (
                <FloatingMenu
                    editor={editor}
                    tippyOptions={{
                        duration: 100,
                        maxWidth: 240,
                        placement: 'bottom-start'
                    }}
                    className="floating-menu"
                    shouldShow={({ state }) => {
                        const { $from } = state.selection
                        const currentLineText = $from.nodeBefore?.textContent || ''
                        return currentLineText === '/'
                    }}
                >
                    <div className="slash-menu">
                        <div className="slash-menu-header">Insert Block</div>
                        {slashCommands.map((cmd, i) => (
                            <button
                                key={i}
                                className="slash-menu-item"
                                onClick={() => {
                                    // Remove the "/" first
                                    editor.chain().focus().deleteRange({
                                        from: editor.state.selection.$from.pos - 1,
                                        to: editor.state.selection.$from.pos
                                    }).run()
                                    cmd.action()
                                }}
                            >
                                <span className="material-symbols-outlined">{cmd.icon}</span>
                                <span>{cmd.label}</span>
                            </button>
                        ))}
                    </div>
                </FloatingMenu>
            )}

            {/* Editor Content */}
            <EditorContent editor={editor} />
        </div>
    )
}
