<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('invoice_id')->relationship('invoice','invoice_number'),
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

    // Define the table for displaying vouchers
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('voucher_type')->sortable(),
                TextColumn::make('beneficiary_name')->sortable(),
                TextColumn::make('amount')->sortable(),
                // More columns can be added here
            ])
            ->filters([
                // Filters can be added here
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Define the relations (if any)
    public static function getRelations(): array
    {
        return [
            // Define relations here if needed
        ];
    }

    // Define the pages for the resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
