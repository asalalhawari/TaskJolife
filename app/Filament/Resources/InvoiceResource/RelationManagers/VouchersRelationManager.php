<?php

namespace App\Filament\Resources\InvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VouchersRelationManager extends RelationManager
{
    protected static string $relationship = 'vouchers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Select::make('invoice_id')->relationship('invoice','invoice_number'),
                Select::make('voucher_type')
                    ->options(['receipt' => 'Receipt', 'payment' => 'Payment'])
                    ->required(),
                TextInput::make('beneficiary_name')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('amount')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('voucher_type')->sortable(),
                TextColumn::make('beneficiary_name')->sortable(),
                TextColumn::make('amount')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
