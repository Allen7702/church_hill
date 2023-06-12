<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mwanafamilia extends Model
{
    //
    protected $fillable = ['jina_kamili','mawasiliano','familia','ndoa','jinsia',
    'ubatizo','ekaristi','kipaimara','komunio','aina_ya_ndoa','taaluma','dob',
    'namba_ya_cheti','parokia_ya_ubatizo','jimbo_la_ubatizo','maoni','namba_utambulisho','cheo_familia','dhehebu','rika'];

    public function familia()

    {
        return $this->belongsTo(Familia::class);
    }

    public function mchango_gawia(){

        return $this->hasMany(AhadiMichangoGawaMwanafamilia::class);
    }
}
