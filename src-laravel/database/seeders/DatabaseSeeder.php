<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       // 1. Rolex
        Brand::create([
            'id' => '01KQ7M1K3021FQRAX6RR5W6BWJ', // 固定のULID
            'name' => 'Rolex',
            'oh_period' => 5
        ]);

        // 2. Omega (追加したい場合)
        Brand::create([
            'id' => '01KQ7M1K3021FQRAX6RR5W6BWK', 
            'name' => 'Omega',
            'oh_period' => 5
        ]);

        // 3. Tudor (追加したい場合)
        Brand::create([
            'id' => '01KQ7M1K3021FQRAX6RR5W6BWL',
            'name' => 'Tudor',
            'oh_period' => 5
        ]);
    }
}
