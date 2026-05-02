<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

//    public function form(Schema $schema): Schema
//    {
//        return $schema->components([
//            Select::make('product_id')
//                ->relationship('product', 'name')
//                ->required(),
//
//            TextInput::make('quantity')
//                ->numeric()
//                ->required(),
//
//            TextInput::make('product.price')
//                ->numeric()
//                ->required(),
//
//            TextInput::make('subtotal')
//                ->numeric()
//                ->required(),
//        ]);
//    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product'),
                ImageColumn::make('product.image'),
                TextColumn::make('quantity'),
                TextColumn::make('product.price')->label('Price'),
                TextColumn::make('subtotal'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
//                EditAction::make(),
//                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
