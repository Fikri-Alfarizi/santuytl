<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Purchase Details
            $table->integer('days'); // Number of VIP days
            $table->decimal('amount', 10, 2); // Purchase amount
            $table->string('currency', 3)->default('IDR');
            // Payment Information
            $table->enum('payment_method', ['qris', 'dana', 'pulsa', 'bank_transfer', 'paypal'])->nullable();
            $table->string('payment_reference')->nullable();
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending');
            // Transaction Dates
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            // Admin Notes
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vip_purchases');
    }
};
