import React, { useState, useEffect, useCallback, useRef } from 'react'
import { Extension } from '@tiptap/core'
import { ReactRenderer } from '@tiptap/react'
import Suggestion from '@tiptap/suggestion'
import tippy from 'tippy.js'
import {
    Type, Heading1, Heading2, Heading3,
    List, ListOrdered, Quote, Code, Minus,
    Image, Link
} from 'lucide-react'

// Helper function to upload image
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

// Trigger file input for image upload
const triggerImageUpload = (editor, range) => {
    const input = document.createElement('input')
    input.type = 'file'
    input.accept = 'image/*'
    input.onchange = async (e) => {
        const file = e.target.files?.[0]
        if (file) {
            const url = await uploadImage(file)
            if (url) {
                editor.chain().focus().deleteRange(range).setImage({ src: url }).run()
            }
        }
    }
    input.click()
}

// Menu Items Configuration
const defaultMenuItems = [
    {
        title: 'Text',
        subtext: 'Just start with plain text',
        icon: Type,
        group: 'Format',
        aliases: ['text', 'paragraph', 'p'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setParagraph().run()
        },
    },
    {
        title: 'Heading 1',
        subtext: 'Big section heading',
        icon: Heading1,
        group: 'Format',
        aliases: ['h1', 'heading1', 'title'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setNode('heading', { level: 1 }).run()
        },
    },
    {
        title: 'Heading 2',
        subtext: 'Medium section heading',
        icon: Heading2,
        group: 'Format',
        aliases: ['h2', 'heading2', 'subtitle'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setNode('heading', { level: 2 }).run()
        },
    },
    {
        title: 'Heading 3',
        subtext: 'Small section heading',
        icon: Heading3,
        group: 'Format',
        aliases: ['h3', 'heading3'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setNode('heading', { level: 3 }).run()
        },
    },
    {
        title: 'Bullet List',
        subtext: 'Create a simple bullet list',
        icon: List,
        group: 'Lists',
        aliases: ['ul', 'unordered', 'bullet'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleBulletList().run()
        },
    },
    {
        title: 'Numbered List',
        subtext: 'Create a numbered list',
        icon: ListOrdered,
        group: 'Lists',
        aliases: ['ol', 'ordered', 'numbered'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleOrderedList().run()
        },
    },
    {
        title: 'Quote',
        subtext: 'Capture a quote',
        icon: Quote,
        group: 'Blocks',
        aliases: ['blockquote', 'quote'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleBlockquote().run()
        },
    },
    {
        title: 'Code Block',
        subtext: 'Add code with syntax highlighting',
        icon: Code,
        group: 'Blocks',
        aliases: ['code', 'codeblock', 'pre'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleCodeBlock().run()
        },
    },
    {
        title: 'Divider',
        subtext: 'Visual separator line',
        icon: Minus,
        group: 'Blocks',
        aliases: ['hr', 'divider', 'line'],
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setHorizontalRule().run()
        },
    },
    {
        title: 'Upload Image',
        subtext: 'Upload from your computer',
        icon: Image,
        group: 'Media',
        aliases: ['image', 'upload', 'photo', 'picture'],
        command: ({ editor, range }) => {
            triggerImageUpload(editor, range)
        },
    },
    {
        title: 'Image from URL',
        subtext: 'Embed with a link',
        icon: Link,
        group: 'Media',
        aliases: ['embed', 'url', 'link', 'img'],
        command: ({ editor, range }) => {
            const url = window.prompt('Enter image URL')
            if (url) {
                editor.chain().focus().deleteRange(range).setImage({ src: url }).run()
            }
        },
    },
]

// Filter items by query
const filterItems = (items, query) => {
    if (!query) return items
    const lowerQuery = query.toLowerCase()
    return items.filter(item =>
        item.title.toLowerCase().includes(lowerQuery) ||
        item.aliases?.some(alias => alias.toLowerCase().includes(lowerQuery))
    )
}

// The Visual Menu Component
export const SlashMenuList = React.forwardRef((props, ref) => {
    const [selectedIndex, setSelectedIndex] = useState(0)
    const menuRef = useRef(null)

    const items = props.items || []

    const selectItem = useCallback((index) => {
        const item = items[index]
        if (item) {
            props.command(item)
        }
    }, [items, props])

    useEffect(() => {
        setSelectedIndex(0)
    }, [items])

    // Scroll selected item into view
    useEffect(() => {
        if (menuRef.current) {
            const selected = menuRef.current.querySelector(`[data-index="${selectedIndex}"]`)
            if (selected) {
                selected.scrollIntoView({ block: 'nearest' })
            }
        }
    }, [selectedIndex])

    React.useImperativeHandle(ref, () => ({
        onKeyDown: ({ event }) => {
            if (event.key === 'ArrowUp') {
                setSelectedIndex((prev) => (prev + items.length - 1) % items.length)
                return true
            }
            if (event.key === 'ArrowDown') {
                setSelectedIndex((prev) => (prev + 1) % items.length)
                return true
            }
            if (event.key === 'Enter') {
                selectItem(selectedIndex)
                return true
            }
            return false
        },
    }))

    if (!items.length) {
        return (
            <div className="bg-white rounded-lg shadow-xl border border-gray-200 p-3 text-sm text-gray-500">
                No results found
            </div>
        )
    }

    // Group items
    const groupedItems = items.reduce((acc, item, index) => {
        const group = item.group || 'Other'
        if (!acc[group]) acc[group] = []
        acc[group].push({ ...item, originalIndex: index })
        return acc
    }, {})

    let flatIndex = 0

    return (
        <div
            ref={menuRef}
            className="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden max-h-80 overflow-y-auto min-w-[280px]"
        >
            {Object.entries(groupedItems).map(([groupName, groupItems]) => (
                <div key={groupName}>
                    <div className="px-3 py-1.5 text-[10px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 border-b border-gray-100">
                        {groupName}
                    </div>
                    {groupItems.map((item) => {
                        const currentIndex = flatIndex++
                        const Icon = item.icon
                        return (
                            <button
                                key={item.title}
                                data-index={currentIndex}
                                onClick={() => selectItem(currentIndex)}
                                className={`w-full flex items-center gap-3 px-3 py-2 text-left transition-colors ${currentIndex === selectedIndex
                                    ? 'bg-indigo-50 text-indigo-700'
                                    : 'text-gray-700 hover:bg-gray-50'
                                    }`}
                            >
                                <div className={`p-1.5 rounded ${currentIndex === selectedIndex ? 'bg-indigo-100' : 'bg-gray-100'}`}>
                                    <Icon className="w-4 h-4" />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <div className="text-sm font-medium">{item.title}</div>
                                    <div className="text-xs text-gray-400 truncate">{item.subtext}</div>
                                </div>
                            </button>
                        )
                    })}
                </div>
            ))}
        </div>
    )
})

SlashMenuList.displayName = 'SlashMenuList'

// The Tiptap Extension
export const SlashDropdownMenu = Extension.create({
    name: 'slashDropdownMenu',

    addOptions() {
        return {
            suggestion: {
                char: '/',
                allowSpaces: false,
                startOfLine: false,
                items: ({ query }) => filterItems(defaultMenuItems, query),
                command: ({ editor, range, props }) => {
                    props.command({ editor, range })
                },
                render: () => {
                    let component
                    let popup

                    return {
                        onStart: (props) => {
                            component = new ReactRenderer(SlashMenuList, {
                                props,
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
                            component?.updateProps(props)
                            if (!props.clientRect) return
                            popup?.[0]?.setProps({ getReferenceClientRect: props.clientRect })
                        },

                        onKeyDown(props) {
                            if (props.event.key === 'Escape') {
                                popup?.[0]?.hide()
                                return true
                            }
                            return component?.ref?.onKeyDown(props) ?? false
                        },

                        onExit() {
                            popup?.[0]?.destroy()
                            component?.destroy()
                        },
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
