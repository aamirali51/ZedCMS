import React from 'react'
import ReactDOM from 'react-dom/client'

// Simple CSS imports - no Mantine!
import './editor.css'

import { ZedEditor } from './components/zed-editor'

const rootElement = document.getElementById('tiptap-editor')

if (rootElement) {
    // Get initial data from PHP
    let content = window.ZERO_INITIAL_CONTENT || null

    // Convert BlockNote format to TipTap format if needed
    // TipTap uses ProseMirror JSON which is similar but not identical
    let initialContent = null

    if (content && Array.isArray(content)) {
        // Convert BlockNote blocks to HTML for TipTap
        initialContent = convertBlocksToHTML(content)
    }

    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <ZedEditor initialContent={initialContent} />
        </React.StrictMode>
    )

    console.log('Zed TipTap Editor Mounted')
} else {
    console.error('Target container #tiptap-editor not found')
}

/**
 * Convert BlockNote blocks to HTML for TipTap
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

            default:
                return text ? `<p>${text}</p>` : ''
        }
    }).join('\n')
}
