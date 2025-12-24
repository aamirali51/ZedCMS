import { Node, mergeAttributes } from '@tiptap/core'
import { ReactNodeViewRenderer } from '@tiptap/react'
import { NodeViewWrapper } from '@tiptap/react'
import { useState } from 'react'

/**
 * YouTube/Video Embed Extension for TipTap
 * 
 * Embeds YouTube and Vimeo videos
 */

// Extract video ID from various URL formats
const getVideoInfo = (url) => {
    if (!url) return null

    // YouTube
    const youtubeMatch = url.match(
        /(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
    )
    if (youtubeMatch) {
        return {
            type: 'youtube',
            id: youtubeMatch[1],
            embedUrl: `https://www.youtube.com/embed/${youtubeMatch[1]}`
        }
    }

    // Vimeo
    const vimeoMatch = url.match(/vimeo\.com\/(\d+)/)
    if (vimeoMatch) {
        return {
            type: 'vimeo',
            id: vimeoMatch[1],
            embedUrl: `https://player.vimeo.com/video/${vimeoMatch[1]}`
        }
    }

    return null
}

// React component for the video embed
const YouTubeComponent = ({ node, updateAttributes }) => {
    const [inputUrl, setInputUrl] = useState('')
    const url = node.attrs.src
    const videoInfo = getVideoInfo(url)

    const handleSubmit = (e) => {
        e.preventDefault()
        if (inputUrl) {
            updateAttributes({ src: inputUrl })
            setInputUrl('')
        }
    }

    if (!videoInfo) {
        // Show URL input
        return (
            <NodeViewWrapper className="youtube-card youtube-empty">
                <div style={{
                    background: '#f8fafc',
                    border: '2px dashed #e2e8f0',
                    borderRadius: '12px',
                    padding: '32px',
                    textAlign: 'center',
                    margin: '16px 0'
                }}>
                    <span
                        className="material-symbols-outlined"
                        style={{ fontSize: '48px', color: '#94a3b8', display: 'block', marginBottom: '12px' }}
                    >
                        play_circle
                    </span>
                    <form onSubmit={handleSubmit} style={{ maxWidth: '400px', margin: '0 auto' }}>
                        <input
                            type="text"
                            value={inputUrl}
                            onChange={(e) => setInputUrl(e.target.value)}
                            placeholder="Paste YouTube or Vimeo URL..."
                            style={{
                                width: '100%',
                                padding: '12px 16px',
                                fontSize: '14px',
                                border: '1px solid #e2e8f0',
                                borderRadius: '8px',
                                marginBottom: '12px'
                            }}
                        />
                        <button
                            type="submit"
                            style={{
                                background: '#6366f1',
                                color: 'white',
                                border: 'none',
                                padding: '10px 24px',
                                borderRadius: '8px',
                                cursor: 'pointer',
                                fontSize: '14px',
                                fontWeight: '500'
                            }}
                        >
                            Embed Video
                        </button>
                    </form>
                </div>
            </NodeViewWrapper>
        )
    }

    // Show embedded video
    return (
        <NodeViewWrapper className="youtube-card">
            <div style={{
                position: 'relative',
                paddingBottom: '56.25%', // 16:9 aspect ratio
                height: 0,
                overflow: 'hidden',
                borderRadius: '12px',
                margin: '16px 0',
                background: '#000'
            }}>
                <iframe
                    src={videoInfo.embedUrl}
                    style={{
                        position: 'absolute',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        border: 'none'
                    }}
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowFullScreen
                />
            </div>
            <button
                onClick={() => updateAttributes({ src: '' })}
                contentEditable={false}
                style={{
                    position: 'absolute',
                    top: '24px',
                    right: '8px',
                    background: 'rgba(0,0,0,0.7)',
                    color: 'white',
                    border: 'none',
                    borderRadius: '4px',
                    padding: '4px 8px',
                    fontSize: '12px',
                    cursor: 'pointer'
                }}
            >
                Change
            </button>
        </NodeViewWrapper>
    )
}

// TipTap extension
export const YouTube = Node.create({
    name: 'youtube',

    group: 'block',

    atom: true,

    addAttributes() {
        return {
            src: {
                default: null
            }
        }
    },

    parseHTML() {
        return [
            {
                tag: 'div[data-youtube]'
            }
        ]
    },

    renderHTML({ HTMLAttributes }) {
        return ['div', mergeAttributes(HTMLAttributes, { 'data-youtube': '' })]
    },

    addNodeView() {
        return ReactNodeViewRenderer(YouTubeComponent)
    },

    addCommands() {
        return {
            setYouTube: (attributes) => ({ commands }) => {
                return commands.insertContent({
                    type: this.name,
                    attrs: attributes
                })
            }
        }
    }
})
