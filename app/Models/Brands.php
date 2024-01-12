<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brands extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name', 'slug'
    ];
    protected $dates = ['deleted_at'];


    public function items(): HasMany
    {
        return $this->hasMany(Items::class);
    }
}
