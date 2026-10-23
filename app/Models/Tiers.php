<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tiers extends Model
{
    protected $fillable = ['name'];

    public function stores(): HasMany
    {
        return $this->hasMany(Stores::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Products::class);
    }
}
