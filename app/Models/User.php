<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the user"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="First name of the user"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         nullable=true,
 *         description="Last name of the user"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email of the user"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         nullable=true,
 *         description="Phone number of the user"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         nullable=true,
 *         description="Password of the user"
 *     ),
 *     @OA\Property(
 *         property="broker_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID of the broker associated with the user"
 *     ),
 *     @OA\Property(
 *         property="role_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID of the role assigned to the user"
 *     ),
 *     @OA\Property(
 *         property="img",
 *         type="string",
 *         nullable=true,
 *         description="Image URL of the user"
 *     ),
 *     @OA\Property(
 *         property="verified",
 *         type="boolean",
 *         description="Verification status of the user"
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Email verification date of the user"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation date of the user"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update date of the user"
 *     )
 * )
 */

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

	public static function checkPassword($password)
	{
		return Hash::check($password, $this->password);
	}

    public function role()
	{

		return $this->belongsTo(Role::class);
    }

    public function getModules()
    {
        $modules = $this->role->modules;
        $this->{'modules'} = $modules;
        $this->role->unsetRelation('modules');

        $role = $this->role;
        $this->unsetRelation('role');
        $this->role = $role;
        return $this;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function favProperties(){
        return $this->belongsToMany(Property::class, 'favs_user_properties', 'user_id', 'property_id');
    }
}
