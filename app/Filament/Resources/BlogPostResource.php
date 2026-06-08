<?php

namespace App\Filament\Resources;

use App\Filament\Pages\WriteBlogPost;
use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Blog Posts';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->extraAttributes(['class' => 'ed-canvas'])
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->hiddenLabel()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Title')
                        ->extraAttributes(['class' => 'ed-title'])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, ?string $state, string $context): void {
                            if ($context === 'create') {
                                $set('slug', Str::slug((string) $state));
                            }
                        }),

                    Forms\Components\Textarea::make('brief')
                        ->hiddenLabel()
                        ->rows(2)
                        ->maxLength(500)
                        ->placeholder('Add a subtitle...')
                        ->extraAttributes(['class' => 'ed-subtitle'])
                        ->autosize(),

                    Forms\Components\RichEditor::make('content_html')
                        ->hiddenLabel()
                        ->placeholder('Tell your story...')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike', 'link',
                            'h2', 'h3', 'blockquote', 'codeBlock',
                            'bulletList', 'orderedList',
                            'attachFiles', 'undo', 'redo',
                        ])
                        ->extraAttributes(['class' => 'ed-body'])
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('blog/content')
                        ->fileAttachmentsVisibility('public'),
                ]),

            Forms\Components\Section::make('Post settings')
                ->collapsible()
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Used in the URL: /blogs/your-slug')
                        ->rules(['alpha_dash']),

                    Forms\Components\Toggle::make('is_visible')
                        ->label('Published (show on site)')
                        ->default(true),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Publish date')
                        ->helperText('Leave blank to use now when published.'),

                    Forms\Components\FileUpload::make('cover_image_url')
                        ->label('Cover image')
                        ->image()
                        ->disk('public')
                        ->directory('blog/covers')
                        ->visibility('public')
                        ->imageEditor(),

                    Forms\Components\TagsInput::make('tags')
                        ->placeholder('Add a tag')
                        ->separator(','),
                ])
                ->columns(2),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image_url')
                    ->label('Cover')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(null),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('read_time_in_minutes')
                    ->label('Read time')
                    ->suffix(' min')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Published'),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('write')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (BlogPost $record): string => WriteBlogPost::getUrl(['record' => $record->id])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
        ];
    }
}
