<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'advertisement_id',
        'path'
    ];

    protected static function boot()
    {
        parent::boot();

        self::deleted(static fn (AdvertisementImage $model) => $model->path ? @unlink(public_path('uploads/profile_images/') . $model->path) : null);
    }
}
