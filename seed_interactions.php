<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Comment;
use App\Models\Post;

// Ensure we have at least one post for the comment
$post = Post::where('post_type', 'post')->first();
$postId = $post ? $post->id : 1;

// Seed an example message (Contact Form submission)
Comment::create([
    'comment_post_id' => 0,
    'comment_author' => 'Budi Santoso',
    'comment_email' => 'budi.santoso@example.com',
    'comment_url' => '',
    'comment_content' => 'Halo min, mau tanya untuk jadwal pendaftaran PPDB tahun ini kapan ya dibuka?',
    'comment_type' => 'message',
    'comment_status' => 'pending', // unread
]);

// Seed an example post comment from a reader
Comment::create([
    'comment_post_id' => $postId,
    'comment_author' => 'Siti Aminah',
    'comment_email' => 'siti.aminah@example.com',
    'comment_url' => '',
    'comment_content' => 'Wah artikel yang sangat bermanfaat! Terima kasih informasinya.',
    'comment_type' => 'post',
    'comment_status' => 'pending', // pending approval
]);

echo "Seeded dummy message and comment.\n";
