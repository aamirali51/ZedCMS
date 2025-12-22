import "@blocknote/core/fonts/inter.css";
import { useCreateBlockNote } from "@blocknote/react";
import { BlockNoteView } from "@blocknote/mantine";
import "@blocknote/mantine/style.css";
import { MantineProvider } from "@mantine/core";
import { useState, useEffect, useCallback } from "react";

export const BlockNoteEditor = ({ initialContent, initialTitle = "" }) => {
    const [title, setTitle] = useState(initialTitle);

    // 1. Initialize the editor
    const editor = useCreateBlockNote({
        initialContent: initialContent ? JSON.parse(initialContent) : undefined,
        // Custom upload handler for images
        uploadFile: async (file) => {
            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('/admin/api/upload', {
                    method: 'POST',
                    body: formData,
                });
                const data = await response.json();
                if (data.success && data.file && data.file.url) {
                    return data.file.url;
                }
                throw new Error(data.error || 'Upload failed');
            } catch (error) {
                console.error('Image upload error:', error);
                return null;
            }
        },
    });

    // 2. Handle content changes - sync to global variable for saving
    const onChange = useCallback(() => {
        window.zero_editor_content = editor.document;
    }, [editor]);

    // 3. Handle title changes
    const handleTitleChange = (e) => {
        setTitle(e.target.value);
        // Sync title to sidebar input if it exists
        const sidebarTitle = document.getElementById('post-title');
        if (sidebarTitle) {
            sidebarTitle.value = e.target.value;
            // Trigger input event for slug generation
            sidebarTitle.dispatchEvent(new Event('input', { bubbles: true }));
        }
    };

    // 4. Expose editor to window for external access
    useEffect(() => {
        if (editor) {
            window.editor = editor;
            window.zero_editor_content = editor.document;
        }
    }, [editor]);

    // 5. Sync initial title from sidebar
    useEffect(() => {
        const sidebarTitle = document.getElementById('post-title');
        if (sidebarTitle && sidebarTitle.value) {
            setTitle(sidebarTitle.value);
        }
    }, []);

    // 6. Render WordPress-style layout
    return (
        <MantineProvider>
            <div className="wordpress-editor w-full min-h-screen bg-white">
                {/* Centered Content Container */}
                <div className="max-w-[650px] mx-auto pt-16 px-6">
                    {/* Large Title Input - WordPress Style */}
                    <input
                        type="text"
                        value={title}
                        onChange={handleTitleChange}
                        placeholder="Add title"
                        className="w-full text-4xl font-serif font-normal text-gray-900 placeholder-gray-400 border-0 outline-none focus:outline-none focus:ring-0 mb-4 bg-transparent"
                        style={{
                            fontFamily: "'Lora', Georgia, serif",
                            lineHeight: 1.2
                        }}
                    />

                    {/* BlockNote Editor */}
                    <div className="blocknote-container -ml-12">
                        <BlockNoteView
                            editor={editor}
                            onChange={onChange}
                            theme="light"
                        />
                    </div>
                </div>
            </div>
        </MantineProvider>
    );
};
