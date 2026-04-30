<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ContactUsResource\Pages;
use App\Filament\Admin\Resources\ContactUsResource\RelationManagers;
use App\Models\ContactUs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

class ContactUsResource extends Resource
{
    protected static ?string $model = ContactUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Contact Messages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('email')->disabled(),
                Forms\Components\TextInput::make('phone')->disabled(),
                Forms\Components\Textarea::make('message')->disabled()->columnSpanFull(),
                Forms\Components\Toggle::make('is_notified')
                    ->label('Notified / Responded')
                    ->helperText('Mark this inquiry as responded to.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('message')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->message)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
                IconColumn::make('is_notified')
                    ->label('Notified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_notified')
                    ->label('Response Status')
                    ->trueLabel('Notified / Responded')
                    ->falseLabel('Pending Response')
                    ->nullable(),
            ])
            ->actions([
                Action::make('mark_notified')
                    ->label('Mark as Notified')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Mark as Notified')
                    ->modalDescription('Confirm that this contact request has been reviewed and responded to.')
                    ->modalSubmitActionLabel('Yes, mark as notified')
                    ->hidden(fn (ContactUs $record): bool => $record->is_notified)
                    ->action(function (ContactUs $record): void {
                        $record->update(['is_notified' => true]);
                        Notification::make()
                            ->title('Marked as Notified')
                            ->body("{$record->name}'s message has been marked as responded.")
                            ->success()
                            ->send();
                    }),

                Action::make('reset_notified')
                    ->label('Reset')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Notification Status')
                    ->modalDescription('This will mark the contact request as pending again.')
                    ->modalSubmitActionLabel('Yes, reset')
                    ->hidden(fn (ContactUs $record): bool => ! $record->is_notified)
                    ->action(function (ContactUs $record): void {
                        $record->update(['is_notified' => false]);
                        Notification::make()
                            ->title('Status Reset')
                            ->body("{$record->name}'s message has been reset to pending.")
                            ->warning()
                            ->send();
                    }),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContactUs::route('/'),
            'create' => Pages\CreateContactUs::route('/create'),
            'edit'   => Pages\EditContactUs::route('/{record}/edit'),
        ];
    }
}
