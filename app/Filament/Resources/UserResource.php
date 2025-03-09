<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $label = 'Users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),

                Select::make('role')
                    ->label('User Role')
                    ->options([
                        'employee' => 'Employee',
                        'hr' => 'HR',
                    ])
                    ->required(),

                FileUpload::make('profile_photo_path')
                    ->label('Profile Photo')
                    ->directory('profile-photos')
                    ->image()
                    ->maxSize(5120),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo_path') 
                    ->label('Profile')
                    ->circular() 
                    ->width(50) 
                    ->height(50) 
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Full Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('role')
                    ->label('Role')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Joined On')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter by Role')
                    ->options([
                        'employee' => 'Employee',
                        'hr' => 'HR',
                    ]),
            ])
            ->actions([
                EditAction::make(),

                DeleteAction::make(),
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
