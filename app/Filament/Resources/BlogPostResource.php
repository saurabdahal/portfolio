<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Blog Posts';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 8;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')->disabled(),
                Forms\Components\TextInput::make('slug')->disabled(),
                Forms\Components\TextInput::make('url')->disabled(),
                Forms\Components\DateTimePicker::make('published_at')->disabled(),
                Forms\Components\Toggle::make('is_visible')
                    ->label('Show on portfolio')
                    ->inline(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('read_time_in_minutes')
                    ->label('Read time')
                    ->suffix(' min')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Visible'),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
