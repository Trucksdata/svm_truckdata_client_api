<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleSpec extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_key_feature' => 'boolean'
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(VehicleSpecValue::class)->whereNull('parent_value_id')->with('childValues');
    }

    public function allValues(): HasMany
    {
        return $this->hasMany(VehicleSpecValue::class);
    }
}
