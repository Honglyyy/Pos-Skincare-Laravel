<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Filament\Resources\Orders\RelationManagers\OrderDetailRelationManager;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Schemas\OrderInfolist;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status','new')->count();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingBag;

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array { return [ OrderDetailRelationManager::class ]; }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view_any_order');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_order');
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->can('update_order');
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->can('delete_order');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_any_order')
            || auth()->user()->can('create_order');
    }

    public static function getNavigationUrl(): string
    {
        if (auth()->user()->can('create_order')
            && !auth()->user()->can('view_any_order')) {
            return static::getUrl('create');
        }

        return static::getUrl(); // normal list for admin
    }
}
