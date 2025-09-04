<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            ['id' => (string) Str::uuid(), 'name' => 'Kudit', 'pin' => '1381'],
            ['id' => (string) Str::uuid(), 'name' => 'Zaydun', 'pin' => '5261'],
            ['id' => (string) Str::uuid(), 'name' => 'Udil', 'pin' => '1212'],
        ]);
    }
}
