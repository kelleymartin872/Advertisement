<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(static fn(Advertisement $advertisement) => $advertisement->images()->get()->each->delete());
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function images(): HasMany
    {
        return $this->hasMany(AdvertisementImage::class, 'advertisement_id');
    }
}
