<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Tags (columns: id, tag, slug, created_at, updated_at, ...)
$tags = [
    ['tag' => 'Pendidikan', 'slug' => 'pendidikan'],
    ['tag' => 'Prestasi', 'slug' => 'prestasi'],
    ['tag' => 'Pengumuman', 'slug' => 'pengumuman'],
    ['tag' => 'Kegiatan', 'slug' => 'kegiatan'],
    ['tag' => 'PPDB', 'slug' => 'ppdb'],
    ['tag' => 'Ekstrakurikuler', 'slug' => 'ekstrakurikuler'],
];
foreach ($tags as $t) {
    $exists = DB::table('tags')->where('slug', $t['slug'])->exists();
    if (!$exists) {
        DB::table('tags')->insert([
            'tag' => $t['tag'],
            'slug' => $t['slug'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
echo "Tags seeded.\n";

// 2. Quotes (columns: id, quote, quote_by, created_at, updated_at, ...)
$quotes = [
    ['quote' => 'Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan, Anda dapat mengubah dunia.', 'quote_by' => 'Nelson Mandela'],
    ['quote' => 'Hiduplah seolah engkau mati besok. Belajarlah seolah engkau hidup selamanya.', 'quote_by' => 'Mahatma Gandhi'],
    ['quote' => 'Mencari ilmu itu wajib bagi setiap muslim.', 'quote_by' => 'HR. Ibnu Majah'],
];
foreach ($quotes as $q) {
    $exists = DB::table('quotes')->where('quote', $q['quote'])->exists();
    if (!$exists) {
        DB::table('quotes')->insert([
            'quote' => $q['quote'],
            'quote_by' => $q['quote_by'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
echo "Quotes seeded.\n";

// 3. Social Media (in settings table)
$socials = [
    'facebook' => 'https://facebook.com/sman1jakarta',
    'twitter' => 'https://twitter.com/sman1jakarta',
    'instagram' => 'https://instagram.com/sman1jakarta',
    'youtube' => 'https://youtube.com/sman1jakarta',
];
foreach ($socials as $key => $val) {
    $exists = DB::table('settings')->where('setting_variable', $key)->exists();
    if ($exists) {
        DB::table('settings')->where('setting_variable', $key)->update(['setting_value' => $val]);
    }
    else {
        DB::table('settings')->insert([
            'setting_variable' => $key,
            'setting_value' => $val,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
echo "Social media links seeded.\n";

echo "All examples seeded successfully!\n";
