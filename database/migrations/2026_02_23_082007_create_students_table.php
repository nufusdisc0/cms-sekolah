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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('major_id')->default(0)->comment('Program Keahlian')->index();
            $table->bigInteger('first_choice_id')->default(0)->comment('Pilihan Pertama PPDB')->index();
            $table->bigInteger('second_choice_id')->default(0)->comment('Pilihan Kedua PPDB')->index();
            $table->string('registration_number', 10)->nullable()->comment('Nomor Pendaftaran')->index();
            $table->string('admission_exam_number', 10)->nullable()->comment('Nomor Ujian Tes Tulis');
            $table->string('selection_result', 100)->nullable()->comment('Hasil Seleksi PPDB/PMB');
            $table->bigInteger('admission_phase_id')->default(0)->comment('Gelombang Pendaftaran')->index();
            $table->bigInteger('admission_type_id')->default(0)->comment('Jalur Pendaftaran')->index();
            $table->string('photo', 100)->nullable();
            $table->text('achievement')->nullable()->comment('Prestasi Calon Peserta Didik / Mahasiswa');
            $table->enum('is_student', ['true', 'false'])->default('false')->comment('Apakah Siswa Aktif ? Set true jika lolos seleksi PPDB...');
            $table->enum('is_prospective_student', ['true', 'false'])->default('false')->comment('Apakah Calon Siswa Baru ?');
            $table->enum('is_alumni', ['true', 'false', 'unverified'])->default('false')->comment('Apakah Alumni?');
            $table->enum('is_transfer', ['true', 'false'])->default('false')->comment('Jenis Pendaftaran : Baru / Pindahan ?');
            $table->enum('re_registration', ['true', 'false'])->nullable()->comment('Konfirmasi Pendaftaran Ulang Calon Siswa Baru');
            $table->date('start_date')->nullable()->comment('Tanggal Masuk Sekolah');
            $table->string('identity_number', 50)->nullable()->comment('NIS/NIM')->index();
            $table->string('nisn', 50)->nullable()->comment('Nomor Induk Siswa Nasional');
            $table->string('nik', 50)->nullable()->comment('Nomor Induk Kependudukan/KTP');
            $table->string('prev_exam_number', 50)->nullable()->comment('Nomor Peserta Ujian Sebelumnya');
            $table->string('prev_diploma_number', 50)->nullable()->comment('Nomor Ijazah Sebelumnya');
            $table->enum('paud', ['true', 'false'])->nullable()->comment('Apakah Pernah PAUD');
            $table->enum('tk', ['true', 'false'])->nullable()->comment('Apakah Pernah TK');
            $table->string('skhun', 50)->nullable()->comment('No. Seri Surat Keterangan Hasil Ujian Nasional Sebelumnya');
            $table->string('prev_school_name', 255)->nullable()->comment('Nama Sekolah Sebelumnya');
            $table->string('prev_school_address', 255)->nullable()->comment('Alamat Sekolah Sebelumnya');
            $table->string('hobby', 255)->nullable();
            $table->string('ambition', 255)->nullable()->comment('Cita-Cita');
            $table->string('full_name', 150)->index();
            $table->enum('gender', ['M', 'F'])->default('M');
            $table->string('birth_place', 255)->nullable();
            $table->date('birth_date')->nullable();
            $table->bigInteger('religion_id')->default(0);
            $table->bigInteger('special_need_id')->default(0)->comment('Berkeburuhan Khusus');
            $table->string('street_address', 255)->nullable()->comment('Alamat Jalan');
            $table->string('rt', 10)->nullable()->comment('Alamat Jalan');
            $table->string('rw', 10)->nullable()->comment('Alamat Jalan');
            $table->string('sub_village', 255)->nullable()->comment('Nama Dusun');
            $table->string('village', 255)->nullable()->comment('Nama Kelurahan/Desa');
            $table->string('sub_district', 255)->nullable()->comment('Kecamatan');
            $table->string('district', 255)->nullable()->comment('Kota/Kabupaten');
            $table->string('postal_code', 20)->nullable()->comment('Kode POS');
            $table->bigInteger('residence_id')->default(0)->comment('Tempat Tinggal');
            $table->bigInteger('transportation_id')->default(0)->comment('Moda Transportasi');
            $table->string('phone', 50)->nullable();
            $table->string('mobile_phone', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('sktm', 100)->nullable()->comment('Surat Keterangan Tidak Mampu (SKTM)');
            $table->string('kks', 100)->nullable()->comment('Kartu Keluarga Sejahtera (KKS)');
            $table->string('kps', 100)->nullable()->comment('Kartu Pra Sejahtera (KPS)');
            $table->string('kip', 100)->nullable()->comment('Kartu Indonesia Pintar (KIP)');
            $table->string('kis', 100)->nullable()->comment('Kartu Indonesia Sehat (KIS)');
            $table->enum('citizenship', ['WNI', 'WNA'])->default('WNI')->comment('Kewarganegaraan');
            $table->string('country', 255)->nullable();

            $table->string('father_name', 150)->nullable();
            $table->year('father_birth_year')->nullable();
            $table->bigInteger('father_education_id')->default(0);
            $table->bigInteger('father_employment_id')->default(0);
            $table->bigInteger('father_monthly_income_id')->default(0);
            $table->bigInteger('father_special_need_id')->default(0);

            $table->string('mother_name', 150)->nullable();
            $table->year('mother_birth_year')->nullable();
            $table->bigInteger('mother_education_id')->default(0);
            $table->bigInteger('mother_employment_id')->default(0);
            $table->bigInteger('mother_monthly_income_id')->default(0);
            $table->bigInteger('mother_special_need_id')->default(0);

            $table->string('guardian_name', 150)->nullable();
            $table->year('guardian_birth_year')->nullable();
            $table->bigInteger('guardian_education_id')->default(0);
            $table->bigInteger('guardian_employment_id')->default(0);
            $table->bigInteger('guardian_monthly_income_id')->default(0);

            $table->smallInteger('mileage')->nullable()->comment('Jarak tempat tinggal ke sekolah');
            $table->smallInteger('traveling_time')->nullable()->comment('Waktu Tempuh');
            $table->smallInteger('height')->nullable()->comment('Tinggi Badan');
            $table->smallInteger('weight')->nullable()->comment('Berat Badan');
            $table->smallInteger('sibling_number')->default(0)->comment('Jumlah Saudara Kandung');
            $table->bigInteger('student_status_id')->default(0)->comment('Status siswa')->index();
            $table->date('end_date')->nullable()->comment('Tanggal Keluar');
            $table->string('reason', 255)->nullable()->comment('Diisi jika peserta didik sudah keluar');

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
        Schema::dropIfExists('students');
    }
};
