<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_role');
    }
}
