<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaliZaKanisa extends Model
{
    //
    protected $fillable = ['aina_ya_mali','jina_la_mali','usajili','thamani','hali_yake','maelezo','slug'];

    public function aina_mali(){
        return $this->belongsTo('App\AinaZaMali','aina_ya_mali');
    }
}
