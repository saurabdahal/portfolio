@php
    use App\Filament\Resources\BlogPostResource;
@endphp

<div>
    @vite(['resources/css/blog-writer.css', 'resources/js/blog-writer.js'])

    <div class="writer-page">
        <header class="writer-topbar">
            <a href="{{ BlogPostResource::getUrl() }}" class="writer-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
                Posts
            </a>

            <span class="writer-status">
                @if ($last_saved)
                    Saved {{ $last_saved }}
                @else
                    Start writing — drafts save automatically
                @endif
            </span>

            <div class="writer-actions">
                @if ($record)
                    <a
                        href="{{ $record->previewUrl() }}"
                        target="_blank"
                        rel="noopener"
                        class="writer-btn"
                    >
                        Preview
                    </a>
                @endif
                <button type="button" class="writer-btn" wire:click="toggleSettings">
                    Settings
                </button>
                <button type="button" class="writer-btn" wire:click="saveDraft">
                    Save
                </button>
                <button type="button" class="writer-btn writer-btn-primary" wire:click="publish">
                    Publish
                </button>
            </div>
        </header>

        <div class="writer-canvas">
            <input
                type="text"
                wire:model.live.debounce.800ms="post_title"
                class="writer-title"
                placeholder="Title"
                autocomplete="off"
            />

            <textarea
                wire:model.live.debounce.800ms="brief"
                class="writer-subtitle"
                placeholder="Add a subtitle..."
                rows="2"
            ></textarea>

            <div
                class="writer-editor-wrap"
                x-data="blogWriter($wire.$entangle('content_html'))"
                @editor-image-uploaded.window="insertImageUrl($event.detail?.url ?? $event.detail[0]?.url)"
            >
                <div class="writer-insert-bar">
                    <button type="button" class="writer-insert-btn" title="Upload image" aria-label="Upload image" @click="pickImage()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 8.25h.008v.008H6.75V8.25Z"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Image from URL" aria-label="Image from URL" @click="run('imageUrl')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Table" aria-label="Table" @click="run('table')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6ZM8.25 3.75v16.5M15.75 3.75v16.5M3.75 9h16.5M3.75 15h16.5"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Link" aria-label="Link" @click="run('link')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m9.86-2.54a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Date" aria-label="Date" @click="run('date')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M4.5 8.25h15M5.25 20.25h13.5A2.25 2.25 0 0 0 21 18V6.75A2.25 2.25 0 0 0 18.75 4.5H5.25A2.25 2.25 0 0 0 3 6.75v11.25A2.25 2.25 0 0 0 5.25 20.25Z"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Divider" aria-label="Divider" @click="run('divider')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" d="M4.5 12h15"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Bullet list" aria-label="Bullet list" @click="run('bullet')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.008v.008H3.75V6.75Zm.008 5.25h.008v.008H3.75v-.008Zm0 5.25h.008v.008H3.75v-.008Z"/></svg>
                    </button>
                    <button type="button" class="writer-insert-btn" title="Code block" aria-label="Code block" @click="run('code')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25"/></svg>
                    </button>
                </div>

                <input
                    type="file"
                    id="editor-image-input"
                    x-ref="imageInput"
                    wire:model="editor_image"
                    accept="image/*"
                    class="hidden"
                />

                <div wire:ignore>
                    <div x-ref="bubbleMenu" class="writer-bubble-menu">
                        <button type="button" @mousedown.prevent @click="run('bold')" :class="{ 'is-active': isActive('bold') }">B</button>
                        <button type="button" @mousedown.prevent @click="run('italic')" :class="{ 'is-active': isActive('italic') }"><em>I</em></button>
                        <button type="button" @mousedown.prevent @click="run('underline')" :class="{ 'is-active': isActive('underline') }"><u>U</u></button>
                        <button type="button" @mousedown.prevent @click="run('strike')" :class="{ 'is-active': isActive('strike') }"><s>S</s></button>
                        <button type="button" @mousedown.prevent @click="run('link')">🔗</button>
                        <button type="button" @mousedown.prevent @click="run('h2')" :class="{ 'is-active': isActive('heading', { level: 2 }) }">H2</button>
                        <button type="button" @mousedown.prevent @click="run('h3')" :class="{ 'is-active': isActive('heading', { level: 3 }) }">H3</button>
                        <button type="button" @mousedown.prevent @click="run('quote')">❝</button>
                        <button type="button" @mousedown.prevent @click="run('code')">&lt;/&gt;</button>
                    </div>

                    <div x-ref="editor" class="writer-editor"></div>
                </div>
            </div>
        </div>

        @if ($settings_open)
            <div class="writer-drawer-backdrop" wire:click="closeSettings"></div>
            <aside class="writer-drawer">
                <h2>Post settings</h2>

                <div class="writer-field">
                    <label for="post-slug">URL slug</label>
                    <input id="post-slug" type="text" wire:model.blur="post_slug" placeholder="your-post-slug" />
                    <p style="font-size:0.75rem;color:var(--writer-muted);margin-top:0.35rem;">/blogs/{{ $post_slug ?: 'your-slug' }}</p>
                </div>

                <div class="writer-field writer-toggle">
                    <input id="published-toggle" type="checkbox" wire:model="is_visible" />
                    <label for="published-toggle">Published on site</label>
                </div>

                <div class="writer-field">
                    <label for="publish-date">Publish date</label>
                    <input id="publish-date" type="datetime-local" wire:model="published_at" />
                </div>

                <div class="writer-field">
                    <label>Cover image</label>
                    @if ($this->coverPreviewUrl())
                        <img src="{{ $this->coverPreviewUrl() }}" alt="Cover" class="writer-cover-preview" />
                    @endif
                    <input type="file" wire:model="cover_upload" accept="image/*" />
                </div>

                <div class="writer-field">
                    <label for="tags">Tags</label>
                    <input id="tags" type="text" wire:model.blur="tags_input" placeholder="aws, python, s3" />
                </div>

                <button type="button" class="writer-btn writer-btn-primary" style="width:100%;margin-top:1rem;" wire:click="closeSettings">
                    Done
                </button>
            </aside>
        @endif
    </div>
</div>
