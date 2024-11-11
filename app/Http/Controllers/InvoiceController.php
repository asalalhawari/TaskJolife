<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function printInvoice($id)
    {
        // قم بجلب الفاتورة مع عناصر الفاتورة المرتبطة بها
        $invoice = Invoice::with('invoiceItems')->findOrFail($id);
        
        // عرض الصفحة الخاصة بطباعة الفاتورة
        return view('invoices.print', compact('invoice'));
    }
}
