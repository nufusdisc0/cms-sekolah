<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'banner_image',
        'banner_title',
        'banner_url',
        'banner_position',
        'banner_order',
        'status',
    ];
}
