/**
 * Zed CMS Editor - BlockNote Implementation
 * 
 * Save/Load Flow:
 * - SAVE: BlockNote JSON blocks → stored in database
 * - EDIT: BlockNote JSON blocks → loaded directly into editor
 * - DISPLAY: BlockNote JSON blocks → converted to HTML by PHP renderer
 */

import { useCreateBlockNote } from "@blocknote/react"
import { BlockNoteView } from "@blocknote/mantine"
import "@blocknote/mantine/style.css"

/**
 * Image upload handler
 */
async function uploadFile(file) {
    const formData = new FormData()
    formData.append('file', file)

    try {
        const response = await fetch('/ZedCMS/admin/api?action=upload_media', {
            method: 'POST',
            body: formData
        })

        if (response.ok) {
            const data = await response.json()
            return data.url || data.file_url
        }
    } catch (error) {
        console.warn('Upload failed, using data URL fallback:', error)
    }

    // Fallback: convert to data URL
    return new Promise((resolve) => {
        const reader = new FileReader()
        reader.onload = () => resolve(reader.result)
        reader.readAsDataURL(file)
    })
}

/**
 * ZedEditor Component
 * 
 * @param {Object} props
 * @param {Array} props.initialBlocks - BlockNote JSON blocks array to load
 * @param {Function} props.onChange - Called when content changes (receives blocks array)
 */
export const ZedEditor = ({ initialBlocks, onChange }) => {
    // Create BlockNote editor with initial content
    const editor = useCreateBlockNote({
        uploadFile,
        defaultStyles: true,
        // Load initial blocks directly if provided
        initialContent: initialBlocks || undefined,
    })

    // Handle content changes - save blocks as JSON (NOT HTML)
    const handleChange = () => {
        if (editor) {
            // Get the blocks as JSON array
            const blocks = editor.document;

            // Store in global for save handler
            window.zed_editor_content = blocks;

            if (onChange) {
                onChange(blocks);
            }
        }
    }

    // Also set initial content on first render
    if (editor && !editor._initialized) {
        editor._initialized = true;
        window.zed_editor_content = editor.document;
    }

    if (!editor) {
        return <div className="editor-loading">Loading editor...</div>
    }

    return (
        <div className="zed-editor">
            <BlockNoteView
                editor={editor}
                onChange={handleChange}
                theme="light"
            />
        </div>
    )
}

export default ZedEditor
