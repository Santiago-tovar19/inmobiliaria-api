<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyView extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
