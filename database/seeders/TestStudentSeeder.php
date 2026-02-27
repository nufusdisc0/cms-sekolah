<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestStudentSeeder extends Seeder
{
    public function run(): void
    {
        // Find the already-created student, or create one
        $student = Student::where('identity_number', '12345')->first();
        if (!$student) {
            $student = Student::create([
                'full_name' => 'Siswa Test',
                'identity_number' => '12345',
                'nisn' => '9876543210',
                'email' => 'siswa@test.com',
                'gender' => 'M',
                'birth_place' => 'Jakarta',
                'birth_date' => '2010-05-15',
                'registration_number' => 'REG001',
                'is_alumni' => 'false',
                'is_transfer' => 'false',
                'is_deleted' => 'false',
                'is_student' => 'true',
                'is_prospective_student' => 'false',
            ]);
        }

        // Create user linked to student (no user_status column)
        $user = User::where('user_name', 'siswa')->first();
        if (!$user) {
            $user = User::create([
                'user_full_name' => 'Siswa Test',
                'user_name' => 'siswa',
                'user_email' => 'siswa@test.com',
                'user_password' => Hash::make('siswa123'),
                'user_type' => 'student',
                'user_profile_id' => $student->id,
                'is_deleted' => 'false',
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }

        $this->command->info("Student user created: siswa / siswa123 (Student ID: {$student->id}, User ID: {$user->id})");
    }
}
