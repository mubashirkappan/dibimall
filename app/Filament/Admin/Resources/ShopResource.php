<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShopResource\Pages;
use App\Models\Shop;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\BelongsToSelect::make('place_id')
                    ->relationship('place', 'name')
                    ->required(),
                Forms\Components\BelongsToSelect::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('from')
                    ->label('Select Store')
                    ->options([
                        'dibimall' => 'DIBIMALL',
                        'thasweel' => 'THASWEEL',
                    ])
                    ->required(),
                Forms\Components\BelongsToSelect::make('type_id')
                    ->relationship('type', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(5),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('landmark')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('country_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('logo_name')
                    ->image()
                    ->required(),
                Forms\Components\Toggle::make('delivery')
                    ->required(),
                Forms\Components\TextInput::make('km')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('image_count')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('item_count')
                    ->required()
                    ->numeric(),

                Forms\Components\Toggle::make('take_away')
                    ->required(),
                Forms\Components\Toggle::make('top_shop')
                    ->required(),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('from')
                    ->searchable(),
                Tables\Columns\TextColumn::make('place.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('landmark')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo_name'),
                Tables\Columns\TextColumn::make('item_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('image_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('delivery')
                    ->boolean(),
                Tables\Columns\TextColumn::make('km')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('take_away')
                    ->boolean(),
                Tables\Columns\IconColumn::make('top_shop')
                    ->boolean(),
                Tables\Columns\TextColumn::make('active')
                    ->numeric()
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListShops::route('/'),
            'create' => Pages\CreateShop::route('/create'),
            'view' => Pages\ViewShop::route('/{record}'),
            'edit' => Pages\EditShop::route('/{record}/edit'),
        ];
    }
}
