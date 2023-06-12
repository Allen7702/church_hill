<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MichangoTaslimu extends Model
{
    //
    protected $fillable = ['aina_ya_mchango','tarehe','kiasi','maelezo','mwanafamilia','kundi','imewekwa'];

    public function aina_ya_mchango(){
        return $this->belongsTo('App\AinaZaMichango','aina_ya_mchango');
    }
}
