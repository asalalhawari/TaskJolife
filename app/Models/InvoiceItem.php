<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{

    use HasFactory;
    
    protected $fillable = ['item_name', 'quantity', 'unit_price', 'invoice_id', 'total_amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function setTotalAmountAttribute($value)
    {
        $quantity = $this->attributes['quantity'];
        $unitPrice = $this->attributes['unit_price'];
        
        $this->attributes['total_amount'] = (int) $quantity * (double) $unitPrice;
    }


}
