import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import '@mantine/core/styles.css';
import { BlockNoteEditor } from './components/blocknote-editor'

const rootElement = document.getElementById('tiptap-editor')

if (rootElement) {
    // Get initial data from PHP
    let content = window.ZERO_INITIAL_CONTENT || null

    // Validate content is valid BlockNote JSON format
    // BlockNote expects an array of block objects
    if (content && typeof content === 'object') {
        // Check if it's BlockNote format (array of blocks)
        if (!Array.isArray(content)) {
            // It's probably old Tiptap format - start fresh
            console.warn('Content is not in BlockNote format, starting with empty document')
            content = null
        }
    } else if (typeof content === 'string') {
        // If it's a string, try to parse it
        try {
            const parsed = JSON.parse(content)
            if (Array.isArray(parsed)) {
                content = JSON.stringify(parsed)
            } else {
                content = null
            }
        } catch {
            content = null
        }
    } else {
        content = null
    }

    // Convert content to string for BlockNoteEditor if valid
    const contentString = content ? JSON.stringify(content) : null

    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <BlockNoteEditor initialContent={contentString} />
        </React.StrictMode>
    )

    console.log('BlockNote Editor Mounted')
} else {
    console.error('Target container #tiptap-editor not found')
}
