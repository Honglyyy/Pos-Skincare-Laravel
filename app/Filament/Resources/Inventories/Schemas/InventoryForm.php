<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Select::make('stock_movement')
                    ->required()
                    ->options([
                        'stock-in' => 'Stock in',
                        'stock-out' => 'Stock out',
                    ])
                    ->default('stock-in'),
            ]);
    }
}
