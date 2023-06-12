<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wahudumu extends Model
{
    protected $guarded = [];

    protected $appends = ['wahudumu'];

    public function wahudumuable()
    {
        return $this->morphTo();
    }

    public function  getWahudumuAttribute()
    {
//        dd($this->wahudumuable_type);
        switch ($this->wahudumuable_type){
            case 'App\Mwanafamilia':
                return  Mwanafamilia::find($this->wahudumuable_id);
                break;
            case 'App\Kanda':
                return  Kanda::find($this->wahudumuable_id);
                break;
            case 'App\Jumuiya':
                return  Jumuiya::find($this->wahudumuable_id);
                break;
            case 'App\Kikundi':
                return  Kikundi::find($this->wahudumuable_id);
                break;
            default:
                return null;
        }

    }
}
