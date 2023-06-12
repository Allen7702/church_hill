<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MichangoBenki extends Model
{
    //
    protected $fillable = ['aina_ya_mchango','tarehe','kiasi','maelezo','nambari_ya_nukushi','mwanafamilia','akaunti_namba','kundi','imewekwa'];

    public function aina_ya_mchango(){
        return $this->belongsTo('App\AinaZaMichango','aina_ya_mchango');
    }

    public function akaunti_ya_benki(){
        return $this->belongsTo('App\AkauntiZaBenki','akaunti_namba');
    }
}
