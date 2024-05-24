<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificationOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function specification(): BelongsTo
    {
        return $this->belongsTo(Specification::class);
    }

    public function childOptions(): HasMany
    {
        return $this->hasMany(SpecificationOption::class, 'parent_option_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = \Illuminate\Support\Str::slug($model->option);
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
