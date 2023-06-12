<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwamuMichango extends Model
{
    //
    protected $guarded=[];

    public function mwanafamilia()
    {
        return $this->belongsTo(Mwanafamilia::class);
    }
}
