<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $incomeCategories = [
            'Salary',
            'Freelance',
            'Business',
            'Investment',
            'Gift',
            'Other',
        ];

        $expenseCategories = [
            'Food',
            'Transport',
            'Shopping',
            'Rent',
            'Electricity',
            'Mobile/Internet',
            'Medical',
            'Education',
            'Entertainment',
            'Other',
        ];

        foreach ($incomeCategories as $category) {
            Category::firstOrCreate([
                'user_id' => null,
                'name' => $category,
                'type' => 'income',
            ], [
                'is_default' => true,
            ]);
        }

        foreach ($expenseCategories as $category) {
            Category::firstOrCreate([
                'user_id' => null,
                'name' => $category,
                'type' => 'expense',
            ], [
                'is_default' => true,
            ]);
        }
    }
}
