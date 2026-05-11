<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('print')
            ->label('Print Invoice')
            ->icon('heroicon-o-printer')
            ->url(fn () => url("/invoice/{$this->record->id}"))
            ->openUrlInNewTab(),
        ];
    }
}
