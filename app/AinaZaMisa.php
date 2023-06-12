<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AinaZaMisa extends Model
{
    //
    protected $fillable = ['jina_la_misa','maelezo','slug'];

    public function sadaka_kuu(){
        return $this->hasMany('App\SadakaKuu','misa');
    }
}
