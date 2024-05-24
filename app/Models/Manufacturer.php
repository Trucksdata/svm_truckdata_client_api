<?php

namespace App\Models;

use App\Filters\QueryFilter\QueryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Manufacturer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'logo' => 'json',
        'banners' => 'json',
        'faq' => 'json'
    ];

    public function vehicleTypes(): BelongsToMany
    {
        return $this->belongsToMany(VehicleType::class, 'manufacturer_vehicle_type');
    }

    public function series(): HasMany
    {
        return $this->hasMany(Series::class);
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->name);
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
