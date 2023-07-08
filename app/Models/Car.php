<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Car",
 *     title="Carro",
 *     description="Carro model",
 *     required={"brand", "model"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="year", type="integer", nullable=true),
 *     @OA\Property(property="color", type="string", nullable=true),
 *     @OA\Property(property="doors", type="integer", nullable=true),
 *     @OA\Property(property="brand", type="integer", nullable=true),
 *     @OA\Property(property="model", type="integer", nullable=true),
 *     @OA\Property(property="placa", type="integer"),
 *     @OA\Property(property="owner_name", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
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
