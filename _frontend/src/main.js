import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import Placeholder from '@tiptap/extension-placeholder';
import Typography from '@tiptap/extension-typography';

// Image upload handler function
const uploadImage = async (file) => {
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
        } else {
            throw new Error(data.error || 'Upload failed');
        }
    } catch (error) {
        console.error('Error uploading image:', error);
        alert('Failed to upload image: ' + error.message);
        return null;
    }
};

// Initialize Editor
const editor = new Editor({
    element: document.querySelector('#tiptap-editor'),
    extensions: [
        StarterKit,
        Typography,
        Link.configure({
            openOnClick: false,
        }),
        Image.configure({
            inline: true,
            allowBase64: true,
        }),
        Placeholder.configure({
            placeholder: 'Write something epic...',
        }),
    ],
    content: window.ZERO_INITIAL_CONTENT || '',
    autofocus: true,
    editorProps: {
        handleDrop: (view, event, slice, moved) => {
            if (!moved && event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0]) {
                const file = event.dataTransfer.files[0];

                if (file.type.startsWith('image/')) {
                    uploadImage(file).then(url => {
                        if (url) {
                            const { schema } = view.state;
                            const coordinates = view.posAtCoords({ left: event.clientX, top: event.clientY });
                            const node = schema.nodes.image.create({ src: url });
                            const transaction = view.state.tr.insert(coordinates.pos, node);
                            view.dispatch(transaction);
                        }
                    });
                    return true; // Handled
                }
            }
            return false;
        },
        handlePaste: (view, event, slice) => {
            const items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (const item of items) {
                if (item.type.indexOf('image') === 0) {
                    const file = item.getAsFile();

                    uploadImage(file).then(url => {
                        if (url) {
                            const transaction = view.state.tr.replaceSelectionWith(
                                view.state.schema.nodes.image.create({ src: url })
                            );
                            view.dispatch(transaction);
                        }
                    });
                    return true; // Handled
                }
            }
            return false;
        }
    },
});

// Expose editor to window for PHP to access
window.editor = editor;

console.log('Tiptap Editor initialized');
