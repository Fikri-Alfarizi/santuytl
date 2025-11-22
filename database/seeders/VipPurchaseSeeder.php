<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VipPurchaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti user_id sesuai user yang ada di tabel users (misal: 1 untuk admin)
        DB::table('vip_purchases')->insert([
            'user_id' => 1,
            'days' => 30,
            'amount' => 50000.00,
            'currency' => 'IDR',
            'payment_method' => 'qris', // enum: qris, dana, pulsa, bank_transfer, paypal
            'payment_reference' => null,
            'status' => 'pending', // enum: pending, paid, expired, cancelled
            'paid_at' => null,
            'expires_at' => null,
            'admin_notes' => null,
            'processed_by' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
