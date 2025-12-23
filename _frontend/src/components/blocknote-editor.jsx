import "@blocknote/core/fonts/inter.css";
import { useCreateBlockNote } from "@blocknote/react";
import { BlockNoteView } from "@blocknote/mantine";
import "@blocknote/mantine/style.css";
import { MantineProvider } from "@mantine/core";
import { useState, useEffect, useCallback } from "react";

/**
 * BlockNote Editor Component
 * 
 * Refactored to:
 * - Use dynamic width (no hard-coded max-width)
 * - Support light/dark theme based on admin settings
 * - Remove duplicate title input (title is in sidebar)
 * - Use BlockNote CSS variables for theming
 */
export const BlockNoteEditor = ({ initialContent }) => {
    const [theme, setTheme] = useState("light");

    // Detect theme from admin panel
    useEffect(() => {
        const checkTheme = () => {
            const isDark = document.documentElement.classList.contains('dark');
            setTheme(isDark ? "dark" : "light");
        };

        checkTheme();

        // Watch for theme changes
        const observer = new MutationObserver(checkTheme);
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });

        return () => observer.disconnect();
    }, []);

    // Initialize the editor with BlockNote options
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

    // Handle content changes - sync to global variable for saving
    const onChange = useCallback(() => {
        window.zero_editor_content = editor.document;
    }, [editor]);

    // Expose editor to window for external access
    useEffect(() => {
        if (editor) {
            window.editor = editor;
            window.zero_editor_content = editor.document;
        }
    }, [editor]);

    // Render clean full-width editor
    return (
        <MantineProvider>
            <div className="blocknote-wrapper">
                <BlockNoteView
                    editor={editor}
                    onChange={onChange}
                    theme={theme}
                />
            </div>
        </MantineProvider>
    );
};
