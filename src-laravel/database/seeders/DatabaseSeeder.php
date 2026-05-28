<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'id' => Str::ulid(),
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // ログイン時のパスワードになります
        ]);

        // 2. Rolex
        Brand::create([
            'id' => '01KQ7M1K3021FQRAX6RR5W6BWJ',
            'name' => 'Rolex',
            'oh_period' => 5
        ]);

        // 3. Omega
        Brand::create([
            'id' => '01KQ7M1K3021FQRAX6RR5W6BWK', 
            'name' => 'Omega',
            'oh_period' => 5
        ]);

        // 4. Tudor
        Brand::create([
            'id' => '01KQ7M1K3021FQRAX6RR5W6BWL',
            'name' => 'Tudor',
            'oh_period' => 5
        ]);

        // ⭐ 5. 作成したユーザーの情報を持たせて MyWatchSeeder を実行する
        $this->callWith(MyWatchSeeder::class, ['user' => $user]);
    }
    
}
