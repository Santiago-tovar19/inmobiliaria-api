<?php

namespace App\Models;

use App\Models\broker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyView extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'broker_id'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }


}
