<?php

use App\Models\Post;
use Illuminate\Support\Str;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pages = [
    'Profil Sekolah' => 'profil',
    'Visi & Misi' => 'visi-misi',
    'Kontak Kami' => 'kontak',
];

foreach ($pages as $title => $slug) {
    Post::firstOrCreate(
    ['post_slug' => $slug, 'post_type' => 'page'],
    [
        'post_title' => $title,
        'post_content' => '<p>Edit halaman ini di pengaturan admin (/backend/pages).</p>',
        'post_status' => 'publish',
        'post_author' => 1
    ]
    );
}
echo "Pages created successfully.\n";
