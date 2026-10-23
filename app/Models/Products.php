<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    protected $fillable = [
        'product_code', 'name', 'category', 'price', 'tier_id',
    ];

    public function tier(): BelongsTo
    {
        return $this->belongsTo(Tiers::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entries::class);
    }   
}
