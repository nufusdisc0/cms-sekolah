<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_letter_number', 255)->nullable()->comment('Nomor Surat Tugas');
            $table->date('assignment_letter_date')->nullable()->comment('Tanggal Surat Tugas');
            $table->date('assignment_start_date')->nullable()->comment('TMT Tugas');
            $table->enum('parent_school_status', ['true', 'false'])->default('true')->comment('Status Sekolah Induk');
            $table->string('full_name', 150)->index();
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->string('nik', 50)->nullable()->index();
            $table->string('birth_place', 255)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('mother_name', 150)->nullable();
            $table->string('street_address', 255)->nullable()->comment('Alamat Jalan');
            $table->string('rt', 10)->nullable()->comment('Rukun Tetangga');
            $table->string('rw', 10)->nullable()->comment('Rukun Warga');
            $table->string('sub_village', 255)->nullable()->comment('Nama Dusun');
            $table->string('village', 255)->nullable()->comment('Nama Kelurahan/Desa');
            $table->string('sub_district', 255)->nullable()->comment('Kecamatan');
            $table->string('district', 255)->nullable()->comment('Kota/Kabupaten');
            $table->string('postal_code', 20)->nullable()->comment('Kode POS');
            $table->bigInteger('religion_id')->default(0)->index();
            $table->bigInteger('marriage_status_id')->default(0)->index();
            $table->string('spouse_name', 255)->nullable()->comment('Nama Pasangan : Suami / Istri');
            $table->bigInteger('spouse_employment_id')->default(0)->comment('Pekerjaan Pasangan : Suami / Istri')->index();
            $table->enum('citizenship', ['WNI', 'WNA'])->default('WNI')->comment('Kewarganegaraan');
            $table->string('country', 255)->nullable();
            $table->string('npwp', 100)->nullable();
            $table->bigInteger('employment_status_id')->default(0)->comment('Status Kepegawaian')->index();
            $table->string('nip', 100)->nullable();
            $table->string('niy', 100)->nullable()->comment('NIY/NIGK');
            $table->string('nuptk', 100)->nullable();
            $table->bigInteger('employment_type_id')->default(0)->comment('Jenis Guru dan Tenaga Kependidikan (GTK)')->index();
            $table->string('decree_appointment', 255)->nullable()->comment('SK Pengangkatan');
            $table->date('appointment_start_date')->nullable()->comment('TMT Pengangkatan');
            $table->bigInteger('institution_lifter_id')->default(0)->comment('Lembaga Pengangkat')->index();
            $table->string('decree_cpns', 100)->nullable()->comment('SK CPNS');
            $table->date('pns_start_date')->nullable()->comment('TMT CPNS');
            $table->bigInteger('rank_id')->default(0)->comment('Pangkat/Golongan')->index();
            $table->bigInteger('salary_source_id')->default(0)->comment('Sumber Gaji')->index();
            $table->enum('headmaster_license', ['true', 'false'])->default('false')->comment('Punya Lisensi Kepala Sekolah?');
            $table->bigInteger('laboratory_skill_id')->default(0)->comment('Keahlian Lab oratorium')->index();
            $table->bigInteger('special_need_id')->default(0)->comment('Mampu Menangani Kebutuhan Khusus')->index();
            $table->enum('braille_skills', ['true', 'false'])->default('false')->comment('Keahlian Braile ?');
            $table->enum('sign_language_skills', ['true', 'false'])->default('false')->comment('Keahlian Bahasa Isyarat ?');
            $table->string('phone', 255)->nullable();
            $table->string('mobile_phone', 255)->nullable();
            $table->string('email', 255)->nullable()->index();
            $table->string('photo', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->datetime('restored_at')->nullable();

            $table->bigInteger('created_by')->default(0);
            $table->bigInteger('updated_by')->default(0);
            $table->bigInteger('deleted_by')->default(0);
            $table->bigInteger('restored_by')->default(0);
            $table->enum('is_deleted', ['true', 'false'])->default('false');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
