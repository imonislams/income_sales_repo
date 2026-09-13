<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            'Cash',
            'Bank',
            'bKash',
            'Nagad',
            'Rocket',
            'Card',
            'Other',
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate([
                'user_id' => null,
                'name' => $method,
            ], [
                'is_default' => true,
            ]);
        }
    }
}
