<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
        ['user_name' => 'admin'],
        [
            'user_password' => Hash::make('mautauaja'),
            'user_full_name' => 'Super Administrator',
            'user_email' => 'admin@sekolahku.web.id',
            'user_type' => 'super_user',
            'user_group_id' => 0,
            'has_login' => 'false',
            'is_deleted' => 'false',
            'created_by' => 0,
            'updated_by' => 0,
            'deleted_by' => 0,
            'restored_by' => 0,
        ]
        );
    }
}
