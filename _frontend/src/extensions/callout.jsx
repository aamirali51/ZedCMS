import { Node, mergeAttributes } from '@tiptap/core'
import { ReactNodeViewRenderer } from '@tiptap/react'
import { NodeViewWrapper, NodeViewContent } from '@tiptap/react'

/**
 * Callout Extension for TipTap
 * 
 * Creates info/warning/success/error boxes
 */

// React component for the callout
const CalloutComponent = ({ node, updateAttributes }) => {
    const type = node.attrs.type || 'info'

    const icons = {
        info: 'info',
        warning: 'warning',
        success: 'check_circle',
        error: 'error'
    }

    const colors = {
        info: { bg: '#eff6ff', border: '#3b82f6', icon: '#3b82f6' },
        warning: { bg: '#fffbeb', border: '#f59e0b', icon: '#f59e0b' },
        success: { bg: '#f0fdf4', border: '#22c55e', icon: '#22c55e' },
        error: { bg: '#fef2f2', border: '#ef4444', icon: '#ef4444' }
    }

    const style = colors[type] || colors.info

    return (
        <NodeViewWrapper
            className={`callout-card callout-${type}`}
            style={{
                background: style.bg,
                borderLeft: `4px solid ${style.border}`,
                borderRadius: '8px',
                padding: '16px 16px 16px 48px',
                margin: '16px 0',
                position: 'relative'
            }}
        >
            <span
                className="material-symbols-outlined callout-icon"
                style={{
                    position: 'absolute',
                    left: '16px',
                    top: '16px',
                    color: style.icon,
                    fontSize: '20px'
                }}
            >
                {icons[type]}
            </span>

            {/* Type selector */}
            <select
                value={type}
                onChange={(e) => updateAttributes({ type: e.target.value })}
                style={{
                    position: 'absolute',
                    right: '12px',
                    top: '12px',
                    fontSize: '11px',
                    padding: '2px 6px',
                    border: '1px solid #e2e8f0',
                    borderRadius: '4px',
                    background: 'white',
                    cursor: 'pointer'
                }}
                contentEditable={false}
            >
                <option value="info">Info</option>
                <option value="warning">Warning</option>
                <option value="success">Success</option>
                <option value="error">Error</option>
            </select>

            <NodeViewContent className="callout-content" />
        </NodeViewWrapper>
    )
}

// TipTap extension
export const Callout = Node.create({
    name: 'callout',

    group: 'block',

    content: 'inline*',

    defining: true,

    addAttributes() {
        return {
            type: {
                default: 'info',
                parseHTML: element => element.getAttribute('data-type'),
                renderHTML: attributes => ({
                    'data-type': attributes.type
                })
            }
        }
    },

    parseHTML() {
        return [
            {
                tag: 'div[data-callout]'
            }
        ]
    },

    renderHTML({ HTMLAttributes }) {
        return ['div', mergeAttributes(HTMLAttributes, { 'data-callout': '' }), 0]
    },

    addNodeView() {
        return ReactNodeViewRenderer(CalloutComponent)
    },

    addCommands() {
        return {
            setCallout: (attributes) => ({ commands }) => {
                return commands.insertContent({
                    type: this.name,
                    attrs: attributes,
                    content: [{ type: 'text', text: 'Type your message here...' }]
                })
            }
        }
    }
})
