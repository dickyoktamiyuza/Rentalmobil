<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Items extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'brand_id',
        'type_id',
        'photos',
        'features',
        'price',
        'star',
        'review'
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    // get first photos  from photo

    public function getThumbnailAttribute()
    {
        if ($this->photos) {
            return Storage::url(json_decode($this->photos)[0]);
        }
        return 'https://via.placeholder.com/800x600';
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brands::class);
    }

    public function type(): BelongsTo
    {
        return  $this->belongsTo(Types::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookings::class);
    }
}
