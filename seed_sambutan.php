<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Seed opening speech (Sambutan Kepala Sekolah)
$exists = DB::table('posts')->where('post_type', 'opening_speech')->exists();
if (!$exists) {
    DB::table('posts')->insert([
        'post_title' => 'Sambutan Kepala Sekolah',
        'post_content' => 'Assalamualaikum Warahmatullahi Wabarakatuh,<br><br>Puji syukur kita panjatkan kehadirat Allah SWT, karena atas rahmat dan karunia-Nya kita dapat terus menjalankan kegiatan pendidikan di SMA Negeri 1 Jakarta dengan baik.<br><br>Sekolah kami berkomitmen untuk mencetak generasi muda yang berprestasi, berkarakter, dan siap menghadapi tantangan di era globalisasi. Dengan dukungan tenaga pendidik yang profesional dan fasilitas yang memadai, kami yakin dapat memberikan pengalaman belajar terbaik bagi seluruh siswa.<br><br>Kami mengajak seluruh warga sekolah, orang tua, dan masyarakat untuk bersama-sama mendukung proses pendidikan yang berkualitas demi masa depan generasi bangsa.<br><br>Wassalamualaikum Warahmatullahi Wabarakatuh.',
        'post_type' => 'opening_speech',
        'post_status' => 'publish',
        'post_visibility' => 'public',
        'post_author' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Opening speech seeded.\n";
}
else {
    echo "Opening speech already exists.\n";
}

// Seed headmaster name setting
$exists = DB::table('settings')->where('setting_variable', 'headmaster')->exists();
if ($exists) {
    DB::table('settings')->where('setting_variable', 'headmaster')->update(['setting_value' => 'Dr. H. Ahmad Fauzi, M.Pd.']);
}
else {
    DB::table('settings')->insert([
        'setting_group' => 'school_profile',
        'setting_variable' => 'headmaster',
        'setting_value' => 'Dr. H. Ahmad Fauzi, M.Pd.',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
echo "Headmaster name seeded.\n";

echo "Done!\n";
