<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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
                    ->required()->columnSpan(2),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('categories')->columnSpan(2)
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->createOptionForm(
                        [TextInput::make('name')]
                    ),
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required()
                    ->createOptionForm(
                        [TextInput::make('name')]
                    ),
                Select::make('suppliers')
                    ->relationship('suppliers', 'name')
                    ->multiple()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->createOptionForm(
                        [
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('phone')
                                ->tel()
                                ->required(),
                            TextInput::make('address')
                                ->required(),
                        ]
                    ),
                TextInput::make('barcode'),
                DateTimePicker::make('expiration_date'),
                FileUpload::make('image')->columnSpan(2),
                RichEditor::make('description'),
            ]);
    }
}
