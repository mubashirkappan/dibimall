<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TasOrderResource\Pages;
use App\Filament\Admin\Resources\TasOrderResource\RelationManagers;
use App\Models\TasOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
class TasOrderResource extends Resource
{
    protected static ?string $model = TasOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Shop Orders';
    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             // Forms\Components\Card::make()->schema([
    //             //     TextInput::make('user_name')->required(),
    //             //     TextInput::make('total_price')->numeric()->prefix('INR'),
    //             // ])->columns(2),

    //             // // The Items Section
    //             // Repeater::make('items') // Relation name in Order Model
    //             //     ->relationship()
    //             //     ->schema([
    //             //         TextInput::make('name')->required(),
    //             //         TextInput::make('quantity')->numeric()->required(),
    //             //         TextInput::make('price_per_item')->numeric()->prefix('INR'),
    //             //     ])
    //             //     ->columns(3)
    //             //     ->label('Ordered Items')


    //         ]);
    // }
    public static function getPluralLabel(): string
    {
        return 'Shop orders'; // Main page header
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shop.name')
                    ->badge()
                    ->label('Store')
                    ->color('success'),
                Tables\Columns\TextColumn::make('user_name')->searchable(),
                Tables\Columns\TextColumn::make('user_phone_number')->searchable()
                    ->label('User Phone'),
                Tables\Columns\TextColumn::make('address')
                    ->label('User Address'),

                Tables\Columns\TextColumn::make('total_price')->sortable()->money('INR')
                    ->tooltip('This includes taxes and delivery fees'),
                // Tables\Columns\TextColumn::make('delivery_time')
                //     ->sortable()
                //     ->dateTime('d M Y, h:i A') // e.g., 12 Feb 2026, 01:30 PM
                //     ->description(fn($record) => $record->delivery_time ? 'Scheduled' : 'Not set'),
                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'pending',
                        'success' => 'delivered',
                    ]),
                // Tables\Columns\IconColumn::make('status')
                //     ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    // ->dateTime()
                    ->dateTime('d M Y, h:i A') // e.g., 12 Feb 2026, 01:30 PM

                    ->sortable(),
                // ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('shop_id')
                    ->label('Filter by Shop')
                    ->relationship('shop', 'name') // Assumes Order has a 'shop' relationship
                    ->searchable() // Helpful if you have many shops
                    ->preload(),   // Loads the list when the page loads
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Order Date From'),
                        DatePicker::make('created_until')
                            ->label('Order Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\Action::make('updateStatus')
                //     ->form([
                //         // Use Select here, NOT SelectColumn
                //         Select::make('status')
                //             ->options([
                //                 'pending' => 'pending',
                //                 'deliverd' => 'delivered',
                //             ]),
                //     ])
                //     ->action(function ($record, $data) {
                //         $record->update($data);
                //     }),
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasOrders::route('/'),
            // 'create' => Pages\CreateTasOrder::route('/create'),
            'edit' => Pages\EditTasOrder::route('/{record}/edit'),
            'view' => Pages\ViewTasOrder::route('/{record}'),

        ];
    }
}
