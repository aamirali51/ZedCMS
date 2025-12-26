/**
 * Zed CMS Editor - BlockNote Implementation
 * 
 * A Notion-style block editor with:
 * - Slash menu for block insertion
 * - Drag handles for block reordering
 * - Formatting toolbar
 * - Table support
 * - Image uploads
 * - Dark mode via Mantine
 */

import { useCreateBlockNote } from "@blocknote/react"
import { BlockNoteView } from "@blocknote/mantine"
import "@blocknote/mantine/style.css"

/**
 * Image upload handler
 * Replace this with your actual upload logic
 */
async function uploadFile(file) {
    // Create FormData for upload
    const formData = new FormData()
    formData.append('file', file)

    try {
        // Try to upload to Zed CMS media endpoint
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

    // Fallback: convert to data URL for preview
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
 * @param {string} props.initialContent - HTML content to load
 * @param {Function} props.onChange - Called when content changes (receives HTML string)
 */
export const ZedEditor = ({ initialContent, onChange }) => {
    // Create BlockNote editor instance
    const editor = useCreateBlockNote({
        uploadFile,
        // Default block types enabled
        defaultStyles: true,
    })

    // Handle content changes - convert blocks to HTML
    const handleChange = async () => {
        if (editor) {
            try {
                // Get HTML from blocks
                const html = await editor.blocksToHTMLLossy(editor.document)

                // Store in global for save handler
                window.zed_editor_content = html

                // Call onChange prop if provided
                if (onChange) {
                    onChange(html)
                }
            } catch (error) {
                console.error('Error converting blocks to HTML:', error)
            }
        }
    }

    // Load initial content when editor is ready
    const handleEditorReady = async (editorInstance) => {
        if (initialContent && typeof initialContent === 'string' && initialContent.trim()) {
            try {
                // Parse HTML to blocks
                const blocks = await editorInstance.tryParseHTMLToBlocks(initialContent)
                if (blocks && blocks.length > 0) {
                    editorInstance.replaceBlocks(editorInstance.document, blocks)
                }
            } catch (error) {
                console.warn('Could not parse initial HTML content:', error)
            }
        }
    }

    // Initialize content on first render
    if (editor && initialContent && !editor._contentLoaded) {
        editor._contentLoaded = true
        handleEditorReady(editor)
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
                data-theming-css-variables-demo
            />
        </div>
    )
}

export default ZedEditor
