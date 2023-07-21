<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="PropertyType",
 *     title="Property Type",
 *     description="Property Type schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the property type"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the property type"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the property type"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the property type"
 *     )
 * )
 */

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
