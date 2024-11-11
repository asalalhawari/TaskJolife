<?php

// app/Filament/Resources/InvoiceItemResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceItemResource\Pages;
use App\Models\InvoiceItem;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceItemResource extends Resource
{
    protected static ?string $model = InvoiceItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent'; 
    


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('invoice_id')->relationship('invoice', 'invoice_number'),
                Forms\Components\TextInput::make('item_name')
                    ->label('Item Name')
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item_name')->label('Item Name'),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity'),
                Tables\Columns\TextColumn::make('unit_price')->label('Unit Price'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoiceItems::route('/'),
            'create' => Pages\CreateInvoiceItem::route('/create'),
            'edit' => Pages\EditInvoiceItem::route('/{record}/edit'),
        ];
    }
}
