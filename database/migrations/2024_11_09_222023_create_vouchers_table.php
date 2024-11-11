<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('invoice_id')->constrained()->onDelete('cascade');  
        $table->enum('voucher_type', ['receipt', 'payment']);  
        $table->string('beneficiary_name');  
        $table->date('date');  
        $table->decimal('amount', 10, 2);  
        $table->enum('status', ['pending', 'completed'])->default('pending'); 
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
