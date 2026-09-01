<?php

namespace Database\Seeders;

use App\Models\User;
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
        foreach ([
            ['username' => 'pelanggan', 'no_hp' => '082388486205', 'level' => 1],
            ['username' => 'mekanik', 'no_hp' => '082388486206', 'level' => 2],
            ['username' => 'admin', 'no_hp' => '082388486207', 'level' => 3],
            ['username' => 'pemilik', 'no_hp' => '082388486208', 'level' => 4],
        ] as $account) {
            User::firstOrCreate(
                ['username' => $account['username']],
                $account + ['password' => 'password', 'status_akun' => 'aktif'],
            );
        }

        $this->call(ServisaCatalogSeeder::class);
    }
}
