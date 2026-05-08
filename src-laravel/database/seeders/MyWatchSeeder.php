<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MyWatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MyWatch::create([
            'id' => \illuminate\Support\Str::ulid(),
            'brand_id' =>'01KQ7M1K3021FQRAX6RR5W6BWJ',
            'model_name'=>'サブマリーナ デイト',
            'reference_number' => '126610LN',
            'purchase_price' => 1200000,
            'purchase_date' => '2024-01-01',
        ]);
    }
}
