<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Group;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Hidden;
use Tiptap\Nodes\Text;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('created_by')
                    ->default(fn () => auth()->id()),
                Select::make('customer_id')
                    ->required()
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpan(2)->hiddenLabel()->prefix('Customer')->dehydrated(),
                DateTimePicker::make('order_date')->required()->default(now())->disabled()->hiddenLabel()->prefix('Date')->dehydrated()->columnSpan(1),
                Group::make()->schema([

                    Section::make()
                        ->description("Order Details")
                        ->schema([
                            Repeater::make('orderDetails')
                                ->relationship()
                                ->schema([
                                    Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->reactive()
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                        ->afterStateUpdated(
                                            function ($state, Set $set, Get $get) {
                                                $product = Product::find($state);
                                                $price = $product-> price ?? 0;
                                                $set('price', $price);

                                                $quantity = $get('quantity') ?? 1;
                                                $set('quantity', $quantity);

                                                $subtotal = $price * $quantity;
                                                $set('subtotal', $subtotal);

                                                $items = $get('../../orderDetails') ?? [];
                                                $total = collect($items)->sum(fn ($item) => $item['subtotal'] ?? 0);
                                                $set('../../total_price', $total);

                                                $discount = $get('../../discount');
                                                $discount_amount = $total * $discount / 100;
                                                $set('../../discount_amount', $discount_amount);
                                                $set('../../total_payment', $total - $discount_amount);
                                            }
                                        ),
                                    TextInput::make('quantity')->numeric()->default(1)
                                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                            $price = $get('price') ?? 0;
                                            $subtotal = $price * $state;
                                            $set('subtotal', $subtotal);

                                            $items = $get('../../orderDetails') ?? [];
                                            $total = collect($items)->sum(fn ($item) => $item['subtotal'] ?? 0);
                                            $set('../../total_price', $total);

                                            $discount = $get('../../discount');
                                            $discount_amount = $total * $discount / 100;
                                            $set('../../discount_amount', $discount_amount);
                                            $set('../../total_payment', $total - $discount_amount);
                                        })
                                        ->reactive()->minValue(1)
                                        ->maxValue(function(Get $get) {
                                            $productId = $get('product_id');
                                            $product = Product::find($productId);
                                            return $product?-> stock ?? 0;
                                        }),
                                    TextInput::make('price')->disabled()->numeric()->prefix("$")
                                        ->formatStateUsing(fn($state, Set $set, Get $get) => $state ?? Product::find($get('product_id'))->price??0),
                                    TextInput::make('subtotal')
                                        ->default(0)
                                        ->disabled()
                                        ->dehydrated()
                                        ->prefix("$"),
                                ])->columns(4)->addAction(fn(Action $action): Action => $action ->label('Add Product')->color('primary')->icon(Heroicon::Plus))
                        ])->columnSpan(2),
                ])->columnSpan(2),

                Section::make()
                    ->schema([
                        Select::make('status')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('new')
                            ->columnSpan(2),
                        TextInput::make('total_price')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->dehydrated()
                            ->prefix('$')
                            ->disabled()
                            ->columnSpan(2),
                        TextInput::make('discount')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $discount = floatval($state ?? 0);
                                $total_price = $get('total_price') ?? 0;
                                $discount_amount = $total_price * $discount / 100;
                                $set('discount_amount', $discount_amount);
                                $set('total_payment', $total_price - $discount_amount);
                            })->default(0)->suffix("%")->minValue(1)->maxValue(100),
                        TextInput::make('discount_amount')
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->prefix("$")
                            ,
                        TextInput::make('total_payment')
                            ->disabled()
                            ->default(0)
                            ->columnSpan(2)
                            ->dehydrated()
                            ->prefix("$"),
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'qr' => 'Qr',
                            ])->default('cash'),
                        Select::make('payment_status')
                            ->options([
                                'paid' => 'Paid',
                                'unpaid' => 'Unpaid',
                            ])->default('unpaid')
                    ])
                    ->description("Order Details")
                    ->columns(2)
                    ->columnSpan(1),
            ])->columns(3);
    }
}
