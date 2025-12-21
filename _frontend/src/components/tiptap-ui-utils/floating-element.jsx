import { autoUpdate, useFloating } from '@floating-ui/react'
import * as React from 'react'

export const getSelectionBoundingRect = (editor) => {
    const { view, state } = editor
    const { from, to } = state.selection
    const start = view.coordsAtPos(from)
    const end = view.coordsAtPos(to)

    return {
        left: Math.min(start.left, end.left),
        right: Math.max(start.right, end.right),
        top: Math.min(start.top, end.top),
        bottom: Math.max(start.bottom, end.bottom),
        width: Math.abs(start.left - end.left),
        height: Math.abs(start.bottom - end.top),
        x: Math.min(start.left, end.left),
        y: Math.min(start.top, end.top),
    }
}

export const FloatingElement = ({
    editor,
    children,
    shouldShow = true,
    referenceElement = null,
    getBoundingClientRect = null,
    floatingOptions = {}
}) => {
    if (!editor || !shouldShow) return null

    const { refs, floatingStyles } = useFloating({
        strategy: 'fixed',
        placement: 'top',
        whileElementsMounted: autoUpdate,
        ...floatingOptions,
    })

    React.useLayoutEffect(() => {
        if (referenceElement) {
            refs.setReference(referenceElement)
            return
        }

        const getRect = getBoundingClientRect || (() => getSelectionBoundingRect(editor))

        const virtualElement = {
            getBoundingClientRect: () => {
                const rect = getRect(editor)
                return {
                    width: rect.width,
                    height: rect.height,
                    top: rect.top,
                    right: rect.right,
                    bottom: rect.bottom,
                    left: rect.left,
                    x: rect.left,
                    y: rect.top,
                }
            },
        }

        refs.setReference(virtualElement)
    }, [editor, referenceElement, getBoundingClientRect, refs])

    return (
        <div
            ref={refs.setFloating}
            style={{ ...floatingStyles, zIndex: 50 }}
            onMouseDown={(e) => e.preventDefault()}
        >
            {children}
        </div>
    )
}
