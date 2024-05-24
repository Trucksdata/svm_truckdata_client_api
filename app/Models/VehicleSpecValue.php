<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleSpecValue extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function vehicleSpec(): BelongsTo
    {
        return $this->belongsTo(VehicleSpec::class);
    }

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function childValues(): HasMany
    {
        return $this->hasMany(VehicleSpecValue::class, 'parent_value_id', 'id');
    }

    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->value);
            $model->slug = $model->getUniqueSlug($model->slug);
        });
    }

    public function getUniqueSlug($slug)
    {
        $count = 1;
        $originalSlug = $slug;

        while (Manufacturer::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
