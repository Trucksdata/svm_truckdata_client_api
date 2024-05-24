<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTypeSpecification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function vehicleType():BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function energySource():BelongsTo
    {
        return $this->belongsTo(EnergySource::class);
    }

    public function specification():BelongsTo
    {
        return $this->belongsTo(Specification::class)->with(['category','options.childOptions']);
    }
}
