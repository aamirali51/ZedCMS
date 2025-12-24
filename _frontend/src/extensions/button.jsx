import { Node, mergeAttributes } from '@tiptap/core'
import { ReactNodeViewRenderer } from '@tiptap/react'
import { NodeViewWrapper } from '@tiptap/react'
import { useState } from 'react'

/**
 * Button/CTA Extension for TipTap
 * 
 * Creates clickable call-to-action buttons
 */

// React component for the button
const ButtonComponent = ({ node, updateAttributes }) => {
    const [isEditing, setIsEditing] = useState(false)
    const [text, setText] = useState(node.attrs.text || 'Click Here')
    const [url, setUrl] = useState(node.attrs.url || '')

    const style = node.attrs.style || 'primary'
    const align = node.attrs.align || 'left'

    const styles = {
        primary: {
            background: 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)',
            color: 'white',
            border: 'none'
        },
        secondary: {
            background: 'white',
            color: '#6366f1',
            border: '2px solid #6366f1'
        },
        dark: {
            background: '#1e293b',
            color: 'white',
            border: 'none'
        }
    }

    const buttonStyle = styles[style] || styles.primary

    const handleSave = () => {
        updateAttributes({ text, url })
        setIsEditing(false)
    }

    if (isEditing) {
        return (
            <NodeViewWrapper className="button-card button-editing">
                <div style={{
                    background: '#f8fafc',
                    border: '1px solid #e2e8f0',
                    borderRadius: '12px',
                    padding: '20px',
                    margin: '16px 0'
                }}>
                    <div style={{ marginBottom: '12px' }}>
                        <label style={{ fontSize: '12px', fontWeight: '600', color: '#64748b' }}>Button Text</label>
                        <input
                            type="text"
                            value={text}
                            onChange={(e) => setText(e.target.value)}
                            style={{
                                width: '100%',
                                padding: '10px 12px',
                                fontSize: '14px',
                                border: '1px solid #e2e8f0',
                                borderRadius: '8px',
                                marginTop: '4px'
                            }}
                        />
                    </div>
                    <div style={{ marginBottom: '12px' }}>
                        <label style={{ fontSize: '12px', fontWeight: '600', color: '#64748b' }}>Link URL</label>
                        <input
                            type="text"
                            value={url}
                            onChange={(e) => setUrl(e.target.value)}
                            placeholder="https://..."
                            style={{
                                width: '100%',
                                padding: '10px 12px',
                                fontSize: '14px',
                                border: '1px solid #e2e8f0',
                                borderRadius: '8px',
                                marginTop: '4px'
                            }}
                        />
                    </div>
                    <div style={{ display: 'flex', gap: '8px', marginBottom: '12px' }}>
                        <label style={{ fontSize: '12px', fontWeight: '600', color: '#64748b', marginRight: '8px' }}>Style:</label>
                        {['primary', 'secondary', 'dark'].map((s) => (
                            <button
                                key={s}
                                onClick={() => updateAttributes({ style: s })}
                                style={{
                                    padding: '4px 12px',
                                    fontSize: '12px',
                                    border: style === s ? '2px solid #6366f1' : '1px solid #e2e8f0',
                                    borderRadius: '4px',
                                    cursor: 'pointer',
                                    background: style === s ? '#eff6ff' : 'white',
                                    textTransform: 'capitalize'
                                }}
                            >
                                {s}
                            </button>
                        ))}
                    </div>
                    <div style={{ display: 'flex', gap: '8px' }}>
                        <button
                            onClick={handleSave}
                            style={{
                                background: '#6366f1',
                                color: 'white',
                                border: 'none',
                                padding: '8px 16px',
                                borderRadius: '6px',
                                cursor: 'pointer',
                                fontSize: '13px'
                            }}
                        >
                            Save
                        </button>
                        <button
                            onClick={() => setIsEditing(false)}
                            style={{
                                background: '#f1f5f9',
                                color: '#64748b',
                                border: 'none',
                                padding: '8px 16px',
                                borderRadius: '6px',
                                cursor: 'pointer',
                                fontSize: '13px'
                            }}
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </NodeViewWrapper>
        )
    }

    return (
        <NodeViewWrapper className="button-card">
            <div style={{
                margin: '16px 0',
                textAlign: align
            }}>
                <button
                    onClick={() => setIsEditing(true)}
                    style={{
                        ...buttonStyle,
                        padding: '14px 28px',
                        fontSize: '15px',
                        fontWeight: '600',
                        borderRadius: '8px',
                        cursor: 'pointer',
                        transition: 'all 0.2s',
                        boxShadow: '0 2px 8px rgba(0,0,0,0.1)'
                    }}
                    contentEditable={false}
                >
                    {node.attrs.text || 'Click Here'}
                    <span className="material-symbols-outlined" style={{
                        marginLeft: '8px',
                        fontSize: '18px',
                        verticalAlign: 'middle'
                    }}>
                        arrow_forward
                    </span>
                </button>
                <div style={{
                    fontSize: '11px',
                    color: '#94a3b8',
                    marginTop: '4px'
                }}>
                    {node.attrs.url || 'Click to edit'}
                </div>
            </div>
        </NodeViewWrapper>
    )
}

// TipTap extension
export const Button = Node.create({
    name: 'button',

    group: 'block',

    atom: true,

    addAttributes() {
        return {
            text: { default: 'Click Here' },
            url: { default: '' },
            style: { default: 'primary' },
            align: { default: 'left' }
        }
    },

    parseHTML() {
        return [{ tag: 'div[data-button]' }]
    },

    renderHTML({ HTMLAttributes }) {
        return ['div', mergeAttributes(HTMLAttributes, { 'data-button': '' })]
    },

    addNodeView() {
        return ReactNodeViewRenderer(ButtonComponent)
    },

    addCommands() {
        return {
            setButton: (attributes) => ({ commands }) => {
                return commands.insertContent({
                    type: this.name,
                    attrs: attributes
                })
            }
        }
    }
})
