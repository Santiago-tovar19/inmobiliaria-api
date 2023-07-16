<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Currency",
 *     title="Currency",
 *     description="Currency schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the currency"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the currency"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the currency"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the currency"
 *     )
 * )
 */

class Currency extends Model
{
    use HasFactory;
}
