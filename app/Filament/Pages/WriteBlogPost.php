<?php

namespace App\Filament\Pages;

use App\Models\BlogPost;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class WriteBlogPost extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Write';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 7;

    protected static ?string $slug = 'write/{record?}';

    protected static string $view = 'filament.pages.write-blog-post';

    public ?BlogPost $record = null;

    public string $post_title = '';

    public string $brief = '';

    public string $content_html = '';

    public string $post_slug = '';

    public bool $is_visible = false;

    public ?string $published_at = null;

    public ?string $cover_image_url = null;

    public string $tags_input = '';

    public $cover_upload = null;

    public $editor_image = null;

    public bool $settings_open = false;

    public ?string $last_saved = null;

    public function getLayout(): string
    {
        return 'filament.layouts.writer';
    }

    public function getTitle(): string
    {
        return $this->post_title !== '' ? $this->post_title : 'Write';
    }

    public function mount(BlogPost|int|null $record = null): void
    {
        if (! $record) {
            return;
        }

        $this->record = $record instanceof BlogPost
            ? $record
            : BlogPost::findOrFail($record);
        $this->post_title = $this->record->title;
        $this->brief = $this->record->brief ?? '';
        $this->content_html = $this->record->content_html ?? '';
        $this->post_slug = $this->record->slug;
        $this->is_visible = $this->record->is_visible;
        $this->published_at = $this->record->published_at?->format('Y-m-d\TH:i');
        $this->cover_image_url = $this->record->cover_image_url;
        $this->tags_input = implode(', ', $this->record->tags ?? []);
    }

    public function updatedPostTitle(): void
    {
        if ($this->record === null && blank($this->post_slug)) {
            $this->post_slug = Str::slug($this->post_title);
        }

        $this->autosave();
    }

    public function updatedBrief(): void
    {
        $this->autosave();
    }

    public function updatedContentHtml(): void
    {
        $this->autosave();
    }

    public function updatedEditorImage(): void
    {
        if (! $this->editor_image) {
            return;
        }

        $path = $this->editor_image->store('blog/content', 'public');
        $url = Storage::disk('public')->url($path);

        $this->dispatch('editor-image-uploaded', url: $url);

        $this->editor_image = null;
    }

    public function toggleSettings(): void
    {
        $this->settings_open = ! $this->settings_open;
    }

    public function closeSettings(): void
    {
        $this->settings_open = false;
    }

    public function saveDraft(bool $silent = false): void
    {
        if ($this->record === null && blank($this->post_title) && blank(strip_tags($this->content_html))) {
            return;
        }

        $this->persistPost($this->is_visible);

        $this->last_saved = now()->format('g:i A');

        if (! $silent) {
            Notification::make()
                ->title('Draft saved')
                ->success()
                ->send();
        }
    }

    public function publish(): void
    {
        $this->validate([
            'post_title' => 'required|string|max:255',
            'content_html' => 'required|string|min:10',
        ]);

        $this->is_visible = true;

        if (blank($this->published_at)) {
            $this->published_at = now()->format('Y-m-d\TH:i');
        }

        $this->persistPost(true);

        $this->last_saved = now()->format('g:i A');

        Notification::make()
            ->title('Published on your site')
            ->success()
            ->send();
    }

    protected function autosave(): void
    {
        $this->saveDraft(silent: true);
    }

    protected function persistPost(bool $visible): void
    {
        if ($this->cover_upload) {
            $this->cover_image_url = $this->cover_upload->store('blog/covers', 'public');
            $this->cover_upload = null;
        }

        $tags = collect(explode(',', $this->tags_input))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        $data = [
            'title' => $this->post_title !== '' ? $this->post_title : 'Untitled draft',
            'brief' => $this->brief ?: null,
            'content_html' => $this->content_html ?: null,
            'slug' => $this->resolveSlug(),
            'is_visible' => $visible,
            'published_at' => $this->published_at ?: ($visible ? now() : null),
            'cover_image_url' => $this->cover_image_url,
            'tags' => $tags,
            'url' => null,
        ];

        if ($this->record) {
            $this->record->update($data);
            $this->post_slug = $this->record->slug;

            return;
        }

        $this->record = BlogPost::create($data);
        $this->post_slug = $this->record->slug;

        $this->redirect(static::getUrl(['record' => $this->record->id]), navigate: true);
    }

    protected function resolveSlug(): string
    {
        $base = Str::slug($this->post_slug !== '' ? $this->post_slug : $this->post_title) ?: 'post';
        $slug = $base;
        $i = 2;

        while (
            BlogPost::where('slug', $slug)
                ->when($this->record, fn ($q) => $q->where('id', '!=', $this->record->id))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function coverPreviewUrl(): ?string
    {
        if (blank($this->cover_image_url)) {
            return null;
        }

        if (Str::startsWith($this->cover_image_url, ['http://', 'https://', '//'])) {
            return $this->cover_image_url;
        }

        return Storage::disk('public')->url($this->cover_image_url);
    }

    public static function writerUrl(?BlogPost $post = null): string
    {
        return $post
            ? static::getUrl(['record' => $post->id])
            : static::getUrl();
    }
}
