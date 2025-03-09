<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentExpiryReminder;
use App\Notifications\DocumentReminderNotification;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Validation\Rules\File;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Document Management';
    protected static ?string $label = 'Documents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->label('Employee')
                    ->required(),

                Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->label('Category')
                    ->required(),

                TextInput::make('document_name')
                    ->label('Document Name')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('file_path')
                    ->label('Upload Document')
                    ->directory('documents')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->required(),

                DatePicker::make('expiry_date')
                    ->label('Expiry Date')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Document::query()
                    ->with(['employee', 'expiryReminder'])
            )
            ->defaultPaginationPageOption(10)
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

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
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

                        $record->employee->notify(new DocumentReminderNotification($reminder));
                    })
                    ->modalHeading('Set Document Reminder')
                    ->modalSubmitActionLabel('Save Reminder')
                    ->icon('heroicon-o-calendar')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => true),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('create_category') 
                    ->label('New Category')
                    ->modalHeading('Create New Category')
                    ->form([
                        TextInput::make('category_name')
                            ->label('Category Name')
                            ->unique('categories', 'category_name')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        Category::create([
                            'category_name' => $data['category_name'],
                        ]);
                    })
                    ->icon('heroicon-o-plus')
                    ->color('gray')
                    ->outlined(false),

                Action::make('remove_category')
                    ->label('Remove Category')
                    ->modalHeading('Remove a Category')
                    ->form([
                        Select::make('category_id')
                            ->label('Select Category to Remove')
                            ->options(Category::doesntHave('documents')->pluck('category_name', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        Category::find($data['category_id'])->delete();
                    })
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('gray'),
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
