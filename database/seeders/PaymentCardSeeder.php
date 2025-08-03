<?php

namespace Database\Seeders;

use App\Models\PaymentCard;
use Illuminate\Database\Seeder;

class PaymentCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentCard::factory()->count(100)->create();
    }
}
