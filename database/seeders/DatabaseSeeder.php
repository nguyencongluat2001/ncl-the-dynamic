<?php

namespace Database\Seeders;

use Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $faker = Faker\Factory::create();
        $item = [
            'id'=> (string)\Str::uuid(),
            'name' => 'Nguyễn Công Luật',
            'email' => 'nguyencongluat092001@gmail.com',
            'password'=> Hash::make('123'),
            'status'=> 1,
            'role'=> 'ADMIN'
        ];
        DB::table('users')->insert($item);
    }
}
