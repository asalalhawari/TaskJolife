<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions\ButtonAction;


class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            ButtonAction::make('Print')
                ->label('Print Invoice')
                ->url(fn($record) => route('invoices.print', $record))
                ->openUrlInNewTab(),
        ];
    }
}
