<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SadakaKuu extends Model
{
    //
    protected $fillable = ['tarehe','kiasi','status','imewekwa','maelezo','misa'];

    public function misa(){
        return $this->belengsTo('App\AinaZaMisa','jina_la_misa');
    }
}
