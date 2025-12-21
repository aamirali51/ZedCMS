import React from 'react'
import { useEditor, EditorContent } from '@tiptap/react'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import Link from '@tiptap/extension-link'
import { Bold, Italic, Strikethrough, Link as LinkIcon, Code, Heading1, Heading2 } from 'lucide-react'
import { SlashDropdownMenu } from './slash-dropdown-menu'
import { DragHandle } from './drag-handle'

// Custom BubbleMenu component for v3
const CustomBubbleMenu = ({ editor, children, className }) => {
    const [isVisible, setIsVisible] = React.useState(false)
    const [position, setPosition] = React.useState({ top: 0, left: 0 })
    const menuRef = React.useRef(null)

    React.useEffect(() => {
        if (!editor) return

        const updatePosition = () => {
            const { from, to, empty } = editor.state.selection

            // Only show when text is selected
            if (empty) {
                setIsVisible(false)
                return
            }

            try {
                const coords = editor.view.coordsAtPos(from)
                const editorRect = editor.view.dom.getBoundingClientRect()

                setPosition({
                    top: coords.top - editorRect.top - 45,
                    left: coords.left - editorRect.left
                })
                setIsVisible(true)
            } catch (e) {
                setIsVisible(false)
            }
        }

        editor.on('selectionUpdate', updatePosition)
        editor.on('transaction', updatePosition)

        return () => {
            editor.off('selectionUpdate', updatePosition)
            editor.off('transaction', updatePosition)
        }
    }, [editor])

    if (!isVisible) return null

    return (
        <div
            ref={menuRef}
            className={className}
            style={{
                position: 'absolute',
                top: position.top,
                left: position.left,
                zIndex: 50
            }}
        >
            {children}
        </div>
    )
}

export const SimpleEditor = ({ content = '' }) => {
    const uploadImage = async (file) => {
        const formData = new FormData()
        formData.append('image', file)

        try {
            const response = await fetch('/admin/api/upload', {
                method: 'POST',
                body: formData,
            })
            const data = await response.json()
            if (data.success && data.file && data.file.url) {
                return data.file.url
            }
            throw new Error(data.error || 'Upload failed')
        } catch (error) {
            console.error('Image upload error:', error)
            return null
        }
    }

    const editor = useEditor({
        extensions: [
            StarterKit,
            Image.configure({
                inline: true,
                allowBase64: true,
            }),
            Link.configure({
                openOnClick: false,
            }),
            SlashDropdownMenu,
        ],
        content,
        editorProps: {
            attributes: {
                class: 'prose prose-xl max-w-none focus:outline-none min-h-[1000px] p-16',
            },
            handleDrop: (view, event, slice, moved) => {
                if (!moved && event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0]) {
                    const file = event.dataTransfer.files[0]
                    if (file.type.startsWith('image/')) {
                        uploadImage(file).then(url => {
                            if (url) {
                                const { schema } = view.state
                                const coordinates = view.posAtCoords({ left: event.clientX, top: event.clientY })
                                const node = schema.nodes.image.create({ src: url })
                                const transaction = view.state.tr.insert(coordinates.pos, node)
                                view.dispatch(transaction)
                            }
                        })
                        return true
                    }
                }
                return false
            },
            handlePaste: (view, event, slice) => {
                const items = (event.clipboardData || event.originalEvent.clipboardData).items
                for (const item of items) {
                    if (item.type.indexOf('image') === 0) {
                        const file = item.getAsFile()
                        uploadImage(file).then(url => {
                            if (url) {
                                const transaction = view.state.tr.replaceSelectionWith(
                                    view.state.schema.nodes.image.create({ src: url })
                                )
                                view.dispatch(transaction)
                            }
                        })
                        return true
                    }
                }
                return false
            }
        },
        onUpdate: ({ editor }) => {
            window.zero_editor_content = editor.getJSON()
        },
    })

    React.useEffect(() => {
        if (editor) {
            window.editor = editor
        }
    }, [editor])

    if (!editor) {
        return null
    }

    return (
        <div className="w-full min-h-[80vh] flex flex-col group">
            <div className="relative mx-auto w-full max-w-[1000px]">
                {/* Custom Drag Handle */}
                <DragHandle editor={editor} />

                {/* Paper Container */}
                <div className="bg-white shadow-lg min-h-[1100px] my-8">
                    <EditorContent editor={editor} />

                    <CustomBubbleMenu
                        editor={editor}
                        className="flex overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl"
                    >
                        {/* BOLD */}
                        <button
                            onClick={() => editor.chain().focus().toggleBold().run()}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('bold') ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Bold"
                        >
                            <Bold className="w-4 h-4" />
                        </button>

                        {/* ITALIC */}
                        <button
                            onClick={() => editor.chain().focus().toggleItalic().run()}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('italic') ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Italic"
                        >
                            <Italic className="w-4 h-4" />
                        </button>

                        {/* STRIKE */}
                        <button
                            onClick={() => editor.chain().focus().toggleStrike().run()}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('strike') ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Strikethrough"
                        >
                            <Strikethrough className="w-4 h-4" />
                        </button>

                        {/* CODE */}
                        <button
                            onClick={() => editor.chain().focus().toggleCode().run()}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('code') ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Code"
                        >
                            <Code className="w-4 h-4" />
                        </button>

                        {/* LINK */}
                        <button
                            onClick={() => {
                                const url = window.prompt('Enter URL')
                                if (url) {
                                    editor.chain().focus().setLink({ href: url }).run()
                                }
                            }}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('link') ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Link"
                        >
                            <LinkIcon className="w-4 h-4" />
                        </button>

                        {/* DIVIDER */}
                        <div className="w-px bg-gray-200 mx-1"></div>

                        {/* H1 */}
                        <button
                            onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('heading', { level: 1 }) ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Heading 1"
                        >
                            <Heading1 className="w-4 h-4" />
                        </button>

                        {/* H2 */}
                        <button
                            onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
                            className={`p-2 hover:bg-gray-100 ${editor.isActive('heading', { level: 2 }) ? 'text-blue-600 bg-gray-50' : 'text-gray-600'}`}
                            title="Heading 2"
                        >
                            <Heading2 className="w-4 h-4" />
                        </button>
                    </CustomBubbleMenu>
                </div>
            </div>
        </div>
    )
}
