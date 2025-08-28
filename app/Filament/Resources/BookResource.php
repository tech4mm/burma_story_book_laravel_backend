<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryFileColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Book Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', \Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from the title for use in URLs.'),
                    ])
                    ->columns(2),

                Forms\Components\Fieldset::make('Media')
                    ->schema([
                        // First row: Cover Image and Description Image, side by side
                        Forms\Components\Group::make([
                            SpatieMediaLibraryFileUpload::make('cover_image')
                                ->collection('cover')
                                ->label('Cover Image')
                                ->image()
                                ->imagePreviewHeight('150')
                                ->required()
                                ->helperText('Main book cover shown in listings.'),
                            SpatieMediaLibraryFileUpload::make('description_image')
                                ->collection('description')
                                ->label('Description Image')
                                ->image()
                                ->required()
                                ->imagePreviewHeight('100')
                                ->maxSize(1024)
                                ->helperText('Secondary image used in detail view or highlights.'),
                        ])->columns(2),
                        // Second row: Book PDF, full width
                        Forms\Components\Group::make([
                            SpatieMediaLibraryFileUpload::make('book_file')
                                ->collection('book')
                                ->label('Book PDF')
                                ->acceptedFileTypes(['application/pdf'])
                                ->required()
                                ->columnSpan('full')
                                ->helperText('Only PDF files are allowed. This is the main content of the book.'),
                        ])->columns(1),
                    ]),

                Forms\Components\Fieldset::make('Description')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('description')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Fieldset::make('Meta')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->helperText('Set the visibility status of the book.'),
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->required()
                            ->helperText('Select the book’s author.'),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->helperText('Choose a category that best fits the book.'),
                        Forms\Components\Group::make([
                            Forms\Components\Toggle::make('is_featured')
                                ->default(false)
                                ->label('Is Featured')
                                ->helperText('Mark as featured to highlight this book.'),
                            Forms\Components\Toggle::make('is_free')
                                ->default(false)
                                ->label('Is Free')
                                ->helperText('Set to true if the book is free to read.'),
                        ])->columns(2),
                    ])
                    ->columns([
                        'sm' => 2,
                        'md' => 3,
                    ]),

                Forms\Components\Fieldset::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('views')
                            ->numeric()
                            ->label('Reads/View Count')
                            ->default(100)
                            ->helperText('Initial number of views shown to users.'),
                        Forms\Components\TextInput::make('pages')
                            ->numeric()
                            ->default(5)
                            ->label('Pages Count')
                            ->nullable()
                            ->helperText('Total number of pages in the book (optional).'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now())
                            ->nullable()
                            ->helperText('Set the book\'s publication date and time.'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover_image')
                    ->collection('cover')
                    ->circular()
                    ->label('Cover'),
                SpatieMediaLibraryImageColumn::make('description_image')
                    ->collection('description')
                    ->circular()
                    ->label('Desc Img'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author_and_category')
                    ->label('👤 / 📚')
                    ->getStateUsing(function (Book $record) {
                        $author = $record->author?->name ?? '—';
                        $category = $record->category?->name ?? '—';
                        return "{$author} / {$category}";
                    }),
                Tables\Columns\TextColumn::make('views')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('pages')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_summary')
                    ->label('Status / Featured / Free')
                    ->getStateUsing(function (Book $record) {
                        $status = $record->status === 'published' ? '✅ published' : '📝 draft';
                        $featured = $record->is_featured ? '✔️' : '✖️';
                        $free = $record->is_free ? '✔️' : '✖️';
                        return "{$status} / {$featured} / {$free}";
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\Action::make('read')
                //     ->label('📖 Read Book')
                //     ->url(fn(Book $record) => $record->getFirstMediaUrl('book'))
                //     ->openUrlInNewTab()
                //     ->visible(fn(Book $record) => $record->getFirstMediaUrl('book')),
                Tables\Actions\Action::make('read')
                    ->label('📖 Read Book')
                    ->url(function (Book $record) {
                        $media = $record->getFirstMedia('book');
                        return $media
                            ? $media->getTemporaryUrl(now()->addMinutes(10), [], 's3')
                            : '#';
                    })
                    ->openUrlInNewTab()
                    ->visible(fn(Book $record) => $record->hasMedia('book')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
