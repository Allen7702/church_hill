<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Kikundi extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['jina_la_kikundi','idadi_ya_wanachama','maoni','slug'];



}
