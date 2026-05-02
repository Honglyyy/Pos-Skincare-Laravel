<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextArea::make('description'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
//                TextInput::make('stock')
//                    ->required()
//                    ->numeric()
//                    ->default(0),
                Select::make('categories')->columnSpan(2)->relationship('categories', 'name')->multiple()->preload()->reactive()->required(),
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required(),
                Select::make('suppliers')->relationship('suppliers', 'name')->multiple()->preload()->reactive()->required(),
                TextInput::make('barcode'),
                DateTimePicker::make('expiration_date'),
                FileUpload::make('image')->columnSpan(2),
            ]);
    }
}
