import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import Image from '@tiptap/extension-image';
import Table from '@tiptap/extension-table';
import TableRow from '@tiptap/extension-table-row';
import TableCell from '@tiptap/extension-table-cell';
import TableHeader from '@tiptap/extension-table-header';
import BubbleMenu from '@tiptap/extension-bubble-menu';

function registerBlogWriter() {
    if (!window.Alpine) {
        return;
    }

    window.Alpine.data('blogWriter', (contentRef) => ({
        editor: null,
        content: contentRef,

        init() {
            const bubbleEl = this.$refs.bubbleMenu;

            this.editor = new Editor({
                element: this.$refs.editor,
                extensions: [
                    StarterKit.configure({
                        heading: { levels: [2, 3] },
                    }),
                    Underline,
                    Link.configure({
                        openOnClick: false,
                        autolink: true,
                        defaultProtocol: 'https',
                    }),
                    Image.configure({
                        inline: false,
                        allowBase64: false,
                    }),
                    Table.configure({
                        resizable: true,
                    }),
                    TableRow,
                    TableHeader,
                    TableCell,
                    Placeholder.configure({
                        placeholder: 'Tell your story...',
                    }),
                    BubbleMenu.configure({
                        element: bubbleEl,
                        shouldShow: ({ editor, state }) => {
                            const { selection } = state;
                            const { empty } = selection;
                            return !empty && editor.isEditable;
                        },
                    }),
                ],
                content: this.content || '',
                onUpdate: ({ editor }) => {
                    this.content = editor.getHTML();
                },
                editorProps: {
                    attributes: {
                        class: 'writer-prose',
                        'data-placeholder': 'Tell your story...',
                    },
                },
            });

            this.$watch('content', (value) => {
                if (!this.editor) {
                    return;
                }

                const current = this.editor.getHTML();
                if (value !== current) {
                    this.editor.commands.setContent(value || '', { emitUpdate: false });
                }
            });
        },

        destroy() {
            this.editor?.destroy();
        },

        pickImage() {
            this.$refs.imageInput?.click();
        },

        insertImageUrl(url) {
            if (!this.editor || !url) {
                return;
            }

            this.editor.chain().focus().setImage({ src: url }).run();
        },

        run(command) {
            if (!this.editor) {
                return;
            }

            const chain = this.editor.chain().focus();
            const actions = {
                bold: () => chain.toggleBold().run(),
                italic: () => chain.toggleItalic().run(),
                underline: () => chain.toggleUnderline().run(),
                strike: () => chain.toggleStrike().run(),
                h2: () => chain.toggleHeading({ level: 2 }).run(),
                h3: () => chain.toggleHeading({ level: 3 }).run(),
                quote: () => chain.toggleBlockquote().run(),
                code: () => chain.toggleCodeBlock().run(),
                bullet: () => chain.toggleBulletList().run(),
                ordered: () => chain.toggleOrderedList().run(),
                divider: () => chain.setHorizontalRule().run(),
                table: () => chain.insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run(),
                imageUrl: () => {
                    const url = window.prompt('Image URL', 'https://');
                    if (!url) {
                        return;
                    }
                    chain.setImage({ src: url }).run();
                },
                date: () => {
                    const today = new Date().toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                    });
                    const value = window.prompt('Date to insert', today);
                    if (!value) {
                        return;
                    }
                    chain.insertContent(`<p><strong>${value}</strong></p>`).run();
                },
                link: () => {
                    const { empty } = this.editor.state.selection;

                    if (empty) {
                        const text = window.prompt('Link text', '');
                        const url = window.prompt('URL', 'https://');
                        if (!text || !url) {
                            return;
                        }
                        chain.insertContent(`<a href="${url}">${text}</a>`).run();
                        return;
                    }

                    const previous = this.editor.getAttributes('link').href;
                    const url = window.prompt('URL', previous || 'https://');
                    if (url === null) {
                        return;
                    }
                    if (url === '') {
                        chain.extendMarkRange('link').unsetLink().run();
                        return;
                    }
                    chain.extendMarkRange('link').setLink({ href: url }).run();
                },
            };

            actions[command]?.();
        },

        isActive(name, attrs = {}) {
            return this.editor?.isActive(name, attrs) ?? false;
        },
    }));
}

if (window.Alpine) {
    registerBlogWriter();
} else {
    document.addEventListener('alpine:init', registerBlogWriter);
}
