<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RatibaYaMisa extends Model
{
    protected $guarded = [];

    protected $with = ['misa', 'wahudumus'];

    public function misa()
    {
        return $this->belongsTo(AinaZaMisa::class, 'aina_za_misa_id');
    }

    public function wahudumus()
    {
        return $this->hasMany(Wahudumu::class,'ratiba_ya_misa_id');
    }
}
