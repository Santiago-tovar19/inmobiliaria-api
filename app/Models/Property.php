<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Property",
 *     title="Propiedad",
 *     description="Modelo de Propiedad",
 *     required={"name", "description", "property_type_id", "address", "mls_number", "construction_year", "location_type", "bedrooms", "bathrooms", "video", "size", "price", "currency_id", "youtube_link", "status_id", "contract_type_id", "lat", "lon", "parking", "kitchen", "elevator", "wifi", "fireplace", "hoa", "stories", "exclusions", "level", "security", "lobby", "balcony", "terrace", "power_plant", "gym", "walk_in_closet", "swimming_pool", "kids_area", "pets_allowed", "central_air_conditioner", "published", "created_by", "published_at"},
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string", nullable=true),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="property_type_id", type="integer", nullable=true),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="mls_number", type="string", nullable=true),
 *     @OA\Property(property="construction_year", type="string", nullable=true),
 *     @OA\Property(property="location_type", type="string", nullable=true),
 *     @OA\Property(property="bedrooms", type="string", nullable=true),
 *     @OA\Property(property="bathrooms", type="string", nullable=true),
 *     @OA\Property(property="video", type="string", nullable=true),
 *     @OA\Property(property="size", type="string", nullable=true),
 *     @OA\Property(property="price", type="string", nullable=true),
 *     @OA\Property(property="currency_id", type="integer", nullable=true),
 *     @OA\Property(property="youtube_link", type="string", nullable=true),
 *     @OA\Property(property="status_id", type="integer", nullable=true),
 *     @OA\Property(property="contract_type_id", type="integer", nullable=true),
 *     @OA\Property(property="lat", type="string", nullable=true),
 *     @OA\Property(property="lon", type="string", nullable=true),
 *     @OA\Property(property="parking", type="integer", default=0),
 *     @OA\Property(property="kitchen", type="integer", default=0),
 *     @OA\Property(property="elevator", type="integer", default=0),
 *     @OA\Property(property="wifi", type="integer", default=0),
 *     @OA\Property(property="fireplace", type="integer", default=0),
 *     @OA\Property(property="hoa", type="integer", default=0),
 *     @OA\Property(property="stories", type="integer", default=0),
 *     @OA\Property(property="exclusions", type="integer", default=0),
 *     @OA\Property(property="level", type="integer", default=0),
 *     @OA\Property(property="security", type="integer", default=0),
 *     @OA\Property(property="lobby", type="integer", default=0),
 *     @OA\Property(property="balcony", type="integer", default=0),
 *     @OA\Property(property="terrace", type="integer", default=0),
 *     @OA\Property(property="power_plant", type="integer", default=0),
 *     @OA\Property(property="gym", type="integer", default=0),
 *     @OA\Property(property="walk_in_closet", type="integer", default=0),
 *     @OA\Property(property="swimming_pool", type="integer", default=0),
 *     @OA\Property(property="kids_area", type="integer", default=0),
 *     @OA\Property(property="pets_allowed", type="integer", default=0),
 *     @OA\Property(property="central_air_conditioner", type="integer", default=0),
 *     @OA\Property(property="published", type="integer", default=0),
 *     @OA\Property(property="created_by", type="integer", nullable=true),
 *     @OA\Property(property="published_at", type="string", nullable=true)
 * )
 */

class Property extends Model
{
    use HasFactory, SoftDeletes;

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function status()
    {
        return $this->belongsTo(PropertyStatus::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function views()
    {
        return $this->hasMany(PropertyView::class);
    }
}
