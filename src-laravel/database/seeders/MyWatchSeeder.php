<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MyWatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(User $user): void
    {
        \App\Models\MyWatch::create([
            'id' => Str::ulid(),
            'user_id'=>$user->id,
            'brand_id' =>'01KQ7M1K3021FQRAX6RR5W6BWJ',
            'model_name'=>'サブマリーナ デイト',
            'reference_number' => '126610LN',
            'purchase_price' => 1200000,
            'purchase_date' => '2024-01-01',
        ]);
    }
}
