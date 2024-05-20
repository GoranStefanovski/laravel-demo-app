<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('connected_at');
    }
}
