<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Document;
use App\Notifications\DocumentReminderNotification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\DocumentExpiryReminder;
use Carbon\Carbon;

class ReminderTableWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Document::query()
                    ->with(['employee', 'expiryReminder'])
                    ->where('expiry_date', '>=', Carbon::now())
                    ->where('expiry_date', '<=', Carbon::now()->addMonths(12))

            )
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('document_name')
                    ->label('Document')
                    ->formatStateUsing(fn ($record) => "<a href='/storage/{$record->file_path}' target='_blank' class='text-blue-500 underline'>{$record->document_name}</a>")
                    ->html(),

                TextColumn::make('category.category_name')
                    ->label('Document Category')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('expiry_date')
                    ->label('Document Expiry Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('expiryReminder.reminder_date')
                    ->label('Reminder Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('expiryReminder.notified')
                    ->label('Notified')
                    ->formatStateUsing(fn ($record) => $record->expiryReminder ? ($record->expiryReminder->notified ? 'Yes' : 'No') : 'No Reminder')
                    ->sortable(),
            ])
            ->filters([
                
                SelectFilter::make('employee_id')
                    ->label('Filter by Employee')
                    ->relationship('employee', 'name')
                    ->searchable(),
            
                SelectFilter::make('category_id')
                    ->label('Filter by Category')
                    ->relationship('category', 'category_name')
                    ->searchable(),
            
                SelectFilter::make('document_name')
                    ->label('Filter by Document Name')
                    ->options(
                        Document::pluck('document_name', 'document_name')->toArray()
                    )
                    ->searchable(),
            
                    Filter::make('expiry_date')
                    ->form([
                        DatePicker::make('expiry_date_start')->label('Start Date'),
                        DatePicker::make('expiry_date_end')->label('End Date'),
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['expiry_date_start']) && !empty($data['expiry_date_end'])) {
                            $query->whereBetween('expiry_date', [
                                Carbon::parse($data['expiry_date_start']),
                                Carbon::parse($data['expiry_date_end'])
                            ]);
                        }
                    }),
            ])
            ->actions([
                Action::make('Set Reminder')
                    ->form([
                        DatePicker::make('reminder_date')
                            ->label('Reminder Date')
                            ->minDate(fn ($record) => Carbon::now()) 
                            ->maxDate(fn ($record) => $record->expiry_date)
                            ->required(),
                    ])
                    ->action(function ($data, $record) {
                        // Find existing reminder or create a new one
                        $reminder = DocumentExpiryReminder::updateOrCreate(
                            [
                                'document_id' => $record->id,
                                'employee_id' => $record->employee_id,
                            ],
                            [
                                'reminder_date' => $data['reminder_date'],
                                'notified' => false,
                            ]
                        );

                        // Send notification to employee
                        $record->employee->notify(new DocumentReminderNotification($reminder));
                    })
                    ->modalHeading('Set Document Reminder')
                    ->modalSubmitActionLabel('Save Reminder')
                    ->icon('heroicon-o-calendar')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => true),
                    // ->visible(fn ($record) => !$record->notified),
            ])
            ->headerActions([
                Action::make('View Documents')
                    ->url(route('filament.admin.resources.documents.index'))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ]);
    }
}
