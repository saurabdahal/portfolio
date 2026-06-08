<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): HtmlString => new HtmlString($this->blogEditorStyles()),
        );
    }

    /**
     * Medium-style writing canvas for the blog editor.
     * Scoped entirely to the `ed-*` classes used only on the blog create/edit form.
     */
    protected function blogEditorStyles(): string
    {
        return <<<'HTML'
<style>
    /* Centered, chrome-free writing canvas */
    .ed-canvas.fi-section,
    .ed-canvas {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
        --tw-ring-color: transparent !important;
    }
    .ed-canvas .fi-section-content-ctn { border: none !important; background: transparent !important; }
    .ed-canvas .fi-section-content { padding: 0 !important; }
    .ed-canvas {
        max-width: 720px;
        margin-inline: auto;
        width: 100%;
    }

    /* Strip the boxed input look from title / subtitle / body */
    .ed-title .fi-input-wrp,
    .ed-subtitle .fi-input-wrp,
    .ed-body .fi-fo-rich-editor {
        box-shadow: none !important;
        background: transparent !important;
        border: none !important;
        --tw-ring-color: transparent !important;
        --tw-ring-width: 0 !important;
    }

    /* Title */
    .ed-title .fi-input {
        font-family: Georgia, 'Iowan Old Style', 'Times New Roman', serif !important;
        font-size: 2.6rem !important;
        font-weight: 800 !important;
        line-height: 1.15 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* Subtitle */
    .ed-subtitle .fi-input {
        font-family: Georgia, 'Iowan Old Style', 'Times New Roman', serif !important;
        font-size: 1.4rem !important;
        font-weight: 400 !important;
        line-height: 1.55 !important;
        padding-left: 0 !important;
        color: #6b7280 !important;
        resize: none;
    }

    /* Body — minimal sticky toolbar + large serif canvas */
    .ed-body .fi-fo-rich-editor-toolbar {
        position: sticky;
        top: 0;
        z-index: 20;
        border: none !important;
        border-bottom: 1px solid rgba(130, 130, 130, 0.18) !important;
        padding-left: 0 !important;
        margin-bottom: 0.5rem;
        background: var(--fi-body-bg, rgba(255, 255, 255, 0.85));
        backdrop-filter: blur(8px);
    }
    .dark .ed-body .fi-fo-rich-editor-toolbar {
        background: rgba(17, 17, 17, 0.85);
    }
    .ed-body .fi-fo-rich-editor-editor,
    .ed-body trix-editor {
        font-family: Georgia, 'Iowan Old Style', 'Times New Roman', serif !important;
        font-size: 1.3rem !important;
        line-height: 1.9 !important;
        min-height: 62vh !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        border: none !important;
    }
    .ed-body trix-editor h1 { font-size: 2rem !important; }
    .ed-body trix-editor h2 { font-size: 1.65rem !important; }
    .ed-body trix-editor pre {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace !important;
        font-size: 0.95rem !important;
    }
</style>
HTML;
    }
}
