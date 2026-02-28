<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Check existing setting to learn the structure
$sample = DB::table('settings')->first();
if ($sample) {
    echo "Sample setting columns: " . implode(', ', array_keys((array)$sample)) . "\n";
    echo "Sample setting_group: " . ($sample->setting_group ?? 'NULL') . "\n";
}

// Social Media settings
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
        echo "Updated: $key\n";
    }
    else {
        DB::table('settings')->insert([
            'setting_group' => 'social_media',
            'setting_variable' => $key,
            'setting_value' => $val,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "Inserted: $key\n";
    }
}

echo "Social media links seeded successfully!\n";
