<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['invoice_id', 'voucher_type', 'beneficiary_name', 'date', 'amount', 'status'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);  
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($voucher) {
            // Ensure the invoice ID is valid
            if (!$voucher->invoice_id) {
                throw new \Exception('Invoice not assigned to this voucher');
            }

            // Load the invoice relation if not already loaded
            $invoice = $voucher->invoice ?? $voucher->invoice()->first();
            if (!$invoice) {
                throw new \Exception('Invoice not found for this voucher');
            }

            $availableAmount = $invoice->getAvailableAmount(); 

            if ($voucher->voucher_type == 'payment' && $voucher->amount > $availableAmount) {
                throw new \Exception('The amount is not available for payment');
            }

            if ($voucher->voucher_type == 'payment') {
                $totalReceipts = $invoice->vouchers()->where('voucher_type', 'receipt')->sum('amount');
                if ($totalReceipts < $voucher->amount) {
                    throw new \Exception('You must receive the amount first before making the payment');
                }
            }
        });
    }
}
