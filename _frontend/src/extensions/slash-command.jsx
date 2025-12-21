import { Extension } from '@tiptap/core'
import Suggestion from '@tiptap/suggestion'
import { ReactRenderer } from '@tiptap/react'
import tippy from 'tippy.js'
import { CommandList } from '../components/command-list'
import { Heading1, Heading2, List, ListOrdered, Code } from 'lucide-react'
import React from 'react'

const suggestionItems = [
    {
        title: 'Heading 1',
        icon: React.createElement(Heading1, { className: 'w-4 h-4' }),
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setNode('heading', { level: 1 }).run()
        },
    },
    {
        title: 'Heading 2',
        icon: React.createElement(Heading2, { className: 'w-4 h-4' }),
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).setNode('heading', { level: 2 }).run()
        },
    },
    {
        title: 'Bullet List',
        icon: React.createElement(List, { className: 'w-4 h-4' }),
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleBulletList().run()
        },
    },
    {
        title: 'Ordered List',
        icon: React.createElement(ListOrdered, { className: 'w-4 h-4' }),
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleOrderedList().run()
        },
    },
    {
        title: 'Code Block',
        icon: React.createElement(Code, { className: 'w-4 h-4' }),
        command: ({ editor, range }) => {
            editor.chain().focus().deleteRange(range).toggleCodeBlock().run()
        },
    },
]

export const SlashCommand = Extension.create({
    name: 'slashCommand',

    addProseMirrorPlugins() {
        console.log('SlashCommand extension registered') // Debug log

        return [
            Suggestion({
                editor: this.editor,
                char: '/',
                startOfLine: false,
                items: ({ query }) => {
                    console.log('Suggestion items queried:', query) // Debug log
                    return suggestionItems.filter(item =>
                        item.title.toLowerCase().includes(query.toLowerCase())
                    )
                },
                command: ({ editor, range, props }) => {
                    console.log('Command executed:', props) // Debug log
                    props.command({ editor, range })
                },
                render: () => {
                    let component
                    let popup

                    return {
                        onStart: (props) => {
                            console.log('Suggestion onStart', props) // Debug log

                            component = new ReactRenderer(CommandList, {
                                props,
                                editor: props.editor,
                            })

                            if (!props.clientRect) {
                                return
                            }

                            popup = tippy('body', {
                                getReferenceClientRect: props.clientRect,
                                appendTo: () => document.body,
                                content: component.element,
                                showOnCreate: true,
                                interactive: true,
                                trigger: 'manual',
                                placement: 'bottom-start',
                            })
                        },

                        onUpdate(props) {
                            component.updateProps(props)

                            if (!props.clientRect) {
                                return
                            }

                            popup[0].setProps({
                                getReferenceClientRect: props.clientRect,
                            })
                        },

                        onKeyDown(props) {
                            if (props.event.key === 'Escape') {
                                popup[0].hide()
                                return true
                            }

                            return component.ref?.onKeyDown(props)
                        },

                        onExit() {
                            popup[0].destroy()
                            component.destroy()
                        },
                    }
                },
            }),
        ]
    },
})
