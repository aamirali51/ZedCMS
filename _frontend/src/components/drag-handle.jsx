import React, { useState, useEffect, useCallback } from 'react'
import { GripVertical, Plus, Trash2 } from 'lucide-react'

export const DragHandle = ({ editor }) => {
    const [position, setPosition] = useState(null)
    const [isHovering, setIsHovering] = useState(false)

    const updatePosition = useCallback(() => {
        if (!editor) return

        const { from } = editor.state.selection

        try {
            const domInfo = editor.view.domAtPos(from)
            const nodeDOM = domInfo.node instanceof HTMLElement
                ? domInfo.node
                : domInfo.node.parentElement

            // Find the closest top-level block
            const block = nodeDOM?.closest('.ProseMirror > *')

            if (block) {
                const rect = block.getBoundingClientRect()
                const editorRect = editor.view.dom.getBoundingClientRect()

                // Position the handle to the left of the block
                setPosition({
                    top: rect.top - editorRect.top + 2,
                    height: rect.height
                })
            } else {
                setPosition(null)
            }
        } catch (e) {
            setPosition(null)
        }
    }, [editor])

    useEffect(() => {
        if (!editor) return

        editor.on('selectionUpdate', updatePosition)
        editor.on('update', updatePosition)
        editor.on('focus', updatePosition)

        // Initial position
        const timer = setTimeout(updatePosition, 100)

        return () => {
            clearTimeout(timer)
            editor.off('selectionUpdate', updatePosition)
            editor.off('update', updatePosition)
            editor.off('focus', updatePosition)
        }
    }, [editor, updatePosition])

    // Also update on mouse move over the editor
    useEffect(() => {
        if (!editor) return

        const editorElement = editor.view.dom

        const handleMouseMove = (e) => {
            // Find the block element under the mouse
            const elements = document.elementsFromPoint(e.clientX, e.clientY)
            const block = elements.find(el => el.matches('.ProseMirror > *'))

            if (block) {
                const rect = block.getBoundingClientRect()
                const editorRect = editorElement.getBoundingClientRect()

                setPosition({
                    top: rect.top - editorRect.top + 2,
                    height: rect.height
                })
                setIsHovering(true)
            }
        }

        const handleMouseLeave = () => {
            setIsHovering(false)
            updatePosition()
        }

        editorElement.addEventListener('mousemove', handleMouseMove)
        editorElement.addEventListener('mouseleave', handleMouseLeave)

        return () => {
            editorElement.removeEventListener('mousemove', handleMouseMove)
            editorElement.removeEventListener('mouseleave', handleMouseLeave)
        }
    }, [editor, updatePosition])

    if (!position) return null

    const selectCurrentBlock = () => {
        const { from } = editor.state.selection
        const $pos = editor.state.doc.resolve(from)

        // Find the start and end of the current top-level node
        if ($pos.depth > 0) {
            const start = $pos.before(1)
            const end = $pos.after(1)
            editor.chain().focus().setTextSelection({ from: start, to: end }).run()
        }
    }

    const insertNewParagraph = () => {
        const { from } = editor.state.selection
        const $pos = editor.state.doc.resolve(from)

        // Insert at the end of current block
        if ($pos.depth > 0) {
            const endOfBlock = $pos.after(1)
            editor.chain()
                .focus()
                .insertContentAt(endOfBlock, { type: 'paragraph' })
                .setTextSelection(endOfBlock + 1)
                .run()
        }
    }

    const deleteCurrentBlock = () => {
        const { from } = editor.state.selection
        const $pos = editor.state.doc.resolve(from)

        if ($pos.depth > 0) {
            const start = $pos.before(1)
            const end = $pos.after(1)
            editor.chain().focus().deleteRange({ from: start, to: end }).run()
        }
    }

    return (
        <div
            className="absolute flex items-center gap-1 transition-opacity duration-150"
            style={{
                top: position.top,
                left: -60,
                opacity: isHovering ? 1 : 0.6,
                zIndex: 40
            }}
            onMouseEnter={() => setIsHovering(true)}
            onMouseLeave={() => setIsHovering(false)}
        >
            {/* ADD BUTTON - Insert new paragraph below */}
            <button
                className="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all duration-150"
                onClick={insertNewParagraph}
                title="Add block below"
            >
                <Plus className="w-5 h-5" />
            </button>

            {/* DRAG GRIP - Selects the whole block */}
            <button
                className="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-md cursor-grab transition-all duration-150 active:cursor-grabbing"
                onClick={selectCurrentBlock}
                onMouseDown={(e) => {
                    // Select the block on mouse down for immediate feedback
                    selectCurrentBlock()
                }}
                title="Click to select block, then Ctrl+X to cut"
            >
                <GripVertical className="w-5 h-5" />
            </button>

            {/* DELETE BUTTON - Remove the block */}
            <button
                className="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all duration-150"
                onClick={deleteCurrentBlock}
                title="Delete block"
            >
                <Trash2 className="w-4 h-4" />
            </button>
        </div>
    )
}
