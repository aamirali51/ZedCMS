import { Extension } from '@tiptap/core'
import Suggestion from '@tiptap/suggestion'

export const SlashCommand = Extension.create({
    name: 'slashCommand',

    addOptions() {
        return {
            suggestion: {
                char: '/',
                command: ({ editor, range, props }) => {
                    props.command({ editor, range })
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

export const getSuggestionItems = ({ query }) => {
    const items = [
        {
            title: 'Heading 1',
            command: ({ editor, range }) => {
                editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .setNode('heading', { level: 1 })
                    .run()
            },
        },
        {
            title: 'Heading 2',
            command: ({ editor, range }) => {
                editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .setNode('heading', { level: 2 })
                    .run()
            },
        },
        {
            title: 'Bullet List',
            command: ({ editor, range }) => {
                editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .toggleBulletList()
                    .run()
            },
        },
        {
            title: 'Numbered List',
            command: ({ editor, range }) => {
                editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .toggleOrderedList()
                    .run()
            },
        },
        {
            title: 'Quote',
            command: ({ editor, range }) => {
                editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .toggleBlockquote()
                    .run()
            },
        },
        {
            title: 'Code Block',
            command: ({ editor, range }) => {
                editor
                    .chain()
                    .focus()
                    .deleteRange(range)
                    .toggleCodeBlock()
                    .run()
            },
        },
    ]

    return items.filter(item =>
        item.title.toLowerCase().startsWith(query.toLowerCase())
    )
}
