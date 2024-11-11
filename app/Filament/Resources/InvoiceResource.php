<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers\VouchersRelationManager;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('invoice_number')
                    ->label('Invoice Number')
                    ->required(), //this column not in db yet

                Forms\Components\Select::make('client_id')
                    ->relationship('client', 'name')
                    ->createOptionForm([
                        TextInput::make('name'),
                        TextInput::make('email'),
                        TextInput::make('phone'),
                    ]),

                Forms\Components\DatePicker::make('invoice_date')
                    ->label('Invoice Date')
                    ->required(),

                Forms\Components\TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                    ])
                    ->default('unpaid')
                    ->required(),

                // Adding the Invoice Items Section
                Forms\Components\Repeater::make('invoiceItems')
                    ->collapsible()
                    ->collapsed()
                    ->relationship('invoiceItems')
                    ->label('Invoice Items')
                    ->schema([
                        Forms\Components\TextInput::make('item_name')
                            ->label('Item Name')
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn(callable $set, $get) => $set('total_amount', (int)$get('quantity') * (float)$get('unit_price'))), // Auto-calculate the total amount

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->numeric()
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn(callable $set, $get) => $set('total_amount', (int)$get('quantity') * (float)$get('unit_price'))), // Auto-calculate the total amount

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->readOnly(),

                    ])
                    ->columns(4)
                    ->columnSpan(2)
                    ->createItemButtonLabel('Add Item')
                    ->maxItems(10), // Max number of items allowed in the invoice
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label('Number'),
                Tables\Columns\TextColumn::make('client.name')->label('Client Name'),
                Tables\Columns\TextColumn::make('invoice_date')->label('Invoice Date'),
                Tables\Columns\TextColumn::make('total_amount')->label('Total Amount')
                    ->summarize(Sum::make()->money('JOD')),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('markAsPaid')
                    ->label('Mark as Paid') // Button label
                    ->action(function (Invoice $record) { // Action to update the status
                        $record->status = 'paid';
                        $record->save();
                    })
                    ->color('success') // Green color when marked as paid
                    ->icon('heroicon-s-check') // Icon for "mark as paid"
                    ->requiresConfirmation()
                    ->visible(fn(Invoice $record) => $record->status !== 'paid'), // Only show if status is not 'paid'
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VouchersRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
