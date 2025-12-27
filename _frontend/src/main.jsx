import React from 'react'
import ReactDOM from 'react-dom/client'

// Mantine core styles (required for BlockNote Mantine theme)
import '@mantine/core/styles.css'

// Minimal editor styles
import './editor.css'

import { ZedEditor } from './components/zed-editor'

const rootElement = document.getElementById('tiptap-editor')

if (rootElement) {
    // Get initial blocks from PHP (BlockNote JSON format)
    // This is an array of blocks, NOT HTML
    let initialBlocks = window.ZED_INITIAL_CONTENT || null;

    // Validate it's a proper blocks array
    if (initialBlocks && Array.isArray(initialBlocks) && initialBlocks.length > 0) {
        // Check if it has real content (not just empty default block)
        const hasRealContent = initialBlocks.some(block => {
            if (block.type === 'paragraph' && (!block.content || block.content.length === 0)) {
                return false;
            }
            return true;
        });

        if (hasRealContent) {
            console.log('Loading BlockNote blocks:', initialBlocks.length, 'blocks');
        } else {
            initialBlocks = null; // Let editor use its own default
        }
    } else {
        initialBlocks = null;
    }

    ReactDOM.createRoot(rootElement).render(
        <React.StrictMode>
            <ZedEditor initialBlocks={initialBlocks} />
        </React.StrictMode>
    )

    console.log('Zed BlockNote Editor Mounted');
} else {
    console.error('Target container #tiptap-editor not found');
}
