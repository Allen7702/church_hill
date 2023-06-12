<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SadakaJumuiya extends Model
{
    //
    protected $fillable = ['jina_la_jumuiya','kiasi','imewekwa_na','tarehe','status','maoni'];

    public function jumuiya(){
        return $this->belongsTo('App\Jumuiya','jina_la_jumuiya');
    }
}

