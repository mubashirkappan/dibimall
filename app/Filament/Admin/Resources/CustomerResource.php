<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('from')
                    ->label('Select Store')
                    ->options([
                        'dibimall' => 'DIBIMALL',
                        'thasweel' => 'THASWEEL',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('firstname')
                    ->maxLength(255),
                TextInput::make('shop_count')
                    ->required()
                    ->maxLength(10)
                    ->rule('regex:/^[0-9]{1,10}$/'),
                Forms\Components\TextInput::make('referal_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_type')
                    ->label('User Type')
                    ->options([
                        1 => 'User',
                        2 => 'Owner',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('lastname')
                    ->maxLength(255),
                TextInput::make('phonenumber')
                    ->tel()
                    ->required()
                    ->maxLength(13)
                    ->minLength(6)
                    ->rule('regex:/^[0-9]{10,15}$/'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('username')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shop_count')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('firstname')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('lastname')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('phonenumber')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 1:
                                return 'Customer';
                            case 2:
                                return 'Owner';
                            case 3:
                                return 'Pending';
                            default:
                                return 'Unknown';
                        }
                    }),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->visible(fn (Customer $record) => $record->user_type == 3)
                    ->action(function (Customer $record) {
                        $record->update(['user_type' => 2]);
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-arrow-trending-up'),
                Action::make('decline')
                    ->label('Decline')
                    ->visible(fn (Customer $record) => $record->user_type == 3)
                    ->action(function (Customer $record) {
                        $record->update(['user_type' => 1]);
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-arrow-trending-down'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->orderByRaw('CASE WHEN user_type = 3 THEN 0 ELSE 1 END')
            ->orderBy('created_at', 'desc');
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
