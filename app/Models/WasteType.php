<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WasteType extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_hazardous', 'status'];

    protected function casts(): array
    {
        return [
            'is_hazardous' => 'boolean',
        ];
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class);
    }
}
