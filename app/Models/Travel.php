<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Travel extends Model
{
    use HasFactory, Sluggable, HasUlids;

    protected $table = 'travels';

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    protected $fillable  = [
        'name',
        'slug',
        'description',
        'is_public',
        'number_of_days'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function numberOfNights() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['number_of_days']- 1
        );
    }
}
