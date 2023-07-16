<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ContractType",
 *     title="Contract Type",
 *     description="Contract Type schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the contract type"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the contract type"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the contract type"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the contract type"
 *     )
 * )
 */

class ContractType extends Model
{
    use HasFactory;
}
