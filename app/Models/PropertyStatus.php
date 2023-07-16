<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="PropertyStatus",
 *     title="Property Status",
 *     description="Property Status schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the property status"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the property status"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the property status"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the property status"
 *     )
 * )
 */

class PropertyStatus extends Model
{
    use HasFactory;

    protected $table = 'property_status';
}
