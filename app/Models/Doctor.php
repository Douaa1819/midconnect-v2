<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends User
{
    protected $fillable = ['user_id', 'specialite_id'];
    public static function allDoctors()
    {
        return self::where('role', 'doctor')->get();
    }
}

