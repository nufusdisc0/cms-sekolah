<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert([
            'setting_group' => 'school_profile',
            'setting_variable' => 'headmaster_photo',
            'setting_value' => '',
            'setting_type' => 'image',
            'setting_description' => 'Foto Kepala Sekolah (JPG/PNG, Max 2MB)',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 0,
            'updated_by' => 0,
            'deleted_by' => 0,
            'restored_by' => 0,
            'deleted_at' => null,
            'restored_at' => null,
            'is_deleted' => 'false',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('setting_variable', 'headmaster_photo')->delete();
    }
};
