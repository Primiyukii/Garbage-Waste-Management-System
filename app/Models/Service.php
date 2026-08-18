<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'category',
        'frequency', 'base_price', 'included_weight_kg', 'status',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'included_weight_kg' => 'decimal:2',
        ];
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
