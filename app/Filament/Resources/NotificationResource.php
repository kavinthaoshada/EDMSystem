<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationResource\Pages;
use App\Filament\Resources\NotificationResource\RelationManagers;
use App\Models\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $label = 'Notifications';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Notification::query()->latest())
            ->columns([
                TextColumn::make('notifiable.name')
                    ->label('Employee Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('data')
                    ->label('Message')
                    ->formatStateUsing(fn ($record) => json_decode($record->data, true)['message'] ?? 'No Message')
                    ->limit(50)
                    ->tooltip(fn ($record) => json_decode($record->data, true)['message'] ?? 'No Message'),

                TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('read_at')
                    ->label('Read At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('read')
                    ->label('Read / Unread')
                    ->query(fn (Builder $query, $state) => 
                        $query->when($state, fn ($query) => $query->whereNotNull('read_at'))
                              ->when(!$state, fn ($query) => $query->whereNull('read_at'))
                    ),
                ]);
            // ->actions([
                
            //     Action::make('view')
            //         ->label('View')
            //         ->icon('heroicon-o-eye')
            //         ->color('primary')
            //         ->modalHeading('Notification Details')
            //         ->modalContent(fn ($record) => View::make('filament.notifications.view', ['notification' => $record])),
            // ]);
    }

    public static function canCreate(): bool
    {
        return false;
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
            'index' => Pages\ListNotifications::route('/'),
        ];
    }
}
