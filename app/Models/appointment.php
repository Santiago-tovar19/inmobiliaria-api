<?php

namespace App\Models;

use App\Models\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class appointment extends Model
{
    use HasFactory;
    protected $fillable = ['property_id', 'email', 'phone', 'message'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
