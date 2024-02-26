<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::find(1)->assignRole('admin');
        User::find(2)->assignRole('member');
        User::whereNotIn('id', [1])->get()->each->assignRole('member');
    }
}
