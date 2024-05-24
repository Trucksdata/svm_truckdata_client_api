<?php

namespace App\Models;

use App\Filters\QueryFilter\QueryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'images' => 'json',
        'brochure' => 'json',
        'video_links' => 'json',
        'faq' => 'json'
    ];

    public function vehicleSpecs(): HasMany
    {
        return $this->hasMany(VehicleSpec::class);
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function energySource(): BelongsTo
    {
        return $this->belongsTo(EnergySource::class);
    }

    public function compareVehicle()
    {
        return $this->hasMany(Vehicle::class, 'compare_vehicle_id', 'id');
    }
        
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->title);
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

    public function parentVehicle()
    {
        return $this->belongsTo(Vehicle::class, 'id', 'compare_vehicle_id');
    }

    public function payloadSpec()
    {
        return $this->hasOne(VehicleSpec::class)->with(['specification','values'])->whereHas('specification', function ($query) {
            $query->where('name', 'like', '%' . 'payload' . '%');
        });
    }
}
