<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        "year",
        "color",
        "doors",
        "brand",
        "model",
        "placa",
        "owner_name"
    ];
}
