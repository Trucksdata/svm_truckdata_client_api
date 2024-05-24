<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EnergySource extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function vehicleTypes(): BelongsToMany
    {
        return $this->belongsToMany(VehicleType::class, 'energy_source_vehicle_type');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(VehicleTypeSpecification::class)->with('specification');
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => $value ? $this->uniqueSlug($value) : null
        );
    }

    private  function uniqueSlug($value)
    {
        $count = 1;
        $orginalSlug = Str::slug($value);
        $slug = $orginalSlug;
        while (EnergySource::where('slug', $slug)->exists()) {
            $slug = $orginalSlug.'-'.$count++;
        }
        return $slug;
    }
}
