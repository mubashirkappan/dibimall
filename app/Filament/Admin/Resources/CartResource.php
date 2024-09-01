<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CartResource\Pages;
use App\Models\Cart;
use App\Models\Shop;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_name')->sortable()->searchable(),
                TextColumn::make('price')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('purchased')
                    ->boolean(),
                TextColumn::make('dibi_price')->sortable()->searchable(),
                TextColumn::make('count')->sortable()->searchable(),
                TextColumn::make('shop.name')->sortable()->searchable(),
                TextColumn::make('Customer.name')->sortable()->searchable(),

                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('shop_id')
                    ->options(Shop::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
        // ->query(fn ($query) => $query->where('purchased', 1));
        // ->query(function ($query) {
        //     return $query->where('purchased', 1);
        // });
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
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }
}
