<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AinaZaMichango extends Model
{
    //
    protected $fillable = ['aina_ya_mchango','slug','maelezo'];

    public function ahadi_ya_aina_za_mchango()
    {
        return $this->hasOne(AhadiAinayamichango::class,'aina_ya_michango_id');
    }

    
}
