import React from 'react'
import ReactDOM from 'react-dom/client'

// Mantine core styles (required for BlockNote Mantine theme)
import '@mantine/core/styles.css'

// Minimal editor styles
import './editor.css'

import { ZedEditor } from './components/zed-editor'

const rootElement = document.getElementById('tiptap-editor')

if (rootElement) {
    // Get initial content from PHP
    // Can be HTML string or BlockNote JSON array
    let initialContent = window.ZERO_INITIAL_CONTENT || null

    // If content is BlockNote JSON array, convert to HTML
    if (initialContent && Array.isArray(initialContent)) {
        initialContent = convertBlocksToHTML(initialContent)
    }

    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <ZedEditor initialContent={initialContent} />
        </React.StrictMode>
    )

    console.log('Zed BlockNote Editor Mounted')
} else {
    console.error('Target container #tiptap-editor not found')
}

/**
 * Convert legacy BlockNote JSON blocks to HTML
 * This allows existing content to work with the new editor
 */
function convertBlocksToHTML(blocks) {
    if (!blocks || !Array.isArray(blocks)) return ''

    return blocks.map(block => {
        const content = block.content || []
        const text = content.map(c => c.text || '').join('')
        const props = block.props || {}

        switch (block.type) {
            case 'heading':
                const level = props.level || 2
                return `<h${level}>${text}</h${level}>`

            case 'paragraph':
                return text ? `<p>${text}</p>` : '<p></p>'

            case 'bulletListItem':
                return `<ul><li>${text}</li></ul>`

            case 'numberedListItem':
                return `<ol><li>${text}</li></ol>`

            case 'codeBlock':
                return `<pre><code>${text}</code></pre>`

            case 'image':
                const url = props.url || props.src || ''
                return url ? `<img src="${url}" />` : ''

            case 'table':
                // Handle table blocks
                return '<table><tbody><tr><td></td></tr></tbody></table>'

            default:
                return text ? `<p>${text}</p>` : ''
        }
    }).join('\n')
}
