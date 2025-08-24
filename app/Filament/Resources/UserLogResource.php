<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserLogResource\Pages;
use App\Filament\Resources\UserLogResource\RelationManagers;
use App\Models\UserLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserLogResource extends Resource
{
    protected static ?string $model = UserLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('User')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(),
                    ])
                    ->columns(1),

                Forms\Components\Fieldset::make('Device Info')
                    ->schema([
                        Forms\Components\TextInput::make('device_name'),
                        Forms\Components\TextInput::make('device_os'),
                        Forms\Components\TextInput::make('os_version'),
                        Forms\Components\TextInput::make('app_version'),
                    ])
                    ->columns(2),

                Forms\Components\Fieldset::make('Network')
                    ->schema([
                        Forms\Components\TextInput::make('ip_address'),
                        Forms\Components\DateTimePicker::make('last_login_at'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('device_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device_os')
                    ->searchable(),
                Tables\Columns\TextColumn::make('os_version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('app_version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_login_at')
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
            'index' => Pages\ListUserLogs::route('/'),
            'create' => Pages\CreateUserLog::route('/create'),
            'edit' => Pages\EditUserLog::route('/{record}/edit'),
        ];
    }
}
