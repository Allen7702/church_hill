<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    //
    protected $fillable = ['jina_la_familia','jina_la_jumuiya','maoni','wanafamilia','mawasiliano','slug'];

    public function wanafamilias()
    {
        return $this->hasMany(Mwanafamilia::class, 'familia', 'id');
    }

    public function jumuiya()
    {
        return $this->belongsTo(Jumuiya::class);
    }
}
