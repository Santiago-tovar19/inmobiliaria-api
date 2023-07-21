<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="PropertyImage",
 *     title="Property Image",
 *     description="Property Image schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the property image"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the property image"
 *     ),
 *     @OA\Property(
 *         property="property_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID of the property associated with the image"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the image ('Banner' or 'Gallery')"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the property image"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the property image"
 *     )
 * )
 */
class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'property_id',
        'type',
    ];
}
