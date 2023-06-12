<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AinaZaMali extends Model
{
    //
    protected $fillable = ['aina_ya_mali','maelezo','slug'];

    public function mali(){
        return $this->hasMany('App\MaliZaKanisa','aina_ya_mali');
    }
}
