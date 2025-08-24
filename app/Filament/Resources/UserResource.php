<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('User Info')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Password::make('password')
                            ->label('Password')
                            ->hint('Leave it blank to keep the existing password')
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context) => $context === 'create')
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null),
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->default(now()),
                    ])
                    ->columns(2),

                Forms\Components\Fieldset::make('Profile')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('profile_image')
                            ->collection('profile')
                            ->avatar()
                            ->label('Profile Image')
                            ->image()
                            ->imageCropAspectRatio('1:1')
                            ->imagePreviewHeight('100')
                            ->maxSize(1024)
                            ->columnSpan('full'),

                        Forms\Components\MarkdownEditor::make('bio')
                            ->label('Bio')
                            ->maxLength(500)
                            ->columnSpan('full'),
                    ])
                    ->columns(1),

                Forms\Components\Fieldset::make('Roles & Status')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\CheckboxList::make('roles')
                                    ->relationship('roles', 'name')
                                    ->searchable(),
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Toggle::make('is_premium')
                                            ->label('Premium User')
                                            ->default(false)
                                            ->helperText('Enable if the user has access to premium features.'),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(false)
                                            ->helperText('Only active users can log in and access the system.'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('profile_image')
                    ->collection('profile')
                    ->circular()
                    ->label('Profile'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('phone')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('bio')
                    ->limit(30)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_premium')
                    ->boolean()
                    ->label('Premium'),
                // Tables\Columns\TextColumn::make('address')
                //     ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
