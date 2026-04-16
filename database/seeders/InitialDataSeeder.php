<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Models\Level;
use App\Models\User;
use App\Models\TypeOfService;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Level::create(['level_name' => 'Administrator']);
        $operator = Level::create(['level_name' => 'Operator']);
        $pimpinan = Level::create(['level_name' => 'Pimpinan']);

        User::create([
            'id_level' => $admin->id,
            'name' => 'Super Admin',
            'email' => 'admin@laundry.com',
            'password' => Hash::make('password')
        ]);
        User::create([
            'id_level' => $operator->id,
            'name' => 'Kasir Operator',
            'email' => 'operator@laundry.com',
            'password' => Hash::make('password')
        ]);
        User::create([
            'id_level' => $pimpinan->id,
            'name' => 'Bapak Pimpinan',
            'email' => 'pimpinan@laundry.com',
            'password' => Hash::make('password')
        ]);

        TypeOfService::create(['service_name' => 'Cuci dan Setrika', 'price' => 5000, 'description' => 'Harga per kg']);
        TypeOfService::create(['service_name' => 'Hanya Cuci', 'price' => 4500, 'description' => 'Harga per kg']);
        TypeOfService::create(['service_name' => 'Hanya Setrika', 'price' => 5000, 'description' => 'Harga per kg']);
        TypeOfService::create(['service_name' => 'Laundry Besar (Selimut, Karpet, dll)', 'price' => 7000, 'description' => 'Harga per kg']);
    }
}
