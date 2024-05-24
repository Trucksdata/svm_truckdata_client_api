<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SpecificationCategory::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(SpecificationOption::class)->whereNull('parent_option_id')->with('childOptions');
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
