<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListInvoices extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    return [
        'all' => Tab::make(),
        'Paid' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'paid')),
        'Unpaid' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'unpaid')),
    ];
}
}
