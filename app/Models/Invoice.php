<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_date',
        'total_amount',
        'status',
        'invoice_number',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class);  
    }

    public function getAvailableAmount()
    {
        $totalReceipt = $this->vouchers()->where('voucher_type', 'receipt')->sum('amount');

        $totalPayment = $this->vouchers()->where('voucher_type', 'payment')->sum('amount');

        return $totalReceipt - $totalPayment; 
    }
    public function getTotalAmountAttribute(){
        return $this->invoiceItems()->sum('total_amount');
    }
}
